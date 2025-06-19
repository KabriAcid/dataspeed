const TIMEOUT = 2000;
document.addEventListener("DOMContentLoaded", function () {
  // Generic handler wrapper to disable button
  function handleButtonClick(btn, handler) {
    btn.disabled = true;
    btn.style.cursor = "not-allowed";
    handler(() => {
      btn.disabled = false;
      btn.style.cursor = "pointer";
    });
  }

  const steps = document.querySelectorAll(".form-step");
  const indicators = document.querySelectorAll(".pagination .page");
  let currentStep = parseInt(sessionStorage.getItem("currentStep")) || 0;

  function showStep(step) {
    steps.forEach((el, index) => {
      el.classList.toggle("d-none", index !== step);
    });

    indicators.forEach((el, index) => {
      el.classList.toggle("active", index === step);
    });

    if (step === 2) {
      const tokenEmail = document.getElementById("token-email");
      const email = sessionStorage.getItem("reset_email") || "";
      if (tokenEmail && email) {
        tokenEmail.textContent = email;
      }
    }

    sessionStorage.setItem("currentStep", step);
  }

  const passwordInput = document.getElementById("password");
  const passwordSubmit = document.getElementById("password-submit");

  passwordSubmit.addEventListener("click", function () {
    handleButtonClick(passwordSubmit, done => {
      const password = passwordInput.value.trim();

      if (password === "") {
        showToasted("Password field is required.", "error");
        return done();
      }

      if (password.length < 8) {
        showToasted("Password must be at least 8 characters long.", "error");
        return done();
      }

      sendAjaxRequest(
        "verify-password.php",
        "POST",
        "password=" + encodeURIComponent(password),
        function (response) {
          if (!response.success) {
            showToasted(response.message, "error");
            done();
          } else {
            setTimeout(() => {
              showToasted(response.message, "success");
              currentStep = 1; // Move to next step
              showStep(currentStep);
            }, TIMEOUT);
            done();
          }
        }
      );
    });
  });

  const emailInput = document.getElementById("email");
  const emailSubmit = document.getElementById("email-submit");

  emailSubmit.addEventListener("click", function () {
    handleButtonClick(emailSubmit, done => {
      const email = emailInput.value.trim();

      if (email === "") {
        showToasted("Email address is required.", "error");
        return done();
      }

      if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        showToasted("Invalid email address.", "error");
        return done();
      }

      sendAjaxRequest(
        "send-token.php",
        "POST",
        "email=" + encodeURIComponent(email),
        function (response) {
          if (!response.success) {
            showToasted(response.message, "error");
          } else {
            sessionStorage.setItem("reset_email", email);
            showToasted(response.message, "success");
            setTimeout(() => {
              currentStep = 2; // Move to token step
              showStep(currentStep);
            }, TIMEOUT);
          }
          done(); // re-enable button
        }
      );
    });
  });

  const tokenInput = document.getElementById("token");
  const tokenSubmit = document.getElementById("token-submit");

  tokenSubmit.addEventListener("click", function () {
    handleButtonClick(tokenSubmit, done => {
      const token = tokenInput.value.trim();
      const email = sessionStorage.getItem("reset_email") || "";

      if (token === "") {
        showToasted("Token is required", "error");
        return done();
      }

      const type = "pin";

      sendAjaxRequest(
        "verify-token.php",
        "POST",
        "token=" +
          encodeURIComponent(token) +
          "&type=" +
          encodeURIComponent(type) +
          "&email=" +
          encodeURIComponent(email),
        function (response) {
          if (!response.success) {
            showToasted(response.message, "error");
          } else {
            setTimeout(() => {
              showToasted(response.message, "success");
              currentStep = 3; // Move to PIN reset step
              showStep(currentStep);
            }, TIMEOUT);
          }
          done(); // re-enable
        }
      );
    });
  });

  const pinInput = document.getElementById("pin");
  const confirmPinInput = document.getElementById("confirm-pin");
  const pinSubmit = document.getElementById("pin-submit");

  pinSubmit.addEventListener("click", function () {
    handleButtonClick(pinSubmit, done => {
      const pin = pinInput.value.trim();
      const confirmPin = confirmPinInput.value.trim();
      const reset_email = sessionStorage.getItem("reset_email");

      if (pin === "" || confirmPin === "") {
        showToasted("Both PIN fields are required.", "error");
        return done();
      }
      if (!/^\d{4}$/.test(pin)) {
        showToasted("PIN must be exactly 4 digits.", "error");
        return done();
      }
      if (pin !== confirmPin) {
        showToasted("PINs do not match.", "error");
        return done();
      }

      sendAjaxRequest(
        "reset-pin.php",
        "POST",
        "pin=" +
          encodeURIComponent(pin) +
          "&reset_email=" +
          encodeURIComponent(reset_email),
        function (response) {
          if (!response.success) {
            showToasted(response.message, "error");
          } else {
            setTimeout(() => {
              showToasted(response.message, "success");
              sessionStorage.clear();
              window.location.href = "dashboard.php?success=pin";
            }, TIMEOUT);
          }
          done();
        }
      );
    });
  });

  showStep(currentStep);
});
