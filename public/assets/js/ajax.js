// Constants and reusable functions
const TIMEOUT_DURATION = 30000;

function sendAjaxRequest(url, method, data, callback) {
  // Check network status before sending request
  if (!navigator.onLine) {
    callback({
      success: false,
      message: "No internet connection. Please check your network.",
    });
    return;
  }

  const xhr = new XMLHttpRequest();
  xhr.open(method, url, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.timeout = TIMEOUT_DURATION;

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 0) {
        // Double-check network status here as well
        if (!navigator.onLine) {
          return callback({
            success: false,
            message: "No internet connection. Please check your network.",
          });
        }
        return callback({
          success: false,
          message:
            "Request failed. You may be offline or the server is unreachable.",
        });
      }
      try {
        const response = JSON.parse(xhr.responseText);
        callback(response);
      } catch (error) {
        callback({
          success: false,
          message: "Invalid JSON response.",
        });
        console.error("Invalid JSON response", xhr.responseText);
      }
    }
  };

  xhr.onerror = function () {
    // Check network status on error
    if (!navigator.onLine) {
      callback({
        success: false,
        message: "No internet connection. Please check your network.",
      });
    } else {
      callback({
        success: false,
        message:
          "An error occurred during the request. Please check your internet connection.",
      });
    }
  };

  xhr.ontimeout = function () {
    callback({
      success: false,
      message: "Request timed out. Please try again.",
    });
  };

  xhr.send(data);
}
