window.addEventListener("pinAuthenticated", function (e) {
  const pin = e.detail.pin;
  // Now process the purchase with all the needed data
  const data = `pin=${encodeURIComponent(pin)}&amount=${encodeURIComponent(
    selectedPlan.price
  )}&phone=${encodeURIComponent(phone)}&network=${encodeURIComponent(
    selectedNetwork
  )}&type=${encodeURIComponent(
    selectedPlan.volume + " (" + selectedPlan.validity + ")"
  )}`;
  sendAjaxRequest("process-purchase.php", "POST", data, function (response) {
    if (response.success) {
      showToasted(response.message, "success");
      setTimeout(() => {
        window.location.href = "transaction-successful.php";
      }, 1200);
    } else {
      showToasted(response.message, "error");
    }
  });
});
