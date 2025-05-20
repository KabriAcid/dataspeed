<?php

require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';

// Set your secret key here or from .env
$secretKey = $_ENV['PRIMEBILLER_API_KEY'];

// Prepare the data payload
$data = [
    "network" => "1",
    "phone" => "07032529431",
    "ref" => "DATA12345",
    "data_plan" => "1",
    "ported_number" => true
];

// Initialize cURL
$ch = curl_init('https://bilalsadasub.com/api/user');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLINFO_HEADER_OUT, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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
echo "<h2>PRIMEBILLER API Test</h2>";

$headers = curl_getinfo($ch, CURLINFO_HEADER_OUT);
echo "<pre>Headers Sent:\n" . $headers . "</pre>";


echo "<strong>Status Code:</strong> $statusCode<br>";
echo "<strong>Response:</strong><pre>" . json_encode(json_decode($response), JSON_PRETTY_PRINT) . "</pre>";