<?php
require_once __DIR__ . '/vendor/autoload.php'; // for Dotenv

use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Collect form input
$network = $_POST['network'];
$phone = $_POST['phone'];
$data_plan = $_POST['data_plan'];
$ref = 'DATA' . rand(10000, 99999); // Generate unique reference
$ported_number = true; // Set according to your form if applicable

// Prepare the payload
$payload = [
    "network" => $_POST['network'],
    "phone" => $_POST['phone'],
    "ref" => "DATA" . rand(10000, 99999),
    "data_plan" => $_POST['data_plan'],
    "ported_number" => true
];


// API Endpoint
$url = "https://primebiller.com/api/v1/data/";

// Headers
$headers = [
    "Content-Type: application/json",
    "Authorization: Bearer {$_ENV['PRIMEBILLER_API_KEY']}"
];

// Initialize cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

// Execute request
$response = curl_exec($ch);
$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

// Decode response
$result = json_decode($response, true);

// Show response or handle errors
if ($httpcode == 200 && isset($result['status']) && strtolower($result['status']) === 'success') {
    echo "<p><strong>Success!</strong> " . htmlspecialchars($result['true_response']) . "</p>";
} else {
    echo "<p><strong>Failed!</strong> Response: " . htmlspecialchars($response) . "</p>";
}