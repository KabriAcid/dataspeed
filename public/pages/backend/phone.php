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
            <label class="form-label">Phone Number</label>
    <div class="input-group">
        <span class="input-group-text">
            <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/7/79/Flag_of_Nigeria.svg/32px-Flag_of_Nigeria.svg.png" 
                 alt="Nigeria Flag" width="25">
        </span>
        <input type="tel" id="phone" name="phone" class="form-control" required placeholder="+234">
     
    </div>

              

                <button type="submit">Verify</button>
                
               
            </form>
        </div>
    </main>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
 
</body>
</html>
