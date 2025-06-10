window.addEventListener("pinAuthenticated", function (e) {
  const pin = e.detail.pin;
  const pinpadModal = document.getElementById("pinpadModal");
  const amount = pinpadModal.dataset.amount;
  const phone = pinpadModal.dataset.phone;
  const network = pinpadModal.dataset.network;
  const type = pinpadModal.dataset.type;

  const data = `pin=${encodeURIComponent(pin)}&amount=${encodeURIComponent(
    amount
  )}&phone=${encodeURIComponent(phone)}&network=${encodeURIComponent(
    network
  )}&type=${encodeURIComponent(type)}`;
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
