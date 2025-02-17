<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require __DIR__ . '/../vendor/autoload.php';

function sendMail($to, $subject, $otpCode)
{
    $config = require __DIR__ . '/../config/mail.php';

    $mail = new PHPMailer(true);

    try {
        // SMTP Configuration
        $mail->isSMTP();
        $mail->Host = $config["host"];
        $mail->SMTPAuth = true;
        $mail->Username = $config["username"];
        $mail->Password = $config["password"];
        $mail->SMTPSecure = $config["encryption"];
        $mail->Port = $config["port"];

        // Sender & Recipient
        $mail->setFrom($config["from_email"], $config["from_name"]);
        $mail->addAddress($to);

        // Custom Email Body
        $body = "
            <div style='font-family: Arial, sans-serif; max-width: 500px; margin: auto; border: 1px solid #ddd; border-radius: 8px; padding: 20px; text-align: center; background-color: #f9f9f9;'>
                <h2 style='color: #722F37;'>DataSpeed OTP Verification</h2>
                <p style='font-size: 16px; color: #555;'>Use the OTP code below to verify your email:</p>
                <h1 style='font-size: 28px; font-weight: bold; background: #722F37; color: #fff; padding: 10px; border-radius: 5px; display: inline-block;'>$otpCode</h1>
                <p style='color: #888; font-size: 14px;'>This OTP is valid for 5 minutes. Do not share it with anyone.</p>
                <hr style='margin: 20px 0; border: none; border-top: 1px solid #ddd;'>
                <p style='font-size: 12px; color: #999;'>If you did not request this, please ignore this email.</p>
            </div>
        ";

        // Content
        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;

        return $mail->send();
    } catch (Exception $e) {
        return false;
    }
}
