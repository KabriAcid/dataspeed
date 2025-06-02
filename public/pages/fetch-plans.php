<?php

require __DIR__ . '/../../config/config.php';

// Set headers for JSON response
header("Content-Type: application/json");

try {
    // Fetch active providers
    $providersStmt = $pdo->prepare("SELECT id, name FROM service_providers");
    $providersStmt->execute();
    $providers = $providersStmt->fetchAll(PDO::FETCH_ASSOC);

    // Fetch data plans for each provider
    $plansStmt = $pdo->prepare("SELECT * FROM service_plans WHERE is_active = 1 ORDER BY price ASC");
    $plansStmt->execute();
    $plans = $plansStmt->fetchAll(PDO::FETCH_ASSOC);

    // Structure response
    echo json_encode([
        "success" => true,
        "providers" => $providers,
        "plans" => $plans
    ]);
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => "Error fetching plans: " . $e->getMessage()]);
}

?>
