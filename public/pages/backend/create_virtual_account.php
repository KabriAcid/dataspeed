<?php
require __DIR__ . '/../../../config/config.php';

$secretKey = $_ENV['FLW_SECRET_KEY'];
$userEmail = $_SESSION['user_email'] ?? 'test@example.com'; // Assuming user is logged in
$userId = $_SESSION['user_id'] ?? 1; // Replace with actual user ID logic

// Flutterwave API endpoint
$url = "https://api.flutterwave.com/v3/virtual-account-numbers";

$data = [
    "email" => $userEmail,
    "is_permanent" => true,
    "tx_ref" => "DS-" . time(),
    "bvn" => "", // Optional: Only if required
    "phonenumber" => "",
    "firstname" => "John",
    "lastname" => "Doe",
];

$curl = curl_init();
curl_setopt_array($curl, [
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_POST => true,
    CURLOPT_HTTPHEADER => [
        "Authorization: Bearer $secretKey",
        "Content-Type: application/json"
    ],
    CURLOPT_POSTFIELDS => json_encode($data)
]);

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);

if ($err) {
    die("cURL Error: " . $err);
}

$result = json_decode($response, true);
if ($result['status'] === 'success') {
    $accountNumber = $result['data']['account_number'];
    $bankName = $result['data']['bank_name'];

    // Save to DB (replace with your DB logic)
    $stmt = $pdo->prepare("INSERT INTO virtual_accounts (user_id, account_number, bank_name) VALUES (?, ?, ?)");
    $stmt->execute([$userId, $accountNumber, $bankName]);

    echo "Virtual Account Created: $accountNumber at $bankName";
} else {
    echo "Failed to create virtual account: " . $result['message'];
}
