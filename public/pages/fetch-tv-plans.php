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
    // Get service_id for 'tv'
    $stmt = $pdo->prepare("SELECT id FROM services WHERE slug = 'tv' LIMIT 1");
    $stmt->execute();
    $service = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$service) {
        echo json_encode(["success" => false, "message" => "Service 'tv' not found"]);
        exit;
    }

    $service_id = $service['id'];

    // Fetch TV plans
    $plansStmt = $pdo->prepare("
        SELECT id AS plan_id, price, name, validity
        FROM service_plans
        WHERE service_id = ?
        AND provider_id = ?
        AND is_active = 1
        ORDER BY price ASC
    ");
    $plansStmt->execute([$service_id, $provider_id]);
    $plans = $plansStmt->fetchAll(PDO::FETCH_ASSOC);

    if (empty($plans)) {
        echo json_encode(["success" => false, "message" => "No plans found", "plans" => $plans]);
        exit;
    }

    echo json_encode([
        "success" => true,
        "plans" => $plans
    ]);
    exit;
} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB error: " . $e->getMessage()]);
    exit;
}
