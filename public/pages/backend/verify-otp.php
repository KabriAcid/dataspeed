<?php

require __DIR__ . '/../../../config/config.php';

header("Content-Type: application/json; charset=UTF-8");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $otp = $_POST["otp"] ?? '';

    if (!preg_match('/^\d{6}$/', $otp)) {
        echo json_encode(["success" => false, "message" => "Invalid OTP format."]);
        exit;
    }

    // Check if OTP exists and is valid
    $stmt = $pdo->prepare("SELECT otp_code, expires_at FROM otp_codes WHERE otp_code = ? AND expires_at > NOW()");
    $stmt->execute([$otp]);
    $otpData = $stmt->fetch();

    if (!$otpData) {
        echo json_encode(["success" => false, "message" => "Invalid or expired OTP."]);
        exit;
    }

    // OTP is valid, delete it to prevent reuse
    $stmt = $pdo->prepare("DELETE FROM otp_codes WHERE otp_code = ?");
    $stmt->execute([$otp]);

    echo json_encode(["success" => true, "message" => "OTP verified. Proceeding to phone number verification."]);
}
