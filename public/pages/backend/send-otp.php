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
        $body = "<p>Your OTP code is: <strong>$otp</strong></p><p>It will expire in 10 minutes.</p>";
        sendMail($email, $subject, $body);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
