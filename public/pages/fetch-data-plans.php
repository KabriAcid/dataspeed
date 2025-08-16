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
$type = strtolower(trim($_POST['type'] ?? ''));
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
    // Fetch candidate plans; we'll bucket by validity below (so <7 days => daily)
    $plansStmt = $pdo->prepare("
                SELECT variation_code AS plan_id, price, base_price, volume, validity, type 
                FROM service_plans 
                WHERE service_id = ? 
                AND provider_id = ? 
                AND is_active = 1
                ORDER BY price ASC
            ");
    $plansStmt->execute([$service_id, $provider_id]);
    $allPlans = $plansStmt->fetchAll(PDO::FETCH_ASSOC);

    // Convert validity strings to days
    $toDays = function ($validity) {
        if (!$validity) return null;
        $v = strtolower(trim((string)$validity));
        if (preg_match('/(\d+)\s*(hour|hr|hrs|hours)/i', $v, $m)) {
            return max(0.0, ((int)$m[1]) / 24.0);
        }
        if (preg_match('/(\d+)\s*(day|days)/i', $v, $m)) {
            return (int)$m[1];
        }
        if (preg_match('/(\d+)\s*(week|weeks|wk|wks)/i', $v, $m)) {
            return (int)$m[1] * 7;
        }
        if (preg_match('/(\d+)\s*(month|months|mo|mos)/i', $v, $m)) {
            return (int)$m[1] * 30;
        }
        if (preg_match('/(\d+)/', $v, $m)) {
            return (int)$m[1];
        }
        return null;
    };

    $bucketed = ['daily' => [], 'weekly' => [], 'monthly' => []];
    foreach ($allPlans as $p) {
        $days = $toDays($p['validity'] ?? '');
        $dbType = strtolower((string)($p['type'] ?? ''));
        if ($days !== null) {
            if ($days < 7) {
                $bucket = 'daily';
            } elseif ($days < 30) {
                $bucket = 'weekly';
            } else {
                $bucket = 'monthly';
            }
        } else {
            $bucket = in_array($dbType, ['daily', 'weekly', 'monthly'], true) ? $dbType : 'monthly';
        }
        $bucketed[$bucket][] = [
            'plan_id' => $p['plan_id'],
            'price' => $p['price'],
            'base_price' => $p['base_price'] ?? null,
            'volume' => $p['volume'] ?? '',
            'validity' => $p['validity'] ?? '',
        ];
    }

    $plans = $bucketed[$type] ?? [];

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
    error_log("DB error in fetch-data-plans: " . $e->getMessage());
    echo json_encode(["success" => false, "message" => "DB error: " . $e->getMessage()]);
    exit;
}
