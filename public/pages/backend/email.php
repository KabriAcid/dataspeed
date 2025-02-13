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
               
                <label class="input">Email address</label>
                <input type="email address" name="email address" required placeholder="Enter your valid email address">

               

                <button type="submit">Next</button>
               
                <button type="button" class="btn btn-grey w-100">
                        <img src="data:image/jpeg;base64,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" alt="Google Logo" style="width: 20px; margin-right: 10px;"> 
                        Sign Up With Google
                    </button>
               
            </form>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
