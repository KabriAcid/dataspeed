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
<script src="../assets/js/session-lock.js"></script>
<script src="../assets/js/bootstrap.js"></script>
<script src="../assets/js/popper.js"></script>