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
    }

    function sendAjaxRequest(url, method, data, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    callback(response);
                } catch (error) {
                    callback({ success: false, message: "Invalid JSON response" });
                }
            }
        };
        xhr.send(data);
    }

    // Spinners
    const spinner = document.getElementById('spinner-icon');
    const emailInput = document.getElementById('email');
    const emailError = document.getElementById('email-error');
    const emailContinueBtn = document.getElementById('email-submit');

    emailContinueBtn.addEventListener("click", function () {
        const email = emailInput.value.trim();
        emailError.textContent = ""; // Clear previous errors
        
        if (email === "") {
            showError(emailError, "Email address is required.");
            emailInput.classList.add("error-input");
            return;
        } else if (email.length <= 6) {
            showError(emailError, "Please enter a valid email address");
            emailInput.classList.add("error-input");
            return;
        } else if (email.includes("mailinator")) {
            showError(emailError, "Email format not supported.");
            emailInput.classList.add("error-input");
            return;
        }

        spinner.classList.remove('d-none');
        emailContinueBtn.style.cursor = 'not-allowed';

        // Validate email and send OTP
        sendAjaxRequest("validate-email.php", "POST", "email=" + encodeURIComponent(email), function (response) {
            if (!response.success) {
                showError(emailError, response.message);
                spinner.classList.add('d-none');
                emailContinueBtn.style.cursor = 'pointer';
            } else {
                // Save the email and registration_id to sessionStorage for use in the OTP page
                sessionStorage.setItem('email', email);
                const registration_id = response.registration_id;
                sessionStorage.setItem('registration_id', registration_id);

                console.log("Email stored:", email);
                console.log("Registration ID stored:", registration_id);

                sendAjaxRequest("send-otp.php", "POST", "email=" + encodeURIComponent(email) + "&registration_id=" + encodeURIComponent(registration_id), function (otpResponse) {
                    if (otpResponse.success) {
                        emailContinueBtn.classList.remove('primary-btn');
                        emailContinueBtn.classList.add('btn-secondary');
                        spinner.classList.add('d-none');

                        setTimeout(() => {
                            nextStep();
                        }, 500); // Delay before moving to the next step
                    } else {
                        showError(emailError, otpResponse.message);
                        spinner.classList.add('d-none');
                        emailContinueBtn.style.cursor = 'pointer';
                    }
                });
            }
        });
    });

    // OTP Verification
    const otpInputs = document.querySelectorAll(".otp-input");
    const verifyOtpBtn = document.getElementById("verify-otp-btn");
    const otpError = document.getElementById("otp-error");

    verifyOtpBtn.addEventListener("click", function () {
        let otp = "";
        otpInputs.forEach(input => otp += input.value.trim());

        if (otp.length !== 6 || isNaN(otp)) {
            showError(otpError, "Enter a valid 6-digit OTP.");
            return;
        }

        const email = sessionStorage.getItem('email');
        const registration_id = sessionStorage.getItem('registration_id');

        console.log("Email retrieved:", email);
        console.log("Registration ID retrieved:", registration_id);

        spinner.classList.remove('d-none');
        verifyOtpBtn.style.cursor = 'not-allowed';

        // Verify OTP
        sendAjaxRequest("verify-otp.php", "POST", "email=" + encodeURIComponent(email) + "&otp=" + encodeURIComponent(otp) + "&registration_id=" + encodeURIComponent(registration_id), function (response) {
            if (response.success) {
                verifyOtpBtn.style.cursor = 'pointer';
                spinner.classList.add('d-none');
                setTimeout(() => {
                    nextStep();
                }, 500); // Delay before moving to the next step
            } else {
                showError(otpError, response.message);
                spinner.classList.add('d-none');
                verifyOtpBtn.style.cursor = 'pointer';
            }
        });
    });

    // OTP INPUT HANDLING
    otpInputs.forEach((input, index) => {
        input.addEventListener("input", (e) => {
            if (e.target.value.length === 1 && index < otpInputs.length - 1) {
                otpInputs[index + 1].focus();
            }
        });

        input.addEventListener("keydown", (e) => {
            if (e.key === "Backspace" && !input.value && index > 0) {
                otpInputs[index - 1].focus();
            }
        });

        input.addEventListener("paste", (e) => {
            e.preventDefault();
            let pastedData = e.clipboardData.getData("text").replace(/\D/g, '').slice(0, otpInputs.length);
            otpInputs.forEach((inp, i) => inp.value = pastedData[i] || "");
        });
    });

    // Phone Number Verification
    const phoneInput = document.getElementById("phone");
    const phoneError = document.getElementById("phone-error");
    const phoneSubmit = document.getElementById("phone-submit");

    phoneSubmit.addEventListener("click", function () {
        let phone = phoneInput.value.trim();
        phoneError.textContent = ""; // Clear previous errors
        phoneInput.classList.remove("error");

        // Check if country code is present
        if (/^(\+234|234)/.test(phone)) {
            showError(phoneError, "Remove the country code.");
            return;
        }

        // Check for leading zero
        if (/^0/.test(phone)) {
            phone = phone.replace(/^0/, '');
        }

        // Nigerian phone number validation (should be 10 digits after sanitization)
        const phonePattern = /^\d{10}$/;

        if (!phonePattern.test(phone)) {
            showError(phoneError, "Enter a valid Nigerian phone number.");
            return;
        }

        spinner.classList.remove('d-none');
        phoneSubmit.style.cursor = 'not-allowed';

        const registration_id = sessionStorage.getItem('registration_id');

        // Validate phone number and update users table
        sendAjaxRequest("validate-phone.php", "POST", "phone=" + encodeURIComponent(phone) + "&registration_id=" + encodeURIComponent(registration_id), function (response) {
            if (!response.success) {
                showError(phoneError, response.message);
                spinner.classList.add('d-none');
                phoneSubmit.style.cursor = 'pointer';
            } else {
                phoneSubmit.classList.remove('primary-btn');
                phoneSubmit.classList.add('btn-secondary');
                spinner.classList.add('d-none');

                setTimeout(() => {
                    nextStep();
                }, 500); // Delay before moving to the next step
            }
        });
    });

    const firstNameInput = document.getElementById("first_name");
    const lastNameInput = document.getElementById("last_name");
    const namesError = document.getElementById("names-error");
    const namesSubmit = document.getElementById("names-submit");

    namesSubmit.addEventListener("click", function () {
        const firstName = firstNameInput.value.trim();
        const lastName = lastNameInput.value.trim();
        namesError.textContent = ""; // Clear previous errors
        firstNameInput.classList.remove("error");
        lastNameInput.classList.remove("error");

        // Validate first name and last name
        if (!firstName || !lastName) {
            showError(namesError, "Both first name and last name are required.");
            return;
        }

        const namePattern = /^[A-Za-z\s'-]+$/;

        if (!namePattern.test(firstName)) {
            showError(namesError, "Enter a valid first name.");
            return;
        }

        if (!namePattern.test(lastName)) {
            showError(namesError, "Enter a valid last name.");
            return;
        }

        // Update user in the database with first name and last name
        const registration_id = sessionStorage.getItem('registration_id');

        spinner.classList.remove('d-none');
        namesSubmit.style.cursor = 'not-allowed';

        // AJAX request to update names
        sendAjaxRequest("validate-names.php", "POST", "first_name=" + encodeURIComponent(firstName) + "&last_name=" + encodeURIComponent(lastName) + "&registration_id=" + encodeURIComponent(registration_id), function (response) {
            if (!response.success) {
                showError(namesError, response.message);
                spinner.classList.add('d-none');
                namesSubmit.style.cursor = 'pointer';
            } else {
                namesSubmit.classList.remove('primary-btn');
                namesSubmit.classList.add('btn-secondary');
                spinner.classList.add('d-none');

                setTimeout(() => {
                    nextStep();
                }, 500); 
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
        passwordError.textContent = "";
        passwordInput.classList.remove("error");
        confirmPasswordInput.classList.remove("error");

        if (password === "" || confirmPassword === "") {
            showError(passwordError, "Both password and confirm password are required.");
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

        const registration_id = sessionStorage.getItem('registration_id');
        spinner.classList.remove('d-none');
        passwordSubmit.style.cursor = 'not-allowed';

        // Final step: Update password and trigger virtual account creation
        sendAjaxRequest("validate-password.php", "POST", "password=" + encodeURIComponent(password) + "&registration_id=" + encodeURIComponent(registration_id), function (response) {
            if (!response.success) {
                showError(passwordError, response.message);
                spinner.classList.add('d-none');
                passwordSubmit.style.cursor = 'pointer';
            } else {
                // Request to create virtual account after successful registration
                sendAjaxRequest("create_virtual_account.php", "POST", "registration_id=" + encodeURIComponent(registration_id), function (vaResponse) {
                    spinner.classList.add('d-none');
                    if (vaResponse.success) {
                        console.log("Registration completed! Virtual account created.");
                        window.location.href = "dashboard.php"; // Redirect after completion
                    } else {
                        showError(passwordError, "Failed to create virtual account. Please try again.");
                        passwordSubmit.style.cursor = 'pointer';
                    }
                });
            }
        });
    });



});
