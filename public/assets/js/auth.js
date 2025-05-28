document.addEventListener('DOMContentLoaded', function () {

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

    function showError(element, message) {
        element.textContent = message;
    }

    const userInput = document.getElementById('user');
    const passwordInput = document.getElementById('password');
    const spinner = document.getElementById('spinner-icon');
    const errorLabel = document.getElementById('email-error');
    const login = document.getElementById('login');

    login.addEventListener('click', function () {
        const user = userInput.value.trim();
        const password = passwordInput.value.trim();

        if (user == "" && password == "") {
            showError(errorLabel, "Phone number and password is required.");
            userInput.classList.add("error-input");
            passwordInput.classList.add("error-input");
            return;
        }
        else if (user == "") {
            showError(errorLabel, "Phone number or email is required.");
            userInput.classList.add("error-input");
            return;
        }
        else if (password == "") {
            showError(errorLabel, "Password is required.");
            passwordInput.classList.add("error-input");
            return;
        }

        // Spin
        spinner.classList.remove('d-none')
        login.style.cursor = 'not-allowed';

        sendAjaxRequest("validate-user.php", "POST", "user=" + encodeURIComponent(user) + "&password=" + encodeURIComponent(password), function (response) {
            if (!response.success) {
                showError(errorLabel, response.message);
                spinner.classList.add('d-none');
            } else {
                // Save the email and registration_id to sessionStorage for use in the OTP page
                window.location.href = 'dashboard.php';
            }
        });
    });

});