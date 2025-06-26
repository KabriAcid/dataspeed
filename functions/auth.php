<?php
session_start();
require __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user'] ?? '');
    $password = trim($_POST['password'] ?? '');

    $stmt = $pdo->prepare("SELECT * FROM users WHERE (email = :user OR phone_number = :user) LIMIT 1");
    $stmt->execute(['user' => $user]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        echo json_encode(['status' => 'error', 'message' => 'Invalid login credentials.']);
        exit;
    }

    // Check if the account is frozen
    if ($userData['account_status'] == ACCOUNT_STATUS_FROZEN) {
        $_SESSION['locked_user_id'] = $userData['user_id'];
        echo json_encode([
            'success' => false,
            'status' => 'locked',
            'message' => 'Your account is locked due to multiple failed login attempts.',
            'redirect' => 'account-locked.php',
            'frozen' => true
        ]);
        exit;
    }

    // Verify the password
    if (password_verify($password, $userData['password'])) {
        $_SESSION['user_id'] = $userData['user_id'];
        echo json_encode(['status' => 'success', 'message' => 'Login successful.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid login credentials.']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
exit;
