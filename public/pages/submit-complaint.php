<?php
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $reason = trim($_POST['reason'] ?? '');
    $user_id = $_SESSION['locked_user_id'] ?? null;

    // Validate input
    if (empty($reason)) {
        echo json_encode(["success" => false, "message" => "Please select a reason."]);
        exit;
    }

    if (!$user_id) {
        echo json_encode(["success" => false, "message" => "User session expired. Please log in again."]);
        exit;
    }

    try {
        // Fetch user details
        $stmt = $pdo->prepare("SELECT email, first_name FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            echo json_encode(["success" => false, "message" => "User not found."]);
            exit;
        }

        // Insert the complaint into the database
        $stmt = $pdo->prepare("INSERT INTO account_complaints (user_id, reason, status) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $reason, 'pending']);

        // Generate a secure token for resetting the account
        $token = bin2hex(random_bytes(32));
        $expires_at = date("Y-m-d H:i:s", strtotime("+1 hour"));

        $stmt = $pdo->prepare("INSERT INTO account_reset_tokens (user_id, token, expires_at) VALUES (?, ?, ?)");
        $stmt->execute([$user_id, $token, $expires_at]);

        // Compose the email
        $resetLink = "http://localhost/dataspeed/public/pages/complain_form.php?token=" . urlencode($token);
        $emailContent = "
            <html>
            <head>
                <style>
                    body {
                        font-family: Arial, sans-serif;
                        background-color: #f9f9f9;
                        color: #333;
                        margin: 0;
                        padding: 0;
                    }
                    .email-container {
                        max-width: 600px;
                        margin: 20px auto;
                        background-color: #ffffff;
                        border: 1px solid #ddd;
                        border-radius: 8px;
                        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                        overflow: hidden;
                    }
                    .email-header {
                        background-color: #007bff;
                        color: #ffffff;
                        padding: 20px;
                        text-align: center;
                    }
                    .email-header img {
                        width: 50px;
                        height: 50px;
                        margin-bottom: 10px;
                    }
                    .email-body {
                        padding: 20px;
                    }
                    .email-body h3 {
                        color: #007bff;
                    }
                    .email-body a {
                        display: inline-block;
                        margin-top: 20px;
                        padding: 10px 20px;
                        background-color: #007bff;
                        color: #ffffff;
                        text-decoration: none;
                        border-radius: 5px;
                        font-weight: bold;
                    }
                    .email-body a:hover {
                        background-color: #0056b3;
                    }
                    .email-footer {
                        text-align: center;
                        padding: 10px;
                        font-size: 12px;
                        color: #666;
                        border-top: 1px solid #ddd;
                    }
                </style>
            </head>
            <body>
                <div class='email-container'>
                    <div class='email-header'>
                        <img src='http://localhost/dataspeed/public/favicon.png' alt='DataSpeed Logo'>
                        <h2>DataSpeed Support</h2>
                    </div>
                    <div class='email-body'>
                        <h3>Account Reset Instructions</h3>
                        <p>Dear {$user['first_name']},</p>
                        <p>Your account has been locked due to multiple failed attempts. To reset your account, please click the link below:</p>
                        <p><a href='{$resetLink}'>Reset Your Account</a></p>
                        <p>If you did not request this, please ignore this email.</p>
                        <p>Thank you,<br>DataSpeed Support Team</p>
                    </div>
                    <div class='email-footer'>
                        <p>&copy; " . date("Y") . " DataSpeed. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>
        ";

        // Send the email
        if (sendMail($user['email'], "Account Reset Instructions", $emailContent)) {
            echo json_encode(["success" => true, "message" => "Complaint submitted successfully. Reset instructions sent to your email."]);
        } else {
            error_log("Failed to send email to " . $user['email']);
            echo json_encode(["success" => true, "message" => "Complaint submitted successfully, but failed to send email."]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => "Database error: " . $e->getMessage()]);
    }
} else {
    echo json_encode(["success" => false, "message" => "Invalid request method."]);
}
