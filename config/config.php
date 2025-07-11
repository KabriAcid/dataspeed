<?php
date_default_timezone_set('Africa/Lagos');

// Error Reporting log
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL);

define('ACCOUNT_STATUS_ACTIVE', 101);
define('ACCOUNT_STATUS_FROZEN', 102);
define('ACCOUNT_STATUS_BANNED', 103);
define('ACCOUNT_STATUS_INACTIVE', 104);

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

    // Check if the user is locked, but only for authenticated users and non-exempt pages
    // if (isset($_SESSION['user_id'])) {
    //     $currentPage = basename($_SERVER['PHP_SELF']);
    //     $exemptPages = ['account-locked.php', 'submit_complaint.php', 'login.php'];

    //     if (!in_array($currentPage, $exemptPages)) {
    //         $stmt = $pdo->prepare("SELECT account_status FROM users WHERE user_id = ?");
    //         $stmt->execute([$_SESSION['user_id']]);
    //         $user = $stmt->fetch();

    //         if ($user && $user['account_status'] == ACCOUNT_STATUS_FROZEN) {
    //             $_SESSION['locked_user_id'] = $_SESSION['user_id'];
    //             header('Location: account-locked.php');
    //             exit;
    //         }
    //     }
    // }
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}
