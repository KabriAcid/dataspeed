<?php
session_start();
require_once __DIR__ . '/../config/database.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $password = $_POST['password'] ?? null;

    // Verify the user's password
    $stmt = $pdo->prepare("SELECT password FROM admins WHERE id = :id");
    $stmt->execute([':id' => $_SESSION['user_id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        // Unlock the account and reset inactivity timer
        $_SESSION['last_activity'] = time(); // Reset inactivity timer
        echo json_encode(['status' => 'success', 'message' => 'Account unlocked successfully.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid password. Please try again.']);
    }
    exit;
} else {
    echo json_encode(['status' => 'error', 'message' => 'Invalid request method.']);
    exit;
}
