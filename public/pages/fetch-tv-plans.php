<?php
session_start();
require __DIR__ . '/../../config/config.php';
header('Content-Type: application/json');

// Ensure POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method"]);
    exit;
}

// Sanitize input
$provider_id = intval($_POST['provider_id'] ?? 0);

if ($provider_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid provider"]);
    exit;
}

try {
    $stmt = $pdo->prepare("SELECT variation_code as plan_id, name, price, validity FROM service_plans WHERE provider_id = ? AND is_active = 1");
    $stmt->execute([$provider_id]);
    $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($plans)) {
        echo json_encode([
            'success' => true,
            'plans' => $plans
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "No plans found",
            "plans" => []
        ]);
    }
    exit;
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB error: " . $e->getMessage()]);
    exit;
}
