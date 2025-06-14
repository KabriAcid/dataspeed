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

    try {
        // Check if user is email or phone number
        if (filter_var($user, FILTER_VALIDATE_EMAIL)) {
            // User is email
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
        } else {
            // Sanitize and format phone number
            $user = preg_replace('/^(\+234|234|0)/', '', $user);

            // Ensure leading zero and 11 digits
            if (strlen($user) === 10) {
                $user = '0' . $user;
            }
            
            $stmt = $pdo->prepare("SELECT * FROM users WHERE phone_number = ?");
        }

        
        $stmt->execute([$user]);
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
        // Banned
        if ($userRow['account_status'] == 'Banned') {
            echo json_encode(["success" => false, "message" => "Account is banned. Contact support."]);
            exit;
        }
        // Inactive
        if ($userRow['account_status'] == 'Inactive') {
            echo json_encode(["success" => false, "message" => "Account is inactive. Please activate your account."]);
            exit;
        }


        if ($userRow && password_verify($password, $userRow['password'])) {
            // Set session variables
            $_SESSION['user_id'] = $userRow['user_id'];

            echo json_encode(["success" => true, "message" => "Login successful."]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid user or password."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}