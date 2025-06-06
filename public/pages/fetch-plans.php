<?php

require __DIR__ . '/../../config/config.php';

header('Content-Type: application/json');

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    
    $network = trim($_POST["network"] ?? '');
    
    if (empty($network)) {
        echo json_encode(["success" => false, "message" => "Network not specified"]);
        exit;
    }
    
    // Fetch the Service ID for "Data"
    $serviceQuery = $pdo->prepare("SELECT id FROM services WHERE slug = 'data'");
    $serviceQuery->execute();
    $dataService = $serviceQuery->fetch(PDO::FETCH_ASSOC);
    
    if (!$dataService) {
        echo json_encode(["success" => false, "message" => "Service 'data' not found"]);
        exit;
    }
    
    // Fetch data plans for the selected network
    $plansQuery = $pdo->prepare("SELECT name, price, type FROM service_plans WHERE service_id = ? AND is_active = 1");
    $plansQuery->execute([$dataService["id"]]);
    $plans = $plansQuery->fetchAll(PDO::FETCH_ASSOC);
    
    if (!$plans) {
        echo json_encode(["success" => false, "message" => "No data plans available"]);
        exit;
    }
    
    echo json_encode(["success" => true, "plans" => $plans]);
    
}