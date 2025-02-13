<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DataSpeed - Register</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">
</head>
<body>
    <?php include '../../partials/header.php'; ?>
    
    <main>
        <div class="login-container">
            <h2>Create an account</h2>
            <p>Set up your account in seconds</p>
             

            <form action="login.php" method="POST">
                <label class="input">Password</label>
                <input type="text" name="password" required placeholder="Password">

                <label class="input">Confirm password</label>
                <input type="Confirm password" name="confirm password" required placeholder="Confirm password">

               

                <button type="submit">Next</button>
                
               
            </form>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
