<?php
require __DIR__ . '/../../../config/config.php';
session_start();
header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = trim($_POST["first_name"] ?? '');
    $last_name = trim($_POST["last_name"] ?? '');
    $registration_id = $_SESSION["registration_id"] ?? '';

    if (empty($first_name) || empty($last_name)) {
        echo json_encode(["success" => false, "message" => "Both first and last names are required."]);
        exit;
    }

    // Ensure names are valid (only letters, at least 2 characters)
    if (!preg_match("/^[A-Za-z]{2,}$/", $first_name) || !preg_match("/^[A-Za-z]{2,}$/", $last_name)) {
        echo json_encode(["success" => false, "message" => "Enter a valid first and last name."]);
        exit;
    }

    try {
        // Update user's name based on registration_id
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE registration_id = ?");
        $stmt->execute([$first_name, $last_name, $registration_id]);

        echo json_encode(["success" => true]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error. Please try again later."]);
    }
}
