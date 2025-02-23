<?php

require __DIR__ . '/../../../config/config.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $otp = $_POST['otp'] ?? '';

    try {
        // Check if the OTP is valid
        $stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE email = ? AND otp_code = ? AND expires_at > NOW()");
        $stmt->execute([$email, $otp]);
        $otpRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($otpRow) {
            // OTP is valid, delete the entry
            $stmt = $pdo->prepare("DELETE FROM otp_codes WHERE email = ? AND otp_code = ?");
            $stmt->execute([$email, $otp]);

            echo json_encode(["success" => true, "message" => "OTP verified successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid or expired OTP."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
