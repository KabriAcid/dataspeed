<?php
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $token = $_POST['token'] ?? '';
    $type = $_POST['type'] ?? 'password';
    $email = $_POST['email'] ?? '';

    if (empty($token)) {
        echo json_encode(["success" => false, "message" => "Token is required."]);
        exit;
    }
    if (empty($email)) {
        echo json_encode(["success" => false, "message" => "Email is required."]);
        exit;
    }

    $table = ($type === 'pin') ? 'forgot_pin' : 'forgot_password';

    try {
        $stmt = $pdo->prepare("SELECT token FROM `$table` WHERE token = ? AND email = ?");
        $stmt->execute([$token, $email]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            echo json_encode(["success" => true, "message" => "Token verified."]);
        } else {
            echo json_encode(["success" => false, "message" => "Invalid or expired token."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
