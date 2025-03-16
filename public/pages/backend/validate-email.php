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
        // Check if email exists and registration status
        $stmt = $pdo->prepare("SELECT registration_status, registration_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['registration_status'] === 'complete') {
                echo json_encode(["success" => false, "message" => "Email already exists. Try another."]);
                exit;
            } else {
                echo json_encode(["success" => true, "message" => "Resuming incomplete registration.", "registration_id" => $user['registration_id']]);
            }
        } else {
            // Generate a unique registration ID
            $registration_id = md5(uniqid());
            $_SESSION['registration_id'] = $registration_id;

            // Insert the new user with incomplete registration status
            $stmt = $pdo->prepare("INSERT INTO users (email, registration_id, registration_status) VALUES (:email, :registration_id, 'incomplete')");
            $stmt->execute([':email' => $email, ':registration_id' => $registration_id]);

            echo json_encode(["success" => true, "message" => "Registration successful.", "registration_id" => $registration_id]);
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) { // Duplicate entry error
            echo json_encode(["success" => false, "message" => "Email already exists. Try another."]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
        }
    }
}
