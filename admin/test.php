<?php
// 1. Login to get JWT token
$loginUrl = "https://ebills.africa/wp-json/jwt-auth/v1/token";
$username = "dataspeedcontact@gmail.com";  // your ebills email/username
$password = "@Abba2835";  // your ebills password

$ch = curl_init($loginUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'username' => $username,
    'password' => $password
]));
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);

$loginResponse = curl_exec($ch);
curl_close($ch);
$loginData = json_decode($loginResponse, true);

if (empty($loginData['token'])) {
    exit("Login failed: " . ($loginData['message'] ?? $loginResponse));
}

$token = $loginData['token'];

// 2. Fetch balance using the token
$balanceUrl = "https://ebills.africa/wp-json/api/v2/balance";
$ch2 = curl_init($balanceUrl);
curl_setopt($ch2, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch2, CURLOPT_HTTPHEADER, [
    "Authorization: Bearer $token",
    "Content-Type: application/json"
]);

$balanceResponse = curl_exec($ch2);
if (curl_errno($ch2)) {
    exit("cURL error: " . curl_error($ch2));
}
curl_close($ch2);

$balanceData = json_decode($balanceResponse, true);

if (isset($balanceData['data']['balance'])) {
    echo "Your balance is: ₦" . $balanceData['data']['balance'];
} else {
    echo "Failed to retrieve balance. Response: " . $balanceResponse;
}
