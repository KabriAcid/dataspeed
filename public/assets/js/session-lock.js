// Configuration variables for inactivity timeout and check interval
const INACTIVITY_TIMEOUT = 5 * 1000;
const CHECK_INTERVAL = 1000;

let inactivityTimeout;
let inactivityCheckInterval;

function resetInactivityTimer() {
  clearTimeout(inactivityTimeout);
  inactivityTimeout = setTimeout(() => {
    // Redirect to lock screen after inactivity timeout
    window.location.href = "auth_modal.php";
  }, INACTIVITY_TIMEOUT);
}

function startInactivityCheck() {
  inactivityCheckInterval = setInterval(() => {
    fetch("session-auth.php")
      .then((res) => res.json())
      .then((data) => {
        if (data.status === "locked") {
          window.location.href = "auth_modal.php";
        }
      })
      .catch(() => console.error("Failed to check inactivity."));
  }, CHECK_INTERVAL);
}

// Reset the timer on user activity
document.addEventListener("mousemove", resetInactivityTimer);
document.addEventListener("keypress", resetInactivityTimer);
document.addEventListener("click", resetInactivityTimer);

// Initialize the inactivity timer
resetInactivityTimer();

// Start the inactivity check
startInactivityCheck();