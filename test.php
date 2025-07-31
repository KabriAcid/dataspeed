<?php
require __DIR__ . '/functions/sendMail.php';

$host = 'smtp.gmail.com';
$port = 587;

$connection = fsockopen($host, $port, $errno, $errstr, 10);
if ($connection) {
    echo "Connected to SMTP server.";
    fclose($connection);
} else {
    echo "Failed to connect to SMTP server: $errstr ($errno)";
}

if (sendMail('kabriacid01@gmail.com', 'Test Email', '<p>This is a test email.</p>')) {
    echo "Email sent successfully.";
} else {
    error_log("Failed to send email.");
    echo "Failed to send email.";
}
