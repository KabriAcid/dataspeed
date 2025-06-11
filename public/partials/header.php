<?php
// Check if user session exists
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $user = getUserInfo($pdo, $user_id);
} else {
    header('Location: login.php');
    exit; // Always exit after redirect!
}

function set_title($title = null)
{
    $default = "DataSpeed";
    return htmlspecialchars($title ?: $default);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Page title is set dynamically -->
    <title><?= set_title($title ?? null) ?></title>
    <link rel="shortcut icon" href="../logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">

    <!-- Font Awesome for icons -->
    <script src="https://kit.fontawesome.com/42d5adcbca.js" crossorigin="anonymous"></script>

    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet">
    <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet">
    
    <!-- Lottie Animations -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>
    <!-- <script src="../assets/js/lottie-player.js"></script> -->
    
    <!-- Toasted JS for notifications -->
    <link rel="stylesheet" href="../assets/css/toasted.css" />
    <script src="../assets/js/toasted.js"></script>

    <link rel="stylesheet" href="../assets/css/style.css">
</head>