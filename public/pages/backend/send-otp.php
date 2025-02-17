<?php
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/sendMail.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email address."]);
        exit;
    }

    // Generate 6-digit OTP
    $otp = rand(100000, 999999);
    $expires_at = date("Y-m-d H:i:s", strtotime("+10 minutes")); // OTP expires in 10 minutes

    // Store OTP in database
    $stmt = $pdo->prepare("INSERT INTO otp_codes (email, otp_code, expires_at) VALUES (?, ?, ?) ON DUPLICATE KEY UPDATE 
    otp_code = VALUES(otp_code), expires_at = VALUES(expires_at), created_at = CURRENT_TIMESTAMP");
    $stmt->execute([$email, $otp, $expires_at]);

    // Send OTP via email
    $subject = "Your OTP Code";
    $body = "<p>Your OTP code is: <strong>$otp</strong></p><p>It will expire in 10 minutes.</p>";

    if (sendMail($email, $subject, $body)) {
        echo json_encode(["success" => true, "message" => "OTP sent successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Failed to send OTP."]);
    }
}
