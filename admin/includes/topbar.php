<nav class="topbar">
    <div class="topbar-container">
        <div class="topbar-left">
            <button class="sidebar-toggle" id="sidebarToggle">
                <i class="ni ni-menu-2"></i>
            </button>
            <div class="page-breadcrumb">
                <h4 class="page-title" id="pageTitle">Dashboard</h4>
            </div>
        </div>

        <div class="topbar-right">
            <div class="topbar-search d-none d-md-block">
                <div class="search-box">
                    <i class="ni ni-zoom-split-in"></i>
                    <input type="text" placeholder="Search..." class="search-input">
                </div>
            </div>

            <div class="topbar-notifications">
                <button onclick="window.location.href='notifications.php'" class="notification-btn">
                    <i class="ni ni-bell-55 fs-4"></i>
                    <span class="notification-badge" id="notifBadge">0</span>
                </button>
            </div>

            <div class="admin-dropdown dropdown">
                <button class="admin-avatar dropdown-toggle" data-bs-toggle="dropdown">
                    <div class="avatar">
                        <i class="ni ni-single-02"></i>
                    </div>
                    <span id="adminNameShort" class="admin-name d-none d-md-inline">Admin</span>
                </button>
                <div class="dropdown-menu dropdown-menu-end premium-dropdown">
                    <!-- Admin Profile Section -->
                    <div class="dropdown-header">
                        <div class="admin-info">
                            <div class="admin-avatar-small">
                                <img id="adminAvatarSmall" src="../public/favicon.png" alt="Avatar" class="img-fluid rounded-circle" />
                            </div>
                            <div class="admin-details">
                                <h6 id="adminNameFull" class="admin-name-full">Super Admin</h6>
                                <small id="adminEmail" class="admin-email">admin@vtuapp.com</small>
                            </div>
                        </div>
                    </div>

                    <div class="dropdown-divider"></div>

                    <!-- Account Management -->
                    <div class="dropdown-section">
                        <h6 class="dropdown-section-title">Account</h6>
                        <a class="dropdown-item" href="profile.php">
                            <div class="dropdown-item-icon">
                                <i class="ni ni-single-02"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <span class="item-title">My Profile</span>
                                <small class="item-subtitle">Manage account settings</small>
                            </div>
                        </a>
                        <a class="dropdown-item" href="settings.php">
                            <div class="dropdown-item-icon">
                                <i class="ni ni-settings-gear-65"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <span class="item-title">Settings</span>
                                <small class="item-subtitle">System preferences</small>
                            </div>
                        </a>
                        <a class="dropdown-item" href="activity-log.php">
                            <div class="dropdown-item-icon">
                                <i class="ni ni-time-alarm"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <span class="item-title">Activity Log</span>
                                <small class="item-subtitle">View recent actions</small>
                            </div>
                        </a>
                    </div>
                    <div class="dropdown-divider"></div>
                    <!-- Logout -->
                    <a class="dropdown-item logout-item" href="logout.php">
                        <div class="dropdown-item-icon">
                            <i class="ni ni-user-run"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <span class="item-title">Sign Out</span>
                            <small class="item-subtitle">Logout from admin panel</small>
                        </div>
                    </a>
                </div>

            </div>
        </div>
    </div>
</nav>