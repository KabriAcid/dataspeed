<?php
// Check if user session exists
if (isset($_SESSION['user'])) {
    $user_id = $_SESSION['user'];
    $user = getUserInfo($pdo, $user_id);
} else {
    header('Location: login.php');
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($title) ? $title : "DataSpeed" ?></title>
    <link rel="shortcut icon" href="../../logo.svg" type="image/x-icon">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap-grid.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap-utilities.min.css">
    <!-- Add font awesome icons to buttons (note that the fa-spin class rotates the icon) -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../../assets/css/style.css">

    <style>
        body {
            font-family: 'Quicksand' !important;
        }
    </style>

</head>