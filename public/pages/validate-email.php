<?php
session_start();
require __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = trim($_POST["email"] ?? '');
    $registration_id = $_SESSION['registration_id'] ?? null;
    $referral_code = $_SESSION['referral_code'] ?? null;

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit;
    }

    if (!$registration_id) {
        echo json_encode(["success" => false, "message" => "Registration session expired. Start again."]);
        exit;
    }

    // if (!$referral_code) {
    //     echo json_encode(["success" => false, "message" => "Referral code not set."]);
    //     exit;
    // }

    try {
        $stmt = $pdo->prepare("SELECT registration_status, registration_id FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($user['registration_status'] === 'complete') {
                echo json_encode(["success" => false, "message" => "Email already exists. Try another."]);
                exit;
            } else {
                echo json_encode([
                    "success" => true,
                    "message" => "Resuming incomplete registration.",
                    "registration_id" => $user['registration_id']
                ]);
            }
        } else {
            $stmt = $pdo->prepare("INSERT INTO users (email, registration_id, registration_status, referred_by) 
                                   VALUES (:email, :registration_id, 'incomplete', :referred_by)");
            $stmt->execute([
                ':email' => $email,
                ':registration_id' => $registration_id,
                ':referred_by' => $referral_code
            ]);

            echo json_encode([
                "success" => true,
                "message" => "OTP has been sent to your email.",
                "registration_id" => $registration_id
            ]);
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000) {
            echo json_encode(["success" => false, "message" => "Email already exists. Try another."]);
        } else {
            echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
        }
    }
}