<?php
//fetch-plans.php
session_start();
require __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');

// Ensure POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// Sanitize input
$type = trim($_POST['type'] ?? '');
$provider_id = intval($_POST['provider_id'] ?? 0);

if (empty($type) || $provider_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid type or provider"]);
    exit;
}

try {
    // Get service_id for 'data'
    $stmt = $pdo->prepare("SELECT id FROM services WHERE slug = 'data' LIMIT 1");
    $stmt->execute();
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        echo json_encode(["success" => false, "message" => "Service 'data' not found"]);
        exit;
    }

    $service_id = $service['id'];

    // Fetch plans
    $plansStmt = $pdo->prepare("
        SELECT variation_code AS plan_id, price, volume, validity 
        FROM service_plans 
        WHERE service_id = ? 
        AND provider_id = ? 
        AND type = ? 
        AND is_active = 1
        ORDER BY price ASC
    ");
    $plansStmt->execute([$service_id, $provider_id, $type]);
    $plans = $plansStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($plans)) {
        echo json_encode(["success" => false, "message" => "No plans found"]);
        exit;
    }

    // Return as JSON array (not HTML)
    echo json_encode([
        "success" => true,
        "plans" => $plans
    ]);
    exit;

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB error: " . $e->getMessage()]);
    exit;
}