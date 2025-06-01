<?php
// MUST BE FIRST LINE — no space above this!
ob_start();
session_start();

header("Content-Type: application/json");

// Required files
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/sendMail.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST['email'] ?? '';

    // Generate token and expiration
    $token = md5(uniqid());
    $tokenExpiry = date('Y-m-d H:i:s', strtotime('+10 minutes'));

    try {
        // Check if user exists and registration is complete
        $stmt = $pdo->prepare("SELECT user_id, registration_status FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            ob_clean();
            echo json_encode(["success" => false, "message" => "Email does not exist. Check for typo."]);
            exit;
        }

        if ($user['registration_status'] !== 'complete') {
            ob_clean();
            echo json_encode(["success" => false, "message" => "Registration not complete."]);
            exit;
        }

        // Insert or update token
        $stmt = $pdo->prepare("
            INSERT INTO forgot_password (email, token, expires_at)
            VALUES (?, ?, ?)
            ON DUPLICATE KEY UPDATE token = VALUES(token), expires_at = VALUES(expires_at)
        ");
        $stmt->execute([$email, $token, $tokenExpiry]);

        // Email content
        $subject = "Password Reset Token";
        $body = "<p>Your password reset token is:</p><br>
            <div style='background-color:#eee;padding:12px;border-radius:8px;'>
                <h3 style='text-align:center'>$token</h3>
            </div>";

        // Send email
        if (sendMail($email, $subject, $body)) {
            ob_clean();
            echo json_encode(["success" => true, "message" => "Token sent to your email."]);
        } else {
            ob_clean();
            echo json_encode(["success" => false, "message" => "Failed to send email."]);
        }
    } catch (Exception $e) {
        ob_clean();
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }

    exit;
} else {
    ob_clean();
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}
