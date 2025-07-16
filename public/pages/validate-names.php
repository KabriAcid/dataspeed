<?php
session_start();
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $first_name = ucwords($_POST['first_name']) ?? '';
    $last_name = ucwords($_POST['last_name']) ?? '';
    $registration_id = $_POST['registration_id'] ?? '';

    // Validate input
    if (empty($first_name) || empty($last_name)) {
        echo json_encode(["success" => false, "message" => "Both first name and last name are required."]);
        exit;
    }

    $namePattern = "/^[A-Za-z\s'-]+$/";

    if (!preg_match($namePattern, $first_name) || !preg_match($namePattern, $last_name)) {
        echo json_encode(["success" => false, "message" => "Enter valid names."]);
        exit;
    }

    try {
        // Update the user's first name and last name using registration_id
        $stmt = $pdo->prepare("UPDATE users SET first_name = ?, last_name = ? WHERE registration_id = ?");
        $stmt->execute([$first_name, $last_name, $registration_id]);

        echo json_encode(["success" => true, "message" => "Names updated successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
