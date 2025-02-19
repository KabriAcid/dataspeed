<?php
require __DIR__ . '/../../../config/database.php'; // Adjust path as needed

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $phone = $_POST["phone"] ?? '';

    if (empty($phone)) {
        echo json_encode(["success" => false, "message" => "Phone number is required."]);
        exit;
    }

    // Check if the phone exists in the database
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE phone_number = ?");
    $stmt->execute([$phone]);

    if ($stmt->fetch()) {
        echo json_encode(["success" => false, "message" => "Phone number already in use."]);
    } else {
        echo json_encode(["success" => true]);
    }
}
