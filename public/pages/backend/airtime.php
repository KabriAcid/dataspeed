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
            <form action="" method="post">
                <!-- Network Selection -->
                <div class="mb-3">
                    <label for="network" class="form-label">Select Network</label>
                    <select name="network" id="network" class="input" required>
                        <option value="MTN">MTN</option>
                        <option value="Airtel">Airtel</option>
                        <option value="9Mobile">9Mobile</option>
                        <option value="Glo">Glo</option>
                    </select>
                </div>

                <!-- Phone Number -->
                <div class="mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="tel" name="phone_number" id="phone_number" class="input" placeholder="Phone Number" required maxlength="11">
                </div>

                <!-- Amount -->
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <input type="number" name="amount" id="amount" class="input" placeholder="Amount" required min="10" max="10000">
                </div>

                <!-- Transaction Pin -->
                <div class="mb-3">
                    <label for="txn_pin" class="form-label">Transaction Pin</label>
                    <input type="number" name="txn_pin" id="txn_pin" class="input" placeholder="Transaction Pin" required min="1000" max="9999">
                </div>

                <!-- Submit Button -->
                <div class="mb-3">
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

        <!-- FontAwesome CDN -->
        <script src="https://kit.fontawesome.com/your-kit-id.js" crossorigin="anonymous"></script>
    </main>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>