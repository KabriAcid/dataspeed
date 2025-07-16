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

    echo json_encode(["success" => true, "message" => "Password reset successfully."]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
}
