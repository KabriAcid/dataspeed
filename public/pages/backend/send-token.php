<?php

require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/sendMail.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';

    if (empty($email)) {
        echo json_encode(["success" => false, "message" => "Email is required."]);
        exit;
    }

    // Generate token
    $token = md5(uniqid());
    $tokenExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    try {
        // Check if the email exists in the users table and registration is complete
        $stmt = $pdo->prepare("SELECT user_id, registration_status FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(["success" => false, "message" => "Email does not exist. Check for typo."]);
            exit;
        }

        if ($user['registration_status'] !== 'complete') {
            echo json_encode(["success" => false, "message" => "Registration not complete."]);
            exit;
        }

        // Insert token into forgot_password table or update if it already exists
        
        
        $stmt = $pdo->prepare("INSERT INTO forgot_password (email, token, expires_at)
        VALUES (?, ?, ?)ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)");
        $stmt->execute([$email, $token, $tokenExpiry]);

        // Prepare the email content
        $subject = "Password Reset Token";
        $body = "<p>Your password reset token is:</p> <br><div style='background-color:#eee;padding:12px;border-radius:8px;'><h3 style='text-align:center'>$token</h3></div>";

        // Use the custom sendMail() function to send the email
        if (sendMail($email, $subject, $body)) {
            echo json_encode(["success" => true, "message" => "Token sent to your email."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to send email."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
