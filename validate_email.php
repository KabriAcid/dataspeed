<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];

    // Basic email format validation
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo "invalid";
        exit;
    }

    // Database connection (update with your DB credentials)
    $conn = new mysqli("localhost", "root", "", "passbank");

    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Check if email already exists
    $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        echo "exists";
    } else {
        echo "valid";
    }

    $stmt->close();
    $conn->close();
}
