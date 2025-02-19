<?php

require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/sendMail.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"] ?? '';

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email address."]);
        exit;
    }

    // Generate 6-digit OTP
    $otp = rand(100000, 999999);
    $expires_at = date("Y-m-d H:i:s", strtotime("+10 minutes"));

    try {
        // Store OTP in database
        $stmt = $pdo->prepare("INSERT INTO otp_codes (email, otp_code, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$email, $otp, $expires_at]);

        // Respond immediately before sending email
        echo json_encode(["success" => true, "message" => "OTP is being sent."]);

        // Send email asynchronously (background process)
        ignore_user_abort(true);  // Allow script to continue if user leaves the page
        set_time_limit(0);        // Prevent timeout
        ob_end_flush();
        flush(); // Flush output buffer

        // Send OTP email
        $subject = "Your OTP Code";
        $body = "
            <div style='font-family: Arial, sans-serif; max-width: 500px; margin: auto; border: 1px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; background-color: #f9f9f9;'>
                <h2 style='color: #722F37;'>DataSpeed OTP Verification</h2>
                <p style='font-size: 16px; color: #555;'>Use the OTP code below to verify your email:</p>
                <h1 style='font-size: 28px; font-weight: bold; background: #722F37; color: #fff; padding: 10px; border-radius: 5px; display: inline-block;'>$otpCode</h1>
                <p style='color: #888; font-size: 14px;'>This OTP is valid for 5 minutes. Do not share it with anyone.</p>
                <hr style='margin: 20px 0; border: none; border-top: 1px solid #ddd;'>
                <p style='font-size: 12px; color: #999;'>If you did not request this, please ignore this email.</p>
            </div>
        ";

        sendMail($email, $subject, $body);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
