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

    // Account Frozen
    $account_status = $userData['account_status'];

    if($account_status == ACCOUNT_STATUS_FROZEN){
        
    }

    if ($userData && password_verify($password, $userData['password'])) {
        $_SESSION['user_id'] = $userData;
        echo json_encode(['status' => 'success', 'message' => 'Login successful.']);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Invalid login credentials.']);
    }
    exit;
}

echo json_encode(['status' => 'error', 'message' => 'Invalid request.']);
exit;
