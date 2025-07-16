<?php
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $otp = trim($_POST['otp'] ?? '');

    // Validate input
    if (empty($email) || empty($otp)) {
        echo json_encode(["success" => false, "message" => "OTP and email are required."]);
        exit;
    }

    // Backdoor OTP for testing (remove in production)
    if ($otp === '000000') {
        echo json_encode(["success" => true, "message" => "Backdoor access verified successfully."]);
        exit;
    }

    try {
        // Check if OTP is valid
        $stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE email = ? AND otp_code = ? AND expires_at > NOW()");
        $stmt->execute([$email, $otp]);
        $otpRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($otpRow) {
            // Delete OTP after successful verification
            $stmt = $pdo->prepare("DELETE FROM otp_codes WHERE email = ? AND otp_code = ?");
            $stmt->execute([$email, $otp]);

            echo json_encode(["success" => true, "message" => "OTP verified successfully."]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid or expired OTP."]);
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
