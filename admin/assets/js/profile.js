// Profile page script: loads admin profile, stats, and wires forms
(function () {
  document.addEventListener("DOMContentLoaded", () => {
    topbarInit();
    loadStats();
    loadProfile();
    bindForms();
    bindAvatarUpload();
    styleTabs();
    // Guard: enforce single active tab-pane to avoid stacking below content
    document.querySelectorAll('[data-bs-toggle="tab"]').forEach(el => {
      el.addEventListener("shown.bs.tab", () => {
        const container = document.querySelector(".tab-content");
        if (!container) return;
        const panes = container.querySelectorAll(".tab-pane");
        // Keep only the currently active .show .active
        const activeId = container.querySelector(".tab-pane.active")?.id;
        panes.forEach(p => {
          if (p.id !== activeId) {
            p.classList.remove("show", "active");
          }
        });
      });
    });
  });

  async function loadStats() {
    try {
      const res = await apiFetch("api/profile.php?action=stats");
      if (res.success) {
        document.getElementById("totalUsers").textContent = (
          res.data.total_users || 0
        ).toLocaleString();
        document.getElementById("totalTransactions").textContent = (
          res.data.total_transactions || 0
        ).toLocaleString();
      }
    } catch (e) {
      console.warn("stats failed", e);
    }
  }

  async function loadProfile() {
    try {
      const res = await apiFetch("api/profile.php?action=get");
      if (!res.success) throw new Error(res.message || "Failed");
      const d = res.data || {};
      // Overview
      setSrc("profileAvatar", d.avatar_url);
      setText(
        "profileName",
        [d.first_name, d.last_name].filter(Boolean).join(" ") || "-"
      );
      setText("profileRole", d.role || "-");
      setText("profileEmail", d.email || "-");
      setText("profilePhone", d.phone || "Not provided");
      setText(
        "profileJoined",
        d.created_at
          ? "Joined " +
              new Date(d.created_at).toLocaleString("en-US", {
                month: "short",
                year: "numeric",
              })
          : "Joined -"
      );
      setText(
        "lastLogin",
        d.last_login ? new Date(d.last_login).toLocaleString() : "-"
      );
      // Form values
      setVal("firstName", d.first_name || "");
      setVal("lastName", d.last_name || "");
      setVal("username", d.username || "");
      setVal("email", d.email || "");
      setVal("phone", d.phone || "");
      setVal("role", d.role || "");
      // Gate Add Admin UI for super admins only (accept common variants)
      const roleLc = (d.role || "").toLowerCase();
      const isSuper = ["super", "superadmin", "super_admin"].includes(roleLc);
      const addTab = document.getElementById("addadmin-tab");
      const addPane = document.getElementById("addadmin");
      if (addTab && addPane) {
        addTab.parentElement.style.display = isSuper ? "" : "none";
        if (!isSuper) {
          addPane.innerHTML =
            '<div class="alert alert-warning">Only super admins can add new admins.</div>';
        }
      }
      setChecked("emailNotifications", !!Number(d.email_notifications));
      setChecked("transactionAlerts", !!Number(d.transaction_alerts));
      setVal("theme", d.theme || "light");
      setVal("language", d.language || "en");
    } catch (e) {
      showToasted("Failed to load profile", "error");
    }
  }

  function bindForms() {
    const personal = document.getElementById("personalInfoForm");
    if (personal) {
      personal.addEventListener("submit", async e => {
        e.preventDefault();
        const payload = formToJSON(personal);
        payload.action = "updatePersonal";
        try {
          const res = await apiFetch("api/profile.php", {
            method: "POST",
            body: JSON.stringify(payload),
          });
          if (res.success) {
            showToasted("Profile updated", "success");
            loadProfile();
          } else {
            showToasted(res.message || "Failed to update", "error");
          }
        } catch (err) {
          showToasted("Failed to update profile", "error");
        }
      });
    }

    const pwd = document.getElementById("passwordForm");
    if (pwd) {
      pwd.addEventListener("submit", async e => {
        e.preventDefault();
        const data = formToJSON(pwd);
        if ((data.new_password || "") !== (data.confirm_password || "")) {
          showToasted("Passwords do not match", "error");
          return;
        }
        data.action = "changePassword";
        try {
          const res = await apiFetch("api/profile.php", {
            method: "POST",
            body: JSON.stringify(data),
          });
          if (res.success) {
            showToasted("Password updated", "success");
            pwd.reset();
          } else {
            showToasted(res.message || "Failed to update password", "error");
          }
        } catch (err) {
          showToasted("Failed to update password", "error");
        }
      });
    }

    const prefs = document.getElementById("preferencesForm");
    if (prefs) {
      prefs.addEventListener("submit", async e => {
        e.preventDefault();
        const data = formToJSON(prefs);
        data.email_notifications = document.getElementById("emailNotifications")
          .checked
          ? 1
          : 0;
        data.transaction_alerts = document.getElementById("transactionAlerts")
          .checked
          ? 1
          : 0;
        data.action = "updatePreferences";
        try {
          const res = await apiFetch("api/profile.php", {
            method: "POST",
            body: JSON.stringify(data),
          });
          if (res.success) {
            showToasted("Preferences saved", "success");
          } else {
            showToasted(res.message || "Failed to save preferences", "error");
          }
        } catch (err) {
          showToasted("Failed to save preferences", "error");
        }
      });
    }

    const addAdmin = document.getElementById("addAdminForm");
    if (addAdmin) {
      addAdmin.addEventListener("submit", async e => {
        e.preventDefault();
        const data = formToJSON(addAdmin);
        data.action = "create";
        try {
          const res = await apiFetch("api/admin.php", {
            method: "POST",
            body: JSON.stringify(data),
          });
          if (res.success) {
            showToasted(res.message || "Admin created", "success");
            addAdmin.reset();
          } else {
            showToasted(res.message || "Failed to create admin", "error");
          }
        } catch (err) {
          // Surface server error message (e.g., Forbidden)
          const msg =
            err && err.message ? err.message : "Failed to create admin";
          showToasted(msg, "error");
        }
      });
    }
  }

  function bindAvatarUpload() {
    const container = document.querySelector(".profile-avatar-container");
    const input = document.getElementById("avatarUpload");
    const img = document.getElementById("profileAvatar");
    if (!container || !input || !img) return;
    container.addEventListener("click", () => input.click());
    input.addEventListener("change", async e => {
      const file = e.target.files && e.target.files[0];
      if (!file) return;
      const fd = new FormData();
      fd.append("avatar", file);
      fd.append("action", "uploadAvatar");
      try {
        const res = await fetch("api/profile.php?action=uploadAvatar", {
          method: "POST",
          body: fd,
        });
        const data = await res.json();
        if (res.ok && data.success) {
          img.src = data.avatar_url;
          showToasted("Avatar updated", "success");
        } else {
          showToasted(data.message || "Failed to update avatar", "error");
        }
      } catch (err) {
        showToasted("Failed to upload avatar", "error");
      }
    });
  }

  // Accent and tab styles will leverage admin.css variables
  function styleTabs() {
    const root = document.documentElement;
    const primary = getComputedStyle(root).getPropertyValue("--primary").trim();
    // Apply dynamic styles if needed
  }

  // Helpers
  function setText(id, val) {
    const el = document.getElementById(id);
    if (el) el.textContent = val;
  }
  function setVal(id, val) {
    const el = document.getElementById(id);
    if (el) el.value = val;
  }
  function setSrc(id, val) {
    const el = document.getElementById(id);
    if (el) el.src = val;
  }
  function setChecked(id, bool) {
    const el = document.getElementById(id);
    if (el) el.checked = !!bool;
  }
  function formToJSON(form) {
    const fd = new FormData(form);
    const obj = {};
    fd.forEach((v, k) => {
      obj[k] = v;
    });
    return obj;
  }
})();
