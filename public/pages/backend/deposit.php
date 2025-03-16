<?php
session_start();
require __DIR__ . '/../../partials/header.php';
require __DIR__ . '/../../../config/config.php';

$public_key = htmlspecialchars($_ENV['FLW_PUBLIC_KEY']);
$email = htmlspecialchars($_SESSION['user']['email']);
$phone_number = htmlspecialchars($_SESSION['user']['phone_number']);
$names = htmlspecialchars($_SESSION['user']['first_name'] . "&nbsp;" . $_SESSION['user']['last_name']);
?>

<body>

    <main class="container-fluid py-4">
        <header class="d-flex justify-content-between align-items-start mb-4">
            <a href="dashboard.php" class="btn btn-secondary">Back</a>
        </header>

        <div class="box-shadow container">
            <h2 class="text-center mb-4">Fund Your Wallet</h2>

            <form id="depositForm">
                <div class="form-group">
                    <label for="amount">Enter Amount (₦)</label>
                    <input type="number" name="amount" id="amount" class="input" placeholder="Minimum ₦100" min="100">
                    <span class="error-label text-center" id="amount-error"></span>
                </div>

                <div class="mb-3">
                    <button type="submit" class="btn primary-btn w-100">
                        <i class="fa fa-spinner fa-spin d-none" id="spinner-icon"></i>
                        Proceed to Payment
                    </button>
                </div>
                <div class="text-center text-secondary text-sm">
                    <span id="connection_status"></span>
                </div>
            </form>
        </div>

        <nav class="mobile-nav mt-4">
            <ul class="nav justify-content-around">
                <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-home"></i></a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-wallet"></i></a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-list"></i></a></li>
                <li class="nav-item"><a href="#" class="nav-link"><i class="fa fa-cog"></i></a></li>
            </ul>
        </nav>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
        <script src="https://checkout.flutterwave.com/v3.js"></script>

        <script>
            window.addEventListener('DOMContentLoaded', function() {
                const connectionStatus = document.getElementById('connection_status');
                const spinner = document.getElementById('spinner-icon');
                const errorLabel = document.getElementById('amount-error');

                // Check Internet Connection
                if (connectionStatus) {
                    if (!navigator.onLine) {
                        connectionStatus.textContent = "You are currently offline. Check your internet connection.";
                        console.log("You are currently offline.");
                        return;
                    }
                }

                // Form Submission Event
                document.getElementById("depositForm").addEventListener("submit", function(e) {
                    e.preventDefault();

                    const FLW_PUBLIC_KEY = <?= json_encode($public_key); ?>;
                    const userEmail = <?= json_encode($email); ?>;
                    const userPhone = <?= json_encode($phone_number); ?>;
                    const userName = <?= json_encode($names); ?>;
                    const amount = document.getElementById("amount").value;

                    // Validate Amount
                    if (!amount || isNaN(amount) || amount < 100) {
                        if (errorLabel) {
                            errorLabel.textContent = "Please enter a valid amount (minimum ₦100).";
                        }
                        return;
                    }

                    // Check if Flutterwave script is loaded
                    if (typeof FlutterwaveCheckout !== "function") {
                        if (errorLabel) {
                            errorLabel.textContent = "Flutterwave script not loaded. Check internet connection.";
                        }
                        return;
                    }

                    // Show spinner if available
                    if (spinner) {
                        spinner.classList.remove('d-none');
                    }

                    // Initiate Payment
                    setTimeout(() => {
                        FlutterwaveCheckout({
                            public_key: FLW_PUBLIC_KEY,
                            tx_ref: "DS-" + Date.now(),
                            amount: parseFloat(amount),
                            currency: "NGN",
                            payment_options: "card,banktransfer,ussd",
                            redirect_url: "payment-success.php",
                            customer: {
                                email: userEmail,
                                phone_number: userPhone,
                                name: userName,
                            },
                            callback: function(response) {
                                console.log(response);
                                if (response.status === "completed") {
                                    window.location.href = "payment-success.php?transaction_id=" + response.transaction_id;
                                } else {
                                    alert("Payment failed. Please try again.");
                                }
                            },
                            onclose: function() {
                                window.location.href = 'deposit.php';
                            },
                            customizations: {
                                title: "DataSpeed Wallet Funding",
                                description: "Fund your wallet easily",
                                logo: "../../logo.svg",
                            },
                        });
                    }, 550);
                });
            });
        </script>
    </main>

</body>

</html>