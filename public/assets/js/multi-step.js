document.addEventListener("DOMContentLoaded", function () {
    const timeout = 1000;
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

    // Referral Code
    const referralInput = document.getElementById('referral-code');
    const referralContinueBtn = document.getElementById('referral-submit');

    referralContinueBtn.addEventListener("click", function () {
        const referralCode = referralInput.value.trim();

        referralContinueBtn.style.cursor = 'not-allowed';

        // Validate referral code
        sendAjaxRequest("validate-referral.php", "POST", "referral_code=" + encodeURIComponent(referralCode), function (response) {
            if (response.success) {
                sessionStorage.setItem('referral_code', referralCode);
                showToasted(response.message, 'success');
                setTimeout(() => {
                    currentStep = 1; // force going to email step
                    showStep(currentStep);
                }, 3000);
            } else {
                showToasted(response.message, 'error');
            }
            
        });
    });

    // Email Verification
    const emailInput = document.getElementById('email');
    const emailContinueBtn = document.getElementById('email-submit');

    emailContinueBtn.addEventListener("click", function () {
        const email = emailInput.value.trim();

        if (email === "") {
            showToasted("Email address is required.", 'error');
            emailInput.classList.add("error-input");
            return;
        } else if (email.length <= 6) {
            showToasted("Please enter a valid email address", 'error');
            emailInput.classList.add("error-input");
            return;
        } else if (email.includes("mailinator")) {
            showToasted("Email format not supported.", 'error');
            emailInput.classList.add("error-input");
            return;
        }

        emailContinueBtn.style.cursor = 'not-allowed';

        // Validate email and send OTP
        sendAjaxRequest("validate-email.php", "POST", "email=" + encodeURIComponent(email), function (response) {
            if (!response.success) {
                showToasted(response.message, 'error');
                emailContinueBtn.style.cursor = 'pointer';
            } else {
                // Save the email and registration_id to sessionStorage for use in the OTP page
                sessionStorage.setItem('email', email);
                const registration_id = response.registration_id;
                sessionStorage.setItem('registration_id', registration_id);

                sendAjaxRequest("send-otp.php", "POST", "email=" + encodeURIComponent(email) + "&registration_id=" + encodeURIComponent(registration_id), function (otpResponse) {
                    if (otpResponse.success) {
                        emailContinueBtn.classList.remove('primary-btn');
                        emailContinueBtn.classList.add('btn-secondary');

                        console.log(otpResponse.otpCode);

                        setTimeout(() => {
                            nextStep();
                        }, timeout); 
                    } else {
                        setTimeout(() => {
                            showToasted(otpResponse.message, 'error');
                            emailContinueBtn.style.cursor = 'pointer';
                        }, timeout);
                    }
                });
            }
        });
    });

    // OTP Verification
    const otpInputs = document.querySelectorAll(".otp-input");
    const verifyOtpBtn = document.getElementById("verify-otp-btn");

    verifyOtpBtn.addEventListener("click", function () {
        let otp = "";
        otpInputs.forEach(input => otp += input.value.trim());

        if (otp.length !== 6) {showToasted("Enter a valid 6-digit OTP.", 'error'); return;}
        if(isNaN(otp)) {showToasted('Enter a valid OTP.', 'error'); return;}

        const email = sessionStorage.getItem('email');
        const registration_id = sessionStorage.getItem('registration_id');

        verifyOtpBtn.style.cursor = 'not-allowed';

        // Verify OTP
        sendAjaxRequest("verify-otp.php", "POST", "email=" + encodeURIComponent(email) + "&otp=" + encodeURIComponent(otp) + "&registration_id=" + encodeURIComponent(registration_id), function (response) {
            if (response.success) {
                verifyOtpBtn.style.cursor = 'pointer';
                document.getElementById('email-msg').textContent = 'Enter the 6-digit code sent to ' + email;
                setTimeout(() => {
                    nextStep();
                }, timeout); 
            } else {
                setTimeout(() => {
                    showToasted(response.message, 'error');
                    verifyOtpBtn.style.cursor = 'pointer'; 
                }, timeout);
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
    const phoneSubmit = document.getElementById("phone-submit");

    phoneSubmit.addEventListener("click", function () {
        let phone = phoneInput.value.trim();
        phoneInput.classList.remove("error");

        // Check if country code is present
        if (/^(\+234|234)/.test(phone)) {
            showToasted("Remove the country code.", 'error');
            return;
        }

        // Check for leading zero
        if (/^0/.test(phone)) {
            phone = phone.replace(/^0/, '');
        }

        // Nigerian phone number validation (should be 10 digits after sanitization)
        const phonePattern = /^\d{10}$/;

        if (!phonePattern.test(phone)) {
            showToasted("Enter a valid Nigerian phone number.", 'error');
            return;
        }

        phoneSubmit.style.cursor = 'not-allowed';

        const registration_id = sessionStorage.getItem('registration_id');

        // Validate phone number and update users table
        sendAjaxRequest("validate-phone.php", "POST", "phone=" + encodeURIComponent(phone) + "&registration_id=" + encodeURIComponent(registration_id), function (response) {
            if (!response.success) {
                showToasted(response.message, 'error');
                phoneSubmit.style.cursor = 'pointer';
            } else {
                phoneSubmit.classList.remove('primary-btn');
                phoneSubmit.classList.add('btn-secondary');

                setTimeout(() => {
                    nextStep();
                }, timeout); 
            }
        });
    });

    const firstNameInput = document.getElementById("first_name");
    const lastNameInput = document.getElementById("last_name");
    const namesSubmit = document.getElementById("names-submit");

    namesSubmit.addEventListener("click", function () {
        const firstName = firstNameInput.value.trim();
        const lastName = lastNameInput.value.trim();
        firstNameInput.classList.remove("error");
        lastNameInput.classList.remove("error");

        // Validate first name and last name
        if (!firstName || !lastName) {
            showToasted("Both first name and last name are required.", 'error');
            return;
        }

        const namePattern = /^[A-Za-z\s'-]+$/;

        if (!namePattern.test(firstName)) {
            showToasted("Enter a valid first name.", 'error');
            return;
        }

        if (!namePattern.test(lastName)) {
            showToasted("Enter a valid last name.", 'error');
            return;
        }
        
        if(firstName.length <= 1){
            showToasted("First name length is too short.", 'error');
            return;
        }
        if(lastName.length <= 1){
            showToasted("Last name length is too short.", 'error');
            return;
        }

        // Update user in the database with first name and last name
        const registration_id = sessionStorage.getItem('registration_id');

        namesSubmit.style.cursor = 'not-allowed';

        // AJAX request to update names
        sendAjaxRequest("validate-names.php", "POST", "first_name=" + encodeURIComponent(firstName) + "&last_name=" + encodeURIComponent(lastName) + "&registration_id=" + encodeURIComponent(registration_id), function (response) {
            if (!response.success) {
                showToasted(response.message, 'error');
                namesSubmit.style.cursor = 'pointer';
            } else {
                namesSubmit.classList.remove('primary-btn');
                namesSubmit.classList.add('btn-secondary');

                setTimeout(() => {
                    nextStep();
                }, timeout);
            }
        });
    });

    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm-password");
    const passwordSubmit = document.getElementById("password-submit");

    passwordSubmit.addEventListener("click", function () {
        const password = passwordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();
        passwordInput.classList.remove("error");
        confirmPasswordInput.classList.remove("error");

        if (password === "" || confirmPassword === "") {
            showToasted("Both password and confirm password are required.", 'error');
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

        const registration_id = sessionStorage.getItem('registration_id');
        passwordSubmit.style.cursor = 'not-allowed';

        // Final step: Update password and trigger virtual account creation
        sendAjaxRequest("validate-password.php", "POST", "password=" + encodeURIComponent(password) + "&registration_id=" + encodeURIComponent(registration_id), 
            function (response) {
                if (!response.success) {
                    showToasted(JSON.stringify(response.api_response, null, 2), 'error');
                    passwordSubmit.style.cursor = 'pointer';
                } else {

                    setTimeout(() => {
                        window.location.href = "login.php?success=1";
                    }, timeout);
                }
            }
        );

    });

});
