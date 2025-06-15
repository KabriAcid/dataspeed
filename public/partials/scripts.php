<?php include __DIR__ . '/../partials/session-unlock.php'; ?>
<script>
    // Add functionality for the header-back-button
    document.addEventListener('DOMContentLoaded', function() {
        const backButton = document.querySelector('.header-back-button');
        if (backButton) {
            backButton.addEventListener('click', function() {
                window.history.back(); // Navigate to the previous page
            });
        }
    });


    // Show the session unlock modal
    function showReauthModal() {
        document.getElementById('reauthModal').style.display = 'flex';
        document.getElementById('reauthPassword').value = '';
        document.getElementById('reauthPassword').focus();
    }

    // Handle unlock submit
    // ...existing code...
    document.getElementById('reauthSubmit').onclick = function() {
        const pwd = document.getElementById('reauthPassword').value;
        sendAjaxRequest('reauth.php', 'POST', 'password=' + encodeURIComponent(pwd), function(response) {
            if (response.success) {
                document.getElementById('reauthModal').style.display = 'none';
                showToasted('Session unlocked!', 'success');
                location.reload(); // Or resume previous action
            } else {
                showToasted(response.message, 'error');
            }
        });
    };
    // ...existing code...

    // Global AJAX response handler
    function handleAjaxResponse(response) {
        if (response.reauth) {
            showReauthModal();
            return false; // Prevent further processing
        }
        return true;
    }

    // Example usage in your AJAX callbacks:
    sendAjaxRequest('some-endpoint.php', 'POST', 'data=...', function(response) {
        if (!handleAjaxResponse(response)) return;
        // ...your normal success/error handling...
    });
</script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/popper.js"></script>