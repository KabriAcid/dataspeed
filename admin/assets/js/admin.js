// API Helper Function
async function apiFetch(url, options = {}) {
  const defaultOptions = {
    method: "GET",
    headers: { "Content-Type": "application/json" },
  };
  const config = { ...defaultOptions, ...options };
  try {
    const response = await fetch(url, config);
    const data = await response.json();
    if (!response.ok) throw new Error(data.message || "Request failed");
    return data;
  } catch (error) {
    console.error("API Error:", error);
    throw error;
  }
}

// Sidebar Management (responsive: mobile off-canvas, desktop collapse)
function initSidebar() {
  const sidebar = document.getElementById("sidebar");
  const sidebarToggle = document.getElementById("sidebarToggle"); // topbar button
  const sidebarCollapse = document.getElementById("sidebarCollapse"); // in sidebar header
  const sidebarOverlay = document.getElementById("sidebarOverlay");
  const body = document.body;

  if (!sidebar || !sidebarToggle) return;

  // Guard against double initialization (pages may call topbarInit() themselves)
  if (window.__dsSidebarInit) return;
  window.__dsSidebarInit = true;

  const isDesktop = () => window.innerWidth >= 992;

  const updateCollapsedTooltips = () => {
    const isCollapsed = sidebar.classList.contains("collapsed");
    document.querySelectorAll(".nav-link").forEach(link => {
      const text = link.querySelector(".nav-text");
      if (isCollapsed && text)
        link.setAttribute("title", text.textContent.trim());
      else link.removeAttribute("title");
    });
  };

  // No persistence: start expanded on each load
  const applyDefaultExpanded = () => {
    sidebar.classList.remove("collapsed");
    body.classList.remove("sidebar-collapsed");
    updateCollapsedTooltips();
  };

  const toggleCollapsed = () => {
    sidebar.classList.toggle("collapsed");
    const collapsed = sidebar.classList.contains("collapsed");
    body.classList.toggle("sidebar-collapsed", collapsed);
    updateCollapsedTooltips();
  };

  const openMobileSidebar = () => {
    sidebar.classList.add("open");
    if (sidebarOverlay) sidebarOverlay.classList.add("show");
  };
  const closeMobileSidebar = () => {
    sidebar.classList.remove("open");
    if (sidebarOverlay) sidebarOverlay.classList.remove("show");
  };

  // Topbar toggle: desktop collapses; mobile opens/closes off-canvas
  sidebarToggle.addEventListener("click", () => {
    if (isDesktop()) toggleCollapsed();
    else
      sidebar.classList.contains("open")
        ? closeMobileSidebar()
        : openMobileSidebar();
  });

  // Sidebar header collapse button (desktop only)
  if (sidebarCollapse) {
    sidebarCollapse.addEventListener("click", () => {
      if (isDesktop()) toggleCollapsed();
      else closeMobileSidebar();
    });
  }

  // Overlay click closes mobile sidebar
  if (sidebarOverlay) {
    sidebarOverlay.addEventListener("click", closeMobileSidebar);
  }

  // Resize handling
  window.addEventListener("resize", () => {
    if (isDesktop()) {
      closeMobileSidebar();
      // Keep current session state; no persistence
    } else {
      body.classList.remove("sidebar-collapsed");
    }
  });

  // Initial state: always start expanded on desktop (no persistence)
  if (isDesktop()) applyDefaultExpanded();
  updateActiveNavLink();
  updateCollapsedTooltips();
}

function updateActiveNavLink() {
  const currentPage = window.location.pathname.split("/").pop();
  const navLinks = document.querySelectorAll(
    ".sidebar .nav-link, .bottom-nav .nav-link"
  );
  navLinks.forEach(link => {
    link.classList.remove("active");
    const href = link.getAttribute("href");
    if (href && href.includes(currentPage)) link.classList.add("active");
  });
}

// Topbar Initialization
function topbarInit() {
  initSidebar();
  const activeNavLink = document.querySelector(".nav-link.active");
  const pageTitle = document.getElementById("pageTitle");
  if (activeNavLink && pageTitle) {
    const title =
      activeNavLink.getAttribute("data-page") ||
      activeNavLink.textContent.trim();
    pageTitle.textContent = title;
  }
}

// Modal Helpers
function openModal(modalId, data = null) {
  const modal = document.getElementById(modalId);
  if (!modal) return;
  const bsModal = new bootstrap.Modal(modal);
  if (data) populateModalForm(modal, data);
  else clearModalForm(modal);
  bsModal.show();
  return bsModal;
}

function populateModalForm(modal, data) {
  const form = modal.querySelector("form");
  if (!form) return;
  Object.keys(data).forEach(key => {
    const input = form.querySelector(`[name="${key}"]`);
    if (input) {
      if (input.type === "checkbox") input.checked = Boolean(data[key]);
      else input.value = data[key] || "";
    }
  });
}

function clearModalForm(modal) {
  const form = modal.querySelector("form");
  if (form) form.reset();
}

