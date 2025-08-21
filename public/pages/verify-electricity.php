<?php
session_start();
require __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$service_id = trim($_POST['service_id'] ?? '');
$meter = trim($_POST['meter'] ?? '');
$type = trim($_POST['type'] ?? 'prepaid');

if (!$service_id || !$meter || !in_array($type, ['prepaid', 'postpaid'])) {
    echo json_encode(['success' => false, 'message' => 'Missing or invalid parameters.']);
    exit;
}

$ebills_username = $_ENV['EBILLS_USERNAME'] ?? '';
$ebills_password = $_ENV['EBILLS_PASSWORD'] ?? '';
$ebills_base_url = $_ENV['EBILLS_BASE_URL'] ?? 'https://ebills.africa/wp-json';

function getEbillToken($username, $password)
{
    // ...use your existing token cache logic...
    // For brevity, call API directly here
    $ch = curl_init("$GLOBALS[ebills_base_url]/jwt-auth/v1/token");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['username' => $username, 'password' => $password]));
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    $response = curl_exec($ch);
    curl_close($ch);
    $data = json_decode($response, true);
    return $data['token'] ?? null;
}

$token = getEbillToken($ebills_username, $ebills_password);
if (!$token) {
    echo json_encode(['success' => false, 'message' => 'Failed to authenticate with eBills.']);
    exit;
}

$url = "$ebills_base_url/api/v2/verify-customer";
$payload = json_encode([
    'service_id' => $service_id,
    'billersCode' => $meter,
    'type' => $type
]);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);
$response = curl_exec($ch);
$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

$result = json_decode($response, true);

if ($http_code !== 200 || ($result['code'] ?? '') !== 'success') {
    echo json_encode(['success' => false, 'message' => $result['message'] ?? 'Verification failed.']);
    exit;
}

$data = $result['data'] ?? [];
echo json_encode([
    'success' => true,
    'customer_name' => $data['customer_name'] ?? '',
    'address' => $data['address'] ?? '',
    'other' => $data
]);
