<?php
require __DIR__ . '/../../../config/config.php';
require __DIR__ . '/../../../functions/Model.php';
require __DIR__ . '/../../partials/header.php';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="d-flex justify-content-between align-items-start mb-4">
            <a href="dashboard.php">Back</a>
        </header>
        <div class="form-container container">
            <h2 class="text-center mb-4">Buy data</h2>
            <form id="dataPurchaseForm" action="purchase-data.php" method="post">
                <!-- Network Selection -->
                <div class="form-group">
                    <label for="network">Select Network</label>
                    <select name="network" id="network" class="input" required>
                        <option value="">-- Select Network --</option>
                        <option value="1">MTN</option>
                        <option value="2">Airtel</option>
                        <option value="3">9Mobile</option>
                        <option value="4">Glo</option>
                    </select>
                </div>

                <!-- Data Plan Selection -->
                <div class="form-group">
                    <label for="data_plan">Select Data Plan</label>
                    <select name="data_plan" id="data_plan" class="input" disabled required>
                        <option value="">-- Select Data Plan --</option>
                    </select>
                </div>

                <!-- Phone Number -->
                <div class="form-group">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" name="phone" id="phone_number" class="input" placeholder="Phone Number" required
                        maxlength="11">
                </div>

                <!-- Hidden Ported Number -->
                <input type="hidden" name="ported_number" value="true">

                <!-- Submit Button -->
                <div class="form-group">
                    <button type="submit" class="btn primary-btn w-100" name="submit"
                        onclick="openModal()">Purchase</button>
                </div>
            </form>

        </div>

        <!-- Bottom navigation -->
        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>

        <script>
        document.addEventListener("DOMContentLoaded", function() {
            const networkSelect = document.getElementById("network");
            const dataPlanSelect = document.getElementById("data_plan");

            const dataPlans = {
                "MTN": [{
                        name: "500MB - ₦100",
                        value: "MTN_500MB"
                    },
                    {
                        name: "1GB - ₦300",
                        value: "MTN_1GB"
                    },
                    {
                        name: "2GB - ₦500",
                        value: "MTN_2GB"
                    }
                ],
                "Airtel": [{
                        name: "750MB - ₦200",
                        value: "Airtel_750MB"
                    },
                    {
                        name: "1.5GB - ₦500",
                        value: "Airtel_1.5GB"
                    },
                    {
                        name: "3GB - ₦1000",
                        value: "Airtel_3GB"
                    }
                ],
                "9Mobile": [{
                        name: "500MB - ₦150",
                        value: "9Mobile_500MB"
                    },
                    {
                        name: "1GB - ₦500",
                        value: "9Mobile_1GB"
                    },
                    {
                        name: "2.5GB - ₦1200",
                        value: "9Mobile_2.5GB"
                    }
                ],
                "Glo": [{
                        name: "1GB - ₦300",
                        value: "Glo_1GB"
                    },
                    {
                        name: "3GB - ₦1000",
                        value: "Glo_3GB"
                    },
                    {
                        name: "5GB - ₦1500",
                        value: "Glo_5GB"
                    }
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
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>