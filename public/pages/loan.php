<?php
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';
require __DIR__ . '/../partials/initialize.php';
require __DIR__ . '/../partials/header.php';

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Withdraws - Under Maintenance</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link rel="stylesheet" href="../assets/css/style.css">

    <style>
        body {
            background-color: #f8f9fa;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
        }

        .maintenance-box {
            max-width: 500px;
        }

        .maintenance-icon {
            font-size: 80px;
            color: #dc3545;
        }

        .btn {
            background-color: #722f37;
            color: white
        }
    </style>
</head>

<body>
    <div class="maintenance-box">
        <div class="maintenance-icon mb-4">
            🛠️
        </div>
        <h1 class="mb-3">Withdraw Page Under Maintenance</h1>
        <p class="lead">We're currently updating the <strong>Withdraw</strong> section. Please check back shortly while we
            make improvements.</p>
        <a href="dashboard.php" class="btn primary-btn">Go Back Home</a>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>