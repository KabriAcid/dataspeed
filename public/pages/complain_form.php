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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password</title>
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