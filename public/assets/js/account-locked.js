document.addEventListener("DOMContentLoaded", function () {
  function withOverlay(handler) {
    const overlay = document.getElementById("bodyOverlay");
    overlay.style.display = "flex";
    handler(() => {
      overlay.style.display = "none";
    });
  }

  const submitComplain = document.getElementById("submit_complain");

  if (!submitComplain) {
    console.error("Element with ID 'submit_complain' not found in the DOM.");
    return;
  }

  submitComplain.addEventListener("click", function (e) {
    e.preventDefault(); // Prevent default form submission

    const reason = document.getElementById("reason").value.trim(); // Get the selected reason

    if (!reason) {
      showToasted("Please select a reason.", "error");
      return;
    }

    submitComplain.disabled = true;
    submitComplain.style.cursor = "not-allowed";

    // Use the withOverlay function to show the loader
    withOverlay(hideOverlay => {
      sendAjaxRequest(
        "submit-complaint.php",
        "POST",
        `reason=${encodeURIComponent(reason)}`,
        function (response) {
          submitComplain.disabled = false;
          submitComplain.style.cursor = "pointer";

          try {
            console.log("Raw response:", response); // Debugging log

            if (!response.success) {
              // Hide the overlay if the response is unsuccessful
              hideOverlay();
              showToasted(response.message, "error");
            } else {
              // Keep the overlay visible for a timeout before redirecting
              showToasted(response.message, "success");
              setTimeout(() => {
                window.location.href = "account_locked.php.php?submitted=true"; // Redirect to success page
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

document.addEventListener("DOMContentLoaded", function () {
  function withOverlay(handler) {
    const overlay = document.getElementById("bodyOverlay");
    overlay.style.display = "flex"; // Show the overlay
    handler(() => {
      overlay.style.display = "none"; // Hide the overlay
    });
  }

  const resendEmailBtn = document.getElementById("resend_email");

  if (resendEmailBtn) {
    resendEmailBtn.addEventListener("click", () => {
      withOverlay(hideOverlay => {
        sendAjaxRequest(
          "resend-email.php",
          "POST",
          `action=resend`,
          function (response) {
            try {
              if (!response.success) {
                hideOverlay();
                showToasted(response.message, "error");
              } else {
                showToasted(response.message, "success");
                hideOverlay();
              }
            } catch (error) {
              console.error("Invalid JSON response:", response);
              showToasted("An error occurred. Please try again.", "error");
              hideOverlay();
            }
          }
        );
      });
    });
  }
});
