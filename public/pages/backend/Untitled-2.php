<?php


$sql = "INSERT INTO users(username, password, email) VALUES (:username, :password, :email)";
$stmt = $pdo->prepare($sql);
// Bind parameters
$stmt->bindValue(':username', $username);
$stmt->bindValue(':password', password_hash($password, PASSWORD_DEFAULT));
$stmt->bindValue(':email', $email);
$stmt->execute();

// Prepare the SQL statement
$stmt->execute([
    ':username' => $username,
    ':password' => $password,
    ':email' => $email
]);


// SELECT
$sql = "SELECT username FROM users WHERE username = :username";
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':username', $username);
$stmt->execute();

$stmt->execute([
    ':username' => $username
]);