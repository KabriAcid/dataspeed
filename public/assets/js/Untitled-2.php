<?php
require __DIR__ . '/../../../config/config.php';
header('Content-Type: application/json');

// Create a virtual account using Flutterwave's API
function createVirtualAccount($user_id, $email, $first_name, $last_name) {
    $url = "https://api.flutterwave.com/v3/virtual-account-numbers";
    $headers = [
        "Authorization: Bearer " . $_ENV['FLW_SECRET_KEY'],
        "Content-Type: application/json"
    ];

    $data = [
        "email" => $email,
        "tx_ref" => uniqid("txn_"),
        "is_permanent" => true,
        "first_name" => $first_name,
        "last_name" => $last_name
    ];

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
    $response = curl_exec($ch);
    curl_close($ch);

    $result = json_decode($response, true);

    if ($result && $result['status'] === "success") {
        global $pdo;
        $stmt = $pdo->prepare("INSERT INTO virtual_accounts (user_id, account_number, bank_name, flw_ref, created_at) VALUES (:user_id, :account_number, :bank_name, :flw_ref, NOW())");
        $stmt->execute([
            'user_id' => $user_id,
            'account_number' => $result['data']['account_number'],
            'bank_name' => $result['data']['bank_name'],
            'flw_ref' => $result['data']['flw_ref']
        ]);
        return $result['data'];
    } else {
        return ["error" => $result['message'] ?? 'Failed to create virtual account.'];
    }
}

// Fetch user data from the database
function getUserDetails($registration_id, $email, $phone_number)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE registration_id = :registration_id AND email = :email AND phone_number = :phone_number LIMIT 1");
    $stmt->execute([
        'registration_id' => $registration_id,
        'email' => $email,
        'phone_number' => $phone_number
    ]);
    return $stmt->fetch();
}

getUserDetails($registration_id, $email, $phone_number);

echo json_encode($account);
?>
