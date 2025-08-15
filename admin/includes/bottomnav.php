<?php if (!defined('DS_BOTTOMNAV_INCLUDED')): define('DS_BOTTOMNAV_INCLUDED', true); ?>
    <nav class="bottom-nav d-lg-none">
        <ul class="bottom-nav-list">
            <li class="bottom-nav-item">
                <a href="dashboard.php" class="nav-link" data-page="Dashboard" aria-label="Dashboard">
                    <i class="ni ni-tv-2"></i>
                    <span class="nav-text">Home</span>
                </a>
            </li>
            <li class="bottom-nav-item">
                <a href="users.php" class="nav-link" data-page="Users" aria-label="Users">
                    <i class="ni ni-circle-08"></i>
                    <span class="nav-text">Users</span>
                </a>
            </li>
            <li class="bottom-nav-item">
                <a href="transactions.php" class="nav-link" data-page="Transactions" aria-label="Transactions">
                    <i class="ni ni-credit-card"></i>
                    <span class="nav-text">Transact</span>
                </a>
            </li>
            <li class="bottom-nav-item">
                <a href="pricing.php" class="nav-link" data-page="Pricing" aria-label="Pricing">
                    <i class="ni ni-money-coins"></i>
                    <span class="nav-text">Pricing</span>
                </a>
            </li>
            <li class="bottom-nav-item">
                <a href="settings.php" class="nav-link" data-page="Settings" aria-label="Settings">
                    <i class="ni ni-settings-gear-65"></i>
                    <span class="nav-text">Settings</span>
                </a>
            </li>
        </ul>
    </nav>
<?php endif; ?>