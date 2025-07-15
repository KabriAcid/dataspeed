<?php
session_start();
require_once __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? null;

    // Verify the user's password
    $stmt = $pdo->prepare("SELECT password FROM users WHERE user_id = :user_id");
    $stmt->execute([':user_id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Unlock the account and reset inactivity timer
        $_SESSION['last_activity'] = time();
        echo json_encode(['status' => 'success', 'message' => 'Account unlocked successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid password. Please try again.']);
    }
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}
