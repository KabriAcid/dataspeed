// DOM Ready
document.addEventListener("DOMContentLoaded", function () {
  const userInput = document.getElementById("user");
  const passwordInput = document.getElementById("password");
  const loginBtn = document.getElementById("login");

  loginBtn.addEventListener("click", function () {
    const user = userInput.value.trim();
    const password = passwordInput.value.trim();

    if (!user && !password) {
      showToasted("Email and password are required.", "error");
      return;
    }
    if (!user) {
      showToasted("Phone number or email is required.", "error");
      return;
    }
    if (!password) {
      showToasted("Password is required.", "error");
      return;
    }

    loginBtn.disabled = true;
    loginBtn.style.cursor = "not-allowed";

    sendAjaxRequest(
      "auth.php",
      "POST",
      `user=${encodeURIComponent(user)}&password=${encodeURIComponent(
        password
      )}`,
      function (response) {
        loginBtn.disabled = false;
        loginBtn.style.cursor = "pointer";

        try {
          if (!response.success) {
            // Redirect to account frozen page
            if (response.locked) {
              showToasted(
                "Your account is locked. Please contact admin...",
                "info"
              );
              setTimeout(() => {
                window.location.href = response.redirect;
              }, 1500);
              return; // Stop further execution
            }

            showToasted(response.message, "error");
          } else {
            window.location.href = "dashboard.php?success=1";

            // After the page loads, remove the "success" parameter
            window.history.replaceState(null, null, "dashboard.php");
          }
        } catch (error) {
          console.error("Invalid JSON response:", response);
          showToasted("An error occurred. Please try again.", "error");
        }
      }
    );
  });
});
