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
          // Call your PIN complete handler here
          // Example: onPinComplete(pin);
          pin = "";
          updateDots();
          updateBackspace();
          pinpadModal.style.display = "none";
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
