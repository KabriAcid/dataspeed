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
        <div class="form-container container">
            <form action="" method="post">
                <h2 class="text-center mb-4">Buy Airtime</h2>
                <!-- Network Selection -->
                <div class="mb-3">
                    <label for="network">Select Network</label>
                    <select name="network" id="network" class="input" required>
                        <option value="MTN">MTN</option>
                        <option value="Airtel">Airtel</option>
                        <option value="9Mobile">9Mobile</option>
                        <option value="Glo">Glo</option>
                    </select>
                </div>

                <!-- Phone Number -->
                <div class="mb-3">
                    <label for="phone_number">Phone Number</label>
                    <input type="tel" name="phone_number" id="phone_number" class="input" placeholder="Phone Number" required maxlength="11">
                </div>

                <!-- Amount -->
                <div class="mb-3">
                    <label for="amount">Amount</label>
                    <input type="number" name="amount" id="amount" class="input" placeholder="Amount" required min="10" max="10000">
                </div>

                <!-- Transaction Pin -->
                <div class="mb-3">
                    <label for="txn_pin">Transaction Pin</label>
                    <input type="number" name="txn_pin" id="txn_pin" class="input" placeholder="Transaction Pin" required min="1000" max="9999">
                </div>

                <!-- Submit Button -->
                <div class="mb-3">
                    <button type="submit" class="btn primary-btn w-100" name="submit">Purchase</button>
                </div>
            </form>
        </div>
        <!-- Bottom navigation -->
        <?php require __DIR__ . '/../../partials/bottom-nav.php' ?>

        <!-- FontAwesome CDN -->
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>