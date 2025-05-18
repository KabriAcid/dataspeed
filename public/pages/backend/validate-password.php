<?php

require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';

header("Content-Type: application/json");
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $password = $_POST['password'] ?? '';
    $registration_id = $_POST['registration_id'] ?? '';

    if (empty($password) || empty($registration_id)) {
        echo json_encode(["success" => false, "message" => "Password and Registration ID are required."]);
        exit;
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Generate unique 10-digit alphanumeric referral code
    function generateReferralCode($pdo)
    {
        do {
            $referralCode = substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, 10);
            $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE referral_code = ?");
            $stmt->execute([$referralCode]);
            $exists = $stmt->fetchColumn();
        } while ($exists);
        return $referralCode;
    }

    // Create a virtual account via Billstack
    function createVirtualAccount($email, $reference, $firstName, $lastName, $phone_number)
    {
        $secretKey = $_ENV['BILLSTACK_SECRET_KEY'];
        $url = $_ENV['BILLSTACK_API_URL'];
        
        $data = [
            "email" => $email,
            "reference" => $reference,
            "firstName" => $firstName,
            "lastName" => $lastName,
            "phone_number" => $phone_number,
            "bank" => "PALMPAY"
        ];

        $payload = json_encode($data);

        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $secretKey,
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($status === 200 || $status === 201) {
            $result = json_decode($response, true);
            return $result['data'] ?? null;
        }

        return null;
    }

    try {
        // Get user details
        $stmt = $pdo->prepare("SELECT email, first_name, last_name, phone_number FROM users WHERE registration_id = ?");
        $stmt->execute([$registration_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(["success" => false, "message" => "User not found."]);
            exit;
        }

        $email = $user['email'];
        $firstName = $user['first_name'];
        $lastName = $user['last_name'];
        $phone_number = $user['phone_number'];
        $reference = uniqid('ref_'); // Unique reference for Billstack

        // Generate referral code
        $referralCode = generateReferralCode($pdo);

        // Create virtual account
        $virtualAccount = createVirtualAccount($email, $reference, $firstName, $lastName, $phone_number);

        if (!$virtualAccount) {
            echo json_encode(["success" => false, "message" => "Failed to create virtual account."]);
            exit;
        }

        // OPTIONAL: Debug virtual account before inserting
        
        // echo json_encode([
        //     "success" => true,
        //     "message" => "Debug only",
        //     "account_number" => $virtualAccount['account_number'],
        //     "bank_name" => $virtualAccount['bank_name']
        // ]);
        // exit;
        

        // Update user data
        $stmt = $pdo->prepare("
            UPDATE users 
            SET password = ?, referral_code = ?, registration_status = 'complete', 
                virtual_account = ?, bank_name = ?
            WHERE registration_id = ?
        ");
        $stmt->execute([
            $hashedPassword,
            $referralCode,
            $virtualAccount['account_number'],
            $virtualAccount['bank_name'],
            $registration_id
        ]);

        echo json_encode([
            "success" => true,
            "message" => "Registration complete. Virtual account created.",
            "account_number" => $virtualAccount['account_number'],
            "bank_name" => $virtualAccount['bank_name']
        ]);
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Error: " . $e->getMessage()
        ]);
    }
}