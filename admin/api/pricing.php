<?php
session_start();
header('Content-Type: application/json');

// Check authentication
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require __DIR__ . '/../../config/config.php';

$method = $_SERVER['REQUEST_METHOD'];

if ($method === 'GET') {
    $action = $_GET['action'] ?? '';
    handleGetRequest($action, $pdo);
} else if ($method === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    $action = $input['action'] ?? '';
    handlePostRequest($action, $input, $pdo);
} else {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
}

function handleGetRequest($action, $pdo)
{
    switch ($action) {
        case 'plans':
            getServicePlans($pdo);
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function handlePostRequest($action, $input, $pdo)
{
    switch ($action) {
        case 'updatePlanPrice':
            updatePlanPrice($input, $pdo);
            break;
        case 'updatePlan':
            updatePlan($input, $pdo);
            break;
        case 'updateAirtimeMarkup':
            updateAirtimeMarkup($input, $pdo);
            break;
        case 'updateDataMarkup':
            updateDataMarkup($input, $pdo);
            break;
        case 'updateTvMarkup':
            updateTvMarkup($input, $pdo);
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function getServicePlans($pdo)
{
    try {
        // Join plans with providers to expose network; map columns to UI shape
        $sql = "
         SELECT sp.id,
                   sp.plan_name AS name,
                   prov.name AS network,
                   sp.variation_code AS code,
                   sp.price,
             sp.base_price,
                   sp.volume AS data_size,
                   sp.validity,
                   sp.is_active,
                   sp.created_at
            FROM service_plans sp
            LEFT JOIN service_providers prov ON prov.id = sp.provider_id
            ORDER BY prov.name, sp.price ASC
        ";
        $stmt = $pdo->query($sql);
        $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];

        $plans = [];
        foreach ($rows as $r) {
            $plans[] = [
                'id' => (int)$r['id'],
                'name' => $r['name'],
                'network' => $r['network'] ?? 'Unknown',
                'code' => $r['code'],
                'price' => (float)$r['price'],
                'base_price' => isset($r['base_price']) ? (float)$r['base_price'] : null,
                'data_size' => $r['data_size'],
                'validity' => $r['validity'],
                'status' => ((int)$r['is_active'] === 1 ? 'active' : 'inactive'),
                'created_at' => date('Y-m-d H:i:s', strtotime($r['created_at']))
            ];
        }

        // Optional: markups; ignore if settings table doesn't exist
        $airtimeMarkup = 0;
        $dataMarkup = 0;
        $tvMarkup = 0;
        try {
            $stmt2 = $pdo->query("SELECT `key`, `value` FROM settings WHERE `key` IN ('airtime_markup','data_markup','tv_markup')");
            if ($stmt2) {
                foreach ($stmt2->fetchAll(PDO::FETCH_ASSOC) as $row) {
                    if ($row['key'] === 'airtime_markup') $airtimeMarkup = (float)$row['value'];
                    if ($row['key'] === 'data_markup') $dataMarkup = (float)$row['value'];
                    if ($row['key'] === 'tv_markup') $tvMarkup = (float)$row['value'];
                }
            }
        } catch (PDOException $e) {
            error_log('Pricing settings lookup skipped: ' . $e->getMessage());
        }

        echo json_encode([
            'success' => true,
            'data' => [
                'plans' => $plans,
                'airtime_markup' => $airtimeMarkup,
                'data_markup' => $dataMarkup,
                'tv_markup' => $tvMarkup
            ]
        ]);
    } catch (PDOException $e) {
        error_log("Get pricing error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to fetch pricing data']);
    }
}

function updatePlanPrice($input, $pdo)
{
    try {
        $planId = $input['plan_id'] ?? '';
        $price = $input['price'] ?? '';

        if (empty($planId) || $planId === '' || $price === '') {
            echo json_encode(['success' => false, 'message' => 'Plan ID and price are required']);
            return;
        }

        if (!is_numeric($price) || $price < 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid price value']);
            return;
        }

        $stmt = $pdo->prepare("UPDATE service_plans SET price = ? WHERE id = ?");
        $stmt->execute([$price, $planId]);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Plan price updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Plan not found or no changes made']);
        }
    } catch (PDOException $e) {
        error_log("Update plan price error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to update plan price']);
    }
}

function updatePlan($input, $pdo)
{
    try {
        $planId = $input['id'] ?? '';
        $name = trim($input['name'] ?? '');
        $network = trim($input['network'] ?? ''); // provider name
        $code = trim($input['code'] ?? ''); // variation_code
        $price = $input['price'] ?? '';
        $dataSize = trim($input['data_size'] ?? ''); // volume
        $validity = trim($input['validity'] ?? '');
        $status = $input['status'] ?? 'active'; // maps to is_active (1/0)

        if (empty($planId)) {
            echo json_encode(['success' => false, 'message' => 'Plan ID is required']);
            return;
        }

        $errors = [];
        if ($name === '') $errors[] = 'Plan name is required';
        if ($code === '') $errors[] = 'Plan code is required';
        if ($price === '' || !is_numeric($price) || $price < 0) $errors[] = 'Valid price is required';
        if (!empty($errors)) {
            echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
            return;
        }

        // Resolve provider_id from network if provided
        $providerId = null;
        if ($network !== '') {
            $stmtProv = $pdo->prepare("SELECT id FROM service_providers WHERE name = ? LIMIT 1");
            $stmtProv->execute([$network]);
            $prov = $stmtProv->fetch(PDO::FETCH_ASSOC);
            if (!$prov) {
                echo json_encode(['success' => false, 'message' => 'Invalid network selected']);
                return;
            }
            $providerId = (int)$prov['id'];
        }

        // Ensure variation_code is unique across other plans
        $stmt = $pdo->prepare("SELECT id FROM service_plans WHERE variation_code = ? AND id != ?");
        $stmt->execute([$code, $planId]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Plan code already exists']);
            return;
        }

        // Build update
        $columns = [
            'plan_name = ?',
            'variation_code = ?',
            'price = ?',
            'volume = ?',
            'validity = ?',
            'is_active = ?'
        ];
        $params = [$name, $code, $price, $dataSize, $validity, ($status === 'active' ? 1 : 0)];
        // Do not override base_price here; it's sourced from provider ingest. If you plan to allow editing base_price, add a field and include it.
        if ($providerId !== null) {
            $columns[] = 'provider_id = ?';
            $params[] = $providerId;
        }
        $params[] = $planId;

        $sql = 'UPDATE service_plans SET ' . implode(', ', $columns) . ' WHERE id = ?';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);

        if ($stmt->rowCount() > 0) {
            echo json_encode(['success' => true, 'message' => 'Plan updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Plan not found or no changes made']);
        }
    } catch (PDOException $e) {
        error_log("Update plan error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to update plan']);
    }
}

function updateAirtimeMarkup($input, $pdo)
{
    try {
        $percentage = $input['percentage'] ?? '';

        if (!is_numeric($percentage) || $percentage < 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid markup percentage']);
            return;
        }

        // Ensure settings table exists with a simple schema
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
                `key` VARCHAR(100) NOT NULL PRIMARY KEY,
                `value` VARCHAR(255) NOT NULL,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        } catch (PDOException $e) {
            error_log('Failed creating settings table: ' . $e->getMessage());
        }

        // Upsert airtime_markup
        $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`, `updated_at`) VALUES ('airtime_markup', ?, NOW())
            ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`)");
        $stmt->execute([$percentage]);

        echo json_encode(['success' => true, 'message' => 'Airtime markup updated successfully']);
    } catch (PDOException $e) {
        error_log("Update airtime markup error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to update airtime markup']);
    }
}

function updateDataMarkup($input, $pdo)
{
    try {
        $percentage = $input['percentage'] ?? '';

        if (!is_numeric($percentage) || $percentage < 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid markup percentage']);
            return;
        }

        // Ensure settings table exists with a simple schema
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
                `key` VARCHAR(100) NOT NULL PRIMARY KEY,
                `value` VARCHAR(255) NOT NULL,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        } catch (PDOException $e) {
            error_log('Failed creating settings table: ' . $e->getMessage());
        }

        // Upsert data_markup
        $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`, `updated_at`) VALUES ('data_markup', ?, NOW())
            ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`)");
        $stmt->execute([$percentage]);

        // Ensure base_price column exists on service_plans
        try {
            $colCheck = $pdo->query("SHOW COLUMNS FROM service_plans LIKE 'base_price'");
            $hasBase = $colCheck && $colCheck->rowCount() > 0;
            if (!$hasBase) {
                $pdo->exec("ALTER TABLE service_plans ADD COLUMN base_price DECIMAL(10,2) NULL AFTER price");
            }
        } catch (PDOException $e) {
            // Ignore if column already exists or insufficient privileges
            error_log('base_price column ensure: ' . $e->getMessage());
        }

        // Determine data service_id
        $dataServiceId = null;
        try {
            $s = $pdo->query("SELECT id FROM services WHERE slug = 'data' LIMIT 1");
            $row = $s ? $s->fetch(PDO::FETCH_ASSOC) : null;
            if ($row) $dataServiceId = (int)$row['id'];
        } catch (PDOException $e) {
            error_log('Lookup data service id failed: ' . $e->getMessage());
        }

        // One-time backfill: set base_price = price when NULL (for data plans only if service_id known; else all)
        try {
            if ($dataServiceId) {
                $bf = $pdo->prepare("UPDATE service_plans SET base_price = price WHERE service_id = ? AND (base_price IS NULL OR base_price = 0)");
                $bf->execute([$dataServiceId]);
            } else {
                $pdo->exec("UPDATE service_plans SET base_price = price WHERE (base_price IS NULL OR base_price = 0)");
            }
        } catch (PDOException $e) {
            error_log('Backfill base_price failed: ' . $e->getMessage());
        }

        // Recalculate retail price from base_price using the new percentage for DATA plans only
        $pct = (float)$percentage;
        try {
            if ($dataServiceId) {
                $stmtU = $pdo->prepare("UPDATE service_plans SET price = ROUND(base_price * (1 + ?/100), 2) WHERE service_id = ?");
                $stmtU->execute([$pct, $dataServiceId]);
            } else {
                // Fallback: update all rows
                $stmtU = $pdo->prepare("UPDATE service_plans SET price = ROUND(base_price * (1 + ?/100), 2) WHERE base_price IS NOT NULL");
                $stmtU->execute([$pct]);
            }
        } catch (PDOException $e) {
            error_log('Recalculate data prices failed: ' . $e->getMessage());
        }

        // Try to push an admin notification/activity entry (best-effort)
        try {
            $adminId = $_SESSION['admin_id'] ?? null;
            if ($adminId) {
                $title = 'Data Markup Updated';
                $message = 'Global data markup set to ' . $percentage . '%; plan prices recalculated.';
                // Attempt admin_notifications table
                $stmtN = $pdo->prepare("INSERT INTO admin_notifications (admin_id, title, message, type, icon, color, is_read, created_at) VALUES (?, ?, ?, ?, ?, ?, 0, NOW())");
                $stmtN->execute([$adminId, $title, $message, 'pricing', 'ni ni-chart-bar-32', 'text-info']);
            }
        } catch (PDOException $e) {
            // Ignore if table doesn't exist
        }

        echo json_encode(['success' => true, 'message' => 'Data markup updated and prices recalculated']);
    } catch (PDOException $e) {
        error_log("Update data markup error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to update data markup']);
    }
}

function updateTvMarkup($input, $pdo)
{
    try {
        $percentage = $input['percentage'] ?? '';

        if (!is_numeric($percentage) || $percentage < 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid markup percentage']);
            return;
        }

        // Ensure settings table exists
        try {
            $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
                `key` VARCHAR(100) NOT NULL PRIMARY KEY,
                `value` VARCHAR(255) NOT NULL,
                `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");
        } catch (PDOException $e) {
            error_log('Failed creating settings table: ' . $e->getMessage());
        }

        // Upsert tv_markup
        $stmt = $pdo->prepare("INSERT INTO settings (`key`, `value`, `updated_at`) VALUES ('tv_markup', ?, NOW())
            ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), `updated_at` = VALUES(`updated_at`)");
        $stmt->execute([$percentage]);

        // Ensure base_price column exists
        try {
            $colCheck = $pdo->query("SHOW COLUMNS FROM service_plans LIKE 'base_price'");
            if (!$colCheck || $colCheck->rowCount() === 0) {
                $pdo->exec("ALTER TABLE service_plans ADD COLUMN base_price DECIMAL(10,2) NULL AFTER price");
            }
        } catch (PDOException $e) {
            error_log('Ensure base_price for TV: ' . $e->getMessage());
        }

        // Find TV service id by slug
        $tvServiceId = null;
        try {
            $s = $pdo->prepare("SELECT id FROM services WHERE slug IN ('cable_tv','tv') ORDER BY FIELD(slug,'cable_tv','tv') LIMIT 1");
            $s->execute();
            $row = $s->fetch(PDO::FETCH_ASSOC);
            if ($row) $tvServiceId = (int)$row['id'];
        } catch (PDOException $e) {
            error_log('Lookup TV service id failed: ' . $e->getMessage());
        }

        // Backfill base_price for TV plans if missing
        try {
            if ($tvServiceId) {
                $bf = $pdo->prepare("UPDATE service_plans SET base_price = price WHERE service_id = ? AND (base_price IS NULL OR base_price = 0)");
                $bf->execute([$tvServiceId]);
            }
        } catch (PDOException $e) {
            error_log('Backfill TV base_price failed: ' . $e->getMessage());
        }

        // Recalculate retail prices for TV plans
        try {
            if ($tvServiceId) {
                $stmtU = $pdo->prepare("UPDATE service_plans SET price = ROUND(base_price * (1 + ?/100), 2) WHERE service_id = ?");
                $stmtU->execute([(float)$percentage, $tvServiceId]);
            }
        } catch (PDOException $e) {
            error_log('Recalculate TV prices failed: ' . $e->getMessage());
        }

        // Admin notification best-effort
        try {
            $adminId = $_SESSION['admin_id'] ?? null;
            if ($adminId) {
                $title = 'TV Markup Updated';
                $message = 'Global TV markup set to ' . $percentage . '%; plan prices recalculated.';
                $stmtN = $pdo->prepare("INSERT INTO admin_notifications (admin_id, title, message, type, icon, color, is_read, created_at) VALUES (?, ?, ?, ?, ?, ?, 0, NOW())");
                $stmtN->execute([$adminId, $title, $message, 'pricing', 'ni ni-tv-2', 'text-info']);
            }
        } catch (PDOException $e) {
            // ignore
        }

        echo json_encode(['success' => true, 'message' => 'TV markup updated and prices recalculated']);
    } catch (PDOException $e) {
        error_log("Update TV markup error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to update TV markup']);
    }
}
