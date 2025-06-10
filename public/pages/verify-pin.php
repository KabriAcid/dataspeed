<?php
session_start();
require __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request."]);
    exit;
}

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized access."]);
    exit;
}

$user_id = $_SESSION['user_id'];
$pin = trim($_POST['pin'] ?? '');

if (empty($pin)) {
    echo json_encode(["success" => false, "message" => "PIN is required."]);
    exit;
}

// Fetch user and PIN
$stmt = $pdo->prepare("SELECT txn_pin FROM users WHERE user_id = ?");
$stmt->execute([$user_id]);
$user = $stmt->fetch();

if (!$user) {
    echo json_encode(["success" => false, "message" => "User not found."]);
    exit;
}

if ($user['txn_pin'] === null || $user['txn_pin'] === '') {
    echo json_encode(["success" => false, "message" => "No Transaction PIN set."]);
    exit;
}

$pinValid = false;
if (strlen($user['txn_pin']) === 4 && is_numeric($user['txn_pin'])) {
    // Plain 4-digit PIN (not recommended for production)
    $pinValid = ($pin === $user['txn_pin']);
} else {
    // Hashed PIN (recommended)
    $pinValid = password_verify($pin, $user['txn_pin']);
}

if (!$pinValid) {
    echo json_encode(["success" => false, "message" => "Incorrect PIN."]);
    exit;
}

echo json_encode([
    "success" => true,
    "message" => "PIN verified successfully."
]);
exit;
