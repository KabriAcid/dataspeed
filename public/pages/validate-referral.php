<?php
session_start();
require __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $referral_code = trim($_POST["referral_code"] ?? '');

    try {
        // Generate and store registration_id in session
        $registration_id = md5(uniqid());
        $_SESSION['registration_id'] = $registration_id;
        $_SESSION['referral_code'] = $referral_code !== "" ? $referral_code : null;


        if ($referral_code) {
            // if referral code is provided, check if it exists in the database
            $stmt = $pdo->prepare("SELECT referral_code, registration_status FROM users WHERE referral_code = ?");
            $stmt->execute([$referral_code]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);


            if (!$user) {
                echo json_encode(["success" => false, "message" => "Invalid referral code."]);
                exit;
            }
            if ($user['registration_status'] != 'complete') {
                echo json_encode(["success" => false, "message" => "Referrer registration incomplete."]);
                exit;
            }

            // Insert reffered_by into the database
            $stmt = $pdo->prepare("UPDATE users SET referred_by = ? WHERE registration_id = ?");
            $stmt->execute([$user['referral_code'], $_SESSION['registration_id']]);
        }

        echo json_encode([
            "success" => true,
            "message" => $referral_code !== "" ? "Referral code valid." : "Proceeding...",
            "registration_id" => $registration_id
        ]);
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}