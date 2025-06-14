document.addEventListener("DOMContentLoaded", function () {
  const pinpadModal = document.getElementById("pinpadModal");
  const pinDots = pinpadModal.querySelectorAll(".pin-dot");
  const keyButtons = pinpadModal.querySelectorAll(".key-button[data-value]");
  const backspaceBtn = pinpadModal.querySelector("#backspace");
  const exitBtn = pinpadModal.querySelector("#pin-exit-btn");
  const forgotBtn = pinpadModal.querySelector("#pin-forgot-btn");
  let pin = "";

  function processPinEntry(pin) {
    const pinpadModal = document.getElementById("pinpadModal");
    const pinpadOverlay = document.getElementById("pinpadOverlay");
    const amount = pinpadModal.dataset.amount;
    const phone = pinpadModal.dataset.phone;
    const network = pinpadModal.dataset.network;
    const type = pinpadModal.dataset.type;

    const data = `pin=${encodeURIComponent(pin)}&amount=${encodeURIComponent(
      amount
    )}&phone=${encodeURIComponent(phone)}&network=${encodeURIComponent(
      network
    )}&type=${encodeURIComponent(type)}`;

    // Show dim overlay
    pinpadOverlay.style.display = "flex";

    sendAjaxRequest("process-purchase.php", "POST", data, function (response) {
      // Hide overlay regardless of result
      pinpadOverlay.style.display = "none";

      if (response.success) {
        console.log("Transaction successful:", response.vtpass_response);
        showToasted(response.message, "success");
        pinpadModal.style.display = "none";
        setTimeout(function () {
           window.location.href =
             "transaction-successful.php?ref=" +
             encodeURIComponent(response.reference);
        }, 1200);
      } else {
        showToasted(response.message, "error");
      }
    });
  }

  // Call processPinEntry(pin) when 4 digits are entered

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

      // If PIN is complete (4 digits), process the transaction
      if (pin.length === 4) {
        setTimeout(() => {
          processPinEntry(pin);
          pin = ""; // Reset PIN after processing
          updateDots();
          updateBackspace();
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
    window.location.href = "/dataspeed/public/pages/dashboard.php";
  });

  forgotBtn?.addEventListener("click", function () {
    // Implement your forgot PIN logic here
    alert("Forgot PIN clicked!");
  });

  // Initialize state
  updateDots();
  updateBackspace();
});
