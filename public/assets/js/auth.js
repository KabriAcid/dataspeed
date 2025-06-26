// DOM Ready
document.addEventListener('DOMContentLoaded', function () {
    const userInput = document.getElementById('user');
    const passwordInput = document.getElementById('password');
    const loginBtn = document.getElementById('login');

    loginBtn.addEventListener('click', function () {
        const user = userInput.value.trim();
        const password = passwordInput.value.trim();

        if (!user && !password) {
            showToasted("Email and password are required.", 'error');
            return;
        }
        if (!user) {
            showToasted("Phone number or email is required.", 'error');
            return;
        }
        if (!password) {
            showToasted("Password is required.", 'error');
            return;
        }

        loginBtn.disabled = true;
        loginBtn.style.cursor = 'not-allowed';

        sendAjaxRequest(
            "validate-user.php",
            "POST",
            `user=${encodeURIComponent(user)}&password=${encodeURIComponent(password)}`,
            function (response) {
                loginBtn.disabled = false;
                loginBtn.style.cursor = 'pointer';

                if (!response.success) {
                    showToasted(response.message, 'error');
                } else {
                    window.location.href = "dashboard.php?success=1";

                    // After the page loads, remove the "success" parameter
                    window.history.replaceState(null, null, "dashboard.php");

                }
            }
        );
    });
});
