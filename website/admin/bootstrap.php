<?php
declare(strict_types=1);

/** LGNewUI 2026 runtime bootstrap (PHP 8.2-8.5). */
if (!headers_sent()) {
    header('X-Content-Type-Options: nosniff');
    header('X-Frame-Options: SAMEORIGIN');
    header('Referrer-Policy: strict-origin-when-cross-origin');
    header('Permissions-Policy: camera=(), microphone=(), geolocation=()');
}

function lg_is_https(): bool
{
    return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
        || (($_SERVER['HTTP_X_FORWARDED_PROTO'] ?? '') === 'https');
}

function lg_start_session(): void
{
    if (session_status() === PHP_SESSION_ACTIVE) {
        return;
    }
    session_set_cookie_params([
        'lifetime' => 0, 'path' => '/', 'secure' => lg_is_https(),
        'httponly' => true, 'samesite' => 'Lax',
    ]);
    session_start();
}

/**
 * Start gzip/deflate output buffering when the client accepts it.
 * Safe to call multiple times; no-ops if headers already sent or buffering active.
 */
function lg_enable_compression(): void
{
    if (headers_sent() || ob_get_level() > 0) {
        return;
    }
    $accept = $_SERVER['HTTP_ACCEPT_ENCODING'] ?? '';
    if ($accept !== '' && str_contains($accept, 'gzip') && function_exists('ob_gzhandler')) {
        ob_start('ob_gzhandler');
        return;
    }
    ob_start();
}

/**
 * @param 'public'|'private'|'no-store' $policy
 */
function lg_cache_headers(string $policy = 'public', int $maxAge = 60): void
{
    if (headers_sent()) {
        return;
    }
    if ($policy === 'no-store') {
        header('Cache-Control: no-store, no-cache, must-revalidate');
        header('Pragma: no-cache');
        return;
    }
    $scope = $policy === 'private' ? 'private' : 'public';
    header('Cache-Control: ' . $scope . ', max-age=' . max(0, $maxAge));
    if ($scope === 'public') {
        header('Vary: Accept-Encoding');
    }
}

function lg_json_response(array $payload, int $status = 200, ?int $cacheMaxAge = null): never
{
    http_response_code($status);
    header('Content-Type: application/json; charset=utf-8');
    if ($cacheMaxAge === null || $cacheMaxAge <= 0) {
        lg_cache_headers('no-store');
    } else {
        lg_cache_headers('public', $cacheMaxAge);
    }
    echo json_encode($payload, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
    exit;
}

/** Current public page basename without .php (index, leaving, …). */
function lg_current_page(): string
{
    $script = basename($_SERVER['SCRIPT_NAME'] ?? 'index.php');
    return pathinfo($script, PATHINFO_FILENAME) ?: 'index';
}
