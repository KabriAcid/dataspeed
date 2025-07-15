<?php
define("INACTIVITY_TIMEOUT", 5);

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = getUserInfo($pdo, $user_id);
} else {
    header('Location: ../pages/login.php');
    exit;
}

// Check for inactivity
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > INACTIVITY_TIMEOUT)) {
    // Redirect to lock screen after 5 seconds of inactivity
    header('Location: auth_modal.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Update last activity timestamp
$_SESSION['last_activity'] = time();
