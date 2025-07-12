<?php
require __DIR__ . '/../../config/config.php';

session_start();

$token = $_GET['token'] ?? null; // Token passed via query string

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

            <div class="form-group">
                <label for="password">New Password</label>
                <input type="password" name="password" id="password" class="input" required minlength="8">
            </div>

            <div class="form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" name="confirm_password" id="confirm_password" class="input" required minlength="8">
            </div>

            <div class="d-flex justify-content-end">
                <button type="submit" class="btn primary-btn" id="submit_reset">Reset Password</button>
            </div>
        </form>
    </main>

    <script src="../assets/js/ajax.js"></script>
    <script src="../assets/js/reset-password.js"></script>
</body>

</html>