// data_purchase.js

// Selected state
let selectedNetwork = null;
let selectedPlan = null;
let phoneNumber = "";
let pin = "";

// DOM elements
const confirmModal = document.getElementById("confirmModal");
const pinModal = document.getElementById("pinModal");
const phoneInput = document.getElementById("phoneInput");
const confirmPayBtn = document.getElementById("confirmPayBtn");
const confirmPhone = document.getElementById("confirmPhone");
const confirmAmount = document.getElementById("confirmAmount");
const confirmVolume = document.getElementById("confirmVolume");
const confirmDuration = document.getElementById("confirmDuration");
const confirmProvider = document.getElementById("confirmProvider");

// Handle network tab selection
document.querySelectorAll(".network-tab").forEach((tab) => {
  tab.addEventListener("click", () => {
    document
      .querySelectorAll(".network-tab")
      .forEach((el) => el.classList.remove("active"));
    tab.classList.add("active");
    selectedNetwork = tab.dataset.network;
  });
});

// Handle data plan selection
document.querySelectorAll(".plan-card").forEach((card) => {
  card.addEventListener("click", () => {
    document
      .querySelectorAll(".plan-card")
      .forEach((el) => el.classList.remove("selected"));
    card.classList.add("selected");

    const amount = card.querySelector("#data-amount").textContent;
    const volume = card.querySelector("#data-volume").textContent;
    const duration = card.querySelector("#data-duration").textContent;

    selectedPlan = {
      amount,
      volume,
      duration,
    };
  });
});

// Validate and proceed
function handlePurchaseClick() {
  phoneNumber = phoneInput.value.trim();

  if (
    !selectedNetwork ||
    !selectedPlan ||
    phoneNumber.length !== 11 ||
    !/^\d{11}$/.test(phoneNumber)
  ) {
    showToasted("Please complete all required fields correctly.", "error");
    return;
  }

  // Populate confirmation modal
  confirmPhone.textContent = phoneNumber;
  confirmAmount.textContent = selectedPlan.amount;
  confirmVolume.textContent = selectedPlan.volume;
  confirmDuration.textContent = selectedPlan.duration;
  confirmProvider.textContent = selectedNetwork.toUpperCase();

  confirmModal.style.display = "flex";
}

// Click-away reset
window.addEventListener("click", (e) => {
  if (e.target.classList.contains("modal-overlay")) {
    closeModal();
  }
});

function closeModal() {
  confirmModal.style.display = "none";
  pinModal.style.display = "none";
  pin = "";
  updateDots();
}

// Balance check and open PIN modal
function proceedToPin() {
  const data = `phone=${phoneNumber}&network=${selectedNetwork}&amount=${selectedPlan.amount}`;
  sendAjaxRequest("/api/check_balance.php", "POST", data, (res) => {
    if (
      res.success &&
      res.balance >= parseFloat(selectedPlan.amount.replace(/[^\d.]/g, ""))
    ) {
      pinModal.style.display = "flex";
    } else {
      closeModal();
      showToasted(res.message || "Insufficient balance.", "error");
    }
  });
}

// PIN pad interaction
function handleKey(val) {
  if (val === "←") {
    pin = pin.slice(0, -1);
  } else if (pin.length < 4 && val !== "") {
    pin += val;
  }
  updateDots();

  if (pin.length === 4) {
    verifyPin();
  }
}

function updateDots() {
  for (let i = 1; i <= 4; i++) {
    const dot = document.getElementById(`dot${i}`);
    dot.classList.toggle("filled", i <= pin.length);
  }
}

function verifyPin() {
  const data = `pin=${pin}&network=${selectedNetwork}&volume=${selectedPlan.volume}&phone=${phoneNumber}`;
  sendAjaxRequest("purchase_data.php", "POST", data, (res) => {
    closeModal();
    if (res.success) {
      showToasted("Data purchase successful!", "success");
    } else {
      showToasted(res.message || "PIN verification failed.", "error");
    }
  });
}

// Expose to global
window.handlePurchaseClick = handlePurchaseClick;
window.proceedToPin = proceedToPin;
window.handleKey = handleKey;
