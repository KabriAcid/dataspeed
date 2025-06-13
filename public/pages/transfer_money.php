<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../partials/header.php';
?>

<body>
    <main class="container py-4">
        <!-- Header Section -->
        <header>
            <div class="page-header mb-4 text-center">
                <svg class="header-back-button" width="8" height="14" viewBox="0 0 8 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7 1L1 7L7 13" stroke="#141C25" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <h5 class="fw-bold text-center">Transfer Money</h5>
                <span></span>
            </div>
        </header>

        <div class="text-center">
            <p class="text-sm text-secondary mb-0">Your available balance:</p>
            <h3>&#8358;<?= getUserBalance($pdo, $user_id) ?></h3>
        </div>
        <div class="text-center">
            <p class="text-secondary mb-3 text-sm text-center">Transfer money to another user on Dataspeed</p>
        </div>
        <div class="form-container">
            <div class="form-row">
                <div class="mb-3">
                    <label for="beneficiary" class="form-label">Beneficiary Email</label>
                    <input type="text" id="email" name="email" class="input" placeholder="Enter beneficiary email" required>
                </div>
                <div class="mb-3">
                    <label for="amount" class="form-label">Amount</label>
                    <div class="input-group-container">
                        <span class="input-group-prefix">
                            &#8358;
                        </span>
                        <input type="text" id="amount" name="amount" maxlength="10"
                            placeholder="Amount" class="input" required inputmode="numeric">
                    </div>
                </div>
                <div class="mb-3">
                    <button type="button" id="transfer-button" class="btn primary-btn w-100">Transfer Money</button>
                </div>
            </div>
        </div>

        <?php require __DIR__ . '/../partials/bottom-nav.php' ?>
    </main>

    <footer class="my-4 text-center text-secondary small">
        &copy; Dreamcodes 2025. All rights reserved.
    </footer>

    <?php require __DIR__ . '/../partials/scripts.php'; ?>
</body>

</html>