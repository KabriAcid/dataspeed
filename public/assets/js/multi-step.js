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

    // EMAIL VALIDATION
    const emailInput = document.querySelector("input[name='email']");
    const emailError = document.querySelector(".error-label");
    const emailContinueBtn = document.querySelector(".form-step:first-child .btn");

    if (emailContinueBtn) {
        emailContinueBtn.addEventListener("click", function () {
            const email = emailInput.value.trim();
            emailError.textContent = ""; // Clear previous errors
            emailInput.classList.remove("error");

            if (email.length < 6) {
                showError("Please enter a valid email address.");
                return;
            } else if (email.includes("xxxxxxxxxx")) {
                showError("Email format not supported.");
                return;
            }

            // Step 1: Validate email before sending OTP
            sendAjaxRequest("validate-email.php?email=" + encodeURIComponent(email), "GET", null, function (response) {
                if (!response.success) {
                    showError(response.message);
                } else {
                    // Step 2: If validation passes, send OTP
                    sendAjaxRequest("send-otp.php", "POST", "email=" + encodeURIComponent(email), function (otpResponse) {
                        if (otpResponse.success) {
                            nextStep(); // Move to the next step after OTP is sent
                        } else {
                            showError(otpResponse.message);
                        }
                    });
                }
            });
        });
    } else {
        console.error("Email continue button not found!");
    }

    // Helper function to show error messages
    function showError(message) {
        emailError.textContent = message;
        emailInput.classList.add("error");
    }

    // Generic AJAX Request Function
    function sendAjaxRequest(url, method, data, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                try {
                    console.log(`Response from ${url}:`, xhr.responseText); // Debugging
                    const response = JSON.parse(xhr.responseText);
                    callback(response);
                } catch (error) {
                    console.error("Invalid JSON response from " + url, error);
                    console.error("Raw Response:", xhr.responseText);
                    showError("Unexpected error. Try again later.");
                }
            }
        };

        xhr.send(data);
    }




    // OTP VERIFICATION
    const otpInputs = document.querySelectorAll(".otp-input");
    const verifyOtpBtn = document.getElementById("verify-otp-btn");
    const otpError = document.getElementById("otp-error");

    if (verifyOtpBtn) {
        verifyOtpBtn.addEventListener("click", function () {
            let otp = "";
            otpInputs.forEach(input => otp += input.value.trim());

            if (otp.length !== 6 || isNaN(otp)) {
                otpError.textContent = "Enter a valid 6-digit OTP.";
                return;
            }

            // AJAX request to verify OTP
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "verify-otp.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    try {
                        const response = JSON.parse(xhr.responseText.trim());
                        if (response.success) {
                            otpError.textContent = "";
                            nextStep(); // Move to the next step after OTP verification
                        } else {
                            otpError.textContent = response.message;
                        }
                    } catch (error) {
                        console.error("Invalid JSON response:", xhr.responseText);
                        otpError.textContent = "An error occurred. Please try again.";
                    }
                }
            };

            xhr.send("otp=" + encodeURIComponent(otp));
        });
    } else {
        console.error("Verify OTP button not found!");
    }

    // PHONE NUMBER VERIFICATION
    
    const phoneInput = document.getElementById("phone");
    const phoneError = document.getElementById("phone-error");
    const phoneSubmit = document.getElementById("phone-submit");

    if (phoneSubmit) {
        phoneSubmit.addEventListener("click", function () {
            const phone = phoneInput.value.trim();

            // Nigerian phone number validation (must start with a valid prefix)
            const phonePattern = /^(070|080|081|090|091|701|702|703|704|705|706|707|708|709|802|803|804|805|806|807|808|809|810|811|812|813|814|815|816|817|818|819|909|908|901|902|903|904|905|906|907)\d{7}$/;

            if (!phonePattern.test(phone)) {
                phoneError.textContent = "Enter a valid Nigerian phone number.";
                return;
            }

            // AJAX request to verify phone number
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "validate-phone.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4 && xhr.status === 200) {
                    const response = JSON.parse(xhr.responseText);
                    if (response.success) {
                        phoneError.textContent = "";
                        nextStep(); // Move to next step
                    } else {
                        phoneError.textContent = response.message;
                    }
                }
            };

            xhr.send("phone=" + encodeURIComponent(phone));
        });
    } else {
        console.error("Phone submit button not found!");
    }

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
            let pastedData = e.clipboardData.getData("text").split("");
            otpInputs.forEach((inp, i) => {
                inp.value = pastedData[i] || "";
            });
        });
    });
});
