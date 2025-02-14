<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - DataSpeed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Montserrat', sans-serif;
        }

        .form-container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            position: relative;
        }

        .btn-primary {
            background-color: #8B2E22;
            border-color: #8B2E22;
        }

        .btn-primary:hover {
            background-color: #6E241A;
            border-color: #6E241A;
        }

        .nav-link {
            color: black !important;
            font-weight: 500;
        }

        .nav-link:hover {
            color: #8B2E22 !important;
        }

        a {
            text-decoration: none;
        }

        .nav-buttons {
            display: flex;
        }

        @media (max-width: 992px) {
            .nav-buttons {
                display: none;
            }
        }

        .error {
            border: 2px solid red !important;
        }

        .error-text {
            color: red;
            font-size: 14px;
        }

        .step-indicator {
            position: absolute;
            top: 10px;
            right: 15px;
            font-weight: bold;
            color: #6E241A;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light px-4">
        <a class="navbar-brand text-danger fw-bold" href="#">DataSpeed</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item"><a class="nav-link" href="#">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="#">Pricing</a></li>
            </ul>
        </div>
        <div class="nav-buttons">
            <a href="#" class="btn btn-outline-dark me-2">Login</a>
            <a href="#" class="btn btn-danger">Create an account</a>
        </div>
    </nav>

    <div class="form-container text-center">
        <span class="step-indicator" id="stepIndicator">Step 1/3</span>
        <h3 class="fw-bold">Create an Account</h3>
        <p>Fill in the details below</p>

        <form id="multiStepForm">
            <div class="step" id="step1">
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Email Address</label>
                    <input type="email" class="form-control" id="email" placeholder="Enter email" required>
                    <small id="emailError" class="error-text d-none">Invalid email address.</small>
                </div>
                <button type="button" class="btn btn-primary w-100" onclick="validateEmail()">Continue</button>
            </div>

            <div class="step d-none" id="step2">
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Phone Number</label>
                    <input type="tel" class="form-control" placeholder="Enter phone number" required>
                </div>
                <button type="button" class="btn btn-primary w-100" onclick="nextStep(3)">Continue</button>
            </div>

            <div class="step d-none" id="step3">
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">First Name</label>
                    <input type="text" class="form-control" placeholder="Enter first name" required>
                </div>
                <div class="mb-3 text-start">
                    <label class="form-label fw-bold">Last Name</label>
                    <input type="text" class="form-control" placeholder="Enter last name" required>
                </div>
                <button type="submit" class="btn btn-success w-100">Submit</button>
            </div>
        </form>
    </div>

    <script>
        function nextStep(step) {
            document.querySelectorAll('.step').forEach(s => s.classList.add('d-none'));
            document.getElementById('step' + step).classList.remove('d-none');
            document.getElementById('stepIndicator').textContent = `Step ${step}/3`;
        }

        function validateEmail() {
            const email = document.getElementById('email').value;
            const emailError = document.getElementById('emailError');
            const emailField = document.getElementById('email');

            fetch('validate_email.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/x-www-form-urlencoded'
                    },
                    body: `email=${email}`
                })
                .then(response => response.text())
                .then(data => {
                    if (data === 'valid') {
                        nextStep(2);
                    } else {
                        emailField.classList.add('error');
                        emailError.classList.remove('d-none');
                    }
                });
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>