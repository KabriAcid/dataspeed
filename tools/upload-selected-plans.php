<?php
// upload-selected-plans.php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/utilities.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$plans = $_POST['plans'] ?? [];

if (empty($plans)) {
    echo json_encode(["success" => false, "message" => "No plans submitted."]);
    exit;
}

$inserted = 0;
$skipped = 0;

try {
    $pdo->beginTransaction();

    foreach ($plans as $plan) {
        if (!isset($plan['selected']) || $plan['selected'] != '1') {
            $skipped++;
            continue;
        }

        $variation_id   = intval($plan['variation_id'] ?? 0);
        $service_id     = htmlspecialchars($plan['service_id'] ?? '');
        $service_name   = htmlspecialchars($plan['service_name'] ?? '');
        $plan_name      = htmlspecialchars($plan['data_plan'] ?? '');
        $price          = floatval($plan['price'] ?? 0);
        $reseller_price = floatval($plan['reseller_price'] ?? 0);
        $validity       = htmlspecialchars($plan['validity'] ?? '');
        $is_active      = intval($plan['is_active'] ?? 0);

        if ($variation_id <= 0 || !$service_id || !$plan_name || $price <= 0) {
            $skipped++;
            continue;
        }

        // Get service_id and provider_id from DB
        $stmt = $pdo->prepare("SELECT s.id as service_id, p.id as provider_id
                              FROM services s
                              JOIN providers p ON p.slug = ?
                              WHERE s.slug = 'data' LIMIT 1");
        $stmt->execute([strtolower($service_id)]);
        $mapping = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$mapping) {
            $skipped++;
            continue;
        }

        $db_service_id  = $mapping['service_id'];
        $db_provider_id = $mapping['provider_id'];

        $insert = $pdo->prepare("INSERT INTO service_plans (
            service_id, provider_id, variation_code, name, price, reseller_price, validity, type, is_active, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        $insert->execute([
            $db_service_id,
            $db_provider_id,
            $variation_id,
            $plan_name,
            $price,
            $reseller_price,
            $validity,
            'general', // or you can parse based on plan name
            $is_active
        ]);

        $inserted++;
    }

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "Plans processed successfully.",
        "inserted" => $inserted,
        "skipped" => $skipped
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    echo json_encode([
        "success" => false,
        "message" => "DB Error: " . $e->getMessage()
    ]);
}
