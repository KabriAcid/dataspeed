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
$type = trim($_POST['plan'] ?? '');
$provider_id = $_SESSION['provider_id'] ?? 0; // OR pass via JS

if (empty($type) || $provider_id <= 0) {
    echo json_encode(["success" => false, "message" => "Invalid type or provider"]);
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

    // ✅ Fetch plans
    $plansStmt = $pdo->prepare("
        SELECT id, name, price, api_id, volume, validity 
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

    // ✅ Generate HTML
    ob_start();
    foreach ($plans as $plan):
    ?>
        <div class="col-4">
            <div class="plan-card" data-id="<?= $plan['id'] ?>" data-price="<?= $plan['price'] ?>" data-volume="<?= $plan['volume'] ?>">
                <div class="data-price">₦<?= number_format($plan['price']) ?></div>
                <div class="data-volume"><?= htmlspecialchars($plan['volume']) ?></div>
                <div class="data-validity"><?= htmlspecialchars($plan['validity'] ?? '') ?></div>
            </div>
        </div>
    <?php
    endforeach;
    $html = ob_get_clean();

    echo json_encode(["success" => true, "html" => $html]);
    exit;

} catch (PDOException $e) {
    echo json_encode(["success" => false, "message" => "DB error: " . $e->getMessage()]);
    exit;
}
