// API Helper Function
async function apiFetch(url, options = {}) {
  const defaultOptions = {
    method: "GET",
    headers: {
      "Content-Type": "application/json",
    },
  };

  const config = { ...defaultOptions, ...options };

  try {
    const response = await fetch(url, config);
    const data = await response.json();

    if (!response.ok) {
      throw new Error(data.message || "Request failed");
    }

    return data;
  } catch (error) {
    console.error("API Error:", error);
    throw error;
  }
}

// Sidebar Management
function initSidebar() {
  const sidebar = document.getElementById("sidebar");
  const sidebarToggle = document.getElementById("sidebarToggle");
  const sidebarCollapse = document.getElementById("sidebarCollapse");
  const sidebarOverlay = document.getElementById("sidebarOverlay");
  const body = document.body;

  if (!sidebar || !sidebarToggle) return;

  // Toggle sidebar
  sidebarToggle.addEventListener("click", () => {
    sidebar.classList.toggle("open");
    sidebarOverlay.classList.toggle("show");
    body.classList.toggle("sidebar-open");
  });

  // Desktop collapse/expand with persistence
  if (sidebarCollapse) {
    // Load saved state
    const savedCollapsed = localStorage.getItem("admin.sidebarCollapsed");
    if (savedCollapsed === "true") {
      sidebar.classList.add("collapsed");
    }

    sidebarCollapse.addEventListener("click", () => {
      sidebar.classList.toggle("collapsed");
      const isCollapsed = sidebar.classList.contains("collapsed");
      localStorage.setItem(
        "admin.sidebarCollapsed",
        isCollapsed ? "true" : "false"
      );
      if (isCollapsed) {
        body.classList.add("sidebar-collapsed");
      } else {
        body.classList.remove("sidebar-collapsed");
      }
      updateCollapsedTooltips();
    });
  }

  // Close sidebar when clicking overlay
  if (sidebarOverlay) {
    sidebarOverlay.addEventListener("click", () => {
      sidebar.classList.remove("open");
      sidebarOverlay.classList.remove("show");
      body.classList.remove("sidebar-open");
    });
  }

  // Handle window resize
  window.addEventListener("resize", () => {
    if (window.innerWidth >= 992) {
      sidebar.classList.remove("open");
      sidebarOverlay.classList.remove("show");
      body.classList.add("sidebar-open");
    } else {
      body.classList.remove("sidebar-open");
    }
  });

  // Set initial state
  if (window.innerWidth >= 992) {
    body.classList.add("sidebar-open");
    if (sidebar.classList.contains("collapsed")) {
      body.classList.add("sidebar-collapsed");
    }
  }

  // Update active nav link
  updateActiveNavLink();

  // Initialize collapsed tooltips
  function updateCollapsedTooltips() {
    const isCollapsed = sidebar.classList.contains("collapsed");
    document.querySelectorAll(".nav-link").forEach(link => {
      const text = link.querySelector(".nav-text");
      if (isCollapsed && text) {
        link.setAttribute("title", text.textContent.trim());
      } else {
        link.removeAttribute("title");
      }
    });
  }
  updateCollapsedTooltips();
}

function updateActiveNavLink() {
  const currentPage = window.location.pathname.split("/").pop();
  const navLinks = document.querySelectorAll(".nav-link");

  navLinks.forEach(link => {
    link.classList.remove("active");
    const href = link.getAttribute("href");
    if (href && href.includes(currentPage)) {
      link.classList.add("active");
    }
  });
}

// Topbar Initialization
function topbarInit() {
  initSidebar();

  // Set page title from nav link
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

  if (data) {
    populateModalForm(modal, data);
  } else {
    clearModalForm(modal);
  }

  bsModal.show();
  return bsModal;
}

function populateModalForm(modal, data) {
  const form = modal.querySelector("form");
  if (!form) return;

  Object.keys(data).forEach(key => {
    const input = form.querySelector(`[name="${key}"]`);
    if (input) {
      if (input.type === "checkbox") {
        input.checked = Boolean(data[key]);
      } else {
        input.value = data[key] || "";
      }
    }
  });
}

function clearModalForm(modal) {
  const form = modal.querySelector("form");
  if (form) {
    form.reset();
  }
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

    // Show loading state
    submitBtn.disabled = true;
    submitBtn.innerHTML =
      '<span class="spinner-border spinner-border-sm me-2"></span>Saving...';

    try {
      const formData = new FormData(form);
      const data = Object.fromEntries(formData.entries());

      const response = await apiFetch(endpoint, {
        method: method,
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
      // Reset button state
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

  const { page, total_pages, count } = pagination;

  if (total_pages <= 1) {
    container.innerHTML = "";
    return;
  }

  let paginationHtml = '<nav><ul class="pagination justify-content-center">';

  // Previous button
  if (page > 1) {
    paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${
      page - 1
    }">Previous</a></li>`;
  }

  // Page numbers
  const startPage = Math.max(1, page - 2);
  const endPage = Math.min(total_pages, page + 2);

  if (startPage > 1) {
    paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="1">1</a></li>`;
    if (startPage > 2) {
      paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
    }
  }

  for (let i = startPage; i <= endPage; i++) {
    const activeClass = i === page ? "active" : "";
    paginationHtml += `<li class="page-item ${activeClass}"><a class="page-link" href="#" data-page="${i}">${i}</a></li>`;
  }

  if (endPage < total_pages) {
    if (endPage < total_pages - 1) {
      paginationHtml += `<li class="page-item disabled"><span class="page-link">...</span></li>`;
    }
    paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${total_pages}">${total_pages}</a></li>`;
  }

  // Next button
  if (page < total_pages) {
    paginationHtml += `<li class="page-item"><a class="page-link" href="#" data-page="${
      page + 1
    }">Next</a></li>`;
  }

  paginationHtml += "</ul></nav>";
  container.innerHTML = paginationHtml;

  // Bind click events
  container.querySelectorAll(".page-link[data-page]").forEach(link => {
    link.addEventListener("click", e => {
      e.preventDefault();
      const newPage = parseInt(link.getAttribute("data-page"));
      if (newPage !== page) {
        window.dispatchEvent(
          new CustomEvent("pageChange", { detail: { page: newPage } })
        );
      }
    });
  });
}

// Loading State Helper
function setLoadingState(element, loading = true) {
  if (loading) {
    element.classList.add("loading");
  } else {
    element.classList.remove("loading");
  }
}

// Format Currency
function formatCurrency(amount, currency = "₦") {
  return currency + parseFloat(amount || 0).toLocaleString();
}

// Format Date
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
  // Initialize sidebar and topbar
  topbarInit();

  // Initialize tooltips
  const tooltipTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="tooltip"]')
  );
  tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl);
  });

  // Initialize popovers
  const popoverTriggerList = [].slice.call(
    document.querySelectorAll('[data-bs-toggle="popover"]')
  );
  popoverTriggerList.map(function (popoverTriggerEl) {
    return new bootstrap.Popover(popoverTriggerEl);
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
