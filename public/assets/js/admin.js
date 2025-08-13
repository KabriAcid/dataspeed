/**
 * Dataspeed Admin UI JavaScript
 * Handles common admin functionality, AJAX requests, and UI interactions
 */

class DataspeedAdmin {
    constructor() {
        this.csrfToken = document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '';
        this.init();
    }

    init() {
        this.setupEventListeners();
        this.initializeComponents();
    }

    setupEventListeners() {
        // Quick search functionality
        const quickSearch = document.getElementById('quickSearch');
        if (quickSearch) {
            let searchTimeout;
            quickSearch.addEventListener('input', (e) => {
                clearTimeout(searchTimeout);
                searchTimeout = setTimeout(() => {
                    this.performQuickSearch(e.target.value);
                }, 300);
            });
        }

        // Table row actions
        document.addEventListener('click', (e) => {
            if (e.target.matches('.btn-action-delete')) {
                e.preventDefault();
                this.confirmDelete(e.target);
            }
            
            if (e.target.matches('.btn-toggle-status')) {
                e.preventDefault();
                this.toggleStatus(e.target);
            }
        });

        // Form submissions with CSRF
        document.addEventListener('submit', (e) => {
            if (e.target.matches('.admin-form')) {
                this.handleFormSubmission(e);
            }
        });

        // Modal handling
        document.addEventListener('show.bs.modal', (e) => {
            this.handleModalShow(e);
        });
    }

