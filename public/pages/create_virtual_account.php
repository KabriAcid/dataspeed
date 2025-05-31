<?php

require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';

// Set your secret key here or from .env
$secretKey = $_ENV['BILLSTACK_SECRET_KEY'];

// Prepare the data payload
$data = [
    "email" => "dadad@example.com",
    "reference" => "tx_ref_21236892",
    "firstName" => "Musa",
    "lastName" => "Abuakar",
    "phone" => "09012345672",
    "bank" => "9PSB"
];

// Initialize cURL
$ch = curl_init('https://api.billstack.co/v2/thirdparty/generateVirtualAccount/');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $secretKey,
    'Content-Type: application/json',
]);

// Execute the request
$response = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Output the results
echo "<h2>Billstack API Test</h2>";
echo "<strong>Status Code:</strong> $statusCode<br>";
echo "<strong>Response:</strong><pre>" . json_encode(json_decode($response), JSON_PRETTY_PRINT) . "</pre>";