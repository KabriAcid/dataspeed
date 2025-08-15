<?php
session_start();
header('Content-Type: application/json');

if (empty($_SESSION['admin_id'])) {
    http_response_code(401);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

require __DIR__ . '/../../config/config.php';

$method = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$action = $_GET['action'] ?? ($method === 'GET' ? 'list' : '');

try {
    if ($method === 'GET') {
        switch ($action) {
            case 'list':
                return handleList($pdo);
            case 'stats':
                return handleStats($pdo);
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $action = $input['action'] ?? '';
        switch ($action) {
            case 'markRead':
                return handleMarkRead($pdo, $input);
            case 'markAllRead':
                return handleMarkAllRead($pdo);
            case 'create':
                return handleCreate($pdo, $input);
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (PDOException $e) {
    error_log('Notifications API error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

function handleList(PDO $pdo)
{
    $search = trim($_GET['search'] ?? '');
    $type = trim($_GET['type'] ?? '');
    $status = trim($_GET['status'] ?? ''); // unread|read|''
    $page = max(1, (int)($_GET['page'] ?? 1));
    $per = max(1, min(50, (int)($_GET['per_page'] ?? 20)));
    $offset = ($page - 1) * $per;

    $where = [];
    $params = [];
    if ($search !== '') {
        $where[] = '(title LIKE ? OR message LIKE ?)';
        $like = "%$search%";
        $params[] = $like;
        $params[] = $like;
    }
    if ($type !== '') {
        $where[] = 'type = ?';
        $params[] = $type;
    }
    if ($status !== '') {
        $where[] = 'is_read = ?';
        $params[] = ($status === 'unread') ? 0 : 1;
    }
    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    $countSql = "SELECT COUNT(*) AS total FROM admin_notifications $whereSql";
    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $total = (int)($stmt->fetch(PDO::FETCH_ASSOC)['total'] ?? 0);

    $sql = "SELECT id, type, title, message, meta, is_read, created_at
            FROM admin_notifications
            $whereSql
            ORDER BY created_at DESC
            LIMIT $per OFFSET $offset";
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'success' => true,
        'data' => [
            'items' => $rows,
            'pagination' => [
                'page' => $page,
                'total_pages' => (int)ceil($total / $per),
                'count' => $total,
                'per_page' => $per,
            ],
        ],
    ]);
}

function handleStats(PDO $pdo)
{
    $total = (int)$pdo->query("SELECT COUNT(*) FROM admin_notifications")->fetchColumn();
    $unread = (int)$pdo->query("SELECT COUNT(*) FROM admin_notifications WHERE is_read = 0")->fetchColumn();
    $read = $total - $unread;
    $today = (int)$pdo->query("SELECT COUNT(*) FROM admin_notifications WHERE DATE(created_at) = CURDATE()")->fetchColumn();
    echo json_encode(['success' => true, 'data' => compact('total', 'unread', 'read', 'today')]);
}

function handleMarkRead(PDO $pdo, array $input)
{
    $id = (int)($input['id'] ?? 0);
    if ($id <= 0) {
        echo json_encode(['success' => false, 'message' => 'Invalid ID']);
        return;
    }
    $stmt = $pdo->prepare('UPDATE admin_notifications SET is_read = 1, updated_at = NOW() WHERE id = ?');
    $stmt->execute([$id]);
    echo json_encode(['success' => true]);
}

function handleMarkAllRead(PDO $pdo)
{
    $pdo->exec('UPDATE admin_notifications SET is_read = 1, updated_at = NOW() WHERE is_read = 0');
    echo json_encode(['success' => true]);
}

function handleCreate(PDO $pdo, array $input)
{
    $type = in_array(($input['type'] ?? 'system'), ['system', 'user', 'security']) ? $input['type'] : 'system';
    $title = trim($input['title'] ?? '');
    $message = trim($input['message'] ?? '');
    $meta = $input['meta'] ?? null;
    if ($title === '' || $message === '') {
        echo json_encode(['success' => false, 'message' => 'Title and message are required']);
        return;
    }
    $stmt = $pdo->prepare('INSERT INTO admin_notifications (type, title, message, meta, is_read, created_at) VALUES (?,?,?,?,0,NOW())');
    $stmt->execute([$type, $title, $message, is_array($meta) ? json_encode($meta) : $meta]);
    echo json_encode(['success' => true, 'id' => (int)$pdo->lastInsertId()]);
}
