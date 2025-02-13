<?php
$title = "Login";
require __DIR__ . '/../../partials/header.php';
?>

<body>
    <?php #require __DIR__ . '/../../partials/navbar.php'; 
    ?>
    <main class="container">
        <div class="row">
            <div class="col-md-8 mx-auto mt-4 shadow-md border-0">
                <div class="card">
                    <div class="card-body">
                        <form action="process.php" method="post">
                            <div class="">
                                <h4>Account Login</h4>
                                <p>Welcome back! Enter your details to login.</p>
                            </div>
                            <div class="form-group">
                                <label for="username or email">Username or Phone Number</label>
                                <input type="text" name="user_id" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>

                        </form>
                    </div>
                </div>
            </div>
        </div>
    </main>
</body>