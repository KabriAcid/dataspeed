<?php
session_start();
require __DIR__ . '/../../config/config.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reason = trim($_POST['reason'] ?? '');
    $user_id = $_SESSION['locked_user_id'] ?? null;

    // Validate input
    if (empty($reason)) {
        echo json_encode(["success" => false, "message" => "Please select a reason."]);
        exit;
    }

    if (!$user_id) {
        echo json_encode(["success" => false, "message" => "User session expired. Please log in again."]);
        exit;
    }

    try {
        // Insert the complaint into the database with a default status of 'pending'
        $stmt = $pdo->prepare("INSERT INTO account_complaints (user_id, reason, status) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $reason, 'pending']);

        echo json_encode(["success" => true, "message" => "Complaint submitted successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
