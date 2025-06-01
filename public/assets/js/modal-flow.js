document.addEventListener("DOMContentLoaded", () => {
  const confirmModal = document.getElementById("confirmModal");
  const pinModal = document.getElementById("pinModal");
  const phoneEl = document.getElementById("customer-phone");
  const payBtn = document.getElementById("payBtn");
  const closeBtns = document.querySelectorAll(".close-btn");

  // Helper: Format phone number as 090 1234 5678
  function formatPhoneNumber(num) {
    return num
      .replace(/\D/g, "")
      .replace(/^(\d{3})(\d{4})(\d{4})$/, "$1 $2 $3");
  }

  // Show modal
  function showModal(modal) {
    modal.style.display = "flex";
  }

  // Hide modal
  function hideModal(modal) {
    modal.style.display = "none";
  }

  // Close buttons
  closeBtns.forEach((btn) => {
    btn.addEventListener("click", () => {
      hideModal(confirmModal);
      hideModal(pinModal);
    });
  });

  // Launch confirm modal (example trigger)
  document.getElementById("launchConfirm").addEventListener("click", () => {
    const rawPhone = "09022223334"; // later replace with dynamic PHP value
    phoneEl.textContent = formatPhoneNumber(rawPhone);
    showModal(confirmModal);
  });

  // On Pay button click: Check balance via AJAX
  payBtn.addEventListener("click", () => {
    payBtn.disabled = true;
    payBtn.textContent = "Checking...";

    sendAjaxRequest("checkbalance.php", "POST", "", (response) => {
      payBtn.disabled = false;
      payBtn.textContent = "Pay";

      if (response.success) {
        showModal(pinModal); // Stack PIN modal
      } else {
        showToasted(response.message || "Insufficient balance", "error");
      }
    });
  });

  // Initialize PIN pad logic
  initPinPad("#pinModal", (pin) => {
    const data = `pin=${encodeURIComponent(pin)}`;

    sendAjaxRequest("authenticate.php", "POST", data, (response) => {
      if (response.success) {
        showToasted("PIN verified! Purchasing...", "success");

        // Option 1: Perform purchase in this file
        sendAjaxRequest("purchase.php", "POST", "", (purchaseResponse) => {
          if (purchaseResponse.success) {
            showToasted("Data purchase successful.", "success");
            hideModal(pinModal);
            hideModal(confirmModal);
          } else {
            showToasted(
              purchaseResponse.message || "Purchase failed.",
              "error"
            );
          }
        });

        // Option 2: OR redirect to a purchase handler page
        // window.location.href = `purchase.php?pin=${pin}`;
      } else {
        showToasted(response.message || "Incorrect PIN. Try again.", "error");
      }
    });
  });
});
