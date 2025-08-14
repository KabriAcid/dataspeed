<?php
session_start();
require_once '../../config/config.php';
require_once '../../functions/pricing.php';

header('Content-Type: application/json');

if (!isset($_SESSION['admin_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['plan_id']) || !isset($input['price'])) {
    echo json_encode(['success' => false, 'message' => 'Invalid input']);
    exit;
}

$plan_id = $input['plan_id'];
$price = floatval($input['price']);

if ($price < 0) {
    echo json_encode(['success' => false, 'message' => 'Price cannot be negative']);
    exit;
}

if (updatePlanPrice($plan_id, $price)) {
    echo json_encode(['success' => true, 'message' => 'Price updated successfully']);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to update price']);
}