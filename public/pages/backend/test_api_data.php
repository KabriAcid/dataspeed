<?php

// Load environment (if using dotenv, otherwise hardcode temporarily)
require __DIR__ . '/../../../config/config.php'; // Make sure this sets $_ENV['PRIMEBILLER_API_KEY'] correctly

// ✅ Hardcode for testing if necessary
$secretKey = $_ENV['PRIMEBILLER_API_KEY'] ?? 'C9xJ2CHpCoCBvAsxmh3fCA7kCACyBAI8cAB5B75062DFw6483xrC1dzqatAG1748291676';


$secretKey = 'Cx2c66xdAz7qJE5cb1C3y904aCorGCAAmC8ksBAb7Fe2lI1vBApfhtBCDiw51748584535';
var_dump($secretKey);

// ✅ Build Airtime Request Data
$data = [
    "network" => "1", // MTN
    "phone" => "08062365769",
    "ref" => "AIRTIME" . time(),
    "airtime_type" => "VTU", // or "SHARE"
    "ported_number" => true,
    "amount" => "100"
];

// ✅ Set endpoint
$url = 'https://primebiller.com/api/v1/airtime/';

// ✅ Initialize cURL
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); // Optional for dev
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $secretKey,
    'Content-Type: application/json',
]);

// ✅ Execute cURL request
$response = curl_exec($ch);
$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
$error = curl_error($ch);
curl_close($ch);

// ✅ Output results
echo "<h2>PRIMEBILLER API Airtime Test</h2>";
echo "<strong>Token Used:</strong> {$secretKey}<br>";
echo "<strong>Endpoint:</strong> {$url}<br>";
echo "<strong>Payload Sent:</strong><pre>" . json_encode($data, JSON_PRETTY_PRINT) . "</pre>";
echo "<strong>Status Code:</strong> $statusCode<br>";

if ($error) {
    echo "<strong>cURL Error:</strong> $error<br>";
}

echo "<strong>Response:</strong><pre>" . json_encode(json_decode($response), JSON_PRETTY_PRINT) . "</pre>";