<?php
session_start();
header('Content-Type: application/json');

require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        if (isset($_POST['nin'])) {
            $nin = trim($_POST['nin']);
            if (!preg_match('/^\d{11}$/', $nin)) {
                echo json_encode(["success" => false, "message" => 'Invalid NIN. Must be 11 digits.']);
                exit;
            }
            $stmt = $pdo->prepare('UPDATE users SET kyc_value = ?, kyc_type = ?, kyc_status = ? WHERE user_id = ?');
            if ($stmt->execute([$nin, 'NIN', 'verified', $user_id])) {
                // Push notification for admin (first admin, or you can select by role)
                $meta = json_encode(['user_id' => $user_id, 'kyc_type' => 'NIN']);
                $adminNotif = $pdo->prepare("INSERT INTO admin_notifications (type, title, message, meta, is_read, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
                $adminNotif->execute([
                    'user',
                    'User KYC Verified',
                    "User #$user_id has verified NIN.",
                    $meta
                ]);
                // Push notification for user
                $title = "KYC Verified";
                $message = "Your NIN has been verified successfully.";
                $type = "kyc";
                $icon = "ni ni-check-bold";
                $color = "text-success";
                pushNotification($pdo, $user_id, $title, $message, $type, $icon, $color, 0);

                echo json_encode(["success" => true, "message" => 'NIN updated and KYC verified successfully.']);
            } else {
                echo json_encode(["success" => false, "message" => 'Failed to update NIN.']);
            }
            exit;
        }
        if (isset($_POST['bvn'])) {
            $bvn = trim($_POST['bvn']);
            if (!preg_match('/^\d{11}$/', $bvn)) {
                echo json_encode(["success" => false, "message" => 'Invalid BVN. Must be 11 digits.']);
                exit;
            }
            $stmt = $pdo->prepare('UPDATE users SET kyc_value = ?, kyc_type = ?, kyc_status = ? WHERE user_id = ?');
            if ($stmt->execute([$bvn, 'BVN', 'verified', $user_id])) {
                // Push notification for admin
                $meta = json_encode(['user_id' => $user_id, 'kyc_type' => 'BVN']);
                $adminNotif = $pdo->prepare("INSERT INTO admin_notifications (type, title, message, meta, is_read, created_at) VALUES (?, ?, ?, ?, 0, NOW())");
                $adminNotif->execute([
                    'user',
                    'User KYC Verified',
                    "User #$user_id has verified BVN.",
                    $meta
                ]);
                // Push notification for user
                $title = "KYC Verified";
                $message = "Your BVN has been verified successfully.";
                $type = "kyc";
                $icon = "ni ni-check-bold";
                $color = "text-success";
                pushNotification($pdo, $user_id, $title, $message, $type, $icon, $color, 0);

                echo json_encode(["success" => true, "message" => 'BVN updated and KYC verified successfully.']);
            } else {
                echo json_encode(["success" => false, "message" => 'Failed to update BVN.']);
            }
            exit;
        }
        echo json_encode(["success" => false, "message" => 'No valid KYC data provided.']);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}
