<?php

// ✅ Load environment config
require __DIR__ . '/../../config/config.php';

// ✅ Get your Flutterwave secret key
$secretKey = $_ENV['FLUTTERWAVE_SECRET_KEY']; // Replace with your test key

// ✅ Build request payload
$payload = [
    "country"        => "NG",                     // Nigeria
    "customer"       => "08062365769",            // Phone number
    "amount"         => 100,                      // Airtime amount
    "recurrence"     => "ONCE",                   // Can be ONCE or WEEKLY
    "type"           => "AIRTIME",                // Service type
    "reference"      => "FLWAIRTIME_" . time(),   // Unique ref
    "biller_name"    => "MTN"                     // MTN, AIRTEL, etc.
];

// ✅ Set endpoint URL
$endpoint = "https://api.flutterwave.com/v3/bills";

// ✅ Initialize cURL
$ch = curl_init($endpoint);
curl_setopt_array($ch, [
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST           => true,
    CURLOPT_POSTFIELDS     => json_encode($payload),
    CURLOPT_HTTPHEADER     => [
        "Authorization: Bearer $secretKey",
        "Content-Type: application/json"
    ]
]);

// ✅ Execute and handle response
$response   = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error      = curl_error($ch);
curl_close($ch);

// ✅ Display results
echo "<h2>Flutterwave Airtime Test</h2>";
echo "<strong>Endpoint:</strong> $endpoint<br>";
echo "<strong>Payload Sent:</strong><pre>" . json_encode($payload, JSON_PRETTY_PRINT) . "</pre>";
echo "<strong>Status Code:</strong> $statusCode<br>";

if ($error) {
    echo "<strong>cURL Error:</strong> $error<br>";
} else {
    $decoded = json_decode($response, true);
    echo "<strong>Response:</strong><pre>" . json_encode($decoded, JSON_PRETTY_PRINT) . "</pre>";
}
