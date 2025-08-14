<?php
session_start();
require_once '../config/config.php';

// Log admin logout if user was logged in
if (isset($_SESSION['user_id']) && isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin') {
    try {
        $stmt = $pdo->prepare("INSERT INTO audit_logs (admin_id, action, entity_type, details, ip_address, created_at) VALUES (?, 'logout', 'admin_session', ?, ?, NOW())");
        $stmt->execute([
            $_SESSION['user_id'],
            json_encode(['session_duration' => time() - ($_SESSION['login_time'] ?? time())]),
            $_SERVER['REMOTE_ADDR']
        ]);
    } catch (Exception $e) {
        error_log("Logout audit error: " . $e->getMessage());
    }
}

// Clear all session data
session_unset();
session_destroy();

// Start new session for flash message
session_start();
$_SESSION['logout_message'] = 'You have been successfully logged out.';

// Redirect to login page
header('Location: /admin/login.php');
exit;
?>