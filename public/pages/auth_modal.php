<?php
session_start();
require __DIR__ . '/../../functions/utilities.php';
if(!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title is set dynamically -->
    <title>Account Locked</title>
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">

    <!-- Font Awesome for icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet">

    <!-- Lottie Animations -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <!-- Toasted JS for notifications -->
    <link rel="stylesheet" href="../assets/css/toasted.css" />
    <script src="../assets/js/toasted.js"></script>

    <link rel="stylesheet" href="../assets/css/soft-design-system-pro.min3f71.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>
    <main class="container">
        <div class="form-container text-center">
            <?php
            if (isset($_GET['expired']) && $_GET['expired'] == 1) {
                echo "<script>
                        showToasted('Your session has expired. Please login again.', 'info')
                    </script>";
            }
            ?>
            <form method="post" onsubmit="return false;" class="mt-5">
                <div>
                    <h2 class="mb-3 text-center">Session Locked</h2>
                    <p class="text-sm mb-3 text-center">For your security, please re-enter your password to continue.</p>
                    <input type="password" id="reauthPassword" class="input mb-2" placeholder="Password" />
                    <div class="d-flex justify-content-between mt-3">
                        <a href="../../index.php" id="reauthExit" class="btn btn-sm secondary-btn mb-2">Exit</a>
                        <button id="reauthSubmit" class="btn primary-btn mb-2">Unlock</button>
                    </div>
                </div>
            </form>
        </div>
    </main>

    <?php include __DIR__ . '/../partials/mini-footer.php'; ?>

    <!-- Overlay -->
    <div id="bodyOverlay" class="body-overlay" style="display: none;">
        <div class="overlay-spinner"></div>
    </div>

    <script src="../assets/js/toggle-password.js"></script>

    <script>
        const reauthSubmit = document.getElementById("reauthSubmit");
        const reauthPasswordInput = document.getElementById("reauthPassword");
        const bodyOverlay = document.getElementById("bodyOverlay");

        reauthSubmit.addEventListener("click", function() {
            const reauthPassword = reauthPasswordInput.value.trim();

            // Validate password input
            if (!reauthPassword) {
                showToasted("Please enter your password", "info");
                return;
            }

            // Show overlay spinner
            bodyOverlay.style.display = "flex";

            // Send authentication request
            fetch("session-auth.php", {
                    method: "POST",
                    headers: {
                        "Content-Type": "application/x-www-form-urlencoded",
                    },
                    body: `password=${encodeURIComponent(reauthPassword)}`,
                })
                .then((res) => res.json())
                .then((data) => {
                    // Hide overlay spinner
                    bodyOverlay.style.display = "none";

                    if (data.status === "success") {
                        showToasted("Authentication successful", "success");
                        window.location.href = "dashboard.php";
                    } else {
                        showToasted(data.message, "error");
                    }
                })
                .catch((err) => {
                    // Hide overlay spinner
                    bodyOverlay.style.display = "none";
                    showToasted("An error occurred. Please try again.", "error");
                    console.error("Error:", err);
                });
        });
    </script>
</body>

</html>