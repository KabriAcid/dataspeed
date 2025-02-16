<?php require __DIR__ . '/../../partials/header.php'; ?>

<body>
    <?php #require __DIR__ . '/../../partials/navbar.php'; 
    ?>

</body>
<main class="container">
    <div class="form-container text-center">
        <div class="form-top-container">
            <a href="">
                <svg width="16" height="15" viewBox="0 0 16 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.60564 1.65147L3.73182 6.5253H16V8.47483H3.73182L8.60564 13.3487L7.22712 14.7272L0 7.50006L7.22712 0.272949L8.60564 1.65147Z" fill="#722F37" />
                </svg>
            </a>
            <div class="pagination">
                <span class="page"></span>
                <span class="page"></span>
                <span class="page"></span>
                <span class="page"></span>
            </div>
        </div>
        <form id="multi-step-form">
            <div class="form-step d-none">
                <h3>What's your email address?</h3>
                <p>You will recieve a verification code so make sure it is active.</p>
                <div class="form-field">
                    <input type="email" name="email" placeholder="Email address" class="input">
                    <label for="" class="error-label"></label>
                    <button type="button" class="btn btn-primary mt-3">Continue</button>
                </div>
            </div>
            <div class="form-step d-none">
                <h3>What's your phone number?</h3>
                <p>Omit the first digit and insert the rest.</p>
                <div class="form-field">
                    <input type="number" name="phone_number" placeholder="Phone Number" class="input">
                    <label for="" class="error-label"></label>
                    <button type="button" class="btn btn-primary mt-3">Continue</button>
                </div>
            </div>
            <div class="form-step">
                <h3>What's your full name?</h3>
                <p>Enter your correct name details.</p>
                <div class="form-field">
                    <input type="text" name="first_name" placeholder="First Name" class="input">
                    <div class="my-3"></div>
                    <input type="text" name="last_name" placeholder="Last Name" class="input">
                    <label for="" class="error-label"></label>
                    <button type="button" class="btn btn-primary mt-3">Continue</button>
                </div>
            </div>
        </form>
    </div>
</main>
<script src="../../assets/js/multi-step.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</html>