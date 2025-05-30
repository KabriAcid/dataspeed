document.addEventListener("DOMContentLoaded", function () {
    const steps = document.querySelectorAll(".form-step");
    const indicators = document.querySelectorAll(".pagination .page");
    let currentStep = parseInt(sessionStorage.getItem("currentStep")) || 0;

    function showStep(step) {
        steps.forEach((el, index) => {
            el.classList.toggle("d-none", index !== step);
        });

        indicators.forEach((el, index) => {
            el.classList.toggle("active", index === step);
        });

        sessionStorage.setItem("currentStep", step);
    }

    const emailInput = document.getElementById("email");
    const emailSubmit = document.getElementById("email-submit");

    emailSubmit.addEventListener("click", function () {
        const email = emailInput.value.trim();

        if (email === "") {
            showToasted("Email address is required.", 'error')
            return;
        }
        
        if (email.length <= 7) {
            showToasted("Invalid email address.", 'error')
            return;
        }

        sendAjaxRequest("send-token.php", "POST", "email=" + encodeURIComponent(email), function (response) {
            if (!response.success) {
                showToasted(response.message, 'error')
                emailInput.classList.add('error-input');
            } else {
                sessionStorage.setItem('reset_email', email);
                showToasted(response.message, 'success')
                setTimeout(() => {
                    currentStep = 1;
                    showStep(currentStep);
                }, 10);
            }
        });
    });

    const tokenInput = document.getElementById("token");
    const tokenSubmit = document.getElementById("token-submit");

    tokenSubmit.addEventListener("click", function () {
        const token = tokenInput.value.trim();

        if (token === "") {
            showToasted('Token is required', 'error');
            return;
        }

        sendAjaxRequest("verify-token.php", "POST", "token=" + encodeURIComponent(token), function (response) {
            if (!response.success) {
                showToasted(response.message, 'error');
                console.log(sessionStorage.getItem('reset_email'));
            } else {
                setTimeout(() => {
                    showToasted(response.message, 'success');
                    currentStep = 2;
                    showStep(currentStep);
                }, 1000);
            }
        });
    });

    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm-password");
    const passwordError = document.getElementById("password-error");
    const passwordSubmit = document.getElementById("password-submit");

    passwordSubmit.addEventListener("click", function () {
        const password = passwordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();

        const reset_email = sessionStorage.getItem('reset_email');

        if (password === "" || confirmPassword === "") {
            showToasted("Both fields are required.", 'error');
            return;
        }

        if (password.length < 8) {
            showToasted("Password must be at least 8 characters long.", 'error');
            return;
        }

        if (password !== confirmPassword) {
            showToasted("Passwords do not match.", 'error');
            return;
        }

        sendAjaxRequest("reset-password.php", "POST", "password=" + encodeURIComponent(password) + "&reset_email=" + encodeURIComponent(reset_email), function (response) {
            if (!response.success) {
                showToasted(response.message, 'error');
            } else {
                setTimeout(() => {
                    showToasted(response.message, 'success');
                    sessionStorage.clear();
                    window.location.href = "login.php?success=1";
                }, 1000);
            }
        });
    });

    showStep(currentStep);
});
