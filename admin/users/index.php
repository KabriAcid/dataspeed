<?php
session_start();
require_once '../../config/config.php';
require_once '../../functions/users.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$search = $_GET['search'] ?? '';
$status = $_GET['status'] ?? '';
$role = $_GET['role'] ?? '';

$result = getUsers($page, 20, $search, $status, $role);
$users = $result['users'];
$total_pages = $result['pages'];

$page_title = 'Users Management';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Users Management</h1>
    <a href="create.php" class="btn btn-primary">
        <i class="bi bi-plus"></i> Add User
    </a>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div class="card-body">
        <form class="row g-3" method="GET">
            <div class="col-md-4">
                <input type="text" class="form-control" name="search" placeholder="Search users..." value="<?= htmlspecialchars($search) ?>">
            </div>
            <div class="col-md-2">
                <select name="status" class="form-select">
                    <option value="">All Status</option>
                    <option value="active" <?= $status === 'active' ? 'selected' : '' ?>>Active</option>
                    <option value="inactive" <?= $status === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                    <option value="suspended" <?= $status === 'suspended' ? 'selected' : '' ?>>Suspended</option>
                </select>
            </div>
            <div class="col-md-2">
                <select name="role" class="form-select">
                    <option value="">All Roles</option>
                    <option value="user" <?= $role === 'user' ? 'selected' : '' ?>>User</option>
                    <option value="admin" <?= $role === 'admin' ? 'selected' : '' ?>>Admin</option>
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

<!-- Users Table -->
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>KYC</th>
                    <th>Balance</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($users as $user): ?>
                <tr>
                    <td><?= $user['id'] ?></td>
                    <td><?= htmlspecialchars($user['name']) ?></td>
                    <td><?= htmlspecialchars($user['email']) ?></td>
                    <td><?= htmlspecialchars($user['phone']) ?></td>
                    <td>
                        <span class="badge bg-info"><?= ucfirst($user['role']) ?></span>
                    </td>
                    <td>
                        <span class="badge status-<?= $user['status'] ?>"><?= ucfirst($user['status']) ?></span>
                    </td>
                    <td>
                        <span class="badge status-<?= $user['kyc_status'] ?>"><?= ucfirst($user['kyc_status']) ?></span>
                    </td>
                    <td>₦<?= number_format($user['balance'], 2) ?></td>
                    <td><?= date('M j, Y', strtotime($user['created_at'])) ?></td>
                    <td>
                        <div class="btn-group btn-group-sm">
                            <a href="view.php?id=<?= $user['id'] ?>" class="btn btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="edit.php?id=<?= $user['id'] ?>" class="btn btn-outline-primary">
                                <i class="bi bi-pencil"></i>
                            </a>
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
                <a class="page-link" href="?page=<?= $i ?>&search=<?= urlencode($search) ?>&status=<?= urlencode($status) ?>&role=<?= urlencode($role) ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<?php
include '../../includes/admin/footer.php';
include '../../includes/admin/scripts.php';
?>