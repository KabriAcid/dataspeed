// Toasted.js
const toastedIcons = {
  success: `<svg viewBox="0 0 24 24"><path d="M7 13.5l3 3 7-7" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>`,
  error: `<svg viewBox="0 0 24 24"><path d="M8 8l8 8M16 8l-8 8" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" stroke-linejoin="round" fill="none"/></svg>`,
  info: `<svg viewBox="0 0 24 24"><circle cx="12" cy="12" r="1.3" fill="currentColor"/><path d="M12 8v2.5" stroke="currentColor" stroke-width="2.2" stroke-linecap="round" fill="none"/></svg>`,
  loading: `<svg viewBox="0 0 50 50"><circle cx="25" cy="25" r="20" stroke="currentColor" stroke-width="5" fill="none"/></svg>`,
};

function showToasted(message, type = "info", duration = 3000) {
  let container = document.getElementById("toasted-container");
  if (!container) {
    container = document.createElement("div");
    container.className = "toasted-container";
    container.id = "toasted-container";
    document.body.appendChild(container);
  }

  // Hide all toasts if type is 'hide'
  if (type === "hide") {
    container.querySelectorAll(".toasted").forEach(t => t.remove());
    return;
  }

  const toast = document.createElement("div");
  toast.className = "toasted";
  toast.dataset.type = type;

  toast.innerHTML = `
    <span class="toasted__icon">${toastedIcons[type] || ""}</span>
    <span>${message}</span>
  `;

  container.appendChild(toast);
  requestAnimationFrame(() => toast.classList.add("show"));

  if (type !== "loading") {
    setTimeout(() => {
      toast.classList.remove("show");
      setTimeout(() => toast.remove(), 400);
    }, duration);
  }
}

window.showToasted = showToasted;
