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
        case 'get':
            getSettings($pdo);
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function handlePostRequest($action, $input, $pdo) {
    switch ($action) {
        case 'update':
            updateSettings($input, $pdo);
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function getSettings($pdo) {
    try {
        $stmt = $pdo->query("SELECT `key`, `value`, description FROM settings ORDER BY `key`");
        $settingsRows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        
        $settings = [];
        foreach ($settingsRows as $row) {
            $settings[$row['key']] = [
                'value' => $row['value'],
                'description' => $row['description']
            ];
        }
        
        echo json_encode([
            'success' => true,
            'data' => $settings
        ]);
        
    } catch (PDOException $e) {
        error_log("Get settings error: " . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to fetch settings']);
    }
}

function updateSettings($input, $pdo) {
    try {
        $settings = $input['settings'] ?? [];
        
        if (empty($settings)) {
            echo json_encode(['success' => false, 'message' => 'No settings provided']);
            return;
        }
        
        $pdo->beginTransaction();
        
        foreach ($settings as $key => $value) {
            // Validate setting key (only allow alphanumeric and underscores)
            if (!preg_match('/^[a-zA-Z0-9_]+$/', $key)) {
                $pdo->rollBack();
                echo json_encode(['success' => false, 'message' => "Invalid setting key: $key"]);
                return;
            }
            
            // Update or insert setting
            $stmt = $pdo->prepare("
                INSERT INTO settings (`key`, `value`, updated_at) 
                VALUES (?, ?, NOW())
                ON DUPLICATE KEY UPDATE `value` = VALUES(`value`), updated_at = VALUES(updated_at)
            ");
            
            $stmt->execute([$key, $value]);
        }
        
        $pdo->commit();
        
        echo json_encode(['success' => true, 'message' => 'Settings updated successfully']);
        
    } catch (PDOException $e) {
        $pdo->rollBack();
        error_log("Update settings error: " . $e->getMessage());
        echo json_encode(['success' => false, 'message' => 'Failed to update settings']);
    }
}
?>