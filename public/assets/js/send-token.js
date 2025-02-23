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

    function showError(message) {
        emailError.textContent = message;
        emailInput.classList.add("error");
    }

    function sendAjaxRequest(url, method, data, callback) {
        const xhr = new XMLHttpRequest();
        xhr.open(method, url, true);
        xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function () {
            if (xhr.readyState === 4) {
                const response = JSON.parse(xhr.responseText);
                callback(response);
                console.log(response)
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
        if (emailContinueBtn.disabled) return; // Prevent multiple clicks

        const email = emailInput.value.trim();
        emailError.textContent = ""; // Clear previous errors
        emailInput.classList.remove("error");

        if (email === "") {
            showError("Email address is required.");
            return;
        } else if (email.length <= 6) {
            showError("Please enter a valid email address");
            return;
        } else if (email.includes("xxxxx")) {
            showError("Email format not supported.");
            return;
        }


        sendAjaxRequest("send-token.php", "POST", "email=" + encodeURIComponent(email), function (response) {
            if (!response.success) {
                showError(response.message);
            } else {
                setTimeout(() => {
                    nextStep();
                }, 500);
                // emailError.textContent = response.message;
            }
        });
    });

});
