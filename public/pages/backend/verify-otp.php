<?php

require __DIR__ . '/../../../config/config.php';

header("Content-Type: application/json; charset=UTF-8");


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
        echo json_encode(["success" => true, "message" => "Debug: OTP verified."]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid or expired OTP."]);
    }
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';
    $otp = $_POST["otp"] ?? '';

    if (!preg_match('/^\d{6}$/', $otp)) {
        echo json_encode(["success" => false, "message" => "Invalid OTP format."]);
        exit;
    }

    // Check if OTP exists for the email
    $stmt = $pdo->prepare("SELECT otp_code, expires_at FROM otp_codes WHERE email = ?");
    $stmt->execute([$email]);
    $otpData = $stmt->fetch();

    if (!$otpData) {
        echo json_encode(["success" => false, "message" => "No OTP found for this email."]);
        exit;
    }

    // Check if OTP matches
    if ($otpData["otp_code"] !== $otp) {
        echo json_encode(["success" => false, "message" => "Incorrect OTP."]);
        exit;
    }

    // Check if OTP is expired
    if (strtotime($otpData["expires_at"]) < time()) {
        echo json_encode(["success" => false, "message" => "OTP has expired. Request a new one."]);
        exit;
    }

    // OTP is valid, delete it
    $stmt = $pdo->prepare("DELETE FROM otp_codes WHERE email = ?");
    $stmt->execute([$email]);

    echo json_encode(["success" => true, "message" => "OTP verified. Proceeding to next step."]);
}