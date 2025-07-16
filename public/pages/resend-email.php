<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/sendMail.php';

header("Content-Type: application/json");

$user_id = $_SESSION['locked_user_id'] ?? null;

if (!$user_id) {
    echo json_encode(["success" => false, "message" => "User session expired. Please log in again."]);
    exit;
}

try {
    // Fetch user details
    $stmt = $pdo->prepare("SELECT email, first_name FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["success" => false, "message" => "User not found."]);
        exit;
    }

    // Fetch the latest token
    $stmt = $pdo->prepare("SELECT token FROM account_reset_tokens WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$user_id]);
    $token = $stmt->fetchColumn();

    if (!$token) {
        echo json_encode(["success" => false, "message" => "No reset token found."]);
        exit;
    }

    // Compose the email
    $resetLink = "http://localhost/dataspeed/public/pages/complain_form.php?token=" . urlencode($token);
    $emailContent = "
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f9f9f9;
                        color: #333;
                        margin: 0;
                        padding: 0;
                    }
                    .email-container {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #ffffff;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        overflow: hidden;
                    }
                    .email-header {
                        background-color: #94241e;
                        color: #ffffff;
                        padding: 20px;
                        text-align: center;
                    }
                    .email-header img {
                        width: 50px;
                        height: 50px;
                        margin-bottom: 10px;
                    }
                    .email-body {
                        padding: 20px;
                    }
                    .email-body h3 {
                        color: #94241e;
                    }
                    .email-body a {
                        display: inline-block;
                        margin-top: 20px;
                        padding: 10px 20px;
                        background-color: #94241e;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 5px;
                        font-weight: bold;
                    }
                    .email-body a:hover {
                        background-color: #94241e;
                    }
                    .email-footer {
                        text-align: center;
                        padding: 10px;
                        font-size: 12px;
                        color: #666;
                        border-top: 1px solid #ddd;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='email-header'>
                        <img src='https://dataspeed.com/public/favicon.png' alt='DataSpeed Logo'>
                        <h2>DataSpeed Support</h2>
                    </div>
                    <div class='email-body'>
                        <h3>Account Reset Instructions</h3>
                        <p>Dear {$user['first_name']},</p>
                        <p>Your account has been locked due to multiple failed attempts. To reset your account, please click the link below:</p>
                        <p><a href='{$resetLink}'>Reset Your Account</a></p>
                        <p>If you did not request this, please ignore this email.</p>
                        <p>Thank you,<br>DataSpeed Support Team</p>
                    </div>
                    <div class='email-footer'>
                        <p>&copy; " . date("Y") . " DataSpeed. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
        ";

    // Send the email
    if (sendMail($user['email'], "Account Reset Instructions", $emailContent)) {

        $stmt = $pdo->prepare("UPDATE account_reset_tokens SET expires_at =? WHERE user_id = ?");
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));
        $stmt->execute([$expiresAt, $user_id]);
        

        echo json_encode(["success" => true, "message" => "Email sent successfully. Please check your inbox"]);
    } else {
        error_log("Failed to send email");
        echo json_encode(["success" => false, "message" => "Failed to resend email."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
