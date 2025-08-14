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

try {
    if ($method === 'GET') {
        $action = $_GET['action'] ?? 'list';
        switch ($action) {
            case 'list':
                handleList($pdo);
                break;
            case 'stats':
                handleStats($pdo);
                break;
            case 'view':
                handleView($pdo);
                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } elseif ($method === 'POST') {
        $input = json_decode(file_get_contents('php://input'), true) ?: [];
        $action = $input['action'] ?? '';
        switch ($action) {
            case 'updateStatus':
                handleUpdateStatus($pdo, $input);
                break;
            default:
                http_response_code(400);
                echo json_encode(['success' => false, 'message' => 'Invalid action']);
        }
    } else {
        http_response_code(405);
        echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    }
} catch (Throwable $e) {
    error_log('Transactions API error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Server error']);
}

function handleList(PDO $pdo): void
{
    $page = max(1, (int)($_GET['page'] ?? 1));
    $perPage = (int)($_GET['per_page'] ?? 20);
    if ($perPage < 1) $perPage = 20;
    if ($perPage > 100) $perPage = 100;
    $offset = ($page - 1) * $perPage;

    $search = trim((string)($_GET['search'] ?? ''));
    $status = trim((string)($_GET['status'] ?? '')); // completed | failed | pending
    $type = trim((string)($_GET['type'] ?? '')); // data | airtime | electricity | cable_tv
    $date = trim((string)($_GET['date'] ?? '')); // YYYY-MM-DD

    $where = [];
    $params = [];

    // Status mapping to DB enum
    if ($status !== '') {
        if ($status === 'completed') {
            $where[] = 't.status = ?';
            $params[] = 'success';
        } elseif ($status === 'failed') {
            $where[] = 't.status = ?';
            $params[] = 'fail';
        } elseif ($status === 'pending') {
            // No pending in schema; return none
            $where[] = '1 = 0';
        }
    }

    // Type filter via services.slug
    if ($type !== '') {
        $typeSlug = $type === 'cable_tv' ? 'tv' : $type; // map UI to DB slug
        $where[] = 'LOWER(s.slug) = LOWER(?)';
        $params[] = $typeSlug;
    }

    if ($date !== '') {
        $where[] = 'DATE(t.created_at) = ?';
        $params[] = $date;
    }

    if ($search !== '') {
        $where[] = '(
            t.reference LIKE ? OR t.description LIKE ? OR 
            u.email LIKE ? OR u.first_name LIKE ? OR u.last_name LIKE ?
        )';
        $like = '%' . $search . '%';
        array_push($params, $like, $like, $like, $like, $like);
    }

    $whereSql = $where ? ('WHERE ' . implode(' AND ', $where)) : '';

    // Count
    $countSql = "
        SELECT COUNT(*) AS cnt
        FROM transactions t
        LEFT JOIN users u ON u.user_id = t.user_id
        LEFT JOIN services s ON s.id = t.service_id
        $whereSql
    ";
    $stmt = $pdo->prepare($countSql);
    $stmt->execute($params);
    $total = (int)$stmt->fetchColumn();

    // Data
    $dataSql = "
        SELECT t.id, t.user_id, t.service_id, t.provider_id, t.plan_id,
               t.type AS raw_type, t.direction, t.description, t.amount, t.email,
               t.reference, t.status, t.created_at,
               u.first_name, u.last_name, u.email AS user_email,
               s.slug AS service_slug
        FROM transactions t
        LEFT JOIN users u ON u.user_id = t.user_id
        LEFT JOIN services s ON s.id = t.service_id
        $whereSql
        ORDER BY t.created_at DESC
        LIMIT $perPage OFFSET $offset
    ";
    $stmt = $pdo->prepare($dataSql);
    $stmt->execute($params);
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC) ?: [];

    $items = array_map(function ($r) {
        $serviceSlug = $r['service_slug'] ?? '';
        $serviceType = $serviceSlug === 'tv' ? 'cable_tv' : ($serviceSlug ?: 'other');
        $uiStatus = $r['status'] === 'success' ? 'completed' : ($r['status'] === 'fail' ? 'failed' : $r['status']);
        $userName = trim(($r['first_name'] ?? '') . ' ' . ($r['last_name'] ?? ''));
        if ($userName === '') $userName = $r['user_email'] ?? 'Unknown';
        return [
            'id' => $r['reference'] ?: ('TXN-' . $r['id']),
            'txn_id' => (int)$r['id'],
            'user_name' => $userName,
            'service_type' => $serviceType,
            'amount' => (float)$r['amount'],
            'status' => $uiStatus,
            'created_at' => $r['created_at']
        ];
    }, $rows);

    $totalPages = (int)ceil($total / $perPage);

    echo json_encode([
        'success' => true,
        'data' => [
            'items' => $items,
            'pagination' => [
                'page' => $page,
                'per_page' => $perPage,
                'total_pages' => $totalPages,
                'count' => $total
            ]
        ]
    ]);
}

