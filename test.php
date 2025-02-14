<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - DataSpeed</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@300;400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #f4f4f4;
            font-family: 'Montserrat', sans-serif;
        }

        .login-container {
            max-width: 400px;
            margin: 50px auto;
            background: white;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
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

    <div class="login-container text-center">
        <h3 class="fw-bold">Login to your account</h3>
        <p>Enter your correct details</p>

        <form>
            <div class="mb-3 text-start">
                <label class="form-label fw-bold">Email address or phone number</label>
                <input type="text" class="form-control" placeholder="Email or phone number">
            </div>
            <div class="mb-3 text-start">
                <label class="form-label fw-bold">Password</label>
                <input type="password" class="form-control" placeholder="Password">
                <div class="text-end mt-1">
                    <a href="#" class="text-muted">Forgot password</a>
                </div>
            </div>
            <button type="submit" class="btn btn-primary w-100">Sign in</button>
        </form>

        <p class="mt-3">Don't have an account? <a href="#" class="text-danger">Sign up</a></p>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>