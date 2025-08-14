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
        case 'get':
            getSettings($pdo);
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function handlePostRequest($action, $input, $pdo)
{
    switch ($action) {
        case 'update':
            updateSettings($input, $pdo);
            break;
        default:
            http_response_code(400);
            echo json_encode(['success' => false, 'message' => 'Invalid action']);
    }
}

function ensureSettingsTable(PDO $pdo): void
{
    try {
        // Create table if missing
        $pdo->exec("CREATE TABLE IF NOT EXISTS settings (
            `key` VARCHAR(100) NOT NULL PRIMARY KEY,
            `value` VARCHAR(255) NOT NULL,
            `description` TEXT NULL,
            `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

        // Make sure description column exists (in case an older table was created elsewhere)
        $pdo->exec("ALTER TABLE settings ADD COLUMN IF NOT EXISTS `description` TEXT NULL AFTER `value`");
        // Ensure updated_at exists for upsert statements
        $pdo->exec("ALTER TABLE settings ADD COLUMN IF NOT EXISTS `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP");
    } catch (PDOException $e) {
        error_log('ensureSettingsTable: ' . $e->getMessage());
    }
}

function getSettings($pdo)
{
    try {
        ensureSettingsTable($pdo);
        $settingsRows = [];
        try {
            $stmt = $pdo->query("SELECT `key`, `value`, `description` FROM settings ORDER BY `key`");
            $settingsRows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
        } catch (PDOException $e) {
            // Fallback if description column is missing
            $stmt = $pdo->query("SELECT `key`, `value` FROM settings ORDER BY `key`");
            $rows = $stmt ? $stmt->fetchAll(PDO::FETCH_ASSOC) : [];
            foreach ($rows as $r) {
                $settingsRows[] = ['key' => $r['key'], 'value' => $r['value'], 'description' => null];
            }
        }

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

function updateSettings($input, $pdo)
{
    try {
        $settings = $input['settings'] ?? [];

        if (empty($settings)) {
            echo json_encode(['success' => false, 'message' => 'No settings provided']);
            return;
        }

        ensureSettingsTable($pdo);
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
