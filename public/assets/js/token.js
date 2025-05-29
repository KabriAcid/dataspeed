document.addEventListener("DOMContentLoaded", function () {
    const steps = document.querySelectorAll(".form-step");
    const indicators = document.querySelectorAll(".pagination .page");
    let currentStep = 0;

    function showStep(step) {
        steps.forEach((el, index) => {
            el.classList.toggle("d-none", index !== step);
        });

        indicators.forEach((el, index) => {
            el.classList.toggle("active", index === step);
        });
    }

    function nextStep() {
        if (currentStep < steps.length - 1) {
            currentStep++;
            showStep(currentStep);
        }
    }

    function sendAjaxRequest(url, method, data, callback) {
        if (!navigator.onLine) {
            callback({
                success: false,
                message: "You are offline. Please check your internet connection."
            });
            return;
        }
    
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                if (xhr.status === 0) {
                    callback({
                        success: false,
                        message: "Request failed. You may be offline or the server is unreachable."
                    });
                } else {
                    try {
                        const response = JSON.parse(xhr.responseText);
                        callback(response);
                    } catch (error) {
                        callback({
                            success: false,
                            message: "Invalid JSON response"
                        });
                    }
                }
            }
        };
    
        xhr.onload = () => {
            try {
                const json = JSON.parse(xhr.responseText);
                callback(json);
            } catch (err) {
                console.error("Invalid JSON:", xhr.responseText);
                showToasted("Invalid JSON response from server.", 'error');
            }
        };
    
        xhr.onerror = function () {
            callback({
                success: false,
                message: "An error occurred during the request. Please check your internet connection."
            });
        };
    
        xhr.ontimeout = function () {
            callback({
                success: false,
                message: "Request timed out. Please check your internet connection and try again."
            });
        };
    
        xhr.timeout = 10000; // Optional: set timeout to 10 seconds
        xhr.send(data);
    }

    const emailInput = document.getElementById("email");
    const emailSubmit = document.getElementById("email-submit");
    const spinner = document.getElementById('spinner-icon');

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

        spinner.classList.remove('d-none');

        // AJAX request to send token to user's email
        sendAjaxRequest("send-token.php", "POST", "email=" + encodeURIComponent(email), function (response) {
            if (!response.success) {
                showToasted(response.message, 'error')
                emailInput.classList.add('error-input');
                spinner.classList.add('d-none');
            } else {
                // Create and store the reset email.
                sessionStorage.setItem('reset_email', email);
                showToasted(response.message, 'success')
                setTimeout(() => {
                    spinner.classList.add('d-none');
                    nextStep(); // Move to the next step
                }, 10); // 1-second delay
            }
        });
    });

    // VERIFYING TOKEN
    const tokenInput = document.getElementById("token");
    const tokenSubmit = document.getElementById("token-submit");

    tokenSubmit.addEventListener("click", function () {
        const token = tokenInput.value.trim();

        if (token === "") {
            showToasted('Token is required', 'error');
            return;
        }

        spinner.classList.remove('d-none');

        // AJAX request to verify token
        sendAjaxRequest("verify-token.php", "POST", "token=" + encodeURIComponent(token), function (response) {
            if (!response.success) {
                showToasted(response.message, 'error');
                spinner.classList.add('d-none');
                console.log(sessionStorage.getItem('reset_email'));
            } else {
                setTimeout(() => {
                    showToasted(response.message, 'success');
                    spinner.classList.add('d-none');
                    nextStep(); // Move to the password step
                }, 1000); // 1-second delay
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

        spinner.classList.remove('d-none');

        // AJAX request to reset password
        sendAjaxRequest("reset-password.php", "POST", "password=" + encodeURIComponent(password) + "&reset_email=" + encodeURIComponent(reset_email), function (response) {
            if (!response.success) {
                showToasted(response.message, 'error');
                spinner.classList.add('d-none');
            } else {
                setTimeout(() => {
                    showToasted(response.message, 'success');
                    window.location.href = "login.php?success=1"; // Redirect to login page
                }, 1000); // 1-second delay
            }
        });
    });
});
