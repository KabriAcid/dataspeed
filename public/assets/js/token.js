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

    function showError(element, message) {
        element.textContent = message;
        element.classList.add("error");
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
                alert("Invalid JSON response from server.");
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
    const emailError = document.getElementById("email-error");
    const spinner = document.getElementById('spinner-icon');

    emailSubmit.addEventListener("click", function () {
        const email = emailInput.value.trim();
        emailError.textContent = ""; // Clear previous errors
        emailInput.classList.remove("error");

        if (email === "") {
            showError(emailError, "Email address is required.");
            return;
        }
        
        if (email.length <= 7) {
            showError(emailError, "Invalid email address.");
            return;
        }

        spinner.classList.remove('d-none');

        // AJAX request to send token to user's email
        sendAjaxRequest("send-token.php", "POST", "email=" + encodeURIComponent(email), function (response) {
            if (!response.success) {
                showError(emailError, response.message);
                emailInput.classList.add('error-input');
                spinner.classList.add('d-none');
            } else {
                // Create and store the reset email.
                sessionStorage.setItem('reset_email', email);
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
    const tokenError = document.getElementById("token-error");

    tokenSubmit.addEventListener("click", function () {
        const token = tokenInput.value.trim();
        tokenError.textContent = ""; // Clear previous errors
        tokenInput.classList.remove("error");

        if (token === "") {
            showError(tokenError, "Token is required.");
            return;
        }

        spinner.classList.remove('d-none');

        // AJAX request to verify token
        sendAjaxRequest("verify-token.php", "POST", "token=" + encodeURIComponent(token), function (response) {
            if (!response.success) {
                showError(tokenError, response.message);
                spinner.classList.add('d-none');
                console.log(sessionStorage.getItem('reset_email'));
            } else {
                setTimeout(() => {
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
        passwordError.textContent = ""; // Clear previous errors
        passwordInput.classList.remove("error");
        confirmPasswordInput.classList.remove("error");

        const reset_email = sessionStorage.getItem('reset_email');

        if (password === "" || confirmPassword === "") {
            showError(passwordError, "Both fields are required.");
            return;
        }

        if (password.length < 8) {
            showError(passwordError, "Password must be at least 8 characters long.");
            return;
        }

        if (password !== confirmPassword) {
            showError(passwordError, "Passwords do not match.");
            return;
        }

        spinner.classList.remove('d-none');

        // AJAX request to reset password
        sendAjaxRequest("reset-password.php", "POST", "password=" + encodeURIComponent(password) + "&reset_email=" + encodeURIComponent(reset_email), function (response) {
            if (!response.success) {
                showError(passwordError, response.message);
                spinner.classList.add('d-none');
            } else {
                setTimeout(() => {
                    spinner.classList.add('d-none');
                    window.location.href = "login.php"; // Redirect to login page
                }, 1000); // 1-second delay
            }
        });
    });
});
