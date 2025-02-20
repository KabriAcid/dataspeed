<?php
require __DIR__ . '/../../../config/config.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit;
    }

    try {
        $pdo->beginTransaction(); // Start transaction

        // Check if email exists
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $exists = $stmt->fetchColumn();

        if ($exists > 0) {
            $pdo->rollBack();
            echo json_encode(["success" => false, "message" => "Email already exists. Try another."]);
            exit;
        }

        // Generate a unique registration ID
        $registration_id = uniqid();
        $_SESSION['registration_id'] = $registration_id;

        // Insert the new user
        $stmt = $pdo->prepare("INSERT INTO users (email, registration_id) VALUES (:email, :registration_id)");
        $stmt->execute([':email' => $email, ':registration_id' => $registration_id]);

        $pdo->commit();
        echo json_encode(["success" => true, "message" => "Registration successful."]);
    } catch (PDOException $e) {
        $pdo->rollBack();

        if ($e->getCode() == 23000) { // Duplicate entry error
            echo json_encode(["success" => false, "message" => "Email already exists. Try another."]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
        }
    }
}
