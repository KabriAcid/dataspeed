// DOM Ready
document.addEventListener("DOMContentLoaded", function () {
  function withOverlay(handler) {
    const overlay = document.getElementById("bodyOverlay");
    overlay.style.display = "flex"; // Show the overlay
    handler(() => {
      overlay.style.display = "none"; // Hide the overlay
    });
  }

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

    // Use the withOverlay function to show the loader
    withOverlay(hideOverlay => {
      sendAjaxRequest(
        "auth.php",
        "POST",
        `user=${encodeURIComponent(user)}&password=${encodeURIComponent(
          password
        )}`,
        function (response) {
          try {
            if (!response.success) {
              // Hide the overlay if the response is unsuccessful
              hideOverlay();

              // Redirect to account frozen page if locked
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
              // Keep the overlay visible for a timeout before redirecting
              showToasted("Login successful!", "success");
              setTimeout(() => {
                window.location.href = "dashboard.php?success=1";
                window.history.replaceState(null, null, "dashboard.php");
                hideOverlay(); // Hide the overlay after redirect
              }, 1500);
            }
          } catch (error) {
            console.error("Invalid JSON response:", response);
            showToasted("An error occurred. Please try again.", "error");
            hideOverlay(); // Hide the overlay in case of an error
          }
        }
      );
    });
  });
});
