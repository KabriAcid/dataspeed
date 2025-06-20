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
    const bodyOverlay = document.getElementById("bodyOverlay");
    const action = pinpadModal.dataset.action;

    let data = `pin=${encodeURIComponent(pin)}`;
    let endpoint;

    switch (action) {
      case "transfer":
        data += `&email=${encodeURIComponent(
          pinpadModal.dataset.email
        )}&amount=${encodeURIComponent(
          pinpadModal.dataset.amount
        )}&action=transfer`;
        endpoint = "process-transfer.php";
        break;

      case "airtime":
        data += `&amount=${encodeURIComponent(
          pinpadModal.dataset.amount
        )}&phone=${encodeURIComponent(
          pinpadModal.dataset.phone
        )}&network=${encodeURIComponent(
          pinpadModal.dataset.network
        )}&type=${encodeURIComponent(pinpadModal.dataset.type)}`;
        endpoint = "airtime-purchase.php";
        break;

      case "data":
        data +=
          `&amount=${encodeURIComponent(pinpadModal.dataset.amount)}` +
          `&phone=${encodeURIComponent(pinpadModal.dataset.phone)}` +
          `&network=${encodeURIComponent(pinpadModal.dataset.network)}` +
          `&type=${encodeURIComponent(pinpadModal.dataset.type)}` +
          `&plan_id=${encodeURIComponent(pinpadModal.dataset.plan_id)}`;
        endpoint = "data-purchase.php";
        break;

      case "electricity":
        data += `&amount=${encodeURIComponent(
          pinpadModal.dataset.amount
        )}&meter=${encodeURIComponent(
          pinpadModal.dataset.meter
        )}&disco=${encodeURIComponent(
          pinpadModal.dataset.disco
        )}&type=${encodeURIComponent(pinpadModal.dataset.type)}`;
        endpoint = "electricity-purchase.php";
        break;

      case "tv":
        data +=
          `&amount=${encodeURIComponent(pinpadModal.dataset.amount)}` +
          `&phone=${encodeURIComponent(pinpadModal.dataset.phone)}` +
          `&network=${encodeURIComponent(pinpadModal.dataset.network)}` +
          `&type=${encodeURIComponent(pinpadModal.dataset.type)}` +
          `&plan_id=${encodeURIComponent(pinpadModal.dataset.plan_id)}` +
          `&iuc=${encodeURIComponent(pinpadModal.dataset.iuc)}`;
        endpoint = "tv-purchase.php";
        break;

      // Add more services as needed
      default:
        showToasted("Unknown service type", "error");
        return;
    }

    bodyOverlay.style.display = "flex";

    sendAjaxRequest(endpoint, "POST", data, function (response) {
      bodyOverlay.style.display = "none";
      if (response.success) {
        showToasted(response.message, "success");
        pinpadModal.style.display = "none";
        setTimeout(function () {
          window.location.href =
            "transaction-successful.php?ref=" +
            encodeURIComponent(response.reference);
        }, 1200);
      } else {
       if (response.redirect) {
         setTimeout(() => {
           window.location.href =
             "password_pin_setting.php?tab=pin&prev_page=" +
             encodeURIComponent(window.location.pathname.replace(/^\/+/, ""));
         }, 1200);
        }
        if (response.phone) {
          console.log(response.phone);
        }
        showToasted(response.message, "error");
        pinpadModal.style.display = 'flex';
      }
    });
  }

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
    window.location.href = 'forgot-pin.php';
  });

  // Initialize state
  updateDots();
  updateBackspace();
});