function bindModalForm(modalElement, options = {}) {
  const form = modalElement.querySelector("form");
  if (!form) return;
  const {
    endpoint,
    method = "POST",
    onSuccess = () => {},
    onError = () => {},
  } = options;
  form.addEventListener("submit", async e => {
    e.preventDefault();
    const submitBtn = form.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.disabled = true;
    submitBtn.innerHTML =
      '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';
    try {
      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());
      const response = await apiFetch(endpoint, {
        method,
        body: JSON.stringify(data),
      });
      if (response.success) {
        showToasted("Operation completed successfully!", "success");
        bootstrap.Modal.getInstance(modalElement).hide();
        onSuccess(response);
      } else {
        showToasted(response.message || "Operation failed", "error");
        onError(response);
      }
    } catch (error) {
      showToasted("An error occurred. Please try again.", "error");
      onError(error);
    } finally {
      submitBtn.disabled = false;
      submitBtn.textContent = originalText;
    }
  });
}

// Inline Edit Helper
function inlineEdit(element, options = {}) {
  const { onSave = () => {}, onCancel = () => {}, type = "text" } = options;
  const originalValue = element.textContent.trim();
  const input = document.createElement("input");
  input.type = type;
  input.value = originalValue;
  input.className = "form-control form-control-sm inline-edit";
  element.replaceWith(input);
  input.focus();
  input.select();
  const save = async () => {
    const newValue = input.value.trim();
    if (newValue !== originalValue) {
      try {
        await onSave(newValue);
        element.textContent = newValue;
      } catch (error) {
        element.textContent = originalValue;
        showToasted("Failed to save changes", "error");
      }
    } else {
      element.textContent = originalValue;
    }
    input.replaceWith(element);
  };
  const cancel = () => {
    element.textContent = originalValue;
    input.replaceWith(element);
    onCancel();
  };
  input.addEventListener("blur", save);
  input.addEventListener("keydown", e => {
    if (e.key === "Enter") {
      e.preventDefault();
      save();
    } else if (e.key === "Escape") {
      e.preventDefault();
      cancel();
    }
  });
}

// Pagination Helper
function renderPagination(container, pagination) {
  if (!container || !pagination) return;
  const { page, total_pages } = pagination;
  if (total_pages <= 1) {
    container.innerHTML = "";
    return;
  }
  let html = '<nav><ul class="pagination justify-content-center">';
  if (page > 1)
    html += `<li class="page-item"><a class="page-link" href="#" data-page="${
      page - 1
    }">Previous</a></li>`;
  const startPage = Math.max(1, page - 2);
  const endPage = Math.min(total_pages, page + 2);
  if (startPage > 1) {
    html += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
    if (startPage > 2)
      html +=
        '<li class="page-item disabled"><span class="page-link">...</span></li>';
  }
  for (let i = startPage; i <= endPage; i++) {
    const active = i === page ? "active" : "";
    html += `<li class="page-item ${active}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
  }
  if (endPage < total_pages) {
    if (endPage < total_pages - 1)
      html +=
        '<li class="page-item disabled"><span class="page-link">...</span></li>';
    html += `<li class="page-item"><a class="page-link" href="#" data-page="${total_pages}">${total_pages}</a></li>`;
  }
  if (page < total_pages)
    html += `<li class="page-item"><a class="page-link" href="#" data-page="${
      page + 1
    }">Next</a></li>`;
  html += "</ul></nav>";
  container.innerHTML = html;
  container.querySelectorAll(".page-link[data-page]").forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();
      const newPage = parseInt(link.getAttribute("data-page"));
      if (newPage !== page)
        window.dispatchEvent(
          new CustomEvent("pageChange", { detail: { page: newPage } })
        );
    });
  });
}

// Loading State Helper
function setLoadingState(element, loading = true) {
  element.classList.toggle("loading", !!loading);
}

// Formatters
function formatCurrency(amount, currency = "₦") {
  return currency + parseFloat(amount || 0).toLocaleString();
}
function formatDate(dateString) {
  const date = new Date(dateString);
  return date.toLocaleDateString("en-US", {
    year: "numeric",
    month: "short",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  });
}

// Debounce Helper
function debounce(func, wait) {
  let timeout;
  return function executedFunction(...args) {
    const later = () => {
      clearTimeout(timeout);
      func(...args);
    };
    clearTimeout(timeout);
    timeout = setTimeout(later, wait);
  };
}

// Initialize admin functionality when DOM is loaded
document.addEventListener("DOMContentLoaded", function () {
  topbarInit();
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.map(function (el) {
    return new bootstrap.Tooltip(el);
  });
  const popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  popoverTriggerList.map(function (el) {
    return new bootstrap.Popover(el);
  });
});

// Export functions for global use
window.apiFetch = apiFetch;
window.showToasted = showToasted;
window.topbarInit = topbarInit;
window.openModal = openModal;
window.bindModalForm = bindModalForm;
window.inlineEdit = inlineEdit;
window.renderPagination = renderPagination;
window.setLoadingState = setLoadingState;
window.formatCurrency = formatCurrency;
window.formatDate = formatDate;
window.debounce = debounce;
