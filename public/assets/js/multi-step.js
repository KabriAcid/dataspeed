document.addEventListener("DOMContentLoaded", function () {
  // Constants
  const TIMEOUT = 2000;

  // Step controls
  const steps = document.querySelectorAll(".form-step");
  const indicators = document.querySelectorAll(".pagination .page");
  let completedSteps = new Set();
  let currentStep = parseInt(sessionStorage.getItem("currentStep")) || 0;

  function showStep(step) {
    steps.forEach((el, index) => {
      el.classList.toggle("d-none", index !== step);
    });
    indicators.forEach((el, index) => {
      el.classList.toggle("active", index === step);
    });
    sessionStorage.setItem("currentStep", step);
  }

  function goToStep(step) {
    if (step === 0 || completedSteps.has(step - 1)) {
      currentStep = step;
      showStep(currentStep);
    }
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

  function withOverlay(handler) {
    document.getElementById("bodyOverlay").style.display = "flex";
    handler(() => {
      document.getElementById("bodyOverlay").style.display = "none";
    });
  }

  // Step 0: Referral Code
  const referralInput = document.getElementById("referral-code");
  const referralContinueBtn = document.getElementById("referral-submit");

  referralContinueBtn.addEventListener("click", () => {
    handleButtonClick(referralContinueBtn, done => {
      const referralCode = referralInput.value.trim();
      sendAjaxRequest(
        "validate-referral.php",
        "POST",
        `referral_code=${encodeURIComponent(referralCode)}`,
        response => {
          if (response.success) {
            sessionStorage.setItem("referral_code", referralCode);
            completedSteps.add(0);
            showToasted(response.message, "success");
            setTimeout(() => {
              goToStep(1);
              done();
            }, TIMEOUT);
          } else {
            showToasted(response.message, "error");
            done();
          }
        }
      );
    });
  });

  // Step 1: Email
  const emailInput = document.getElementById("email");
  const emailContinueBtn = document.getElementById("email-submit");
  const backButton = document.querySelectorAll(".prev-button");

  backButton.forEach(button => {
    button.addEventListener("click", () => {
      if (currentStep > 0) {
        currentStep--;
        showStep(currentStep);
        sessionStorage.setItem("currentStep", currentStep);
      }
    });
  });

  emailContinueBtn.addEventListener("click", () => {
    withOverlay(hideOverlay => {
      handleButtonClick(emailContinueBtn, done => {
        const email = emailInput.value.trim();

        if (email === "") {
          showToasted("Email address is required.", "error");
          hideOverlay();
          done();
          return;
        }

        if (email.length <= 6 || email.includes("mailinator")) {
          showToasted("Email address not supported.", "error");
          hideOverlay();
          done();
          return;
        }

        sendAjaxRequest(
          "validate-email.php",
          "POST",
          `email=${encodeURIComponent(email)}`,
          response => {
            if (!response.success) {
              showToasted(response.message, "error");
              if (response.registration_id) {
                sessionStorage.clear();
                window.location.href = "register.php";
              }
              hideOverlay();
              done();
            } else {
              sessionStorage.setItem("email", email);
              const registration_id = response.registration_id;
              sessionStorage.setItem("registration_id", registration_id);
              // Skip OTP: proceed directly to Phone
              completedSteps.add(1);
              setTimeout(() => {
                goToStep(2);
                hideOverlay();
                done();
                showToasted(response.message || "Email captured.", "success");
              }, TIMEOUT);
            }
          }
        );
      });
    });
  });

  // Step 2: Phone
  const phoneInput = document.getElementById("phone");
  const phoneSubmit = document.getElementById("phone-submit");

  phoneSubmit.addEventListener("click", () => {
    handleButtonClick(phoneSubmit, done => {
      let phone = phoneInput.value.trim().replace(/^0/, "");

      if (phone === "") {
        showToasted("Phone number is required.", "error");
        done();
        return;
      }
      if (/^(\+234|234)/.test(phoneInput.value)) {
        showToasted("Remove the country code.", "error");
        done();
        return;
      }

      if (!/^\d{10}$/.test(phone)) {
        showToasted("Enter a valid Nigerian phone number.", "error");
        done();
        return;
      }

      const registration_id = sessionStorage.getItem("registration_id");

      sendAjaxRequest(
        "validate-phone.php",
        "POST",
        `phone=${encodeURIComponent(
          phone
        )}&registration_id=${encodeURIComponent(registration_id)}`,
        response => {
          if (response.success) {
            showToasted(response.message, "success");
            completedSteps.add(2);
            setTimeout(() => {
              goToStep(3);
              done();
            }, TIMEOUT);
          } else {
            showToasted(response.message, "error");
            done();
          }
        }
      );
    });
  });

  // Step 3: Names
  const firstNameInput = document.getElementById("first_name");
  const lastNameInput = document.getElementById("last_name");
  const namesSubmit = document.getElementById("names-submit");

  namesSubmit.addEventListener("click", () => {
    handleButtonClick(namesSubmit, done => {
      const firstName = firstNameInput.value.trim();
      const lastName = lastNameInput.value.trim();
      const namePattern = /^[A-Za-z\s'-]+$/;

      if (
        !firstName ||
        !lastName ||
        firstName.length <= 1 ||
        lastName.length <= 1 ||
        !namePattern.test(firstName) ||
        !namePattern.test(lastName)
      ) {
        showToasted("Enter valid first and last names.", "error");
        done();
        return;
      }

      const registration_id = sessionStorage.getItem("registration_id");
      sendAjaxRequest(
        "validate-names.php",
        "POST",
        `first_name=${encodeURIComponent(
          firstName
        )}&last_name=${encodeURIComponent(
          lastName
        )}&registration_id=${encodeURIComponent(registration_id)}`,
        response => {
          if (response.success) {
            showToasted(response.message, "success");
            completedSteps.add(3);
            setTimeout(() => {
              goToStep(4);
              done();
            }, TIMEOUT);
          } else {
            showToasted(response.message, "error");
            done();
          }
        }
      );
    });
  });

  // Step 4: Username
  const userNameInput = document.getElementById("username");
  const userNameSubmit = document.getElementById("username-submit");

  userNameSubmit.addEventListener("click", () => {
    handleButtonClick(userNameSubmit, done => {
      const userName = userNameInput.value.trim();

      if (!userName) {
        showToasted("Username is required.", "error");
        done();
        return;
      }
      if (userName.length <= 4 || userName.length > 20) {
        showToasted("Username length is invalid.", "error");
        done();
        return;
      }

      const registration_id = sessionStorage.getItem("registration_id");

      sendAjaxRequest(
        "validate-username.php",
        "POST",
        `user_name=${encodeURIComponent(
          userName
        )}&registration_id=${encodeURIComponent(registration_id)}`,
        response => {
          if (response.success) {
            showToasted(response.message, "success");
            completedSteps.add(4);
            setTimeout(() => {
              goToStep(5);
              done();
            }, TIMEOUT);
          } else {
            showToasted(response.message, "error");
            done();
          }
        }
      );
    });
  });

  // Step 5: Password
  const passwordInput = document.getElementById("password");
  const confirmPasswordInput = document.getElementById("confirm-password");
  const passwordSubmit = document.getElementById("password-submit");

  passwordSubmit.addEventListener("click", () => {
    withOverlay(hideOverlay => {
      handleButtonClick(passwordSubmit, done => {
        const password = passwordInput.value.trim();
        const confirmPassword = confirmPasswordInput.value.trim();

        if (!password || !confirmPassword) {
          showToasted("Both password fields are required.", "error");
          hideOverlay();
          done();
          return;
        }
        if (password !== confirmPassword) {
          showToasted("Passwords does not match.", "error");
          hideOverlay();
          done();
          return;
        }
        if (password.length < 8) {
          showToasted("Passwords must be at least 8 characters.", "error");
          hideOverlay();
          done();
          return;
        }

        const registration_id = sessionStorage.getItem("registration_id");
        sendAjaxRequest(
          "validate-password.php",
          "POST",
          `password=${encodeURIComponent(
            password
          )}&registration_id=${encodeURIComponent(registration_id)}`,
          response => {
            if (response.success) {
              showToasted(response.message, "success");
              setTimeout(() => {
                sessionStorage.clear();
                window.location.href = "login.php?success=1";
              }, 3000);
            } else {
              hideOverlay();
              showToasted(response.message || "Registration failed.", "error");
              done();
            }
          }
        );
      });
    });
  });

  showStep(currentStep);
});
              done();
