<?php
session_start();
require '../../config/config.php';
require '../../functions/Model.php';

$user_id = $_SESSION['user_id'] ?? null;
$setting = $_POST['setting'] ?? '';
$value = $_POST['value'] ?? '';

if ($user_id && $setting === 'session_expiry_enabled') {
    // Check if user already has a settings row
    $stmt = $pdo->prepare("SELECT 1 FROM user_settings WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $exists = $stmt->fetchColumn();

    if ($exists) {
        // Update existing row
        $stmt = $pdo->prepare("UPDATE user_settings SET session_expiry_enabled = ? WHERE user_id = ?");
        $stmt->execute([$value, $user_id]);
    } else {
        // Insert new row
        $stmt = $pdo->prepare("INSERT INTO user_settings (user_id, session_expiry_enabled) VALUES (?, ?)");
        $stmt->execute([$user_id, $value]);
    }

    $_SESSION['session_expiry_enabled'] = $value;

    // Push notification
    $title = "Security Setting Updated";
    $message = $value
        ? "Session expiry is now enabled. Your account will require re-authentication after 10 minutes of inactivity."
        : "Session expiry is now disabled. You will remain logged in unless you log out.";
    $type = 'security';
    $icon = 'ni-lock-circle-open';
    $color = $value ? 'text-success' : 'text-warning';

    pushNotification($pdo, $user_id, $title, $message, $type, $icon, $color, 0);

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}