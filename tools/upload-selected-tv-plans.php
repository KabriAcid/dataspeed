<?php
require __DIR__ . '/../config/config.php';
require __DIR__ . '/../functions/utilities.php';

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
    exit;
}

$plans = $_POST['selected_tv_plans'] ?? [];

if (empty($plans)) {
    echo json_encode(["success" => false, "message" => "No TV plans submitted."]);
    exit;
}

$inserted = 0;
$skipped = 0;

try {
    $pdo->beginTransaction();

    foreach ($plans as $planJson) {
        $plan = json_decode($planJson, true);
        if (!$plan || !isset($plan['variation_id'])) {
            $skipped++;
            continue;
        }

        $variation_id   = intval($plan['variation_id']);
        $service_id     = strtolower(trim($plan['service_id'] ?? ''));
        $service_name   = trim($plan['service_name'] ?? '');
        $plan_name      = trim($plan['plan_name'] ?? '');
        $price          = isset($plan['price']) ? floatval($plan['price']) : 0;
        $reseller_price = isset($plan['reseller_price']) ? floatval($plan['reseller_price']) : 0;
        $validity       = trim($plan['validity'] ?? '');
        $is_active      = isset($plan['availability']) && strtolower($plan['availability']) === 'available' ? 1 : 0;

        // Basic validation
        if ($variation_id <= 0 || !$service_id || !$plan_name || $price <= 0) {
            $skipped++;
            continue;
        }

        // Get service_id and provider_id from DB (no JOINs)
        $stmt = $pdo->prepare("SELECT id FROM services WHERE slug = 'tv' LIMIT 1");
        $stmt->execute();
        $serviceRow = $stmt->fetch(PDO::FETCH_ASSOC);

        $stmt = $pdo->prepare("SELECT id FROM service_providers WHERE slug = ? LIMIT 1");
        $stmt->execute([$service_id]);
        $providerRow = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$serviceRow || !$providerRow) {
            $skipped++;
            continue;
        }

        $db_service_id  = $serviceRow['id'];
        $db_provider_id = $providerRow['id'];

        $insert = $pdo->prepare("INSERT INTO service_plans (
            service_id, provider_id, variation_code, plan_name, price, reseller_price, validity, type, is_active, created_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())");

        $insert->execute([
            $db_service_id,
            $db_provider_id,
            $variation_id,
            $plan_name,
            $price,
            $reseller_price ?? null,
            $validity,
            'tv',
            $is_active
        ]);

        $inserted++;
    }

    $pdo->commit();

    echo json_encode([
        "success" => true,
        "message" => "TV plans processed successfully.",
        "inserted" => $inserted,
        "skipped" => $skipped
    ]);
} catch (PDOException $e) {
    $pdo->rollBack();
    error_log("DB error in upload-selected-tv-plans: " . $e->getMessage());
    echo json_encode([
        "success" => false,
        "message" => "DB Error: " . $e->getMessage()
    ]);
}
