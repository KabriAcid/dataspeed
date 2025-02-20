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

    // Spinners
    const spinner = document.getElementById('spinner-icon');

    emailContinueBtn.addEventListener("click", function () {
        if (emailContinueBtn.disabled) return; // Prevent multiple clicks

        const email = emailInput.value.trim();
        emailError.textContent = ""; // Clear previous errors
        emailInput.classList.remove("error");

        if (email == "") {
            showError("Email address is required.");
            return;
        } else if (email.length <= 6) {
            showError("Please enter a valid email address");
            return;
        } else if (email.includes("xxxxx")) {
            showError("Email format not supported.");
            return;
        }

        emailContinueBtn.disabled = true; // Disable button

        sendAjaxRequest("validate-email.php", "POST", "email=" + encodeURIComponent(email), function (response) {
            if (!response.success) {
                showError(response.message);
                emailContinueBtn.disabled = false; // Re-enable button on error
            } else {
                // Step 2: If validation passes, send OTP
                sendAjaxRequest("send-otp.php", "POST", "email=" + encodeURIComponent(email), function (otpResponse) {
                    if (otpResponse.success) {
                        emailContinueBtn.classList.remove('btn-primary');
                        emailContinueBtn.classList.add('btn-secondary');
                        spinner.classList.remove('d-none');

                        setTimeout(() => {
                            nextStep();
                        }, 500);
                    } else {
                        showError(otpResponse.message);
                        emailContinueBtn.disabled = false; // Re-enable button if OTP fails
                    }
                });
            }
        });
    });




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
                            nextStep();
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


    // PHONE NUMBER VERIFICATION
    const phoneInput = document.getElementById("phone");
    const phoneError = document.getElementById("phone-error");
    const phoneSubmit = document.getElementById("phone-submit");

    if (phoneSubmit) {
        phoneSubmit.addEventListener("click", function () {
            let phone = phoneInput.value.trim();

            // Automatically remove leading zero if present
            if (/^0[7-9]\d{9}$/.test(phone)) {
                phone = phone.substring(1); // Remove only ONE leading zero
            }


            // Nigerian phone number validation (after stripping 0)
            const phonePattern = /^(234)?(70\d|80\d|81\d|90\d|91\d|701|702|703|704|705|706|707|708|709|802|803|804|805|806|807|808|809|810|811|812|813|814|815|816|817|818|819|908|909|901|902|903|904|905|906|907|912|913|914|915|916|917|918|919)\d{6}$/;


            if (!phonePattern.test(phone)) {
                phoneError.textContent = "Enter a valid Nigerian phone number.";
                return;
            }

            // Disable button & show spinner during request
            spinner.classList.remove("d-none");

            // AJAX request to verify phone number
            const xhr = new XMLHttpRequest();
            xhr.open("POST", "validate-phone.php", true);
            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

            xhr.onreadystatechange = function () {
                if (xhr.readyState === 4) {
                    phoneSubmit.disabled = false; // Re-enable button
                    spinner.classList.add("d-none"); // Hide spinner

                    try {
                        const response = JSON.parse(xhr.responseText.trim());
                        if (response.success) {
                            phoneError.textContent = "";
                            nextStep(); // Move to next step
                        } else {
                            phoneError.textContent = response.message;
                        }
                    } catch (error) {
                        phoneError.textContent = "An error occurred. Please try again.";
                        console.error("Invalid JSON response:", xhr.responseText);
                    }
                }
            };

            xhr.send("phone=" + encodeURIComponent(phone));
        });

        // Clear error message when user types
        phoneInput.addEventListener("input", function () {
            phoneError.textContent = "";
        });
    } else {
        console.error("Phone submit button not found!");
    }

    // NAME VERIFICATION
    const firstNameInput = document.getElementById("first_name");
    const lastNameInput = document.getElementById("last_name");
    const nameError = document.getElementById("names-error");
    const nameSubmitBtn = document.getElementById("names-submit");

    if (nameSubmitBtn) {
        nameSubmitBtn.addEventListener("click", function () {
            const firstName = firstNameInput.value.trim();
            const lastName = lastNameInput.value.trim();
            nameError.textContent = ""; // Clear previous errors

            // Validate names (at least 2 letters, no numbers or symbols)
            const namePattern = /^[A-Za-z]{3,}(?:\s[A-Za-z]{3,})?$/;

            if (!namePattern.test(firstName) || !namePattern.test(lastName)) {
                nameError.textContent = "Enter a valid first and last name.";
                return;
            }

            // Send AJAX request to update names
            sendAjaxRequest("validate-names.php", "POST",`first_name=${encodeURIComponent(firstName)}&last_name=${encodeURIComponent(lastName)}`,
                function (response) {
                    if (response.success) {
                        nameError.textContent = "";
                        nextStep(); // Proceed to the next step
                    } else {
                        nameError.textContent = response.message;
                    }
                }
            );
        });
    } else {
        console.error("Name submit button not found!");
    }



});
