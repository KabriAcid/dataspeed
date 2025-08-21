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


try {
    $token = getEbillToken($ebills_username, $ebills_password);
    if (!$token) {
        error_log('[verify-electricity] Failed to authenticate with eBills.');
        echo json_encode(['success' => false, 'message' => 'Failed to authenticate with eBills.']);
        exit;
    }

    // Generate a unique request_id (timestamp + meter + random)
    $request_id = 'VERIFY_' . substr(md5($meter . time() . rand()), 0, 16);
    $amount = 1000; // Default minimum for verification
    $url = "$ebills_base_url/api/v2/verify-customer";
    $payload = json_encode([
        'request_id' => $request_id,
        'customer_id' => $meter,
        'service_id' => $service_id,
        'variation_id' => $type,
        'amount' => $amount
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
    $curl_error = curl_error($ch);
    curl_close($ch);

    if ($curl_error) {
        error_log('[verify-electricity] CURL error: ' . $curl_error);
        echo json_encode(['success' => false, 'message' => 'Network error. Please try again.']);
        exit;
    }

    $result = json_decode($response, true);
    $apiCode = $result['code'] ?? '';
    $apiMsg = $result['message'] ?? '';

    // Log full response for debugging
    if ($apiCode !== 'success') {
        error_log('[verify-electricity] API error: HTTP ' . $http_code . ' - ' . $apiMsg . ' | Response: ' . $response);
        // Map known error codes to user-friendly messages
        $errorMap = [
            'missing_fields' => 'Required parameters missing.',
            'invalid_service_id' => 'Invalid electricity provider selected.',
            'invalid_variation_id' => 'Invalid meter type selected.',
            'below_minimum_amount' => 'Amount is below the minimum allowed.',
            'below_customer_arrears' => 'Amount is below customer arrears.',
            'insufficient_funds' => 'Insufficient wallet balance.',
            'duplicate_request_id' => 'Duplicate transaction. Please try again.',
            'duplicate_order' => 'Duplicate order within 3 minutes. Please wait and retry.',
            'rest_forbidden' => 'Unauthorized access. Please login again.'
        ];
        $userMsg = $errorMap[$apiCode] ?? ($apiMsg ?: 'Verification failed.');
        echo json_encode(['success' => false, 'message' => $userMsg]);
        exit;
    }

    $data = $result['data'] ?? [];
    echo json_encode([
        'success' => true,
        'customer_name' => $data['customer_name'] ?? '',
        'address' => $data['customer_address'] ?? $data['address'] ?? '',
        'other' => $data
    ]);
} catch (Throwable $e) {
    error_log('[verify-electricity] Exception: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'An unexpected error occurred. Please try again.']);
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
