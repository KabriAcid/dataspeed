<?php
require __DIR__ . '/../../../config/config.php';

// Get user's virtual account details
function getVirtualAccount($user_id) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM virtual_accounts WHERE user_id = :user_id LIMIT 1");
    $stmt->execute(['user_id' => $user_id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Assign virtual account if none exists
function assignVirtualAccount($user_id, $email, $first_name, $last_name) {
    $account = getVirtualAccount($user_id);
    if ($account) return $account; // Skip if already assigned

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
        $stmt = $pdo->prepare("INSERT INTO virtual_accounts (user_id, account_number, bank_name, flw_ref, created_at)
                               VALUES (:user_id, :account_number, :bank_name, :flw_ref, NOW())");
        $stmt->execute([
            'user_id' => $user_id,
            'account_number' => $result['data']['account_number'],
            'bank_name' => $result['data']['bank_name'],
            'flw_ref' => $result['data']['flw_ref']
        ]);
        return $result['data'];
    } else {
        echo "Failed to create virtual account: " . $result['message'];
        return null;
    }
}

// Fetch user data from the database
function getUserDetails($registration_id, $email, $phone_number) {
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM users WHERE registration_id = :registration_id AND email = :email AND phone_number = :phone_number LIMIT 1");
    $stmt->execute([
        'registration_id' => $registration_id,
        'email' => $email,
        'phone_number' => $phone_number
    ]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Example usage with dynamic user data from registration process
$registration_id = $_SESSION['registration_id'] ?? null;
$email = $_SESSION['email'] ?? null;
$phone_number = $_SESSION['phone_number'] ?? null;

$user = getUserDetails($registration_id, $email, $phone_number);

if (!$user) {
    die("User not found.");
}

$user_id = $user['id'];
$first_name = $user['first_name'];
$last_name = $user['last_name'];

$account = assignVirtualAccount($user_id, $email, $first_name, $last_name);
?>