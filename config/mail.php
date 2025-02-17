<?php

require __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;

// Load .env file
$dotenv = Dotenv::createImmutable(__DIR__ . '/../');
$dotenv->safeLoad(); // `safeLoad()` prevents errors if .env file is missing

// Return SMTP configuration
return [
    "host"        => $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com',
    "port"        => $_ENV['MAIL_PORT'] ?? 587,
    "username"    => $_ENV['MAIL_USERNAME'] ?? 'your-email@example.com',
    "password"    => $_ENV['MAIL_PASSWORD'] ?? 'your-app-password',
    "encryption"  => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
    "from_email"  => $_ENV['MAIL_FROM_ADDRESS'] ?? 'dataspeed@gmail.com',
    "from_name"   => $_ENV['MAIL_FROM_NAME'] ?? 'DataSpeed',
];
