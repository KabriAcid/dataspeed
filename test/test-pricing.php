<?php
// Simple CLI test for admin/api/pricing.php
// Usage: php test/test-pricing.php [action]

// Ensure we get consistent error output for debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Fake a session as if an admin is logged in
session_start();
$_SESSION['admin_id'] = 1;

// Simulate a request
$action = $argv[1] ?? 'plans';
$_SERVER['REQUEST_METHOD'] = 'GET';
$_GET['action'] = $action;

// Buffer output so headers from included file don't break CLI
ob_start();
require __DIR__ . '/../admin/api/pricing.php';
$output = ob_get_clean();

// Print result
echo "\n=== Response ===\n";
echo $output . "\n";
