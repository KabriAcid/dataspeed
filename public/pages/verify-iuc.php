<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/utilities.php';

header('Content-Type: application/json');

$ebills_username = $_ENV['EBILLS_USERNAME'] ?? '';
$ebills_password = $_ENV['EBILLS_PASSWORD'] ?? '';
$ebills_base_url = $_ENV['EBILLS_BASE_URL'] ?? 'https://ebills.africa/wp-json';
$token_cache_file = __DIR__ . '/../../cache/ebills_token.json';

function getCachedToken()
{
    global $token_cache_file;
    if (!file_exists($token_cache_file)) return null;
    $data = json_decode(file_get_contents($token_cache_file), true);
    if (!$data || !isset($data['token']) || !isset($data['expires_at'])) return null;
    if (time() >= $data['expires_at']) return null;
    return $data['token'];
}

function saveTokenToCache($token)
{
    global $token_cache_file;
    $data = [
        'token' => $token,
        'expires_at' => time() + (6 * 24 * 60 * 60)
    ];
    file_put_contents($token_cache_file, json_encode($data));
}

function getEbillToken($username, $password)
{
    $cached = getCachedToken();
    if ($cached) return $cached;

    $auth_url = $GLOBALS['ebills_base_url'] . '/jwt-auth/v1/token';
    $payload = json_encode([
        "username" => $username,
        "password" => $password
    ]);

    $ch = curl_init($auth_url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    $res = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($res, true);
    if (isset($result['token'])) {
        saveTokenToCache($result['token']);
        return $result['token'];
    }
    return null;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$iuc = trim($_POST['iuc'] ?? '');
$network = trim($_POST['network'] ?? '');

if (empty($iuc) || !preg_match('/^\d{6,12}$/', $iuc)) {
    echo json_encode(['success' => false, 'message' => 'Invalid IUC/Smartcard number.']);
    exit;
}

if (empty($network) || !in_array(strtolower($network), ['dstv', 'gotv', 'startimes'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid or missing network.']);
    exit;
}

$token = getEbillToken($ebills_username, $ebills_password);
if (!$token) {
    echo json_encode(['success' => false, 'message' => 'Failed to authenticate with eBills.']);
    exit;
}

$url = $ebills_base_url . "/api/v2/verify-customer?customer_id={$iuc}&service_id={$network}";

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($http_code !== 200 || ($result['code'] ?? '') !== 'success') {
    echo json_encode([
        'success' => false,
        'message' => $result['message'] ?? 'Failed to verify IUC/Smartcard number.'
    ]);
    exit;
}

$data = $result['data'] ?? [];
echo json_encode([
    'success' => true,
    'customer_name' => $data['customer_name'] ?? 'Unknown',
    'renewal_amount' => $data['renewal_amount'] ?? null,
    'current_bouquet' => $data['current_bouquet'] ?? null,
    'due_date' => $data['due_date'] ?? null
]);
