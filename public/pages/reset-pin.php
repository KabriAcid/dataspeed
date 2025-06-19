<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';

header("Content-Type: application/json");

session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $pin = $_POST['pin'] ?? '';
    $reset_email = $_POST['reset_email'] ?? '';

    if (!preg_match('/^\d{4}$/', $pin)) {
        echo json_encode(["success" => false, "message" => "PIN must be exactly 4 digits."]);
        exit;
    }

    $hashedPin = password_hash($pin, PASSWORD_DEFAULT);

    try {
        // Update the user's PIN
        $stmt = $pdo->prepare("UPDATE users SET txn_pin = ? WHERE email = ?");
        $stmt->execute([$hashedPin, $reset_email]);

        // Remove the token from forgot_pin table
        $stmt = $pdo->prepare("DELETE FROM forgot_pin WHERE email = ?");
        $stmt->execute([$reset_email]);

        // Fetch user_id for notification
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE email = ?");
        $stmt->execute([$reset_email]);
        $user = $stmt->fetch();

        if ($user && isset($user['user_id'])) {
            pushNotification(
                $pdo,
                $user['user_id'],
                "PIN Reset Successful",
                "Your transaction PIN was reset successfully.",
                "pin_reset",
                "ni ni-key-55",
                "text-success",
                0
            );
        }

        echo json_encode(["success" => true, "message" => "PIN reset successfully."]);
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}