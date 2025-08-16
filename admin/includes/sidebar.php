<aside class="sidebar" id="sidebar">
    <div class="sidebar-header">
        <div class="sidebar-logo">
            <img src="../public/favicon.png" alt="" class="img-fluid favicon">
            <span class="logo-text">DataSpeed</span>
        </div>
        <button class="sidebar-toggle" id="sidebarCollapse" type="button" aria-label="Collapse sidebar" title="Collapse sidebar">
            <i class="ni ni-bold-left"></i>
        </button>
    </div>

    <nav class="sidebar-nav">
        <ul class="nav-list">
            <li class="nav-item">
                <a href="dashboard.php" class="nav-link active" data-page="Dashboard">
                    <i class="ni ni-tv-2"></i>
                    <span class="nav-text">Dashboard</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="users.php" class="nav-link" data-page="Users">
                    <i class="ni ni-circle-08"></i>
                    <span class="nav-text">Users</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="pricing.php" class="nav-link" data-page="Pricing">
                    <i class="ni ni-money-coins"></i>
                    <span class="nav-text">Pricing</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="transactions.php" class="nav-link" data-page="Transactions">
                    <i class="ni ni-credit-card"></i>
                    <span class="nav-text">Transactions</span>
                </a>
            </li>

            <li class="nav-item">
                <a href="settings.php" class="nav-link" data-page="Settings">
                    <i class="ni ni-settings-gear-65"></i>
                    <span class="nav-text">Settings</span>
                </a>
            </li>

            <!-- Notifications -->
            <li class="nav-item">
                <a href="notifications.php" class="nav-link" data-page="Notifications">
                    <i class="ni ni-bell-55"></i>
                    <span class="nav-text">Notifications</span>
                </a>
            </li>

            <!-- Activity Log -->
            <!-- <li class="nav-item">
                <a href="activity-log.php" class="nav-link" data-page="Activity Log">
                    <i class="ni ni-bullet-list-67"></i>
                    <span class="nav-text">Activity Log</span>
                </a>
            </li> -->

        </ul>
    </nav>
    <!-- Bottom favicon only visible when collapsed -->
    <div class="sidebar-footer">
        <img src="../public/favicon.png" alt="" class="img-fluid favicon cursor pointer">
    </div>
</aside>

<div class="sidebar-overlay" id="sidebarOverlay"></div>