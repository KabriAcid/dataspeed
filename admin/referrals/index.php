<?php
session_start();
require_once '../../config/config.php';

if (!isset($_SESSION['admin_id'])) {
    header('Location: ../login.php');
    exit;
}

// Get referrals data
$page = max(1, (int)($_GET['page'] ?? 1));
$limit = 20;
$offset = ($page - 1) * $limit;

$query = "SELECT r.*, 
                 u1.name as referrer_name, u1.email as referrer_email,
                 u2.name as referred_name, u2.email as referred_email
          FROM referrals r
          LEFT JOIN users u1 ON r.referrer_id = u1.id
          LEFT JOIN users u2 ON r.referred_id = u2.id
          ORDER BY r.created_at DESC
          LIMIT $limit OFFSET $offset";

$stmt = $pdo->query($query);
$referrals = $stmt->fetchAll();

// Get total count
$countStmt = $pdo->query("SELECT COUNT(*) FROM referrals");
$total = $countStmt->fetchColumn();
$total_pages = ceil($total / $limit);

// Get referral stats
$stats = [];
$stmt = $pdo->query("SELECT COUNT(*) FROM referrals WHERE status = 'completed'");
$stats['completed'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COUNT(*) FROM referrals WHERE status = 'pending'");
$stats['pending'] = $stmt->fetchColumn();

$stmt = $pdo->query("SELECT COALESCE(SUM(reward_amount), 0) FROM referrals WHERE status = 'completed'");
$stats['total_rewards'] = $stmt->fetchColumn();

$page_title = 'Referrals Management';
include '../../includes/admin/header.php';
include '../../includes/admin/sidebar.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <h1 class="h3 mb-0">Referrals Management</h1>
</div>

<!-- Stats Cards -->
<div class="row g-4 mb-4">
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-check-circle text-success display-6"></i>
                <div class="h4 text-success mt-2"><?= $stats['completed'] ?></div>
                <small class="text-muted">Completed Referrals</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-clock text-warning display-6"></i>
                <div class="h4 text-warning mt-2"><?= $stats['pending'] ?></div>
                <small class="text-muted">Pending Referrals</small>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-body text-center">
                <i class="bi bi-currency-dollar text-primary display-6"></i>
                <div class="h4 text-primary mt-2">₦<?= number_format($stats['total_rewards'], 2) ?></div>
                <small class="text-muted">Total Rewards Paid</small>
            </div>
        </div>
    </div>
</div>

<!-- Referrals Table -->
<div class="card">
    <div class="card-header">
        <h5 class="card-title mb-0">Referral History</h5>
    </div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Referrer</th>
                    <th>Referred User</th>
                    <th>Reward Amount</th>
                    <th>Status</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($referrals as $referral): ?>
                <tr>
                    <td><?= $referral['id'] ?></td>
                    <td>
                        <?= htmlspecialchars($referral['referrer_name']) ?><br>
                        <small class="text-muted"><?= htmlspecialchars($referral['referrer_email']) ?></small>
                    </td>
                    <td>
                        <?= htmlspecialchars($referral['referred_name']) ?><br>
                        <small class="text-muted"><?= htmlspecialchars($referral['referred_email']) ?></small>
                    </td>
                    <td>₦<?= number_format($referral['reward_amount'], 2) ?></td>
                    <td>
                        <span class="badge status-<?= $referral['status'] ?>"><?= ucfirst($referral['status']) ?></span>
                    </td>
                    <td><?= date('M j, Y', strtotime($referral['created_at'])) ?></td>
                    <td>
                        <?php if ($referral['status'] === 'pending'): ?>
                            <button class="btn btn-sm btn-outline-success" 
                                    onclick="if(confirm('Mark this referral as completed?')) location.href='?action=complete&id=<?= $referral['id'] ?>'">
                                Complete
                            </button>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                
                <?php if (empty($referrals)): ?>
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">No referrals found</td>
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
                <a class="page-link" href="?page=<?= $i ?>"><?= $i ?></a>
            </li>
        <?php endfor; ?>
    </ul>
</nav>
<?php endif; ?>

<?php
// Handle complete referral action
if (isset($_GET['action']) && $_GET['action'] === 'complete' && isset($_GET['id'])) {
    $referral_id = (int)$_GET['id'];
    
    $stmt = $pdo->prepare("UPDATE referrals SET status = 'completed' WHERE id = ? AND status = 'pending'");
    if ($stmt->execute([$referral_id])) {
        echo "<script>window.location.href = 'index.php';</script>";
    }
}
?>

<?php
include '../../includes/admin/footer.php';
include '../../includes/admin/scripts.php';
?>