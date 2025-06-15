<?php
$stmt = $pdo->prepare("SELECT session_expiry_enabled FROM user_settings WHERE user_id = ?");
$stmt->execute([$_SESSION['user_id']]);
$userSettings = $stmt->fetch();

// Session expiry check (10 minutes = 600 seconds)
if (session_status() === PHP_SESSION_ACTIVE || session_status() === PHP_SESSION_NONE) {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    // Only check if user is logged in
    if (isset($_SESSION['user_id'])) {
        // Fetch user settings from DB (if not already in session)
        if (!isset($_SESSION['session_expiry_enabled'])) {
            $stmt = $pdo->prepare("SELECT session_expiry_enabled FROM user_settings WHERE user_id = ?");
            $stmt->execute([$_SESSION['user_id']]);
            $settings = $stmt->fetch();
            $_SESSION['session_expiry_enabled'] = $settings['session_expiry_enabled'] ?? 1;
        }

        if (
            $_SESSION['session_expiry_enabled'] &&
            isset($_SESSION['last_activity']) &&
            (time() - $_SESSION['last_activity'] > 10)
        ) {
            $_SESSION['reauth_required'] = true;
        } else {
            $_SESSION['last_activity'] = time();
            unset($_SESSION['reauth_required']);
        }
    }
}
