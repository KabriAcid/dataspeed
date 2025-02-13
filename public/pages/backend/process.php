<?php
session_start();
require __DIR__ . '/../../../config/config.php';

if(isset($_POST['register'])){
    $first_name = $_POST['first_name'];
    $last_name = $_POST['last_name'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Check if email already exists
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    $result = $stmt->fetchAll();

    if(count($result) > 0){
        echo "Email already taken";
        exit();
    }

    // Prepare INSERT statement with named parameters
    $stmt = $conn->prepare("INSERT INTO users (first_name, last_name, email, password) VALUES (:first_name, :last_name, :email, :password)");
    
    // Bind parameters and execute
    if($stmt->execute([
        ':first_name' => $first_name,
        ':last_name'  => $last_name,
        ':email'      => $email,
        ':password'   => $password
    ])){
        header("Location: login.php");
    } else {
        echo "Failed";
    }
}

if (isset($_POST['login'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $sql = "SELECT * FROM users WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->execute([':email' => $email]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row) {
        // Here, you may want to verify the password using password_verify() if hashed.
        $_SESSION['loggedin'] = $row;
        header("Location: dashboard.php");
    } else {
        echo "Email doesn't exist";
    }
}
?>
