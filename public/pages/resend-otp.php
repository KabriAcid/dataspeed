<?php

session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/sendMail.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';

    // Validate email
    if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email address."]);
        exit;
    }

    try {
        // Fetch last resend time from the database
        $stmt = $pdo->prepare("SELECT expires_at, last_resend_time FROM otp_codes WHERE email = ?");
        $stmt->execute([$email]);
        $userRow = $stmt->fetch();

        if (!$userRow) {
            echo json_encode(["success" => false, "message" => "User not found."]);
            exit;
        }

        $lastResendTime = strtotime($userRow['last_resend_time'] ?? 0);
        $currentTime = time();

        // Enforce 3-minute cooldown
        if ($currentTime - $lastResendTime < 180) { 
            $remainingTime = 180 - ($currentTime - $lastResendTime);
            echo json_encode(["success" => false, "message" => "Please wait {$remainingTime} seconds before requesting a new OTP."]);
            exit;
        }

        // Generate new OTP
        $newOTP = rand(100000, 999999);
        $expires_at = date("Y-m-d H:i:s", strtotime("+10 minutes"));
        $last_resend_time = date("Y-m-d H:i:s");

        // Update database
        $stmt = $pdo->prepare("UPDATE otp_codes SET otp_code = ?, expires_at = ?, last_resend_time = ? WHERE email = ?");
        $stmt->execute([$newOTP, $expires_at, $last_resend_time, $email]);

        // Send new OTP via email
        $subject = "Your New OTP Code";
        $body = "
            <div style='font-family: Quicksand, sans-serif; max-width: 500px; margin: auto; border: 1px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; background-color: #f9f9f9;'>
                <h2 style='color: #94241E;'>DataSpeed OTP Resend Request</h2>
                <p style='font-size: 16px; color: #555;'>Here is your new OTP code:</p>
                <h1 style='font-size: 28px; font-weight: bold; background: #94241E; color: #fff; padding: 10px; border-radius: 5px; display: inline-block;'>$newOTP</h1>
                <p style='color: #888; font-size: 14px;'>This OTP remains valid for 10 minutes.</p>
            </div>
        ";

        sendMail($email, $subject, $body);

        echo json_encode(["success" => true, "message" => "New OTP sent successfully!"]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Server error: " . $e->getMessage()]);
    }
}
