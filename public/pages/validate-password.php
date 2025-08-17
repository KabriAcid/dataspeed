<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';

header("Content-Type: application/json");

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$password = $_POST['password'] ?? '';
$registration_id = $_POST['registration_id'] ?? '';

if (empty($password) || empty($registration_id)) {
    echo json_encode(["success" => false, "message" => "Password and Registration ID are required."]);
    exit;
}

try {
    // Fetch user info
    $stmt = $pdo->prepare("SELECT email, first_name, last_name, phone_number, virtual_account FROM users WHERE registration_id = ?");
    $stmt->execute([$registration_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        echo json_encode(["success" => false, "message" => "User not found."]);
        exit;
    }

    // If virtual account already exists, return it without calling API again
    if (!empty($user['virtual_account'])) {
        echo json_encode([
            "success" => true,
            "message" => "User already has a virtual account.",
            "account_number" => $user['virtual_account']
        ]);
        exit;
    }

    // Helper: generate referral code
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

    // Helper: create virtual account from Billstack
    function createVirtualAccount($email, $reference, $firstName, $lastName, $phone_number)
    {
        $secretKey = $_ENV['BILLSTACK_SECRET_KEY'];

        $bank_type = ["9PSB", "PALMPAY"];
        // $random = floor(rand(0, 1));

        $data = [
            "email" => $email,
            "reference" => $reference,
            "firstName" => $firstName,
            "lastName" => $lastName,
            "phone" => $phone_number,
            "bank" => $bank_type[1]
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
            error_log("cURL Error: $curlError");
            return ["success" => false, "message" => "cURL Error: $curlError"];
        }

        $decoded = json_decode($response, true);

        if (!$decoded) {
            return ["success" => false, "message" => "Failed to decode API response."];
        }

        if (!isset($decoded['data']['account'][0]['account_number'])) {
            return ["success" => false, "message" => "Invalid response from Billstack API.", "api_response" => $decoded];
        }

        return [
            "account_number" => $decoded['data']['account'][0]['account_number'],
            "bank_name" => $decoded['data']['account'][0]['bank_name'],
            "account_name" => $decoded['data']['account'][0]['account_name'],
            "billstack_ref" => $decoded['data']['reference'] ?? null,
            "api_response" => $decoded
        ];
    }

    // Prepare data for API call
    $email = $user['email'];
    $firstName = $user['first_name'];
    $lastName = $user['last_name'];

    // Clean phone number to digits only
    $phone_number = preg_replace('/\D/', '', $user['phone_number']);

    // Normalize phone number for Nigeria if needed (e.g., add leading 0 if 10 digits)
    if (strlen($phone_number) === 10 && $phone_number[0] !== '0') {
        $phone_number = '0' . $phone_number;
    }

    // Generate unique reference for Billstack - ensure uniqueness
    function generateUUIDv4()
    {
        $data = random_bytes(16);
        $data[6] = chr((ord($data[6]) & 0x0f) | 0x40); // version 4
        $data[8] = chr((ord($data[8]) & 0x3f) | 0x80); // variant
        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }

    $reference = 'ref_' . generateUUIDv4();

    // Call API to create virtual account
    $virtualAccount = createVirtualAccount($email, $reference, $firstName, $lastName, $phone_number);

    if (isset($virtualAccount['success']) && $virtualAccount['success'] === false) {
        echo json_encode([
            "success" => false,
            "message" => $virtualAccount['message'] ?? 'Error creating virtual account',
            "api_response" => $virtualAccount['api_response'] ?? null
        ]);
        exit;
    }

    // Generate referral code for user
    $referralCode = generateReferralCode($pdo);

    $referralLink = "https://dataspeed.com.ng/public/pages/register.php?referral_code=" . $referralCode;

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update user with virtual account details and password
    $stmt = $pdo->prepare("UPDATE users SET password = ?, referral_code = ?, referral_link = ?, registration_status = 'complete', 
        virtual_account = ?, bank_name = ?, account_name = ?, billstack_ref = ? WHERE registration_id = ?");

    $updateSuccess = $stmt->execute([
        $hashedPassword,
        $referralCode,
        $referralLink,
        $virtualAccount['account_number'],
        $virtualAccount['bank_name'],
        $virtualAccount['account_name'],
        $virtualAccount['billstack_ref'],
        $registration_id
    ]);

    if (!$updateSuccess) {
        $errorInfo = $stmt->errorInfo();
        echo json_encode(["success" => false, "message" => "Failed to update user data.", "error" => $errorInfo]);
        exit;
    }

    // Get user ID from registration ID in the users table
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE registration_id = ?");
    $stmt->execute([$registration_id]);
    $user_id = $stmt->fetchColumn();

    if (!$user_id) {
        echo json_encode(["success" => false, "message" => "User ID not found after registration."]);
        exit;
    }

    $wallet_balance = 0;

    // Check if account_balance already exists for this user
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM account_balance WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $balanceExists = $stmt->fetchColumn();

    if (!$balanceExists) {
        try {
            $stmt = $pdo->prepare("INSERT INTO account_balance (user_id, wallet_balance, email, phone_number) VALUES (?, ?, ?, ?)");
            $stmt->execute([
                $user_id,
                $wallet_balance,
                $user['email'],
                $user['phone_number']
            ]);
        } catch (PDOException $th) {
            echo json_encode([
                "success" => false,
                "message" => "Account balance creation failed.",
                "error" => $th->getMessage()
            ]);
            exit;
        }
    }

    // Insert user settings along with ip address
    try {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        $stmt = $pdo->prepare("INSERT INTO user_settings (user_id, biometrics_enabled, hide_balance, session_expiry_enabled, ip_address) VALUES (?, ?, ?, ?, ?)");
        $stmt->execute([
            $user_id,
            0,
            0,
            1, // Default to enabled
            $ipAddress
        ]);
    } catch (PDOException $th) {
        echo json_encode([
            "success" => false,
            "message" => "User settings failed to update.",
        ]);
        exit;
    }

    // Insert notification for user in the db
    $title = 'Virtual Account Created';
    $message = 'Congratulations! Your virtual account has been created successfully.';
    $type = 'virtual_account';
    $icon = 'ni ni-building';
    $color = 'text-success';
    pushNotification($pdo, $user_id, $title, $message, $type, $icon, $color, '0');

    // Insert notification for PIN not set
    $pinTitle = 'Set Your Transaction PIN';
    $pinMessage = 'For your security, please set your transaction PIN to enable transactions.';
    $pinType = 'security';
    $pinIcon = 'ni ni-key-25';
    $pinColor = 'text-warning';
    pushNotification($pdo, $user_id, $pinTitle, $pinMessage, $pinType, $pinIcon, $pinColor, '0');

    // Handle referral logic (optional)
    if (isset($_SESSION['referral_code']) && !empty($_SESSION['referral_code'])) {
        $stmt = $pdo->prepare("SELECT user_id FROM users WHERE referral_code = ?");
        $stmt->execute([$_SESSION['referral_code']]);
        $referrerId = $stmt->fetchColumn();

        if ($referrerId) {
            $insertReferral = $pdo->prepare("INSERT INTO referral_reward (user_id, referee_email, reward, status, created_at) 
                VALUES (?, ?, ?, ?, NOW())");

            $insertReferral->execute([
                $referrerId,
                $email,
                100,
                'pending'
            ]);
            // Push notification for referral reward
            $title = "Referral Reward Earned";
            $message = "You have earned a referral reward! Go to your rewards page to claim it.";
            $type = "referral";
            $icon = "ni ni-diamond";
            $color = "text-info";
            pushNotification($pdo, $referrerId, $title, $message, $type, $icon, $color, '0');
        }

        unset($_SESSION['referral_code']);
    }

    // FINAL SUCCESS RESPONSE (only here!)
    echo json_encode([
        "success" => true,
        "message" => "Registration complete.",
        "account_number" => $virtualAccount['account_number'],
        "bank_name" => $virtualAccount['bank_name'],
        "api_response" => $virtualAccount['api_response']
    ]);
    exit;
} catch (Exception $e) {
    echo json_encode([
        "success" => false,
        "message" => "Server error: " . $e->getMessage()
    ]);
}
