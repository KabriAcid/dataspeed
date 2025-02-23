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
        $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? AND registration_status = 'complete'");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(["success" => false, "message" => "User credentials dosen't exists."]);
        } else {
            $token = md5(uniqid());
            $stmt = $pdo->prepare("INSERT INTO forgot_password (token, email) VALUES (?, ?) ON DUPLICATE KEY UPDATE token = VALUES (token)");
            $stmt->execute([$token, $email]);
            echo json_encode(["success" => true, "message" => "Token sent successfully"]);
            
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
