<?php
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../partials/header.php';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="d-flex justify-content-between align-items-start mb-4">
            <a href="dashboard.php">Back</a>
        </header>
        <div class="container">
            <form id="dataPurchaseForm" action="" method="post">
                <!-- Network Selection -->
                <div class="form-group">
                    <label for="network" class="form-label">Select Network</label>
                    <select name="network" id="network" class="input" required>
                        <option value="">-- Select Network --</option>
                        <option value="MTN">MTN</option>
                        <option value="Airtel">Airtel</option>
                        <option value="9Mobile">9Mobile</option>
                        <option value="Glo">Glo</option>
                    </select>
                </div>

                <!-- Data Plan Selection -->
                <div class="form-group">
                    <label for="data_plan" class="form-label">Select Data Plan</label>
                    <select name="data_plan" id="data_plan" class="input" disabled required>
                        <option value="">-- Select Data Plan --</option>
                    </select>
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="tel" name="phone_number" id="phone_number" class="form-control" placeholder="Phone Number" required maxlength="11">
                </div>

                <!-- Amount -->
                <div class="form-group">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" id="amount" class="form-control" placeholder="Amount" required min="50" max="50000">
                </div>

                <!-- Transaction Pin -->
                <div class="form-group">
                    <label for="txn_pin" class="form-label">Transaction Pin</label>
                    <input type="number" name="txn_pin" id="txn_pin" class="form-control" placeholder="Transaction Pin" required min="1000" max="9999">
                </div>

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="btn primary-btn w-100" name="submit">Purchase</button>
                </div>
            </form>
        </div>

        <!-- Bottom navigation -->
        <nav class="mobile-nav">
            <ul>
                <li><a href="#" class="mobile-nav-link" id="home"><i class="fa fa-home"></i></a></li>
                <li><a href="#" class="mobile-nav-link" id="wallet"><i class="fa fa-bell"></i></a></li>
                <li><a href="#" class="mobile-nav-link" id="transactions"><i class="fa fa-list"></i></a></li>
                <li><a href="#" class="mobile-nav-link" id="settings"><i class="fa fa-cog"></i></a></li>
            </ul>
        </nav>

        <script>
            document.addEventListener("DOMContentLoaded", function() {
                const networkSelect = document.getElementById("network");
                const dataPlanSelect = document.getElementById("data_plan");

                const dataPlans = {
                    "MTN": [
                        { name: "500MB - ₦100", value: "MTN_500MB" },
                        { name: "1GB - ₦300", value: "MTN_1GB" },
                        { name: "2GB - ₦500", value: "MTN_2GB" }
                    ],
                    "Airtel": [
                        { name: "750MB - ₦200", value: "Airtel_750MB" },
                        { name: "1.5GB - ₦500", value: "Airtel_1.5GB" },
                        { name: "3GB - ₦1000", value: "Airtel_3GB" }
                    ],
                    "9Mobile": [
                        { name: "500MB - ₦150", value: "9Mobile_500MB" },
                        { name: "1GB - ₦500", value: "9Mobile_1GB" },
                        { name: "2.5GB - ₦1200", value: "9Mobile_2.5GB" }
                    ],
                    "Glo": [
                        { name: "1GB - ₦300", value: "Glo_1GB" },
                        { name: "3GB - ₦1000", value: "Glo_3GB" },
                        { name: "5GB - ₦1500", value: "Glo_5GB" }
                    ]
                };

                networkSelect.addEventListener("change", function() {
                    const selectedNetwork = this.value;
                    dataPlanSelect.innerHTML = '<option value="">-- Select Data Plan --</option>';

                    if (selectedNetwork && dataPlans[selectedNetwork]) {
                        dataPlans[selectedNetwork].forEach(plan => {
                            let option = document.createElement("option");
                            option.value = plan.value;
                            option.textContent = plan.name;
                            dataPlanSelect.appendChild(option);
                        });
                        dataPlanSelect.disabled = false;
                    } else {
                        dataPlanSelect.disabled = true;
                    }
                });

                // Form validation
                document.getElementById("dataPurchaseForm").addEventListener("submit", function(event) {
                    const phone = document.getElementById("phone_number").value;
                    const amount = document.getElementById("amount").value;
                    const txnPin = document.getElementById("txn_pin").value;

                    if (!/^\d{11}$/.test(phone)) {
                        alert("Phone number must be 11 digits.");
                        event.preventDefault();
                        return;
                    }

                    if (amount < 50 || amount > 50000) {
                        alert("Amount must be between ₦50 and ₦50,000.");
                        event.preventDefault();
                        return;
                    }

                    if (!/^\d{4}$/.test(txnPin)) {
                        alert("Transaction Pin must be 4 digits.");
                        event.preventDefault();
                    }
                });
            });
        </script>
        <!-- FontAwesome CDN -->
        <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>