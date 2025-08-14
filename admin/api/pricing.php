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

function handleGetRequest($action, $pdo) {
    switch ($action) {
        case 'plans':
            getServicePlans($pdo);
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function handlePostRequest($action, $input, $pdo) {
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
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function getServicePlans($pdo) {
    try {
        // Get service plans
        $stmt = $pdo->query("
            SELECT id, name, network, code, price, data_size, validity, status, created_at
            FROM service_plans 
            ORDER BY network, price ASC
        ");
        $plans = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        // Format data
        foreach ($plans as &$plan) {
            $plan['price'] = (float)$plan['price'];
            $plan['created_at'] = date('Y-m-d H:i:s', strtotime($plan['created_at']));
        }
        
        // Get airtime markup
        $stmt = $pdo->query("SELECT value FROM settings WHERE key = 'airtime_markup' LIMIT 1");
        $markupResult = $stmt->fetch(PDO::FETCH_ASSOC);
        $airtimeMarkup = $markupResult ? (float)$markupResult['value'] : 0;
        
        echo json_encode([
            'success' => true,
            'data' => [
                'plans' => $plans,
                'airtime_markup' => $airtimeMarkup
            ]
        ]);
        
    } catch (PDOException $e) {
        error_log("Get pricing error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to fetch pricing data']);
    }
}

function updatePlanPrice($input, $pdo) {
    try {
        $planId = $input['plan_id'] ?? '';
        $price = $input['price'] ?? '';
        
        if (empty($planId) || empty($price)) {
            echo json_encode(['success' => false, 'message' => 'Plan ID and price are required']);
            return;
        }
        
        if (!is_numeric($price) || $price < 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid price value']);
            return;
        }
        
        $stmt = $pdo->prepare("UPDATE service_plans SET price = ?, updated_at = NOW() WHERE id = ?");
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

function updatePlan($input, $pdo) {
    try {
        $planId = $input['id'] ?? '';
        $name = trim($input['name'] ?? '');
        $network = trim($input['network'] ?? '');
        $code = trim($input['code'] ?? '');
        $price = $input['price'] ?? '';
        $dataSize = trim($input['data_size'] ?? '');
        $validity = trim($input['validity'] ?? '');
        $status = $input['status'] ?? 'active';
        
        if (empty($planId)) {
            echo json_encode(['success' => false, 'message' => 'Plan ID is required']);
            return;
        }
        
        // Validate input
        $errors = [];
        
        if (empty($name)) {
            $errors[] = 'Plan name is required';
        }
        
        if (empty($network)) {
            $errors[] = 'Network is required';
        }
        
        if (empty($code)) {
            $errors[] = 'Plan code is required';
        }
        
        if (empty($price) || !is_numeric($price) || $price < 0) {
            $errors[] = 'Valid price is required';
        }
        
        if (!empty($errors)) {
            echo json_encode(['success' => false, 'message' => implode(', ', $errors)]);
            return;
        }
        
        // Check if code exists for other plans
        $stmt = $pdo->prepare("SELECT id FROM service_plans WHERE code = ? AND id != ?");
        $stmt->execute([$code, $planId]);
        if ($stmt->fetch()) {
            echo json_encode(['success' => false, 'message' => 'Plan code already exists']);
            return;
        }
        
        // Update plan
        $stmt = $pdo->prepare("
            UPDATE service_plans 
            SET name = ?, network = ?, code = ?, price = ?, data_size = ?, validity = ?, status = ?, updated_at = NOW()
            WHERE id = ?
        ");
        
        $stmt->execute([$name, $network, $code, $price, $dataSize, $validity, $status, $planId]);
        
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

function updateAirtimeMarkup($input, $pdo) {
    try {
        $percentage = $input['percentage'] ?? '';
        
        if (!is_numeric($percentage) || $percentage < 0) {
            echo json_encode(['success' => false, 'message' => 'Invalid markup percentage']);
            return;
        }
        
        // Update or insert airtime markup setting
        $stmt = $pdo->prepare("
            INSERT INTO settings (key, value, updated_at) 
            VALUES ('airtime_markup', ?, NOW())
            ON DUPLICATE KEY UPDATE value = VALUES(value), updated_at = VALUES(updated_at)
        ");
        
        $stmt->execute([$percentage]);
        
        echo json_encode(['success' => true, 'message' => 'Airtime markup updated successfully']);
        
    } catch (PDOException $e) {
        error_log("Update airtime markup error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to update airtime markup']);
    }
}
?>