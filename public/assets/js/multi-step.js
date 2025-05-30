document.addEventListener("DOMContentLoaded", function () {
    // Constants
    const TIMEOUT = 2000;
    const OTP_LENGTH = 6;

    // Step controls
    const steps = document.querySelectorAll(".form-step");
    const indicators = document.querySelectorAll(".pagination .page");
    let currentStep = 0;
    let completedSteps = new Set();

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
            completedSteps.add(currentStep);
            currentStep++;
            showStep(currentStep);
        }
    }

    function goToStep(step) {
        if (completedSteps.has(step - 1) || step === 0) {
            currentStep = step;
            showStep(currentStep);
        }
    }

    function sendAjaxRequest(url, method, data, callback) {
        if (!navigator.onLine) {
            return callback({ success: false, message: "You are offline. Please check your internet connection." });
        }

        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.timeout = 10000;

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    const response = JSON.parse(xhr.responseText);
                    callback(response);
                } catch {
                    callback({ success: false, message: "Invalid JSON response" });
                }
            }
        };

        xhr.onerror = () => callback({ success: false, message: "Request failed." });
        xhr.ontimeout = () => callback({ success: false, message: "Request timed out." });

        xhr.send(data);
    }

    // Generic handler wrapper to disable button
    function handleButtonClick(btn, handler) {
        btn.disabled = true;
        btn.style.cursor = "not-allowed";
        handler(() => {
            btn.disabled = false;
            btn.style.cursor = "pointer";
        });
    }

    // Step 0: Referral Code
    const referralInput = document.getElementById('referral-code');
    const referralContinueBtn = document.getElementById('referral-submit');

    referralContinueBtn.addEventListener("click", () => {
        handleButtonClick(referralContinueBtn, (done) => {
            const referralCode = referralInput.value.trim();
            sendAjaxRequest("validate-referral.php", "POST", `referral_code=${encodeURIComponent(referralCode)}`, (response) => {
                if (response.success) {
                    sessionStorage.setItem('referral_code', referralCode);
                    showToasted(response.message, 'success');
                    setTimeout(() => { goToStep(1); done(); }, TIMEOUT);
                } else {
                    showToasted(response.message, 'error');
                    done();
                }
            });
        });
    });

    // Step 1: Email
    const emailInput = document.getElementById('email');
    const emailContinueBtn = document.getElementById('email-submit');

    emailContinueBtn.addEventListener("click", () => {
        handleButtonClick(emailContinueBtn, (done) => {
            const email = emailInput.value.trim();

            if (!email || email.length <= 6 || email.includes("mailinator")) {
                showToasted("Enter a valid email address.", 'error');
                emailInput.classList.add("error-input");
                done();
                return;
            }

            sendAjaxRequest("validate-email.php", "POST", `email=${encodeURIComponent(email)}`, (response) => {
                if (!response.success) {
                    showToasted(response.message, 'error');
                    done();
                } else {
                    sessionStorage.setItem('email', email);
                    const registration_id = response.registration_id;
                    sessionStorage.setItem('registration_id', registration_id);

                    sendAjaxRequest("send-otp.php", "POST", `email=${encodeURIComponent(email)}&registration_id=${encodeURIComponent(registration_id)}`, (otpResponse) => {
                        if (otpResponse.success) {
                            setTimeout(() => { nextStep(); done(); }, TIMEOUT);
                        } else {
                            showToasted(otpResponse.message, 'error');
                            done();
                        }
                    });
                }
            });
        });
    });

    // Step 2: OTP
    const otpInputs = document.querySelectorAll(".otp-input");
    const verifyOtpBtn = document.getElementById("verify-otp-btn");

    verifyOtpBtn.addEventListener("click", () => {
        handleButtonClick(verifyOtpBtn, (done) => {
            let otp = "";
            otpInputs.forEach(input => otp += input.value.trim());

            if (otp.length !== OTP_LENGTH || isNaN(otp)) {
                showToasted("Enter a valid 6-digit OTP.", 'error');
                done();
                return;
            }

            const email = sessionStorage.getItem('email');
            const registration_id = sessionStorage.getItem('registration_id');

            sendAjaxRequest("verify-otp.php", "POST", `email=${encodeURIComponent(email)}&otp=${encodeURIComponent(otp)}&registration_id=${encodeURIComponent(registration_id)}`, (response) => {
                if (response.success) {
                    document.getElementById('email-msg').textContent = `Enter the 6-digit code sent to ${email}`;
                    setTimeout(() => { nextStep(); done(); }, TIMEOUT);
                } else {
                    showToasted(response.message, 'error');
                    done();
                }
            });
        });
    });

    // Step 3: Phone
    const phoneInput = document.getElementById("phone");
    const phoneSubmit = document.getElementById("phone-submit");

    phoneSubmit.addEventListener("click", () => {
        handleButtonClick(phoneSubmit, (done) => {
            let phone = phoneInput.value.trim().replace(/^0/, "");

            if (/^(\+234|234)/.test(phoneInput.value)) {
                showToasted("Remove the country code.", 'error');
                done(); return;
            }

            if (!/^\d{10}$/.test(phone)) {
                showToasted("Enter a valid Nigerian phone number.", 'error');
                done(); return;
            }

            const registration_id = sessionStorage.getItem('registration_id');

            sendAjaxRequest("validate-phone.php", "POST", `phone=${encodeURIComponent(phone)}&registration_id=${encodeURIComponent(registration_id)}`, (response) => {
                if (response.success) {
                    setTimeout(() => { nextStep(); done(); }, TIMEOUT);
                } else {
                    showToasted(response.message, 'error');
                    done();
                }
            });
        });
    });

    // Step 4: Names
    const firstNameInput = document.getElementById("first_name");
    const lastNameInput = document.getElementById("last_name");
    const namesSubmit = document.getElementById("names-submit");

    namesSubmit.addEventListener("click", () => {
        handleButtonClick(namesSubmit, (done) => {
            const firstName = firstNameInput.value.trim();
            const lastName = lastNameInput.value.trim();
            const namePattern = /^[A-Za-z\s'-]+$/;

            if (!firstName || !lastName || firstName.length <= 1 || lastName.length <= 1 ||
                !namePattern.test(firstName) || !namePattern.test(lastName)) {
                showToasted("Enter valid first and last names.", 'error');
                done(); return;
            }

            const registration_id = sessionStorage.getItem('registration_id');
            sendAjaxRequest("validate-names.php", "POST", `first_name=${encodeURIComponent(firstName)}&last_name=${encodeURIComponent(lastName)}&registration_id=${encodeURIComponent(registration_id)}`, (response) => {
                if (response.success) {
                    setTimeout(() => { nextStep(); done(); }, TIMEOUT);
                } else {
                    showToasted(response.message, 'error');
                    done();
                }
            });
        });
    });

    // Step 5: Password
    const passwordInput = document.getElementById("password");
    const confirmPasswordInput = document.getElementById("confirm-password");
    const passwordSubmit = document.getElementById("password-submit");

    passwordSubmit.addEventListener("click", () => {
        handleButtonClick(passwordSubmit, (done) => {
            const password = passwordInput.value.trim();
            const confirmPassword = confirmPasswordInput.value.trim();

            if (password.length < 8 || password !== confirmPassword) {
                showToasted("Passwords must match and be at least 8 characters.", 'error');
                done(); return;
            }

            const registration_id = sessionStorage.getItem('registration_id');
            sendAjaxRequest("validate-password.php", "POST", `password=${encodeURIComponent(password)}&registration_id=${encodeURIComponent(registration_id)}`, (response) => {
                if (response.success) {
                    setTimeout(() => {
                        window.location.href = "login.php?success=1";
                    }, TIMEOUT);
                } else {
                    showToasted(JSON.stringify(response.api_response, null, 2), 'error');
                    done();
                }
            });
        });
    });

    showStep(currentStep);
});
