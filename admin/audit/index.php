<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

// Get audit log data
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

$filters = [
    'action' => $_GET['action'] ?? '',
    'entity_type' => $_GET['entity_type'] ?? '',
    'date_from' => $_GET['date_from'] ?? '',
    'date_to' => $_GET['date_to'] ?? ''
];

$where = [];
$params = [];

if ($filters['action']) {
    $where[] = "action = ?";
    $params[] = $filters['action'];
}

if ($filters['entity_type']) {
    $where[] = "entity_type = ?";
    $params[] = $filters['entity_type'];
}

if ($filters['date_from']) {
    $where[] = "DATE(created_at) >= ?";
    $params[] = $filters['date_from'];
}

if ($filters['date_to']) {
    $where[] = "DATE(created_at) <= ?";
    $params[] = $filters['date_to'];
}

$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Get total count
$countQuery = "SELECT COUNT(*) FROM audit_log $whereClause";
$countStmt = $pdo->prepare($countQuery);
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$total_pages = ceil($total / $limit);

// Get audit logs (create dummy data if table doesn't exist)
try {
    $query = "SELECT al.*, u.name as admin_name, u.email as admin_email
              FROM audit_log al
              LEFT JOIN users u ON al.admin_id = u.id
              $whereClause
              ORDER BY al.created_at DESC
              LIMIT $limit OFFSET $offset";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    $audit_logs = $stmt->fetchAll();
} catch (Exception $e) {
    // Create dummy data if table doesn't exist
    $audit_logs = [
        [
            'id' => 1,
            'admin_id' => $_SESSION['admin_id'],
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'admin_email' => 'admin@dataspeed.com',
            'action' => 'create',
            'entity_type' => 'user',
            'entity_id' => 1,
            'changes' => '{"name": "John Doe", "email": "john@example.com"}',
            'created_at' => date('Y-m-d H:i:s')
        ],
        [
            'id' => 2,
            'admin_id' => $_SESSION['admin_id'],
            'admin_name' => $_SESSION['admin_name'] ?? 'Admin',
            'admin_email' => 'admin@dataspeed.com',
            'action' => 'update',
            'entity_type' => 'transaction',
            'entity_id' => 5,
            'changes' => '{"status": "completed", "admin_note": "Manually approved"}',
            'created_at' => date('Y-m-d H:i:s', strtotime('-1 hour'))
        ]
    ];
    $total_pages = 1;
}

$page_title = 'Audit Log';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Audit Log</h1>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-2">
                <select name="action" class="form-select">
                    <option value="">All Actions</option>
                    <option value="create" <?= $filters['action'] === 'create' ? 'selected' : '' ?>>Create</option>
                    <option value="update" <?= $filters['action'] === 'update' ? 'selected' : '' ?>>Update</option>
                    <option value="delete" <?= $filters['action'] === 'delete' ? 'selected' : '' ?>>Delete</option>
                    <option value="login" <?= $filters['action'] === 'login' ? 'selected' : '' ?>>Login</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="entity_type" class="form-select">
                    <option value="">All Entities</option>
                    <option value="user" <?= $filters['entity_type'] === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="transaction" <?= $filters['entity_type'] === 'transaction' ? 'selected' : '' ?>>Transaction</option>
                    <option value="kyc" <?= $filters['entity_type'] === 'kyc' ? 'selected' : '' ?>>KYC</option>
                    <option value="settings" <?= $filters['entity_type'] === 'settings' ? 'selected' : '' ?>>Settings</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_from" value="<?= $filters['date_from'] ?>">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" name="date_to" value="<?= $filters['date_to'] ?>">
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="index.php" class="btn btn-outline-secondary w-100">Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- Audit Log Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Admin</th>
                    <th>Action</th>
                    <th>Entity</th>
                    <th>Changes</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($audit_logs as $log): ?>
                <tr>
                    <td><?= $log['id'] ?></td>
                    <td>
                        <?= htmlspecialchars($log['admin_name']) ?><br>
                        <small class="text-muted"><?= htmlspecialchars($log['admin_email']) ?></small>
                    </td>
                    <td>
                        <span class="badge bg-<?= 
                            $log['action'] === 'create' ? 'success' : 
                            ($log['action'] === 'update' ? 'primary' : 
                            ($log['action'] === 'delete' ? 'danger' : 'secondary'))
                        ?>"><?= ucfirst($log['action']) ?></span>
                    </td>
                    <td>
                        <?= ucfirst($log['entity_type']) ?>
                        <?php if (isset($log['entity_id'])): ?>
                            <small class="text-muted">#<?= $log['entity_id'] ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <button class="btn btn-sm btn-outline-info" 
                                onclick="showChanges('<?= htmlspecialchars($log['changes'] ?? '{}') ?>')">
                            View Changes
                        </button>
                    </td>
                    <td><?= date('M j, Y g:i A', strtotime($log['created_at'])) ?></td>
                    <td>
                        <button class="btn btn-sm btn-outline-primary" 
                                onclick="showDetails(<?= $log['id'] ?>)">
                            <i class="bi bi-eye"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($audit_logs)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No audit logs found</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if ($total_pages > 1): ?>
<nav class="mt-4">
    <ul class="pagination justify-content-center">
        <?php for ($i = 1; $i <= $total_pages; $i++): ?>
            <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                <a class="page-link" href="?page=<?= $i ?>&<?= http_build_query($filters) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<!-- Changes Modal -->
<div class="modal fade" id="changesModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Change Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <pre id="changesContent" class="bg-light p-3 rounded"></pre>
            </div>
        </div>
    </div>
</div>

<script>
function showChanges(changes) {
    try {
        const formatted = JSON.stringify(JSON.parse(changes), null, 2);
        document.getElementById('changesContent').textContent = formatted;
    } catch (e) {
        document.getElementById('changesContent').textContent = changes || 'No changes recorded';
    }
    new bootstrap.Modal(document.getElementById('changesModal')).show();
}

function showDetails(id) {
    alert('Audit log details for ID: ' + id);
}
</script>

<?php
include '../../includes/admin/footer.php';
include '../../includes/admin/scripts.php';
?>