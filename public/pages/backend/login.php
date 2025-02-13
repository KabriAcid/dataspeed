<?php
session_start();
include '../../../config/config.php';// Database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email_or_phone = $_POST["email_or_phone"];
    $password = $_POST["password"];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ? OR phone = ?");
    $stmt->execute([$email_or_phone, $email_or_phone]);
    $user = $stmt->fetch();

    if ($user && password_verify($password, $user["password"])) {
        $_SESSION["user_id"] = $user["id"];
        header("Location: dashboard.php"); // Redirect to user dashboard
        exit();
    } else {
        echo "<script>alert('Invalid email/phone or password'); window.location.href='index.html';</script>";
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataSpeed - Login</title>
    <link rel="stylesheet" href="../../assets/css/style.css">
    
</head>
<body>

    <?php include '../../partials/header.php'; ?>

    <main>
        <div class="login-container">
            <h2>Login to your account</h2>
            <p>Enter your correct details</p>
             

            <form action="login.php" method="POST">
                <label class="input">Email address or phone number</label>
                <input type="text" name="email_or_phone" required placeholder="Email or phone number">

                <label class="input">Password</label>
                <input type="password" name="password" required placeholder="Password">

                <div class="forgot-password">
                    <a href="#" class="link">Forgot password?</a>
                </div>

                <button type="submit">Sign in</button>
                
                <p class="signup-link">Don't have an account? <a href="register.html" class="link">Sign up</a></p>
            </form>
        </div>
    </main>

</body>
</html>
