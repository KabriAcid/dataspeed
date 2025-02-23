<?php

require __DIR__ . '/../../../config/config.php';
header("Content-type: application/json");

session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user = trim($_POST['user']) ?? '';
    $password = trim($_POST['password']) ?? '';

    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user = ? AND password = ?");
        $stmt->execute([$user, $password]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if($user){
            echo json_encode(["success" => true, "message" => "Credentials found."]);
        } else {
            echo json_encode(["success" => false, "message" => "No credentialsss found."]);
        }
    } catch(error){
        echo json_encode(["success" => false, "message" => "No credentials found."]);
    }
}
