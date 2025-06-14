// Constants and reusable functions
const TIMEOUT_DURATION = 30000;

function sendAjaxRequest(url, method, data, callback) {
  if (!navigator.onLine) {
      showToasted('You are offline. Please check your internet connection.', 'error');
      // return;
  }

  const xhr = new XMLHttpRequest();
  xhr.open(method, url, true);
  xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
  xhr.timeout = TIMEOUT_DURATION;

  xhr.onreadystatechange = function () {
    if (xhr.readyState === 4) {
      if (xhr.status === 0) {
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
    callback({
      success: false,
      message:
        "An error occurred during the request. Please check your internet connection.",
    });
  };

  xhr.ontimeout = function () {
    callback({
      success: false,
      message: "Request timed out. Please try again.",
    });
  };

  xhr.send(data);
}
