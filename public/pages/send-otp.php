<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/sendMail.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST['email'] ?? '';

    // Generate OTP
    $otp = rand(100000, 999999);
    $expires_at = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    try {
        // Insert or update OTP
        $stmt = $pdo->prepare("INSERT INTO otp_codes (email, otp_code, expires_at) VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE otp_code = VALUES(otp_code), expires_at = VALUES(expires_at)");
        $stmt->execute([$email, $otp, $expires_at]);

        // Send OTP Email
        $subject = "Your OTP Code";
        $body = "
            <div style='font-family: 'Quicksand', sans-serif; max-width: 500px; margin: auto; border: 1px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; background-color: #f9f9f9;'>
                <h2 style='color: #94241E;'>DataSpeed OTP Verification</h2>
                <p style='font-size: 16px; color: #555;'>Use the OTP code below to verify your email:</p>
                <h1 style='font-size: 28px; font-weight: bold; background: #94241E; color: #fff; padding: 10px; border-radius: 5px; display: inline-block;'>$otp</h1>
                <p style='color: #888; font-size: 14px;'>This OTP is valid for 10 minutes. Do not share it with anyone.</p>
                <hr style='margin: 20px 0; border: none; border-top: 1px solid #ddd;'>
                <p style='font-size: 12px; color: #999;'>If you did not request this, please ignore this email.</p>
            </div>
        ";

        sendMail($email, $subject, $body);

        echo json_encode(["success" => true, "message" => "OTP sent successfully.", "otpCode" => $otp, "email" => $email]);
    } catch (PDOException $e) {
        error_log("Error sending OTP: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}