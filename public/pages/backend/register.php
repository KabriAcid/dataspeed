<?php require __DIR__ . '/../../partials/header.php'; ?>

<body>
    <?php require __DIR__ . '/../../partials/navbar.php'; ?>

</body>
<main class="container">
    <form class="form-card" action="/register" method="post">
        <div id="pagination">
            <span class="text-right d-block">1 / 2</span>
        </div>
        <div style="margin-top: 30px;width: 100%;">
            <h3>Create an account</h3>
            <p>Set up your accounts in seconds.</p>
        </div>
        <div id="form-field">
            <label for="email" class="strong-color">Email address</label>
            <input type="email" name="email" class="input" placeholder="Email">
            <button type="submit" name="submit" class="btn submit-btn btn-primary w-100" style="margin-top: 20px;width: 100%;">Submit</button>
        </div>
    </form>
</main>

</html>