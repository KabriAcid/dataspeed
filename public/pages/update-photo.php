<?php
session_start();
require __DIR__ . '/../../config/config.php';
require __DIR__ . '/../../functions/Model.php';
require __DIR__ . '/../../functions/utilities.php';

header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Unknown error'];

if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $fileTmp = $_FILES['photo']['tmp_name'];
    $fileName = basename($_FILES['photo']['name']);
    $targetDir = 'uploads/profile_photos/';
    $targetFile = $targetDir . uniqid() . '-' . $fileName;

    if (move_uploaded_file($fileTmp, $targetFile)) {
        // Update DB or session as needed
        $response = ['success' => true, 'message' => 'Profile photo updated successfully.'];
    } else {
        $response['message'] = 'Failed to save the uploaded file.';
    }
} else {
    $response['message'] = 'No photo uploaded or file error.';
}

header('Content-Type: application/json');
echo json_encode($response);
