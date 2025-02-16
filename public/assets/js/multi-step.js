document.addEventListener("DOMContentLoaded", function () {
    const emailInput = document.getElementById("email");
    const emailError = document.getElementById("email-error");
    const emailSubmit = document.getElementById("email-submit");

    emailSubmit.addEventListener("click", function () {
        const email = emailInput.value.trim();

        // Email format validation (Basic check)
        if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
            emailError.textContent = "Please enter a valid email address.";
            return;
        }

        // Send AJAX request to check email existence
        const xhr = new XMLHttpRequest();
        xhr.open("POST", "validate-email.php", true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4 && xhr.status === 200) {
                const response = JSON.parse(xhr.responseText);
                if (response.success) {
                    emailError.textContent = "";
                    alert("Email verified! Proceeding...");
                    // Move to next step
                } else {
                    emailError.textContent = response.message;
                }
            }
        };

        xhr.send("email=" + encodeURIComponent(email));
    });
});


document.addEventListener("DOMContentLoaded", function () {
    const otpInputs = document.querySelectorAll(".otp-input");

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
