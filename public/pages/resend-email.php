<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/sendMail.php';

header("Content-Type: application/json");

// Only allow POST requests (client uses POST)
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

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

    // Simple rate limit: prevent resending within the last 120 seconds
    try {
        $rl = $pdo->prepare("SELECT created_at FROM activity_log WHERE action_type = 'resend_account_reset' AND username = ? ORDER BY created_at DESC LIMIT 1");
        $rl->execute([$user['email']]);
        $last = $rl->fetch(PDO::FETCH_ASSOC);
        if ($last) {
            $lastTs = strtotime($last['created_at']);
            $elapsed = time() - $lastTs;
            $window = 120; // 2 minutes window
            if ($elapsed < $window) {
                $wait = max(1, $window - $elapsed);
                echo json_encode(["success" => false, "message" => "Please wait {$wait}s before resending."]);
                exit;
            }
        }
    } catch (Throwable $e) {
        // Don't block on rate limit check failure
        error_log('Rate limit check failed: ' . $e->getMessage());
    }

    // Fetch the latest token
    $stmt = $pdo->prepare("SELECT token, expires_at FROM account_reset_tokens WHERE user_id = ? ORDER BY created_at DESC LIMIT 1");
    $stmt->execute([$user_id]);
    $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

    // Check if token exists and is valid
    if (!$tokenData || strtotime($tokenData['expires_at']) < time()) {
        // Generate a new token if none exists or the token is expired
        $token = bin2hex(random_bytes(32));
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        $stmt = $pdo->prepare("INSERT INTO account_reset_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $token, $expiresAt]);
    } else {
        // Use the existing token and update its expiration time
        $token = $tokenData['token'];
        $expiresAt = date('Y-m-d H:i:s', strtotime('+1 hour'));

        // Update only the selected latest token's expiry, not all rows for the user
        $stmt = $pdo->prepare("UPDATE account_reset_tokens SET expires_at = ? WHERE user_id = ? AND token = ?");
        $stmt->execute([$expiresAt, $user_id, $token]);
    }

    // Compose the email
    $resetLink = "http://localhost/dataspeed/public/pages/account_unlock.php?token=" . urlencode($token);
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
        // Best-effort admin notification + activity log (won't block response)
        try {
            $nstmt = $pdo->prepare('INSERT INTO admin_notifications (type, title, message, meta, is_read, created_at) VALUES (?,?,?,?,0,NOW())');
            $meta = json_encode(['user_id' => (int)$user_id]);
            $nstmt->execute(['account_locked', 'Reset Email Resent', "A reset email was resent to {$user['email']}.", $meta]);

            $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
            $lstmt = $pdo->prepare('INSERT INTO activity_log (username, action_type, action_description, ip_address, created_at) VALUES (?,?,?,?,NOW())');
            $lstmt->execute([$user['email'], 'resend_account_reset', 'User requested resend of account reset email', $ip]);
        } catch (Throwable $e) {
            error_log('Resend admin notify/log failed: ' . $e->getMessage());
        }

        echo json_encode(["success" => true, "message" => "Email sent successfully. Please check your inbox"]);
    } else {
        error_log("Failed to send email");
        echo json_encode(["success" => false, "message" => "Failed to resend email."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
