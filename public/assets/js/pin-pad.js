function initPinPad(containerSelector, onComplete) {
  const container = document.querySelector(containerSelector);
  if (!container) return;

  const dots = container.querySelectorAll(".pin-dot");
  const keys = container.querySelectorAll(".key-button");
  const backspace = container.querySelector("#backspace");
  const logoutBtn = container.querySelector(".logout");
  const forgotBtn = container.querySelector(".forgot");
  let pin = "";
  const MAX_LENGTH = 4;

  const updateDots = () => {
    dots.forEach((dot, i) => {
      dot.classList.toggle("filled", i < pin.length);
    });
    backspace.style.visibility = pin.length > 0 ? "visible" : "hidden";
  };

  const resetPin = () => {
    pin = "";
    updateDots();
  };

  const addDigit = digit => {
    if (!/^\d$/.test(digit)) return;
    if (pin.length < MAX_LENGTH) {
      pin += digit;
      updateDots();
      if (pin.length === MAX_LENGTH) {
        setTimeout(() => {
          onComplete(pin);
          resetPin();
        }, 250);
      }
    }
  };

  const removeDigit = () => {
    if (pin.length > 0) {
      pin = pin.slice(0, -1);
      updateDots();
    }
  };

  keys.forEach(btn => {
    btn.addEventListener("click", () => {
      const val = btn.dataset.value;
      addDigit(val);
    });
  });

  backspace?.addEventListener("click", removeDigit);

  // Initial state
  updateDots();

  // Keyboard input support (when modal is visible)
  document.addEventListener("keydown", e => {
    if (container.offsetParent === null) return;
    if (/^\d$/.test(e.key)) {
      addDigit(e.key);
    } else if (e.key === "Backspace") {
      removeDigit();
    }
  });

  // Logout and Forgot PIN hooks
  logoutBtn?.addEventListener("click", () => {
    showToasted("Logging out...", "info");
  });

  forgotBtn?.addEventListener("click", () => {
    showToasted("Forgot PIN clicked.", "info");
  });
}

// Click outside the PIN modal to dismiss it
document.addEventListener("click", function (e) {
  const modal = document.getElementById("pinpadModal");
  if (!modal || modal.style.display !== "flex") return;

  const content = modal.querySelector(".pin-container");
  if (content && !content.contains(e.target)) {
    modal.style.display = "none";
  }
});
