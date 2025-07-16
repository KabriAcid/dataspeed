<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    if (!isset($_SESSION['user_id'])) {
        echo json_encode(["success" => false, "message" => "Unauthorized access."]);
        exit;
    }

    $email = trim($_POST["email"] ?? '');
    $user_id = $_SESSION['user_id'];

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(["success" => false, "message" => "Invalid email format."]);
        exit;
    }

    try {
        $stmt = $pdo->prepare("SELECT email, first_name, last_name, city, phone_number, registration_status FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if (!$user) {
            echo json_encode(["success" => false, "message" => "User not found."]);
            exit;
        }

        if ($user['registration_status'] != 'complete') {

            $userName  = $user['first_name'] . ' ' . $user['last_name'];

            $title = "Money Transfer failed";
            $message = $userName . " " . "must complete registration before receiving any funds.";
            $type = 'transfer_fail';
            $icon = 'ni ni-fat-remove';
            $color = 'text-danger';

            pushNotification($pdo, $user_id, $title, $message, $type, $icon, $color, '0');

            echo json_encode(["success" => false, "message" => "User must complete registration."]);
            exit;
        } else {
            echo json_encode(["success" => true, "data" => $user]);
            exit;
        }
    } catch (PDOException $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
}
