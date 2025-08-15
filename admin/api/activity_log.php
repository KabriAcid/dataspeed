<?php
session_start();
header('Content-Type: application/json');

// Auth guard
if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require __DIR__ . '/../../config/config.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = $_GET['action'] ?? 'list';

if ($method !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

switch ($action) {
    case 'list':
        getActivityLogs($pdo);
        break;
    default:
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

function getActivityLogs($pdo)
{
    try {
        $search = $_GET['search'] ?? '';
        $type = $_GET['type'] ?? '';
        $date = $_GET['date'] ?? '';
        $page = max(1, (int)($_GET['page'] ?? 1));
        $perPage = max(1, min(100, (int)($_GET['per_page'] ?? 20)));
        $offset = ($page - 1) * $perPage;

        $where = [];
        $params = [];

        if ($search !== '') {
            $where[] = '(username LIKE ? OR action_description LIKE ?)';
            $like = '%' . $search . '%';
            $params[] = $like;
            $params[] = $like;
        }
        if ($type !== '') {
            $where[] = 'action_type = ?';
            $params[] = $type;
        }
        if ($date !== '') {
            $where[] = 'DATE(created_at) = ?';
            $params[] = $date;
        }

        $whereSql = empty($where) ? '' : ('WHERE ' . implode(' AND ', $where));

        // Count
        $countSql = "SELECT COUNT(*) AS total FROM activity_log $whereSql";
        $stmt = $pdo->prepare($countSql);
        $stmt->execute($params);
        $total = (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

        // Data
        $sql = "SELECT id, username, action_type, action_description, ip_address, created_at
                FROM activity_log
                $whereSql
                ORDER BY created_at DESC
                LIMIT $perPage OFFSET $offset";

        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

        echo json_encode([
            'success' => true,
            'data' => [
                'items' => $rows,
                'pagination' => [
                    'page' => $page,
                    'total_pages' => (int)ceil($total / $perPage),
                    'count' => $total,
                    'per_page' => $perPage,
                ],
            ],
        ]);
    } catch (PDOException $e) {
        error_log('Activity log error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to fetch activity logs']);
    }
}
