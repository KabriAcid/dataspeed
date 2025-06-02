<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/sendMail.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $email = $_POST['email'] ?? '';

    // Fetch previous OTP request time
    $stmt = $pdo->prepare("SELECT expires_at FROM otp_codes WHERE email = ?");
    $stmt->execute([$email]);
    $userRow = $stmt->fetch();

    if (!$userRow) {
        echo json_encode(["success" => false, "message" => "User not found."]);
        exit;
    }

    $lastRequestTime = $_SESSION['last_otp_request'] ?? 0;
    
    // Enforce 3-minute cooldown
    if (time() - $lastRequestTime < 180) {
        echo json_encode(["success" => false, "message" => "Please wait before requesting a new OTP."]);
        exit;
    }

    $_SESSION['last_otp_request'] = time(); // Store last request time

    // Generate and store new OTP
    $newOTP = rand(100000, 999999);
    $expires_at = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    $stmt = $pdo->prepare("UPDATE otp_codes SET otp_code = ?, expires_at = ? WHERE email = ?");
    $stmt->execute([$newOTP, $expires_at, $email]);

    // Send new OTP email
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

    echo json_encode(["success" => true, "message" => "New OTP sent successfully."]);
}
