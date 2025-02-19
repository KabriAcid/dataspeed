<?php

require __DIR__ . '/../../../config/config.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $otp = $_POST["otp"] ?? '';

    // Verify OTP in database
    $stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE email = ? AND otp_code = ? AND expires_at > NOW()");
    $stmt->execute([$email, $otp]);
    $otpData = $stmt->fetch();

    if ($otpData) {
        // OTP is valid, delete it to prevent reuse
        $stmt = $pdo->prepare("DELETE FROM otp_codes WHERE email = ?");
        $stmt->execute([$email]);

        echo json_encode(["success" => true, "message" => "OTP verified. Proceeding to next step."]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid or expired OTP."]);
    }
}
