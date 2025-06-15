<?php if (!empty($_SESSION['reauth_required'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showReauthModal();
        });
    </script>
<?php endif; ?>
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


    document.addEventListener('DOMContentLoaded', function() {
        // Handle unlock submit
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

        // Handle unlock exit
        document.getElementById('reauthExit').onclick = function() {
            document.getElementById('reauthModal').style.display = 'none';
            // Optionally redirect or perform other actions
            window.location.href = 'logout.php';
        };
    });

    // Show the session unlock modal
    function showReauthModal() {
        var modal = document.getElementById('reauthModal');
        modal.style.display = 'flex';
        setTimeout(function() {
            document.getElementById('reauthPassword').focus();
        }, 100); // slight delay to ensure modal is visible
    }

</script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/popper.js"></script>