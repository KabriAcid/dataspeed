<?php
session_start();
require __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user = trim($_POST['user'] ?? ''); // This can be username, email, or phone number
    $password = trim($_POST['password'] ?? '');

    // Validate input
    if (empty($user) || empty($password)) {
        echo json_encode(['success' => false, 'message' => 'Username, Email or Phone and password are required.']);
        exit;
    }

    // Query to check username, email, or phone number
    $stmt = $pdo->prepare("
        SELECT * 
        FROM users 
        WHERE (user_name = :user OR email = :user OR phone_number = :user) 
        LIMIT 1
    ");
    $stmt->execute(['user' => $user]);
    $userData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userData) {
        echo json_encode(['success' => false, 'message' => 'Invalid login credentials.']);
        exit;
    }

    // Check if the account is frozen
    if ($userData['account_status'] == ACCOUNT_STATUS_LOCKED) {
        $_SESSION['locked_user_id'] = $userData['user_id'];
        echo json_encode([
            'success' => false,
            'locked' => true,
            'message' => 'Your account is locked due to multiple failed login attempts.',
            'redirect' => 'account_locked.php'
        ]);
        exit;
    }

    // Verify the password
    if (password_verify($password, $userData['password'])) {
        $_SESSION['user_id'] = $userData['user_id'];
        echo json_encode(['success' => true, 'message' => 'Login successful.']);
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid login credentials.']);
    }
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request.']);
exit;