    initializeComponents() {
        // Initialize tooltips
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl);
        });

        // Initialize data tables with sorting
        this.initializeDataTables();
        
        // Auto-refresh dashboard stats
        if (document.body.classList.contains('dashboard-page')) {
            this.startDashboardRefresh();
        }
    }

    performQuickSearch(query) {
        if (query.length < 2) return;
        
        // Show loading state
        const searchResults = document.getElementById('quickSearchResults');
        if (searchResults) {
            searchResults.innerHTML = '<div class="text-center p-2"><div class="spinner-border spinner-border-sm" role="status"></div></div>';
            searchResults.style.display = 'block';
        }

        fetch('/admin/api/quick-search.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': this.csrfToken
            },
            body: JSON.stringify({ query: query })
        })
        .then(response => response.json())
        .then(data => {
            this.displaySearchResults(data);
        })
        .catch(error => {
            console.error('Search error:', error);
            this.showToast('Search failed', 'error');
        });
    }

    displaySearchResults(results) {
        const searchResults = document.getElementById('quickSearchResults');
        if (!searchResults) return;

        if (results.length === 0) {
            searchResults.innerHTML = '<div class="p-2 text-muted">No results found</div>';
            return;
        }

        let html = '';
        results.forEach(result => {
            html += `
                <a href="${result.url}" class="dropdown-item border-radius-md">
                    <div class="d-flex py-1">
                        <div class="my-auto">
                            <i class="${result.icon} me-2"></i>
                        </div>
                        <div class="d-flex flex-column justify-content-center">
                            <h6 class="text-sm font-weight-normal mb-1">${result.title}</h6>
                            <p class="text-xs text-secondary mb-0">${result.subtitle}</p>
                        </div>
                    </div>
                </a>
            `;
        });
        
        searchResults.innerHTML = html;
    }

    confirmDelete(button) {
        const entityType = button.dataset.entity || 'item';
        const entityId = button.dataset.id;
        const entityName = button.dataset.name || 'this item';

        if (confirm(`Are you sure you want to delete ${entityName}? This action cannot be undone.`)) {
            this.performDelete(entityType, entityId, button);
        }
    }

    performDelete(entityType, entityId, button) {
        const originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
        button.disabled = true;

        fetch(`/admin/api/delete.php`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': this.csrfToken
            },
            body: JSON.stringify({
                entity_type: entityType,
                entity_id: entityId
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Remove the row from table
                const row = button.closest('tr');
                if (row) {
                    row.style.transition = 'opacity 0.3s';
                    row.style.opacity = '0';
                    setTimeout(() => row.remove(), 300);
                }
                this.showToast('Item deleted successfully', 'success');
            } else {
                throw new Error(data.message || 'Delete failed');
            }
        })
        .catch(error => {
            console.error('Delete error:', error);
            button.innerHTML = originalText;
            button.disabled = false;
            this.showToast(error.message || 'Delete failed', 'error');
        });
    }

    toggleStatus(button) {
        const entityType = button.dataset.entity;
        const entityId = button.dataset.id;
        const currentStatus = button.dataset.status;
        const newStatus = currentStatus === 'active' ? 'inactive' : 'active';

        const originalText = button.innerHTML;
        button.innerHTML = '<span class="spinner-border spinner-border-sm" role="status"></span>';
        button.disabled = true;

        fetch('/admin/api/toggle-status.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-Token': this.csrfToken
            },
            body: JSON.stringify({
                entity_type: entityType,
                entity_id: entityId,
                status: newStatus
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Update button and badge
                button.dataset.status = newStatus;
                button.innerHTML = newStatus === 'active' ? 'Deactivate' : 'Activate';
                button.className = button.className.replace(/btn-\w+/, newStatus === 'active' ? 'btn-warning' : 'btn-success');
                
                // Update status badge
                const statusBadge = button.closest('tr').querySelector('.badge-status');
                if (statusBadge) {
                    statusBadge.textContent = newStatus;
                    statusBadge.className = statusBadge.className.replace(/badge-status-\w+/, `badge-status-${newStatus}`);
                }
                
                this.showToast(`Status updated to ${newStatus}`, 'success');
            } else {
                throw new Error(data.message || 'Status update failed');
            }
        })
        .catch(error => {
            console.error('Status toggle error:', error);
            this.showToast(error.message || 'Status update failed', 'error');
        })
        .finally(() => {
            button.disabled = false;
        });
    }

    handleFormSubmission(e) {
        const form = e.target;
        const submitButton = form.querySelector('button[type="submit"]');
        
        if (submitButton) {
            const originalText = submitButton.innerHTML;
            submitButton.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Processing...';
            submitButton.disabled = true;
            
            // Re-enable button after 5 seconds as fallback
            setTimeout(() => {
                submitButton.innerHTML = originalText;
                submitButton.disabled = false;
            }, 5000);
        }
    }

    handleModalShow(e) {
        const modal = e.target;
        const trigger = e.relatedTarget;
        
        if (trigger && trigger.dataset.dynamicContent) {
            this.loadModalContent(modal, trigger.dataset.dynamicContent);
        }
    }

    loadModalContent(modal, contentUrl) {
        const modalBody = modal.querySelector('.modal-body');
        if (!modalBody) return;

        modalBody.innerHTML = '<div class="text-center p-4"><div class="spinner-border" role="status"></div></div>';

        fetch(contentUrl, {
            headers: {
                'X-CSRF-Token': this.csrfToken
            }
        })
        .then(response => response.text())
        .then(html => {
            modalBody.innerHTML = html;
        })
        .catch(error => {
            console.error('Modal content load error:', error);
            modalBody.innerHTML = '<div class="alert alert-danger">Failed to load content</div>';
        });
    }

    initializeDataTables() {
        const tables = document.querySelectorAll('.admin-table[data-sortable="true"]');
        tables.forEach(table => {
            this.makeSortable(table);
        });
    }

    makeSortable(table) {
        const headers = table.querySelectorAll('th[data-sort]');
        headers.forEach(header => {
            header.style.cursor = 'pointer';
            header.addEventListener('click', () => {
                this.sortTable(table, header.dataset.sort, header);
            });
        });
    }

    sortTable(table, column, header) {
        const currentSort = header.dataset.sortDirection || 'asc';
        const newSort = currentSort === 'asc' ? 'desc' : 'asc';
        
        // Update header indicators
        table.querySelectorAll('th').forEach(th => {
            th.classList.remove('sort-asc', 'sort-desc');
        });
        header.classList.add(`sort-${newSort}`);
        header.dataset.sortDirection = newSort;

        // Perform sort via AJAX if data-ajax="true"
        if (table.dataset.ajax === 'true') {
            this.ajaxSort(table, column, newSort);
        } else {
            this.clientSort(table, column, newSort);
        }
    }

    ajaxSort(table, column, direction) {
        const tbody = table.querySelector('tbody');
        tbody.style.opacity = '0.6';

        const params = new URLSearchParams(window.location.search);
        params.set('sort', column);
        params.set('direction', direction);

        fetch(`${window.location.pathname}?${params.toString()}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'X-CSRF-Token': this.csrfToken
            }
        })
        .then(response => response.text())
        .then(html => {
            const parser = new DOMParser();
            const doc = parser.parseFromString(html, 'text/html');
            const newTbody = doc.querySelector('.admin-table tbody');
            if (newTbody) {
                tbody.innerHTML = newTbody.innerHTML;
            }
        })
        .catch(error => {
            console.error('Sort error:', error);
            this.showToast('Sort failed', 'error');
        })
        .finally(() => {
            tbody.style.opacity = '1';
        });
    }

    clientSort(table, column, direction) {
        // Simple client-side sorting implementation
        const tbody = table.querySelector('tbody');
        const rows = Array.from(tbody.querySelectorAll('tr'));
        
        rows.sort((a, b) => {
            const aVal = a.querySelector(`[data-sort-value="${column}"]`)?.textContent || '';
            const bVal = b.querySelector(`[data-sort-value="${column}"]`)?.textContent || '';
            
            if (direction === 'asc') {
                return aVal.localeCompare(bVal, undefined, { numeric: true });
            } else {
                return bVal.localeCompare(aVal, undefined, { numeric: true });
            }
        });
        
        rows.forEach(row => tbody.appendChild(row));
    }

    startDashboardRefresh() {
        // Refresh dashboard stats every 30 seconds
        setInterval(() => {
            this.refreshDashboardStats();
        }, 30000);
    }

    refreshDashboardStats() {
        fetch('/admin/api/dashboard-stats.php', {
            headers: {
                'X-CSRF-Token': this.csrfToken
            }
        })
        .then(response => response.json())
        .then(data => {
            this.updateDashboardStats(data);
        })
        .catch(error => {
            console.error('Dashboard refresh error:', error);
        });
    }

    updateDashboardStats(stats) {
        Object.keys(stats).forEach(key => {
            const element = document.querySelector(`[data-stat="${key}"]`);
            if (element) {
                element.textContent = stats[key];
            }
        });
    }

    showToast(message, type = 'info') {
        const toast = document.getElementById('liveToast');
        const toastTitle = document.getElementById('toastTitle');
        const toastBody = document.getElementById('toastBody');
        
        if (!toast) return;

        // Set toast content
        toastBody.textContent = message;
        
        // Set toast type styling
        toast.className = `toast show bg-${type === 'error' ? 'danger' : type}`;
        toastTitle.textContent = type.charAt(0).toUpperCase() + type.slice(1);
        
        // Show toast
        const bsToast = new bootstrap.Toast(toast);
        bsToast.show();
    }

    // Utility method for making API calls
    async apiCall(endpoint, data = {}, method = 'POST') {
        try {
            const response = await fetch(`/admin/api/${endpoint}`, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-Token': this.csrfToken
                },
                body: method !== 'GET' ? JSON.stringify(data) : undefined
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            return await response.json();
        } catch (error) {
            console.error('API call error:', error);
            throw error;
        }
    }
}

// Initialize admin functionality when DOM is loaded
document.addEventListener('DOMContentLoaded', () => {
    window.dataspeedAdmin = new DataspeedAdmin();
});

// Export for use in other scripts
if (typeof module !== 'undefined' && module.exports) {
    module.exports = DataspeedAdmin;
}