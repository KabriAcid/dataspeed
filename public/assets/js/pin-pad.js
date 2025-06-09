document.addEventListener("DOMContentLoaded", function () {
  const pinpadModal = document.getElementById("pinpadModal");
  const pinDots = pinpadModal.querySelectorAll(".pin-dot");
  const keyButtons = pinpadModal.querySelectorAll(".key-button[data-value]");
  const backspaceBtn = pinpadModal.querySelector("#backspace");
  const exitBtn = pinpadModal.querySelector("#pin-exit-btn");
  const forgotBtn = pinpadModal.querySelector("#pin-forgot-btn");
  let pin = "";

  // Utility: Update PIN dots
  function updateDots() {
    pinDots.forEach((dot, idx) => {
      dot.classList.toggle("filled", idx < pin.length);
    });
  }

  // Utility: Show/hide backspace
  function updateBackspace() {
    if (pin.length > 0) {
      backspaceBtn.classList.add("visible");
    } else {
      backspaceBtn.classList.remove("visible");
    }
  }

  // Add digit to PIN
  function addDigit(digit) {
    if (pin.length < 4 && /^\d$/.test(digit)) {
      pin += digit;
      updateDots();
      updateBackspace();
      if (pin.length === 4) {
        setTimeout(() => {
          // --- BEGIN: AJAX purchase logic ---
          // Gather data from modal
          const amountText =
            document.getElementById("confirm-amount").textContent;
          const rawAmount = amountText.replace(/[^\d]/g, "");
          const phone = document
            .getElementById("customer-phone")
            .getAttribute("data-raw");
          const network = document
            .getElementById("confirm-network")
            .getAttribute("data-network");
          const type = document.getElementById("confirm-plan").textContent;

          // Prepare data string for AJAX
          const data = `pin=${encodeURIComponent(
            pin
          )}&amount=${encodeURIComponent(rawAmount)}&phone=${encodeURIComponent(
            phone
          )}&network=${encodeURIComponent(network)}&type=${encodeURIComponent(
            type
          )}`;

          // Use your custom AJAX function
          sendAjaxRequest(
            "process-purchase.php",
            "POST",
            data,
            function (response) {
              if (response.success) {
                showToasted(
                  response.message || "Purchase successful!",
                  "success"
                );
                pinpadModal.style.display = "none";
                // Optionally update balance or UI here
              } else {
                showToasted(response.message || "Purchase failed!", "error");
                // Optionally reset PIN dots for another try
              }
              pin = "";
              updateDots();
              updateBackspace();
            }
          );
          // --- END: AJAX purchase logic ---
        }, 200);
      }
    }
  }

  // Remove last digit
  function removeDigit() {
    if (pin.length > 0) {
      pin = pin.slice(0, -1);
      updateDots();
      updateBackspace();
    }
  }

  // Keypad button clicks
  keyButtons.forEach(btn => {
    btn.addEventListener("click", () => addDigit(btn.dataset.value));
  });

  // Backspace click
  backspaceBtn.addEventListener("click", removeDigit);

  // Keyboard support
  pinpadModal.addEventListener("keydown", function (e) {
    if (e.key >= "0" && e.key <= "9") {
      addDigit(e.key);
    } else if (e.key === "Backspace") {
      removeDigit();
    } else if (e.key === "Escape") {
      pinpadModal.style.display = "none";
    }
  });

  // Focus modal for keyboard input when shown
  const observer = new MutationObserver(() => {
    if (pinpadModal.style.display !== "none") {
      pinpadModal.setAttribute("tabindex", "-1");
      pinpadModal.focus();
    }
  });
  observer.observe(pinpadModal, {
    attributes: true,
    attributeFilter: ["style"],
  });

  // Dismiss modal on outside click
  pinpadModal.addEventListener("click", function (e) {
    if (e.target === pinpadModal) {
      pinpadModal.style.display = "none";
      pin = "";
      updateDots();
      updateBackspace();
    }
  });

  // Exit and Forgot PIN actions
  exitBtn?.addEventListener("click", function () {
    pinpadModal.style.display = "none";
    pin = "";
    updateDots();
    updateBackspace();
    window.location.href = '/dataspeed/public/pages/dashboard.php';
  });

  forgotBtn?.addEventListener("click", function () {
    // Implement your forgot PIN logic here
    alert("Forgot PIN clicked!");
  });

  // Initialize state
  updateDots();
  updateBackspace();
});
