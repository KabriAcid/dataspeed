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

    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Generate referral code
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

    // Create virtual account via Billstack
    function createVirtualAccount($email, $reference, $firstName, $lastName, $phone_number)
    {
        $secretKey = $_ENV['BILLSTACK_SECRET_KEY'];

        $data = [
            "email" => $email,
            "reference" => $reference,
            "firstName" => $firstName,
            "lastName" => $lastName,
            "phone" => $phone_number,
            "bank" => "PALMPAY"
        ];

        $payload = json_encode($data);

        $ch = curl_init('https://api.billstack.co/v2/thirdparty/generateVirtualAccount/');
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Authorization: Bearer ' . $secretKey,
            'Content-Type: application/json',
        ]);

        $response = curl_exec($ch);
        $curlError = curl_error($ch);
        curl_close($ch);

        if ($curlError) {
            return ["error" => true, "api_response" => "cURL Error: $curlError"];
        }

        $decoded = json_decode($response, true);

        // Always return decoded response, even on failure
        if (!$decoded || !isset($decoded['data']['account'][0]['account_number'])) {
            return ["error" => true, "message" => "Invalid response from Billstack", "api_response" => $response];
        }

        return [
            "account_number" => $decoded['data']['account'][0]['account_number'],
            "bank_name" => $decoded['data']['account'][0]['bank_name'],
            "account_name" => $decoded['data']['account'][0]['account_name'],
            "billstack_ref" => $decoded['data']['reference'] ?? null,
            "api_response" => $decoded
        ];
    }

    try {
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

        // Format phone number (ensure it starts with 0 and is 11 digits)
        $phone_number = preg_replace('/[^0-9]/', '', $user['phone_number']);
        if (strlen($phone_number) === 10) {
            $phone_number = '0' . $phone_number;
        }

        $reference = uniqid('ref_');
        $referralCode = generateReferralCode($pdo);

        $virtualAccount = createVirtualAccount($email, $reference, $firstName, $lastName, $phone_number);

        if (isset($virtualAccount['error']) && $virtualAccount['error'] === true) {
            echo json_encode([
                "success" => false,
                "api_response" => $virtualAccount['api_response']
            ]);
            exit;
        }

        // Hashed password
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Update user with virtual account data
        $stmt = $pdo->prepare("UPDATE users SET password = ?, referral_code = ?, registration_status = 'complete', 
        virtual_account = ?, bank_name = ?, account_name = ?, billstack_ref = ? WHERE registration_id = ?");
        $stmt->execute([
            $hashedPassword,
            $referralCode,
            $virtualAccount['account_number'],
            $virtualAccount['bank_name'],
            $virtualAccount['account_name'],
            $virtualAccount['billstack_ref'],
            $registration_id
        ]);

        echo json_encode([
            "success" => true,
            "message" => "Registration complete. Virtual account created.",
            "account_number" => $virtualAccount['account_number'],
            "bank_name" => $virtualAccount['bank_name'],
            "api_response" => $virtualAccount['api_response']
        ]);

        // After successful registration and you have $userId (new user ID)
        if (isset($_SESSION['referral_code']) && !empty($_SESSION['referral_code'])) {
            // Get referrer user_id using referral_code from session
            $stmt = $pdo->prepare("SELECT user_id FROM users WHERE referral_code = ?");
            $stmt->execute([$_SESSION['referral_code']]);
            $userId = $stmt->fetchColumn();

            if ($userId) {
                $insertReferral = $pdo->prepare("INSERT INTO referrals (user_id, referral_code, referral_link, reward, status, created_at) 
            VALUES (?, ?, ?, ?, ?, NOW())");

                $referralLink = "https://dataspeed.com/public/pages/backend/register?referral_code=" . $_SESSION['referral_code'];

                $insertReferral->execute([
                    $userId,
                    $_SESSION['referral_code'],
                    $referralLink,
                    100,
                    'pending'
                ]);
            }
            // Optionally clear the referral_code session after use
            unset($_SESSION['referral_code']);
        }
    } catch (Exception $e) {
        echo json_encode([
            "success" => false,
            "message" => "Server error: " . $e->getMessage()
        ]);
    }
}