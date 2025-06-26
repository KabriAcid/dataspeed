<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../../partials/initialize.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Ensure user is authenticated
    if (!isset($_SESSION['user_id'])) {
        echo json_encode(['success' => false, 'message' => 'Unauthorized access.']);
        exit;
    }

    $user_id = $_SESSION['user_id'];

    // Trim inputs
    $newPassword = trim($_POST['newPassword'] ?? '');
    $confirmPassword = trim($_POST['confirmPassword'] ?? '');
    $newPin = trim($_POST['newPin'] ?? '');
    $confirmPin = trim($_POST['confirmPin'] ?? '');

    try {
        // Password section
        if ($newPassword !== '' && $confirmPassword !== '') {
            if ($newPassword !== $confirmPassword) {
                throw new Exception('Passwords do not match.');
            }
            if (strlen($newPassword) < 6) {
                throw new Exception('Password must be at least 6 characters.');
            }

            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare("UPDATE users SET password = :password WHERE user_id = :user_id");
            $stmt->execute([
                'password' => $hashedPassword,
                'user_id' => $user_id
            ]);

            // Push notification for password change
            $title = "Password Changed";
            $message = "Your account password was changed successfully.";
            $type = "security";
            $icon = "ni ni-lock-circle-open";
            $color = "text-primary";
            pushNotification($pdo, $user_id, $title, $message, $type, $icon, $color, '0');

            echo json_encode(['success' => true, 'message' => 'Password updated successfully.']);
            exit;
        }

        // PIN section
        elseif ($newPin !== '' && $confirmPin !== '') {
            if ($newPin !== $confirmPin) {
                throw new Exception('PINs do not match.');
            }
            if (!preg_match('/^\d{4}$/', $newPin)) {
                throw new Exception('PIN must be exactly 4 digits.');
            }

            // ✅ Securely hash the PIN
            $hashedPin = password_hash($newPin, PASSWORD_DEFAULT);

            $stmt = $pdo->prepare("UPDATE users SET txn_pin = :pin WHERE user_id = :user_id");
            $stmt->execute([
                'pin' => $hashedPin,
                'user_id' => $user_id
            ]);

            // Push notification for PIN change
            $title = "PIN Changed";
            $message = "Your transaction PIN was changed successfully.";
            $type = "security";
            $icon = "ni ni-key-25";
            $color = "text-warning";
            pushNotification($pdo, $user_id, $title, $message, $type, $icon, $color, '0');

            echo json_encode(['success' => true, 'message' => 'PIN updated successfully.']);
            exit;
        }

        // Nothing submitted
        else {
            throw new Exception('Please fill in the required fields.');
        }
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => htmlspecialchars($e->getMessage(), ENT_QUOTES, 'UTF-8')
        ]);
        exit;
    }
}
