<?php
session_start();
// Required files
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/sendMail.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';
    $type = $_POST['type'] ?? 'password'; // default to password

    // Generate token and expiration
    $token = md5(uniqid());
    $tokenExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    try {
        // Check if user exists and registration is complete
        $stmt = $pdo->prepare("SELECT user_id, registration_status, account_status FROM users WHERE email = ?");
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

        if ($user['account_status'] !== ACCOUNT_STATUS_ACTIVE) {
            echo json_encode(["success" => false, "message" => "Account is not active."]);
            exit;
        }

        // Choose table and email content based on type
        if ($type === 'pin') {
            $table = 'forgot_pin';
            $subject = "PIN Reset Token";
            $body = "<p>Your PIN reset token is:</p><br>
                <div style='background-color:#eee;padding:12px;border-radius:8px;'>
                    <h3 style='text-align:center'>$token</h3>
                </div>";
        } else {
            $table = 'forgot_password';
            $subject = "Password Reset Token";
            $body = "<p>Your password reset token is:</p><br>
                <div style='background-color:#eee;padding:12px;border-radius:8px;'>
                    <h3 style='text-align:center'>$token</h3>
                </div>";
        }

        // Insert or update token
        $stmt = $pdo->prepare("
            INSERT INTO `$table` (email, token, expires_at)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)
        ");
        $stmt->execute([$email, $token, $tokenExpiry]);

        // Send email
        if (sendMail($email, $subject, $body)) {
            echo json_encode(["success" => true, "message" => "Token sent to your email."]);
        } else {
            echo json_encode(["success" => false, "message" => "Failed to send email."]);
        }
    } catch (Exception $e) {
        error_log("Error in send-token.php: " . $e->getMessage());
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }

    exit;
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}
