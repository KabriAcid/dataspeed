<?php
session_start();
require_once '../../config/config.php';
require_once '../../functions/transactions.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$filters = [
    'status' => $_GET['status'] ?? '',
    'type' => $_GET['type'] ?? '',
    'provider' => $_GET['provider'] ?? '',
    'date_from' => $_GET['date_from'] ?? '',
    'date_to' => $_GET['date_to'] ?? ''
];

$result = getTransactions($page, 20, $filters);
$transactions = $result['transactions'];
$total_pages = $result['pages'];

// Handle CSV export
if (isset($_GET['export']) && $_GET['export'] === 'csv') {
    $export_data = exportTransactionsCSV($filters);
    
    header('Content-Type: text/csv');
    header('Content-Disposition: attachment; filename="transactions_' . date('Y-m-d') . '.csv"');
    
    $output = fopen('php://output', 'w');
    fputcsv($output, ['ID', 'User', 'Email', 'Service Type', 'Amount', 'Status', 'Provider', 'Reference', 'Date']);
    
    foreach ($export_data as $row) {
        fputcsv($output, $row);
    }
    
    fclose($output);
    exit;
}

$page_title = 'Transactions';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Transactions</h1>
    <a href="?export=csv&<?= http_build_query($filters) ?>" class="btn btn-outline-success">
        <i class="bi bi-download"></i> Export CSV
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" <?= $filters['status'] === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="completed" <?= $filters['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                    <option value="failed" <?= $filters['status'] === 'failed' ? 'selected' : '' ?>>Failed</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="type" class="form-select">
                    <option value="">All Types</option>
                    <option value="airtime" <?= $filters['type'] === 'airtime' ? 'selected' : '' ?>>Airtime</option>
                    <option value="data" <?= $filters['type'] === 'data' ? 'selected' : '' ?>>Data</option>
                    <option value="tv" <?= $filters['type'] === 'tv' ? 'selected' : '' ?>>TV</option>
                    <option value="electricity" <?= $filters['type'] === 'electricity' ? 'selected' : '' ?>>Electricity</option>
                </select>
            </div>
            <div class="col-md-2">
                <input type="text" class="form-control" name="provider" placeholder="Provider" value="<?= htmlspecialchars($filters['provider']) ?>">
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
        </form>
    </div>
</div>

<!-- Transactions Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Type</th>
                    <th>Amount</th>
                    <th>Status</th>
                    <th>Provider</th>
                    <th>Reference</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($transactions as $txn): ?>
                <tr>
                    <td><?= $txn['id'] ?></td>
                    <td>
                        <?= htmlspecialchars($txn['user_name']) ?><br>
                        <small class="text-muted"><?= htmlspecialchars($txn['user_email']) ?></small>
                    </td>
                    <td>
                        <span class="badge bg-info"><?= ucfirst($txn['service_type']) ?></span>
                    </td>
                    <td>₦<?= number_format($txn['amount'], 2) ?></td>
                    <td>
                        <span class="badge status-<?= $txn['status'] ?>"><?= ucfirst($txn['status']) ?></span>
                    </td>
                    <td><?= htmlspecialchars($txn['provider']) ?></td>
                    <td>
                        <code><?= htmlspecialchars($txn['reference']) ?></code>
                    </td>
                    <td><?= date('M j, Y g:i A', strtotime($txn['created_at'])) ?></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="view.php?id=<?= $txn['id'] ?>" class="btn btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <?php if ($txn['status'] === 'pending'): ?>
                                <a href="edit.php?id=<?= $txn['id'] ?>" class="btn btn-outline-primary">
                                    <i class="bi bi-pencil"></i>
                                </a>
                            <?php endif; ?>
                        </div>
                    </td>
                </tr>
                <?php endforeach; ?>
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

<?php
include '../../includes/admin/footer.php';
include '../../includes/admin/scripts.php';
?>