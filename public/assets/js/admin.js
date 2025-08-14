// Sidebar Toggle
document.addEventListener('DOMContentLoaded', function() {
    const sidebarToggle = document.getElementById('sidebar-toggle');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            overlay.classList.toggle('show');
        });
    }

    if (overlay) {
        overlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            overlay.classList.remove('show');
        });
    }

    // Confirm Deletes
    document.querySelectorAll('.confirm-delete').forEach(function(btn) {
        btn.addEventListener('click', function(e) {
            if (!confirm('Are you sure you want to delete this item?')) {
                e.preventDefault();
            }
        });
    });

    // Table Row Actions
    document.querySelectorAll('.table-actions select').forEach(function(select) {
        select.addEventListener('change', function() {
            if (this.value && confirm('Apply this action?')) {
                const form = this.closest('form');
                if (form) form.submit();
            }
        });
    });

    // Auto-hide alerts
    setTimeout(function() {
        const alerts = document.querySelectorAll('.alert:not(.alert-permanent)');
        alerts.forEach(function(alert) {
            alert.style.transition = 'opacity 0.5s';
            alert.style.opacity = '0';
            setTimeout(function() {
                alert.remove();
            }, 500);
        });
    }, 5000);
});

// Inline Editing for Pricing
function toggleEdit(elementId, currentValue) {
    const element = document.getElementById(elementId);
    const isEditing = element.querySelector('input');
    
    if (isEditing) {
        // Save mode
        const input = element.querySelector('input');
        const newValue = input.value;
        savePricing(elementId.replace('price-', ''), newValue);
        element.innerHTML = newValue;
    } else {
        // Edit mode
        element.innerHTML = `<input type="number" step="0.01" value="${currentValue}" 
                            onblur="toggleEdit('${elementId}', '${currentValue}')"
                            onkeypress="if(event.key==='Enter') this.blur()">`;
        element.querySelector('input').focus();
    }
}

function savePricing(planId, newPrice) {
    fetch('/dataspeed/admin/settings/update-pricing.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            plan_id: planId,
            price: newPrice
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showAlert('Price updated successfully', 'success');
        } else {
            showAlert('Error updating price', 'danger');
        }
    })
    .catch(error => {
        showAlert('Error updating price', 'danger');
    });
}

function showAlert(message, type) {
    const alertDiv = document.createElement('div');
    alertDiv.className = `alert alert-${type} alert-dismissible fade show position-fixed top-0 end-0 m-3`;
    alertDiv.style.zIndex = '9999';
    alertDiv.innerHTML = `
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;
    document.body.appendChild(alertDiv);
    
    setTimeout(() => alertDiv.remove(), 3000);
}