function handleStats(PDO $pdo): void
{
    try {
        $completed = (int)$pdo->query("SELECT COUNT(*) FROM transactions WHERE status = 'success'")->fetchColumn();
        $failed = (int)$pdo->query("SELECT COUNT(*) FROM transactions WHERE status = 'fail'")->fetchColumn();
        $pending = 0; // No pending in current schema
        $totalVolume = (float)$pdo->query("SELECT COALESCE(SUM(amount),0) FROM transactions WHERE status = 'success'")->fetchColumn();

        echo json_encode([
            'success' => true,
            'data' => [
                'completed' => $completed,
                'pending' => $pending,
                'failed' => $failed,
                'total_volume' => $totalVolume
            ]
        ]);
    } catch (PDOException $e) {
        error_log('Stats error: ' . $e->getMessage());
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Failed to fetch stats']);
    }
}

function handleView(PDO $pdo): void
{
    $id = $_GET['id'] ?? '';
    if ($id === '') {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Missing id']);
        return;
    }

    // Allow lookup by numeric id or reference
    $isNumeric = ctype_digit((string)$id);
    $where = $isNumeric ? 't.id = ?' : 't.reference = ?';
    $stmt = $pdo->prepare("SELECT t.*, u.first_name, u.last_name, u.email AS user_email, s.name AS service_name, s.slug AS service_slug, sp.name AS provider_name
                           FROM transactions t
                           LEFT JOIN users u ON u.user_id = t.user_id
                           LEFT JOIN services s ON s.id = t.service_id
                           LEFT JOIN service_providers sp ON sp.id = t.provider_id
                           WHERE $where LIMIT 1");
    $stmt->execute([$id]);
    $tx = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$tx) {
        echo json_encode(['success' => false, 'message' => 'Transaction not found']);
        return;
    }

    $serviceType = ($tx['service_slug'] ?? '') === 'tv' ? 'cable_tv' : ($tx['service_slug'] ?? 'other');
    $uiStatus = $tx['status'] === 'success' ? 'completed' : ($tx['status'] === 'fail' ? 'failed' : $tx['status']);
    $userName = trim(($tx['first_name'] ?? '') . ' ' . ($tx['last_name'] ?? ''));
    if ($userName === '') $userName = $tx['user_email'] ?? 'Unknown';

    echo json_encode([
        'success' => true,
        'data' => [
            'id' => $tx['reference'] ?: ('TXN-' . $tx['id']),
            'txn_id' => (int)$tx['id'],
            'user_name' => $userName,
            'email' => $tx['user_email'],
            'service_type' => $serviceType,
            'provider' => $tx['provider_name'] ?? null,
            'amount' => (float)$tx['amount'],
            'status' => $uiStatus,
            'direction' => $tx['direction'],
            'description' => $tx['description'],
            'reference' => $tx['reference'],
            'created_at' => $tx['created_at']
        ]
    ]);
}

function handleUpdateStatus(PDO $pdo, array $input): void
{
    $id = $input['id'] ?? '';
    $newStatus = $input['status'] ?? '';
    if ($id === '' || !in_array($newStatus, ['completed', 'failed'], true)) {
        http_response_code(400);
        echo json_encode(['success' => false, 'message' => 'Invalid payload']);
        return;
    }

    // Map to DB status
    $dbStatus = $newStatus === 'completed' ? 'success' : 'fail';

    // Allow numeric id or reference
    $isNumeric = ctype_digit((string)$id);
    $where = $isNumeric ? 'id = ?' : 'reference = ?';

    $stmt = $pdo->prepare("UPDATE transactions SET status = ? WHERE $where");
    $stmt->execute([$dbStatus, $id]);

    if ($stmt->rowCount() > 0) {
        echo json_encode(['success' => true, 'message' => 'Status updated']);
    } else {
        echo json_encode(['success' => false, 'message' => 'No changes made']);
    }
}
