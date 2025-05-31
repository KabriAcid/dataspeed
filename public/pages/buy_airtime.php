<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container-fluid py-4">
        <!-- Header Section -->
        <header class="mb-5">
            <h5 class="text-center fw-bold">Buy Airtime</h5>
        </header>
        <div class="quick-amounts d-flex flex-wrap gap-3 mb-3">
            <button class="btn amount-btn">₦100</button>
            <button class="btn amount-btn">₦200</button>
            <button class="btn amount-btn">₦500</button>
            <button class="btn amount-btn">₦1,000</button>
            <button class="btn amount-btn">₦2,000</button>
            <button class="btn amount-btn">₦5,000</button>
        </div>

        <div class="input-group">
            <span class="input-group-text px-4 fw-bolder fs-5">₦</span>
            <input type="text" id="selectedAmount" class="input bg-white form-control fs-6">
        </div>
        <button type="submit" class="btn w-100 mt-3 primary-btn">Purchase</button>


        <!-- Bottom navigation -->
        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>

    </main>
    <script>
    document.querySelectorAll('.amount-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            // Remove active class from all
            document.querySelectorAll('.amount-btn').forEach(b => b.classList.remove('active'));
            // Add to clicked one
            this.classList.add('active');

            // Update hidden input
            document.getElementById('selectedAmount').value = this.textContent;
        });
    });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>