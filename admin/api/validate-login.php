<?php
// Admin Login Validation API
session_start();
header('Content-Type: application/json');

require_once '../../config/config.php';
require_once '../../functions/utilities.php';

// Basic input
$email = trim($_POST['email'] ?? '');

if ($email === '') {
    echo json_encode([
        'success' => false,
        'message' => 'Email is required.',
    ]);
    exit;
}

try {
    // Look up the admin by email
    $stmt = $pdo->prepare('SELECT id, status, COALESCE(NULLIF(otp_secret, ""), NULL) AS otp_secret FROM admins WHERE email = ? LIMIT 1');
    $stmt->execute([$email]);
    $admin = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$admin) {
        echo json_encode([
            'success' => true,
            'exists' => false,
            'status' => null,
            'otp_required' => false,
            'message' => 'No account found with this email.',
        ]);
        exit;
    }

    echo json_encode([
        'success' => true,
        'exists' => true,
        'status' => $admin['status'],
        'otp_required' => !empty($admin['otp_secret']),
        'message' => $admin['status'] === 'active' ? 'Account is active.' : 'Account is not active.',
    ]);
} catch (Throwable $e) {
    error_log('Admin validate-login error: ' . $e->getMessage());
    echo json_encode([
        'success' => false,
        'message' => 'Validation failed. Try again.',
    ]);
}
