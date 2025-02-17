<?php
require __DIR__ . '/../../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? '');

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit;
    }

    // Check if email exists in the database
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
    $stmt->execute([$email]);
    
    if ($stmt->fetch()) {
        
        echo json_encode(["success" => false, "message" => "Email already exists. Try another."]);
    } else {
        echo json_encode(["success" => true]);
    }
}
?>
