<?php
session_start();
require '../../config/config.php';

$user_id = $_SESSION['user_id'] ?? null;
$setting = $_POST['setting'] ?? '';
$value = $_POST['value'] ?? '';

if ($user_id && $setting === 'session_expiry_enabled') {
    $stmt = $pdo->prepare("UPDATE user_settings SET session_expiry_enabled = ? WHERE user_id = ?");
    $stmt->execute([$value, $user_id]);
    // Also update session cache
    $_SESSION['session_expiry_enabled'] = $value;
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false]);
}