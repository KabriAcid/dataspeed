function sendAjaxRequest(url, method, data, callback) {
    if (!navigator.onLine) {
        callback({
            success: false,
            message: "You are offline. Please check your internet connection."
        });
        return;
    }

    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onreadystatechange = function () {
        if (xhr.readyState === 4) {
            if (xhr.status === 0) {
                callback({
                    success: false,
                    message: "Request failed. You may be offline or the server is unreachable."
                });
            } else {
                try {
                    const response = JSON.parse(xhr.responseText);
                    callback(response);
                } catch (error) {
                    callback({
                        success: false,
                        message: "Invalid JSON response"
                    });
                }
            }
        }
    };


    xhr.onerror = function () {
        callback({
            success: false,
            message: "An error occurred during the request. Please check your internet connection."
        });
    };

    xhr.ontimeout = function () {
        callback({
            success: false,
            message: "Request timed out. Please check your internet connection and try again."
        });
    };

    xhr.timeout = 10000; // Optional: set timeout to 10 seconds
    xhr.send(data);
}