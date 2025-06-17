<?php
session_start();
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $user = $_POST['user'] ?? '';
    $password = $_POST['password'] ?? '';

    // Validate input
    if (empty($user) || empty($password)) {
        echo json_encode(["success" => false, "message" => "Both user and password are required."]);
        exit;
    }

    // Determine input type using regex
    if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
        $field = 'email';
        $value = $user;
    } elseif (preg_match('/^\+?\d{10,15}$/', $user) || preg_match('/^0\d{10}$/', $user)) {
        // Normalize phone number for comparison
        $normalizedPhone = preg_replace('/^(\+234|234|0)/', '', $user);
        if (strlen($normalizedPhone) === 10) {
            $normalizedPhone = '0' . $normalizedPhone;
        }
        $field = 'phone_number';
        $value = $normalizedPhone;
    } else {
        $field = 'user_name'; // or 'username' if that's your column name
        $value = $user;
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE $field = ?");
        $stmt->execute([$value]);
        $userRow = $stmt->fetch();

        if (!$userRow) {
            echo json_encode(["success" => false, "message" => "Invalid user or password."]);
            exit;
        }

        // Check account active status in the database
        if ($userRow['account_status'] == 'Frozen') {
            echo json_encode(["success" => false, "message" => "Account is frozen. Try again in 1 hour."]);
            exit;
        }
        if ($userRow['account_status'] == 'Banned') {
            echo json_encode(["success" => false, "message" => "Account is banned. Contact support."]);
            exit;
        }
        if ($userRow['account_status'] == 'Inactive') {
            echo json_encode(["success" => false, "message" => "Account is inactive. Please activate your account."]);
            exit;
        }

        if (password_verify($password, $userRow['password'])) {
            $_SESSION['user_id'] = $userRow['user_id'];
            echo json_encode(["success" => true, "message" => "Login successful."]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid user or password."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}