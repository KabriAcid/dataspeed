<?php

function ebills_get_cached_token_path(): string
{
    $dir = __DIR__ . '/../cache';
    if (!is_dir($dir)) @mkdir($dir, 0777, true);
    return $dir . '/ebills_token.json';
}

function ebills_get_cached_balance_path(): string
{
    $dir = __DIR__ . '/../cache';
    if (!is_dir($dir)) @mkdir($dir, 0777, true);
    return $dir . '/ebills_balance.json';
}

function ebills_clear_token_cache(): void
{
    $file = ebills_get_cached_token_path();
    if (is_file($file)) {
        @unlink($file);
    }
}

function ebills_get_token_cached(): ?string
{
    $file = ebills_get_cached_token_path();
    if (!file_exists($file)) return null;
    $data = json_decode(@file_get_contents($file), true);
    if (!$data || empty($data['token']) || empty($data['expires_at'])) return null;
    if (time() >= (int)$data['expires_at']) return null;
    return $data['token'];
}

function ebills_save_token_cache(string $token, int $ttlSeconds = 6 * 24 * 60 * 60): void
{
    $file = ebills_get_cached_token_path();
    @file_put_contents($file, json_encode([
        'token' => $token,
        'expires_at' => time() + $ttlSeconds
    ], JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE));
}

function ebills_fetch_token(string $username, string $password): ?string
{
    $base = $_ENV['EBILLS_BASE_URL'] ?? 'https://ebills.africa/wp-json';
    $url = rtrim($base, '/') . '/jwt-auth/v1/token';
    $payload = json_encode(['username' => $username, 'password' => $password]);

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => $payload,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_TIMEOUT => 20
    ]);
    $res = curl_exec($ch);
    if ($res === false) {
        error_log('ebills_fetch_token: cURL error: ' . curl_error($ch));
    }
    curl_close($ch);

    $json = json_decode((string)$res, true);
    if (isset($json['code']) && $json['code'] === 'rest_forbidden') {
        error_log('ebills_fetch_token: rest_forbidden - IP not whitelisted or credentials issue');
    }
    if (!empty($json['token'])) {
        ebills_save_token_cache($json['token']);
        return $json['token'];
    }
    if (!empty($json['message'])) {
        error_log('ebills_fetch_token: message: ' . $json['message']);
    }
    return null;
}

function ebills_get_token(bool $forceRefresh = false): ?string
{
    $username = $_ENV['EBILLS_USERNAME'] ?? '';
    $password = $_ENV['EBILLS_PASSWORD'] ?? '';
    if (!$forceRefresh) {
        $cached = ebills_get_token_cached();
        if ($cached) return $cached;
    }
    if ($username && $password) {
        return ebills_fetch_token($username, $password);
    }
    return null;
}

/**
 * Returns float balance, or null if not available. Caches response briefly to avoid rate limits.
 */
function ebills_get_balance(int $ttlSeconds = 60): ?float
{
    $cacheFile = ebills_get_cached_balance_path();
    // Cache hit
    if (file_exists($cacheFile)) {
        $cached = json_decode(@file_get_contents($cacheFile), true);
        if ($cached && !empty($cached['balance']) && !empty($cached['ts']) && (time() - (int)$cached['ts'] < $ttlSeconds)) {
            return (float)$cached['balance'];
        }
    }

    $base = $_ENV['EBILLS_BASE_URL'] ?? 'https://ebills.africa/wp-json';
    $url = rtrim($base, '/') . '/api/v2/balance';

    // Try with cached token first
    $token = ebills_get_token(false);
    $balance = ebills_call_balance_endpoint($url, $token);

    // If forbidden or invalid, clear token cache, refresh token once and retry
    if ($balance === '__REFRESH__') {
        ebills_clear_token_cache();
        $token = ebills_get_token(true);
        $balance = ebills_call_balance_endpoint($url, $token);
    }

    // Final fallback: try direct login once like the working test script if still not numeric
    if (!is_numeric($balance)) {
        $username = $_ENV['EBILLS_USERNAME'] ?? '';
        $password = $_ENV['EBILLS_PASSWORD'] ?? '';
        if ($username && $password) {
            $directToken = ebills_fetch_token($username, $password);
            $balance = ebills_call_balance_endpoint($url, $directToken);
        }
    }

    if (is_numeric($balance)) {
        @file_put_contents($cacheFile, json_encode(['balance' => (float)$balance, 'ts' => time()]));
        return (float)$balance;
    }
    return null;
}

/**
 * Internal helper to call eBills balance endpoint. Returns:
 * - float balance on success
 * - '__REFRESH__' string when token invalid and should refresh
 * - null on other errors
 */
function ebills_call_balance_endpoint(string $url, ?string $token)
{
    if (!$token) return '__REFRESH__';

    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => [
            'Authorization: Bearer ' . $token,
            'Content-Type: application/json'
        ],
        CURLOPT_TIMEOUT => 20
    ]);
    $res = curl_exec($ch);
    if ($res === false) {
        error_log('ebills_call_balance_endpoint: cURL error: ' . curl_error($ch));
    }
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $json = json_decode((string)$res, true);
    // eBills returns {code:'rest_forbidden'} when token is invalid
    if ($code !== 200 || (isset($json['code']) && $json['code'] === 'rest_forbidden')) {
        error_log('ebills_call_balance_endpoint: HTTP ' . $code . ' body: ' . substr((string)$res, 0, 500));
        return '__REFRESH__';
    }
    if (isset($json['data']['balance'])) {
        return (float)$json['data']['balance'];
    }
    error_log('ebills_call_balance_endpoint: unexpected response: ' . substr((string)$res, 0, 500));
    return null;
}
