<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/utilities.php';

header('Content-Type: application/json');

$iuc = trim($_POST['iuc'] ?? '');
$network = trim($_POST['network'] ?? '');

if (empty($iuc) || !preg_match('/^\d{10}$/', $iuc)) {
    echo json_encode(["success" => false, "message" => "Invalid IUC/Smartcard number."]);
    exit;
}

if (empty($network)) {
    echo json_encode(["success" => false, "message" => "Network is required."]);
    exit;
}

// IUC Verification API Setup
$verifyData = [
    'serviceID'   => strtolower($network),
    'billersCode' => $iuc,
    'type'        => 'verify',
];

$ch = curl_init($vtpass_url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($verifyData));
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "api-key: $vtpass_api_key",
    "secret-key: $vtpass_secret_key",
    "Content-Type: application/x-www-form-urlencoded"
]);
$verifyResponse = curl_exec($ch);
$verifyResult = json_decode($verifyResponse, true);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($httpCode !== 200 || ($verifyResult['code'] ?? '') !== '000') {
    echo json_encode([
        "success" => false,
        "message" => $verifyResult['response_description'] ?? "Failed to verify IUC/Smartcard number.",
    ]);
    exit;
}

// Extract verified details
$customerName = $verifyResult['content']['customer_name'] ?? 'Unknown';
$accountStatus = $verifyResult['content']['account_status'] ?? 'Unknown';

echo json_encode([
    "success" => true,
    "customer_name" => $customerName,
    "account_status" => $accountStatus,
]);
