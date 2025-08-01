document.addEventListener("DOMContentLoaded", function () {
  // Constants
  const TIMEOUT = 2000;
  const OTP_LENGTH = 6;

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

    if (step === 2) {
      // Token step
      const email = sessionStorage.getItem("email") || "";
      const userEmail = document.getElementById("user-email");
      if (userEmail && email) {
        userEmail.textContent = email;
      }
    }

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
        if (currentStep != 2) {
          currentStep--;
          showStep(currentStep);
          sessionStorage.setItem("currentStep", currentStep);
        } else {
          currentStep -= 2;
        }
      }
    });
  });

  emailContinueBtn.addEventListener("click", () => {
    withOverlay(hideOverlay => {
      handleButtonClick(emailContinueBtn, done => {
        const email = emailInput.value.trim();

        if (email == "") {
          showToasted("Email address is required.", "error");
          hideOverlay(); // Hide the overlay
          done();
          return;
        }

        if (email.length <= 6 || email.includes("mailinator")) {
          showToasted("Email address not supported.", "error");
          hideOverlay(); // Hide the overlay
          done();
          return;
        }

        // Checking network before any AJAX
        // if (!navigator.onLine) {
        //   showToasted(
        //     "No internet connection. Please connect and try again.",
        //     "error"
        //   );
        //   done();
        //   return;
        // }

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
              hideOverlay(); // Hide the overlay
              done();
            } else {
              sessionStorage.setItem("email", email);
              const registration_id = response.registration_id;
              sessionStorage.setItem("registration_id", registration_id);

              // Checking network before any AJAX
              if (!navigator.onLine) {
                showToasted(
                  "No internet connection. Please connect and try again.",
                  "error"
                );
                done();
                return;
              }

              sendAjaxRequest(
                "send-otp.php",
                "POST",
                `email=${encodeURIComponent(
                  email
                )}&registration_id=${encodeURIComponent(registration_id)}`,
                otpResponse => {
                  if (otpResponse.success) {
                    completedSteps.add(1);
                    setTimeout(() => {
                      goToStep(2);
                      hideOverlay(); // Hide the overlay
                      done();
                      showToasted(otpResponse.message, "success");
                    }, TIMEOUT);
                  } else {
                    showToasted(otpResponse.message, "error");
                    hideOverlay(); // Hide the overlay
                    done();
                  }
                }
              );
            }
          }
        );
      });
    });
  });

  let countdown = 60; // 1 minute
  const timerDisplay = document.getElementById("otp-timer");
  const resendOtpBtn = document.getElementById("resend-otp-btn");

  resendOtpBtn.classList.add("disabled-link");

  function updateTimer() {
    const minutes = Math.floor(countdown / 60);
    const seconds = countdown % 60;
    timerDisplay.textContent = `${minutes}:${seconds < 10 ? "0" : ""}${seconds}`;

    if (countdown === 0) {
      resendOtpBtn.classList.remove("disabled-link");
      resendOtpBtn.classList.add("active-link");
      clearInterval(timerInterval);
    }

    countdown--;
  }

  const timerInterval = setInterval(updateTimer, 1000);

  resendOtpBtn.addEventListener("click", event => {
    event.preventDefault();
    if (!resendOtpBtn.classList.contains("active-link")) return;

    const email = sessionStorage.getItem("email");

    sendAjaxRequest(
      "resend-otp.php",
      "POST",
      `email=${encodeURIComponent(email)}`,
      response => {
        if (response.success) {
          showToasted("New OTP sent successfully!", "success");
          countdown = 60;
          resendOtpBtn.classList.remove("active-link");
          resendOtpBtn.classList.add("disabled-link");
          setInterval(updateTimer, 1000);
        } else {
          showToasted(response.message, "error");
        }
      }
    );
  });

  // Step 2: OTP
  const otpInputs = document.querySelectorAll(".otp-input");
  const verifyOtpBtn = document.getElementById("verify-otp-btn");

  verifyOtpBtn.addEventListener("click", () => {
    handleButtonClick(verifyOtpBtn, done => {
      let otp = "";
      otpInputs.forEach(input => (otp += input.value.trim()));

      if (otp.length !== OTP_LENGTH || isNaN(otp)) {
        showToasted("Enter a valid 6-digit OTP.", "error");
        done();
        return;
      }

      const email = sessionStorage.getItem("email");
      const registration_id = sessionStorage.getItem("registration_id");

      if (!registration_id) {
        showToasted("Registration ID not set", "error");
        sessionStorage.clear();
        window.location.href = "register.php";
        return;
      }

      sendAjaxRequest(
        "verify-otp.php",
        "POST",
        `email=${encodeURIComponent(email)}&otp=${encodeURIComponent(
          otp
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

  // OTP INPUT HANDLING
  otpInputs.forEach((input, index) => {
    input.addEventListener("input", e => {
      if (e.target.value.length === 1 && index < otpInputs.length - 1) {
        otpInputs[index + 1].focus();
      }
    });

    input.addEventListener("keydown", e => {
      if (e.key === "Backspace" && !input.value && index > 0) {
        otpInputs[index - 1].focus();
      }
    });

    input.addEventListener("paste", e => {
      e.preventDefault();
      let pastedData = e.clipboardData
        .getData("text")
        .replace(/\D/g, "")
        .slice(0, otpInputs.length);
      otpInputs.forEach((inp, i) => (inp.value = pastedData[i] || ""));
    });
  });

  // Step 3: Phone
  const phoneInput = document.getElementById("phone");
  const phoneSubmit = document.getElementById("phone-submit");

  phoneSubmit.addEventListener("click", () => {
    handleButtonClick(phoneSubmit, done => {
      let phone = phoneInput.value.trim().replace(/^0/, "");

      if (phone == "") {
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

  // Step 4: Names
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

  // Step 5: Username
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
            completedSteps.add(5);
            setTimeout(() => {
              goToStep(6);
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


  // Step 6: Password
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
