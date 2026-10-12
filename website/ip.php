<?php

$time = gmdate("Y-m-d H:i:s", time() + 8 * 3600);
$page_visited = $_SERVER['REQUEST_URI'] ?? '/';
$request_method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$user_agent = $_SERVER['HTTP_USER_AGENT'] ?? '';
$referer = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'Direct Access';

// Throttle writes: one record per IP cookie window (2 minutes)
if (!isset($_COOKIE["getIP"]) && isset($connect) && isset($ip_if)) {
    require_once __DIR__ . '/admin/VisitStats.php';

    if (!lg_visit_should_skip()) {
        $geo = Get_CityInfo($ip_if);
        $gsd = $geo['label'];

        // Keep legacy file log for compatibility
        $log_file = __DIR__ . "/access_log.log";
        $log_entry = "\n" . $ip_if . "|" . $gsd . "|" . $time . "|" . $request_method . "|" . $page_visited . "|" . $user_agent . "|" . $referer . "\n";
        $fp = @fopen($log_file, "a");
        if ($fp) {
            fputs($fp, $log_entry);
            fclose($fp);
        }

        lg_visit_record(
            $connect,
            (string) $ip_if,
            $geo,
            (string) $page_visited,
            (string) $request_method,
            (string) $user_agent,
            (string) $referer
        );
    }

    $secure = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
    setcookie("getIP", (string) $ip_if, time() + 120, '/', '', $secure, true);
}
