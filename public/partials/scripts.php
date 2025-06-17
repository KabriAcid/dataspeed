<?php
$missing_env = checkEnvVars();
if (!empty($missing_env)) {
    $_SESSION['toast_error'] = "Service not configured. Missing: " . $missing_env;
}
if (!empty($_SESSION['reauth_required'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showReauthModal();
        });
    </script>
<?php endif; ?>
<?php if (!empty($_SESSION['toast_error'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToasted("<?= addslashes($_SESSION['toast_error']) ?>", "error");
        });
    </script>
<?php unset($_SESSION['toast_error']);
endif; ?>
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
</script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/popper.js"></script>