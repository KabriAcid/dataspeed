<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';

// VTpass API credentials (replace with your actual credentials)
$vtpass_api_key = $_ENV['VTPASS_API_KEY'];
$vtpass_secret_key = $_ENV['VTPASS_SECRET_KEY'];


// Hardcoded test values
$serviceID = 'mtn';
$amount = 100; // Airtime amount in Naira
$phone = '08011111111'; // Test phone number
$request_id = uniqid('airtime_', true);

// Prepare POST data
$postData = [
    'serviceID' => $serviceID,
    'amount' => $amount,
    'phone' => $phone,
    'request_id' => $request_id
];

$url = $_ENV['VTPASS_API_URL'] ?? 'https://sandbox.vtpass.com/api/pay';
// Initialize cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($postData));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "Content-Type: application/x-www-form-urlencoded",
    "api-key: $vtpass_api_key",
    "secret-key: $vtpass_secret_key"
]);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

// Execute request
$response = curl_exec($ch);

if ($response === false) {
    $error = curl_error($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    header('Content-Type: application/json');
    echo json_encode([
        'http_code' => $http_code,
        'error' => $error
    ], JSON_PRETTY_PRINT);
    exit;
}

$http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Output response for testing
header('Content-Type: application/json');
echo json_encode([
    'http_code' => $http_code,
    'response' => json_decode($response, true)
], JSON_PRETTY_PRINT);
