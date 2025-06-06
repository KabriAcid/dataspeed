<?php
require __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

$network = trim($_POST["network"] ?? '');

if (empty($network)) {
    echo json_encode(["success" => false, "message" => "Network not specified"]);
    exit;
}

// Fetch the service ID for the selected network
$serviceQuery = $pdo->prepare("SELECT id FROM services WHERE slug = ?");
$serviceQuery->execute([$network]);
$service = $serviceQuery->fetch(PDO::FETCH_ASSOC);

if (!$service) {
    echo json_encode(["success" => false, "message" => "Invalid network"]);
    exit;
}

// Fetch active plans for the selected network
$plansQuery = $pdo->prepare("SELECT name, price, type FROM service_plans WHERE service_id = ? AND is_active = 1");
$plansQuery->execute([$service["id"]]);
$plans = $plansQuery->fetchAll(PDO::FETCH_ASSOC);

if (!$plans) {
    echo json_encode(["success" => false, "message" => "No plans available for this network"]);
    exit;
}

echo json_encode(["success" => true, "plans" => $plans]);
