document.addEventListener("DOMContentLoaded", function () {
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
            showToasted(response.message, "error");
          } else {
            showToasted(response.message, "success");
            setTimeout(() => {
              window.location.href = "account-locked.php?submitted=true"; // Redirect to success page
            }, 1500);
          }
        } catch (error) {
          console.error("Invalid JSON response:", response);
          showToasted("An error occurred. Please try again.", "error");
        }
      }
    );
  });
});
