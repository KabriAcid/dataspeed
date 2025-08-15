<?php

/**
 * Returns float provider balance (if BILLSTACK_BALANCE_URL is configured), or null.
 * Assumes Authorization: Bearer {BILLSTACK_SECRET_KEY}. Adjust if your provider differs.
 * Caches for a short TTL to reduce calls.
 */
function billstack_get_balance(int $ttlSeconds = 60): ?float
{
    $url = $_ENV['BILLSTACK_BALANCE_URL'] ?? '';
    if (!$url) return null;

    $cacheFile = __DIR__ . '/../cache/billstack_balance.json';
    if (file_exists($cacheFile)) {
        $cached = json_decode(@file_get_contents($cacheFile), true);
        if ($cached && isset($cached['balance'], $cached['ts']) && (time() - (int)$cached['ts'] < $ttlSeconds)) {
            return (float)$cached['balance'];
        }
    }

    $secret = $_ENV['BILLSTACK_SECRET_KEY'] ?? '';
    $ch = curl_init($url);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => array_values(array_filter([
            $secret ? ('Authorization: Bearer ' . $secret) : null,
            'Accept: application/json',
        ])),
        CURLOPT_TIMEOUT => 20,
    ]);
    $res = curl_exec($ch);
    $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    if ($code !== 200) return null;

    $json = json_decode((string)$res, true);
    // Try common shapes
    $candidates = [
        $json['data']['balance'] ?? null,
        $json['balance'] ?? null,
        $json['result']['balance'] ?? null,
    ];
    foreach ($candidates as $val) {
        if (is_numeric($val)) {
            $val = (float)$val;
            @file_put_contents($cacheFile, json_encode(['balance' => $val, 'ts' => time()]));
            return $val;
        }
    }
    return null;
}
