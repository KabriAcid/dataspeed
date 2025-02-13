<?php
$title = "Register";
require __DIR__ . '/../../partials/header.php';
?>

<body>
    <?php require __DIR__ . '/../../partials/navbar.php'; ?>
    <main class="container">
        <form action="process.php" method="post" class="shadow p-5">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="first_name" placeholder="First Name" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="text" name="last_name" placeholder="Last Name" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="email" name="email" placeholder="Email Address" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <div class="form-group">
                        <input type="password" name="password" placeholder="Password" class="form-control">
                    </div>
                </div>
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success" name="register">Submit</button>
                </div>
                <div class="mt-3">
                    <p>Already have an account? <a href="login.php">Login</a></p>
                </div>
            </div>
        </form>
    </main>
</body>