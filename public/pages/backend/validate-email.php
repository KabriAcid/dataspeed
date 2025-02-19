<?php
require __DIR__ . '/../../../config/config.php';
header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "GET") {
    $email = trim($_GET["email"] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit;
    }

    try {
        // Check if email exists in the database
        $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $exists = $stmt->fetchColumn();

        if ($exists) {
            echo json_encode(["success" => false, "message" => "Email already exists. Try another."]);
            exit;
        }

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error. Please try again later."]);
    }
}
