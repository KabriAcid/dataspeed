<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$token = $_POST['token'] ?? null;
$password = $_POST['password'] ?? null;
$confirmPassword = $_POST['confirm_password'] ?? null;

if (!$token || !$password || !$confirmPassword) {
    echo json_encode(["success" => false, "message" => "All fields are required."]);
    exit;
}

if ($password !== $confirmPassword) {
    echo json_encode(["success" => false, "message" => "Passwords do not match."]);
    exit;
}

if (strlen($password) < 6) {
    echo json_encode(["success" => false, "message" => "Password must be at least 6 characters long."]);
    exit;
}

try {
    // Validate the token
    $stmt = $pdo->prepare("SELECT user_id, expires_at FROM account_reset_tokens WHERE token = ?");
    $stmt->execute([$token]);
    $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tokenData) {
        echo json_encode(["success" => false, "message" => "Invalid or expired token."]);
        exit;
    }

    // Check if the token has expired
    if (strtotime($tokenData['expires_at']) < time()) {
        echo json_encode(["success" => false, "message" => "Token has expired."]);
        exit;
    }

    $user_id = $tokenData['user_id'];

    // Hash the new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update the user's password, reset failed attempts, and set account status to active
    $stmt = $pdo->prepare("UPDATE users SET password = ?, account_status = ?, failed_attempts = ? WHERE user_id = ?");
    $stmt->execute([$hashedPassword, ACCOUNT_STATUS_ACTIVE, 0, $user_id]);

    // Update the account complaints table as resolved
    $stmt = $pdo->prepare("UPDATE account_complaints SET status = 'resolved' WHERE user_id = ? AND status = 'pending'");
    $stmt->execute([$user_id]);

    // Push a notification to the user
    $notificationTitle = "Account Unlocked";
    $notificationMessage = "Your account has been successfully unlocked and your password has been updated.";
    pushNotification($pdo, $user_id, $notificationTitle, $notificationMessage, "security", "ni ni-lock-circle-open", "text-success");


    // Delete the token after successful password reset
    $stmt = $pdo->prepare("DELETE FROM account_reset_tokens WHERE token = ?");
    $stmt->execute([$token]);

    // Clear any locked session marker
    if (isset($_SESSION['locked_user_id']) && (int)$_SESSION['locked_user_id'] === (int)$user_id) {
        unset($_SESSION['locked_user_id']);
    }

    // Best-effort activity log (non-blocking)
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        $lstmt = $pdo->prepare('INSERT INTO activity_log (username, action_type, action_description, ip_address, created_at) VALUES (?,?,?,?,NOW())');
        // Fetch user email for logging
        $u = $pdo->prepare('SELECT email FROM users WHERE user_id = ?');
        $u->execute([$user_id]);
        $email = ($u->fetch(PDO::FETCH_ASSOC)['email'] ?? '') ?: (string)$user_id;
        $lstmt->execute([$email, 'account_unlocked', 'User unlocked account via reset link', $ip]);
    } catch (Throwable $e) {
        error_log('Unlock activity log failed: ' . $e->getMessage());
    }

    echo json_encode(["success" => true, "message" => "Password reset successfully."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
