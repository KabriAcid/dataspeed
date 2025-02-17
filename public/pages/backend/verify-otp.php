<?php
require __DIR__ . '/../../../config/config.php'; // Load database connection
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $otp = $_POST["otp"] ?? '';

    if (empty($otp) || strlen($otp) !== 6) {
        echo json_encode(["success" => false, "message" => "Invalid OTP."]);
        exit;   
    }

    // Retrieve OTP from the database
    $stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE otp_code = ? AND expires_at > NOW() LIMIT 1");
    $stmt->execute([$otp]);
    $otpRecord = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($otpRecord) {
        // OTP is valid, delete it to prevent reuse
        $stmt = $pdo->prepare("DELETE FROM otp_codes WHERE otp_code = ?");
        $stmt->execute([$otp]);

        echo json_encode(["success" => true, "message" => "OTP verified successfully."]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid or expired OTP."]);
    }
}
