<?php
$current_page = $_SERVER['REQUEST_URI'];
$nav_items = [
    'Dashboard' => '/dataspeed/admin/',
    'Users' => '/dataspeed/admin/users/',
    'Transactions' => '/dataspeed/admin/transactions/',
    'KYC' => '/dataspeed/admin/kyc/',
    'Notifications' => '/dataspeed/admin/notifications/',
    'Referrals' => '/dataspeed/admin/referrals/',
    'Settings' => '/dataspeed/admin/settings/',
    'Audit Log' => '/dataspeed/admin/audit/'
];
?>

<!-- Sidebar -->
<div class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <h5 class="text-white mb-0">Dataspeed Admin</h5>
    </div>
    
    <nav class="sidebar-nav">
        <?php foreach ($nav_items as $label => $url): ?>
            <a href="<?= $url ?>" class="nav-link <?= strpos($current_page, $url) === 0 ? 'active' : '' ?>">
                <i class="bi bi-<?= strtolower(str_replace(' ', '-', $label)) === 'dashboard' ? 'speedometer2' : 
                    (strtolower($label) === 'users' ? 'people' : 
                    (strtolower($label) === 'transactions' ? 'credit-card' : 
                    (strtolower($label) === 'kyc' ? 'shield-check' :
                    (strtolower($label) === 'notifications' ? 'bell' :
                    (strtolower($label) === 'referrals' ? 'share' :
                    (strtolower($label) === 'settings' ? 'gear' : 'journal-text')))))) ?>"></i>
                <span><?= $label ?></span>
            </a>
        <?php endforeach; ?>
    </nav>
</div>

<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebar-overlay"></div>

<!-- Main Content -->
<div class="main-content">
    <!-- Mobile Header -->
    <div class="mobile-header d-lg-none">
        <button class="btn btn-link sidebar-toggle" id="sidebar-toggle">
            <i class="bi bi-list"></i>
        </button>
        <span class="fw-bold">Dataspeed Admin</span>
        <a href="/dataspeed/admin/logout.php" class="btn btn-outline-danger btn-sm">
            <i class="bi bi-box-arrow-right"></i>
        </a>
    </div>
    
    <div class="content-wrapper">