<?php
// Insert notification for user in the db
$title = 'Virtual Account Created';
$message = 'Congratulations! Your virtual account has been created successfully.';
pushNotification($pdo, $user_id, $title, $message, 'virtual_account', 'fa-home', false);