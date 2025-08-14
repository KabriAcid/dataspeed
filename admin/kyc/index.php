<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

// Get KYC applications
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

$where = [];
$params = [];

if ($search) {
    $where[] = "(u.name LIKE ? OR u.email LIKE ?)";
    $params[] = "%$search%";
    $params[] = "%$search%";
}

if ($status) {
    $where[] = "k.status = ?";
    $params[] = $status;
}

$whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';

// Get total count
$countQuery = "SELECT COUNT(*) FROM kyc_applications k 
               LEFT JOIN users u ON k.user_id = u.id 
               $whereClause";
$countStmt = $pdo->prepare($countQuery);
$countStmt->execute($params);
$total = $countStmt->fetchColumn();
$total_pages = ceil($total / $limit);

// Get KYC applications
$query = "SELECT k.*, u.name as user_name, u.email as user_email 
          FROM kyc_applications k 
          LEFT JOIN users u ON k.user_id = u.id 
          $whereClause 
          ORDER BY k.created_at DESC 
          LIMIT $limit OFFSET $offset";

$stmt = $pdo->prepare($query);
$stmt->execute($params);
$kyc_applications = $stmt->fetchAll();

$page_title = 'KYC Management';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">KYC Management</h1>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" placeholder="Search by user name or email..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-3">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="pending" <?= $status === 'pending' ? 'selected' : '' ?>>Pending</option>
                    <option value="approved" <?= $status === 'approved' ? 'selected' : '' ?>>Approved</option>
                    <option value="rejected" <?= $status === 'rejected' ? 'selected' : '' ?>>Rejected</option>
                </select>
            </div>
            <div class="col-md-2">
                <button type="submit" class="btn btn-outline-primary">Filter</button>
            </div>
            <div class="col-md-2">
                <a href="index.php" class="btn btn-outline-secondary">Clear</a>
            </div>
        </form>
    </div>
</div>

<!-- KYC Applications Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Document Type</th>
                    <th>Status</th>
                    <th>Submitted</th>
                    <th>Reviewed</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kyc_applications as $kyc): ?>
                <tr>
                    <td><?= $kyc['id'] ?></td>
                    <td>
                        <?= htmlspecialchars($kyc['user_name']) ?><br>
                        <small class="text-muted"><?= htmlspecialchars($kyc['user_email']) ?></small>
                    </td>
                    <td>
                        <span class="badge bg-secondary"><?= ucfirst($kyc['document_type']) ?></span>
                    </td>
                    <td>
                        <span class="badge status-<?= $kyc['status'] ?>"><?= ucfirst($kyc['status']) ?></span>
                    </td>
                    <td><?= date('M j, Y', strtotime($kyc['created_at'])) ?></td>
                    <td>
                        <?= $kyc['reviewed_at'] ? date('M j, Y', strtotime($kyc['reviewed_at'])) : '-' ?>
                    </td>
                    <td>
                        <a href="review.php?id=<?= $kyc['id'] ?>" class="btn btn-sm btn-outline-primary">
                            <i class="bi bi-eye"></i> Review
                        </a>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($kyc_applications)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No KYC applications found</td>
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
                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<?php
include '../../includes/admin/footer.php';
include '../../includes/admin/scripts.php';
?>