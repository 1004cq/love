<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/admin/bootstrap.php';

$started = microtime(true);
$required = ['mysqli', 'pdo_mysql', 'mbstring', 'json', 'curl'];
$extensions = [];
foreach ($required as $extension) {
    $extensions[$extension] = extension_loaded($extension);
}

$db = [
    'ok' => false,
    'error' => null,
];

try {
    require_once dirname(__DIR__) . '/admin/Config_DB.php';
    mysqli_report(MYSQLI_REPORT_OFF);
    $conn = @mysqli_connect($db_address, $db_username, $db_password, $db_name);
    if ($conn) {
        mysqli_set_charset($conn, 'utf8mb4');
        $ping = mysqli_query($conn, 'SELECT 1');
        $db['ok'] = (bool) $ping;
        mysqli_close($conn);
    } else {
        $db['error'] = 'connection_failed';
    }
} catch (Throwable $e) {
    $db['error'] = 'config_or_connect_error';
}

$extOk = !in_array(false, $extensions, true);
$status = ($extOk && $db['ok']) ? 'ok' : 'degraded';

lg_json_response([
    'status' => $status,
    'app' => 'LGNewUI',
    'edition' => '2026-upgraded',
    'api_version' => '3.1',
    'php' => PHP_VERSION,
    'extensions' => $extensions,
    'database' => $db,
    'time' => gmdate(DATE_ATOM),
    'duration_ms' => (int) round((microtime(true) - $started) * 1000),
], 200, 15);
