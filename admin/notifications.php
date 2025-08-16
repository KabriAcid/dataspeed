<?php
session_start();
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
require __DIR__ . '/includes/header.php';
?>

<body class="admin-body">
    <?php include __DIR__ . '/includes/topbar.php'; ?>
    <?php include __DIR__ . '/includes/sidebar.php'; ?>

    <main class="main-content notifications-page">
        <div class="container-fluid">
            <div class="page-header row g-2 align-items-center">
                <div class="col-12 col-md">
                    <h1 class="page-title mb-1" id="pageTitle">Notifications</h1>
                    <p class="page-subtitle mb-0">All system, user, and security alerts</p>
                </div>
                <div class="col-12 col-md-auto">
                    <div class="d-flex gap-2 flex-wrap justify-content-md-end">
                        <button id="markAllBtn" class="btn btn-outline-primary btn-sm w-100 w-md-auto">
                            <i class="ni ni-check-bold me-1"></i> Mark all as read
                        </button>
                        <button id="refreshBtn" class="btn btn-outline-secondary btn-sm w-100 w-md-auto">
                            <i class="ni ni-refresh-02 me-1"></i> Refresh
                        </button>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-body">
                    <div class="row g-3 align-items-end">
                        <div class="col-12 col-md-5">
                            <label class="form-label small text-muted mb-1">Search</label>
                            <input type="text" id="searchInput" class="form-control" placeholder="Search notifications..." />
                        </div>
                        <div class="col-6 col-md-3">
                            <label class="form-label small text-muted mb-1">Type</label>
                            <select id="typeFilter" class="form-select">
                                <option value="">All types</option>
                                <option value="system">System</option>
                                <option value="user">User</option>
                                <option value="security">Security</option>
                            </select>
                        </div>
                        <div class="col-6 col-md-2">
                            <label class="form-label small text-muted mb-1">Status</label>
                            <select id="statusFilter" class="form-select">
                                <option value="">All</option>
                                <option value="unread">Unread</option>
                                <option value="read">Read</option>
                            </select>
                        </div>
                        <div class="col-12 col-md-2 d-grid">
                            <button id="clearFilters" class="btn btn-light">Clear</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-12 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted small">Total</div>
                                    <div class="fs-4 fw-semibold" id="statTotal">0</div>
                                </div>
                                <span class="badge bg-secondary">All</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted small">Unread</div>
                                    <div class="fs-4 fw-semibold" id="statUnread">0</div>
                                </div>
                                <span class="badge bg-primary">Unread</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-6 col-lg-4">
                    <div class="card h-100">
                        <div class="card-body">
                            <div class="d-flex align-items-center justify-content-between">
                                <div>
                                    <div class="text-muted small">Today</div>
                                    <div class="fs-4 fw-semibold" id="statToday">0</div>
                                </div>
                                <span class="badge bg-success">Today</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-3 mb-5">
                <div class="card-header bg-white">
                    <div class="d-flex align-items-center justify-content-between">
                        <h2 class="h6 mb-0">All Notifications</h2>
                        <span class="badge rounded-pill bg-primary" id="badgeUnread">0 Unread</span>
                    </div>
                </div>
                <div class="card-body p-0">
                    <div id="listContainer" class="list-group list-group-flush">
                        <!-- items injected here -->
                    </div>
                    <div id="emptyState" class="text-center py-5 d-none">
                        <div class="empty-state">
                            <i class="ni ni-bell-55 fs-1 d-block mb-3"></i>
                            <h3>No notifications found</h3>
                            <p class="text-muted">Try adjusting your filters</p>
                        </div>
                    </div>
                </div>
                <div class="card-footer bg-white">
                    <div id="pagination" class="my-2"></div>
                </div>
            </div>
        </div>
    </main>

    <?php include __DIR__ . '/includes/footer.php'; ?>
    <?php include __DIR__ . '/includes/scripts.php'; ?>
    <style>
        /* Responsive tweaks for notifications list */
        .notifications-page #listContainer .list-group-item {
            gap: .5rem;
        }

        .notifications-page .notif-title {
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }

        .notifications-page .notif-message {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        @media (max-width: 576px) {
            .notifications-page #listContainer .list-group-item {
                padding-right: 1rem;
                padding-left: 1rem;
            }

            .notifications-page .notif-actions {
                width: 100%;
                display: flex;
                justify-content: flex-start;
            }

            .notifications-page .notif-date {
                margin-left: 0 !important;
            }
        }
    </style>
    <script>
        (function() {
            const listEl = document.getElementById('listContainer');
            const emptyEl = document.getElementById('emptyState');
            const paginationEl = document.getElementById('pagination');
            const searchInput = document.getElementById('searchInput');
            const typeFilter = document.getElementById('typeFilter');
            const statusFilter = document.getElementById('statusFilter');
            const clearBtn = document.getElementById('clearFilters');
            const refreshBtn = document.getElementById('refreshBtn');
            const markAllBtn = document.getElementById('markAllBtn');
            const statTotal = document.getElementById('statTotal');
            const statUnread = document.getElementById('statUnread');
            const statToday = document.getElementById('statToday');
            const badgeUnread = document.getElementById('badgeUnread');

            let page = 1;
            const perPage = 20;

            async function loadStats() {
                try {
                    const {
                        data
                    } = await apiFetch('api/notifications.php?action=stats');
                    statTotal.textContent = data.total;
                    statUnread.textContent = data.unread;
                    statToday.textContent = data.today;
                    badgeUnread.textContent = data.unread + ' Unread';
                } catch (e) {
                    console.warn('Stats load failed');
                }
            }

            function typeBadge(type) {
                const map = {
                    system: 'secondary',
                    user: 'info',
                    security: 'danger'
                };
                const cls = map[type] || 'secondary';
                return `<span class="badge bg-${cls} me-2 text-capitalize">${type}</span>`;
            }

            function itemTemplate(n) {
                const readCls = n.is_read == 1 ? '' : 'list-group-item-warning';
                return `
                <div class="list-group-item d-flex align-items-start flex-wrap ${readCls}">
                    <div class="me-3 mt-1 flex-shrink-0">
                        ${typeBadge(n.type)}
                    </div>
                    <div class="flex-grow-1 min-w-0">
                        <div class="d-flex align-items-start justify-content-between flex-column flex-sm-row gap-1">
                            <h6 class="mb-1 notif-title w-100">${n.title}</h6>
                            <small class="text-muted notif-date flex-shrink-0">${formatDate(n.created_at)}</small>
                        </div>
                        <div class="text-muted small notif-message">${n.message}</div>
                    </div>
                    <div class="notif-actions ms-0 ms-sm-3 mt-2 mt-sm-0">
                        ${n.is_read == 1 ? '' : `<button class=\"btn btn-sm btn-outline-primary\" data-action=\"mark\" data-id=\"${n.id}\"><i class=\"ni ni-check-bold\"></i></button>`}
                    </div>
                </div>`;
            }

            function bindRowActions() {
                listEl.querySelectorAll('[data-action="mark"]').forEach(btn => {
                    btn.addEventListener('click', async () => {
                        const id = parseInt(btn.getAttribute('data-id'));
                        btn.disabled = true;
                        try {
                            await apiFetch('api/notifications.php', {
                                method: 'POST',
                                body: JSON.stringify({
                                    action: 'markRead',
                                    id
                                })
                            });
                            await Promise.all([loadStats(), loadList()]);
                            // Update topbar badge count
                            if (window.refreshAdminNotifBadge) {
                                await window.refreshAdminNotifBadge().catch(() => {});
                            }
                        } catch (e) {
                            /* noop */
                        } finally {
                            btn.disabled = false;
                        }
                    });
                });
            }

            async function loadList() {
                const params = new URLSearchParams({
                    action: 'list',
                    search: searchInput.value.trim(),
                    type: typeFilter.value,
                    status: statusFilter.value,
                    page: page,
                    per_page: perPage
                }).toString();
                try {
                    const {
                        data
                    } = await apiFetch('api/notifications.php?' + params);
                    const items = data.items || [];
                    listEl.innerHTML = items.map(itemTemplate).join('');
                    emptyEl.classList.toggle('d-none', items.length > 0);
                    renderPagination(paginationEl, data.pagination);
                    bindRowActions();
                } catch (e) {
                    listEl.innerHTML = '';
                    emptyEl.classList.remove('d-none');
                }
            }

            const debouncedReload = debounce(() => {
                page = 1;
                loadList();
            }, 300);
            searchInput.addEventListener('input', debouncedReload);
            typeFilter.addEventListener('change', debouncedReload);
            statusFilter.addEventListener('change', debouncedReload);
            clearBtn.addEventListener('click', () => {
                searchInput.value = '';
                typeFilter.value = '';
                statusFilter.value = '';
                page = 1;
                loadList();
            });
            refreshBtn.addEventListener('click', () => loadList());
            markAllBtn.addEventListener('click', async () => {
                markAllBtn.disabled = true;
                try {
                    await apiFetch('api/notifications.php', {
                        method: 'POST',
                        body: JSON.stringify({
                            action: 'markAllRead'
                        })
                    });
                    await Promise.all([loadStats(), loadList()]);
                    // Update topbar badge count
                    if (window.refreshAdminNotifBadge) {
                        await window.refreshAdminNotifBadge().catch(() => {});
                    }
                } catch (e) {
                    /* noop */
                } finally {
                    markAllBtn.disabled = false;
                }
            });

            window.addEventListener('pageChange', e => {
                page = e.detail.page;
                loadList();
            });

            // initial
            loadStats();
            loadList();
        })();
    </script>
</body>

</html>