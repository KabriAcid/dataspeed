<?php
date_default_timezone_set('Africa/Lagos');

require __DIR__ . '/../vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->load();

$DB_HOST = $_ENV['DB_HOST'];
$DB_USER = $_ENV['DB_USER'];
$DB_PASS = $_ENV['DB_PASS'];
$DB_NAME = $_ENV['DB_NAME'];

try {
    $pdo = new PDO("mysql:host=$DB_HOST;dbname=$DB_NAME", $DB_USER, $DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

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
            (time() - $_SESSION['last_activity'] > 600)
        ) {
            // Session expired
            session_unset();
            session_destroy();
            header("Location: /public/pages/login.php?expired=1");
            exit;
        } else {
            $_SESSION['last_activity'] = time();
        }
    }
}