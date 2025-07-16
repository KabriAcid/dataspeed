<?php
require __DIR__ . '/../../config/config.php';

session_start();

$token = $_GET['token'] ?? null;

if (!$token) {
    echo "<p>Invalid request. Token is required.</p>";
    exit;
}

try {
    // Validate the token
    $stmt = $pdo->prepare("SELECT user_id, expires_at FROM account_reset_tokens WHERE token = ?");
    $stmt->execute([$token]);
    $tokenData = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$tokenData) {
        echo "<p>Invalid or expired token.</p>";
        exit;
    }

    // Check if the token has expired
    if (strtotime($tokenData['expires_at']) < time()) {
        echo "<p>Token has expired.</p>";
        exit;
    }

    $user_id = $tokenData['user_id'];
} catch (Exception $e) {
    echo "<p>Database error: " . $e->getMessage() . "</p>";
    exit;
}

function set_title($title = null)
{
    $default = "DataSpeed";
    return htmlspecialchars($title ?: $default);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title is set dynamically -->
    <title><?= set_title('Reset Password' ?? null) ?></title>
    <link rel="shortcut icon" href="../favicon.png" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">

    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <!-- Font Awesome for icons -->
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet">

    <!-- Lottie Animations -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <!-- <script src="../assets/js/lottie-player.js"></script> -->

    <!-- Toasted JS for notifications -->
    <link rel="stylesheet" href="../assets/css/toasted.css" />
    <script src="../assets/js/toasted.js"></script>


    <link rel="stylesheet" href="../assets/css/soft-design-system-pro.min3f71.css">
    <link rel="stylesheet" href="../assets/css/style.css">

</head>


<body>
    <main class="container py-4">
        <h3 class="text-center">Reset Your Password</h3>
        <p class="text-center">Enter your new password below.</p>

        <form id="resetPasswordForm" method="POST" class="form-container">
            <input type="hidden" name="token" value="<?php echo htmlspecialchars($token); ?>">
            <!--  -->
            <div class="form-group">
                <label for="password" class="form-label">New Password</label>
                <div class="mb-3 password-wrapper">
                    <input type="password" placeholder="Password" id="password" name="password" class="input" minlength="6">
                    <button type="button" class="eye-button" aria-label="Toggle visibility"><span class="eye-icon"></span></button>
                </div>
            </div>
            <div class="form-group">
                <label for="confirm_password" class="form-label">Confirm Password</label>
                <div class="mb-3 password-wrapper">
                    <input type="password" placeholder="Re-Password" id="confirm_password" name="confirm_password" class="input" minlength="6">
                    <button type="button" class="eye-button" aria-label="Toggle visibility"><span class="eye-icon"></span></button>
                </div>
            </div>
            <!--  -->
            <div class="d-flex justify-content-end">
                <button type="submit" class="btn primary-btn" id="submit_reset">Reset Password</button>
            </div>
        </form>

        <!-- Overlay -->
        <div id="bodyOverlay" class="body-overlay" style="display: none;">
            <div class="overlay-spinner"></div>
        </div>
        
    </main>
    <?php require __DIR__ . '/../partials/mini-footer.php' ?>
    <script src="../assets/js/toggle-password.js"></script>
    <script>
        document.getElementById("resetPasswordForm").addEventListener("submit", function(e) {
            e.preventDefault();

            const password = document.getElementById("password").value.trim();
            const confirmPassword = document.getElementById("confirm_password").value.trim();

            // if both fields are empty
            if (password == "" || confirmPassword == "") {
                showToasted("Please fill in both password fields.", "error");
                return;
            }

            if (password.length < 6 || confirmPassword.length < 6) {
                showToasted("Password must be at least 6 characters long.", "error");
                return;
            }

            // Validate matching passwords
            if (password !== confirmPassword) {
                showToasted("Passwords do not match.", "error");
                return;
            }

            const formData = new FormData(this);
            const bodyOverlay = document.getElementById("bodyOverlay");

            // Show overlay spinner
            bodyOverlay.style.display = "flex";

            fetch("api_account_unlock.php", {
                    method: "POST",
                    body: formData,
                })
                .then((response) => response.json())
                .then((data) => {
                    // Hide overlay spinner
                    bodyOverlay.style.display = "none";

                    if (data.success) {
                        showToasted(data.message, "success");
                        setTimeout(() => {
                            window.location.href = "login.php";
                        }, 1500);
                    } else {
                        showToasted(data.message, "error");
                    }
                })
                .catch((error) => {
                    // Hide overlay spinner
                    bodyOverlay.style.display = "none";
                    showToasted("An error occurred. Please try again.", "error");
                    console.error("Error:", error);
                });
        });
    </script>
</body>

</html>