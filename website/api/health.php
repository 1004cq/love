<?php
declare(strict_types=1);
require_once dirname(__DIR__) . '/admin/bootstrap.php';

$required = ['mysqli', 'pdo_mysql', 'mbstring', 'json', 'curl'];
$extensions = [];
foreach ($required as $extension) {
    $extensions[$extension] = extension_loaded($extension);
}

lg_json_response([
    'status' => !in_array(false, $extensions, true) ? 'ok' : 'degraded',
    'app' => 'LGNewUI',
    'edition' => '2026-upgraded',
    'api_version' => '3.1',
    'php' => PHP_VERSION,
    'extensions' => $extensions,
    'time' => gmdate(DATE_ATOM),
]);
