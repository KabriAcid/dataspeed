<?php

function authenticate_ebills($username, $password)
{
    $url = "https://ebills.africa/wp-json/jwt-auth/v1/token";
    $data = json_encode([
        "username" => $username,
        "password" => $password
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ["Content-Type: application/json"]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_POST, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

function get_wallet_balance($jwt_token)
{
    $url = "https://ebills.africa/wp-json/api/v2/balance";

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $jwt_token"
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $response = curl_exec($ch);
    curl_close($ch);

    return json_decode($response, true);
}

// Example usage
$auth = authenticate_ebills("dataspeedcontact@gmail.com", "@Abba2835");
if (isset($auth["token"])) {
    $token = $auth["token"];
    $wallet = get_wallet_balance($token);
    echo json_encode($wallet, JSON_PRETTY_PRINT);
} else {
    echo "Authentication failed.";
}
