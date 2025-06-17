<?php
// filepath: c:\xampp\htdocs\dataspeed\public\pages\validate-user.php
session_start();
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$user = trim($_POST['user'] ?? '');
$password = $_POST['password'] ?? '';

// Validate input
if ($user === '' || $password === '') {
    echo json_encode(["success" => false, "message" => "Both user and password are required."]);
    exit;
}

// Determine input type and normalize
if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
    $field = 'email';
    $value = strtolower($user);
} elseif (preg_match('/^\+?234\d{10}$/', $user) || preg_match('/^0\d{10}$/', $user)) {
    // Normalize phone number to 11 digits starting with 0
    $normalizedPhone = preg_replace('/^\+?234/', '0', $user); // +234 or 234 to 0
    $normalizedPhone = preg_replace('/^0+/', '0', $normalizedPhone); // Remove extra leading zeros
    if (strlen($normalizedPhone) !== 11) {
        echo json_encode(["success" => false, "message" => "Invalid phone number format."]);
        exit;
    }
    $field = 'phone_number';
    $value = $normalizedPhone;
} else {
    $field = 'user_name'; // or 'username' if that's your column name
    $value = $user;
}

try {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE $field = ? LIMIT 1");
    $stmt->execute([$value]);
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$userRow) {
        echo json_encode(["success" => false, "message" => "Invalid user or password."]);
        exit;
    }

    // Check account status
    switch (strtolower($userRow['account_status'])) {
        case 'frozen':
            echo json_encode(["success" => false, "message" => "Account is frozen. Try again in 1 hour."]);
            exit;
        case 'banned':
            echo json_encode(["success" => false, "message" => "Account is banned. Contact support."]);
            exit;
        case 'inactive':
            echo json_encode(["success" => false, "message" => "Account is inactive. Please activate your account."]);
            exit;
    }

    // Password check
    if (password_verify($password, $userRow['password'])) {
        $_SESSION['user_id'] = $userRow['user_id'];
        echo json_encode(["success" => true, "message" => "Login successful."]);
    } else {
        echo json_encode(["success" => false, "message" => "Invalid user or password."]);
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Database error."]);
}
