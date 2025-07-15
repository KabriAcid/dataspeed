<?php

define("INACTIVITY_TIMEOUT", 10 * 60);

// Check if the user is logged in
if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit;
}

// Check for inactivity
if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity'] > INACTIVITY_TIMEOUT)) {
    // Redirect to lock screen after 5 seconds of inactivity
    header('Location: session-lock.php');
    exit;
}

$user_id = $_SESSION['user_id'];

// Update last activity timestamp
$_SESSION['last_activity'] = time();