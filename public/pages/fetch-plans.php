<?php
session_start();
require __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');

// ✅ Ensure POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// ✅ Sanitize input
$provider_id = isset($_POST['provider_id']) ? intval($_POST['provider_id']) : 0;
$type = trim($_POST['type'] ?? '');

if ($provider_id <= 0 || empty($type)) {
    echo json_encode(["success" => false, "message" => "Missing provider or type"]);
    exit;
}

try {
    // ✅ Get service_id for 'data'
    $stmt = $pdo->prepare("SELECT id FROM services WHERE slug = 'data' LIMIT 1");
    $stmt->execute();
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        echo json_encode(["success" => false, "message" => "Service 'data' not found"]);
        exit;
    }

    $service_id = $service['id'];

    // ✅ Fetch plans using ? placeholders
    $plansStmt = $pdo->prepare("
        SELECT id, name, price, api_id 
        FROM service_plans 
        WHERE service_id = ? 
        AND provider_id = ? 
        AND type = ? 
        AND is_active = 1
    ");
    $plansStmt->execute([$service_id, $provider_id, $type]);

    $plans = $plansStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($plans)) {
        echo json_encode(["success" => false, "message" => "No plans found"]);
        exit;
    }

    echo json_encode(["success" => true, "plans" => $plans]);
    exit;

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    exit;
}
