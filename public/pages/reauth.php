<?php
session_start();
require '../../config/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Not authenticated']);
    exit;
}

$password = $_POST['password'] ?? '';
$stmt = $pdo->prepare("SELECT password FROM users WHERE id = ?");
$stmt->execute([$_SESSION['user_id']]);
$user = $stmt->fetch();

if ($user && password_verify($password, $user['password'])) {
    unset($_SESSION['reauth_required']);
    $_SESSION['last_activity'] = time();
    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'message' => 'Incorrect password.']);
}