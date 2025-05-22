<?php
session_start();
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';
require __DIR__ . '/../../partials/header.php';


header('Content-Type: application/json');
// Handle POST requests for updating password or PIN
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Trim inputs
    $newPassword = trim($_POST['newPassword'] ?? '');
    $confirmPassword = trim($_POST['confirmPassword'] ?? '');
    $newPin = trim($_POST['newPin'] ?? '');
    $confirmPin = trim($_POST['confirmPin'] ?? '');

    try {
        if ($newPassword !== '' && $confirmPassword !== '') {
            // Validate passwords match and meet basic length requirements
            if ($newPassword !== $confirmPassword) {
                throw new Exception('Passwords do not match.');
            }
            if (strlen($newPassword) < 6) {
                throw new Exception('Password must be at least 6 characters.');
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
            $stmt->execute(['password' => $hashedPassword, 'user_id' => $user_id]);

            echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
            exit;
        } elseif ($newPin !== '' && $confirmPin !== '') {
            // Validate PIN format and matching
            if ($newPin !== $confirmPin) {
                throw new Exception('PINs do not match.');
            }
            if (!preg_match('/^\d{4}$/', $newPin)) {
                throw new Exception('PIN must be exactly 4 digits.');
            }

            $stmt = $pdo->prepare("UPDATE users SET txn_pin = :pin WHERE user_id = :user_id");
            $stmt->execute(['pin' => $newPin, 'user_id' => $user_id]);

            echo json_encode(['success' => true, 'message' => 'PIN updated successfully.']);
            exit;
        } else {
            throw new Exception('Please fill in all required fields.');
        }
    } catch (Exception $e) {
        echo json_encode(['success' => false, 'message' => $e->getMessage()]);
        exit;
    }
}
?>