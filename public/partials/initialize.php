<?php
session_start();
define("INACTIVITY_TIMEOUT", 600);

if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = getUserInfo($pdo, $user_id);

    if (!$user) {
        // User not found, force logout
        header("Location: logout.php");
        exit();
    }

    $account_status = $user['account_status'];

    if ($account_status != ACCOUNT_STATUS_ACTIVE) {
        header("Location: logout.php");
        exit();
    }
} else {
    header('Location: login.php');
    exit;
}

// Check for inactivity
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > INACTIVITY_TIMEOUT)) {
    header('Location: auth_modal.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Update last activity timestamp
$_SESSION['last_activity'] = time();
