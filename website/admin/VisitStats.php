<?php
declare(strict_types=1);

/**
 * Public visit analytics helpers (UV by IP + recent IP/country list).
 */
function lg_visit_ensure_table(mysqli $connect): bool
{
    static $ready = null;
    if ($ready !== null) {
        return $ready;
    }

    $sql = <<<SQL
CREATE TABLE IF NOT EXISTS `VisitLog` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `ip` varchar(64) NOT NULL,
  `country` varchar(64) NOT NULL DEFAULT '',
  `region` varchar(128) NOT NULL DEFAULT '',
  `page` varchar(255) NOT NULL DEFAULT '',
  `method` varchar(16) NOT NULL DEFAULT 'GET',
  `user_agent` varchar(512) NOT NULL DEFAULT '',
  `referer` varchar(512) NOT NULL DEFAULT '',
  `visit_date` date NOT NULL,
  `visit_time` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `idx_visit_date` (`visit_date`),
  KEY `idx_ip_date` (`ip`, `visit_date`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
SQL;

    $ready = (bool) mysqli_query($connect, $sql);
    return $ready;
}

function lg_visit_should_skip(): bool
{
    $uri = $_SERVER['REQUEST_URI'] ?? '';
    $script = $_SERVER['SCRIPT_NAME'] ?? '';
    $ua = strtolower($_SERVER['HTTP_USER_AGENT'] ?? '');

    if (preg_match('#/(admin|api|install|Style|vendor|Botui|icon|OwO|phpmailer)/#i', $uri . ' ' . $script)) {
        return true;
    }
    if ($ua === '' || preg_match('/bot|spider|crawl|slurp|curl|wget|python-requests|scrapy/i', $ua)) {
        return true;
    }
    return false;
}

function lg_visit_record(mysqli $connect, string $ip, array $geo, string $page, string $method, string $ua, string $referer): bool
{
    if (!lg_visit_ensure_table($connect)) {
        return false;
    }

    $stmt = mysqli_prepare(
        $connect,
        'INSERT INTO VisitLog (ip, country, region, page, method, user_agent, referer, visit_date, visit_time) VALUES (?,?,?,?,?,?,?,?,?)'
    );
    if (!$stmt) {
        return false;
    }

    $country = mb_substr((string) ($geo['country'] ?? '未知'), 0, 64);
    $region = mb_substr((string) ($geo['region'] ?? ''), 0, 128);
    $page = mb_substr($page, 0, 255);
    $method = mb_substr($method !== '' ? $method : 'GET', 0, 16);
    $ua = mb_substr($ua, 0, 512);
    $referer = mb_substr($referer, 0, 512);
    $date = date('Y-m-d');
    $time = time();

    mysqli_stmt_bind_param(
        $stmt,
        'ssssssssi',
        $ip,
        $country,
        $region,
        $page,
        $method,
        $ua,
        $referer,
        $date,
        $time
    );
    $ok = mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    return $ok;
}

function lg_visit_count_unique(mysqli $connect, ?string $date = null): int
{
    if (!lg_visit_ensure_table($connect)) {
        return 0;
    }
    if ($date === null) {
        $res = mysqli_query($connect, 'SELECT COUNT(DISTINCT ip) AS c FROM VisitLog');
        $row = $res ? mysqli_fetch_assoc($res) : null;
        return (int) ($row['c'] ?? 0);
    }
    $dateEsc = mysqli_real_escape_string($connect, $date);
    $res = mysqli_query($connect, "SELECT COUNT(DISTINCT ip) AS c FROM VisitLog WHERE visit_date = '{$dateEsc}'");
    $row = $res ? mysqli_fetch_assoc($res) : null;
    return (int) ($row['c'] ?? 0);
}

function lg_visit_count_pv(mysqli $connect, ?string $date = null): int
{
    if (!lg_visit_ensure_table($connect)) {
        return 0;
    }
    if ($date === null) {
        $res = mysqli_query($connect, 'SELECT COUNT(*) AS c FROM VisitLog');
        $row = $res ? mysqli_fetch_assoc($res) : null;
        return (int) ($row['c'] ?? 0);
    }
    $dateEsc = mysqli_real_escape_string($connect, $date);
    $res = mysqli_query($connect, "SELECT COUNT(*) AS c FROM VisitLog WHERE visit_date = '{$dateEsc}'");
    $row = $res ? mysqli_fetch_assoc($res) : null;
    return (int) ($row['c'] ?? 0);
}

/** @return array{today:int,yesterday:int,total:int,today_pv:int,yesterday_pv:int,total_pv:int} */
function lg_visit_dashboard_stats(mysqli $connect): array
{
    $today = date('Y-m-d');
    $yesterday = date('Y-m-d', strtotime('-1 day'));
    return [
        'today' => lg_visit_count_unique($connect, $today),
        'yesterday' => lg_visit_count_unique($connect, $yesterday),
        'total' => lg_visit_count_unique($connect, null),
        'today_pv' => lg_visit_count_pv($connect, $today),
        'yesterday_pv' => lg_visit_count_pv($connect, $yesterday),
        'total_pv' => lg_visit_count_pv($connect, null),
    ];
}

/** @return list<array<string,mixed>> */
function lg_visit_recent(mysqli $connect, int $limit = 50): array
{
    if (!lg_visit_ensure_table($connect)) {
        return [];
    }
    $limit = max(1, min(200, $limit));
    $res = mysqli_query(
        $connect,
        "SELECT id, ip, country, region, page, method, user_agent, referer, visit_date, visit_time
         FROM VisitLog ORDER BY id DESC LIMIT {$limit}"
    );
    $rows = [];
    while ($res && ($row = mysqli_fetch_assoc($res))) {
        $rows[] = $row;
    }
    return $rows;
}
