<?php
require __DIR__ . '/functions/sendMail.php';

if (sendMail('kabriacid01@gmail.com', 'Test Email', '<p>This is a test email.</p>')) {
    echo "Email sent successfully.";
} else {
    error_log("Failed to send email.");
    echo "Failed to send email.";
}
