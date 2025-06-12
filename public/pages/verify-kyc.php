<?php
session_start();
header('Content-Type: application/json');

require __DIR__ . '/../../config/config.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(["success" => false, "message" => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user_id'];

try {
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        foreach ($_POST as $key => $value) {
            if (!$key) {
                echo json_encode(["success" => false, "message" => 'KYC values not set']);
                exit;
            }

            // NIN VALIDATION
            if ($key == 'nin') {
                // Example: Replace with your actual API endpoint and headers
                $apiUrl = "https://api.example.com/nin/verify";
                $payload = json_encode(["nin" => $value]);
                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Bearer YOUR_API_KEY'
                ]);
                $apiResponse = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode == 200 && $apiResponse) {
                    $data = json_decode($apiResponse, true);
                    if ($data['status'] === 'success') {
                        // Return details for confirmation modal
                        echo json_encode([
                            "success" => true,
                            "type" => "NIN",
                            "details" => $data['data'], // Adjust based on API response structure
                            "message" => "NIN found. Please confirm details."
                        ]);
                        exit;
                    } else {
                        echo json_encode([
                            "success" => false,
                            "message" => $data['message'] ?? 'NIN not found or invalid.'
                        ]);
                        exit;
                    }
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to connect to NIN verification service."
                    ]);
                    exit;
                }
            }

            // BVN VALIDATION
            elseif ($key == 'bvn') {
                // Example: Replace with your actual API endpoint and headers
                $apiUrl = "https://api.example.com/bvn/verify";
                $payload = json_encode(["bvn" => $value]);
                $ch = curl_init($apiUrl);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                curl_setopt($ch, CURLOPT_POST, true);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
                curl_setopt($ch, CURLOPT_HTTPHEADER, [
                    'Content-Type: application/json',
                    'Authorization: Bearer YOUR_API_KEY'
                ]);
                $apiResponse = curl_exec($ch);
                $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                curl_close($ch);

                if ($httpCode == 200 && $apiResponse) {
                    $data = json_decode($apiResponse, true);
                    if ($data['status'] === 'success') {
                        // Return details for confirmation modal
                        echo json_encode([
                            "success" => true,
                            "type" => "BVN",
                            "details" => $data['data'], // Adjust based on API response structure
                            "message" => "BVN found. Please confirm details."
                        ]);
                        exit;
                    } else {
                        echo json_encode([
                            "success" => false,
                            "message" => $data['message'] ?? 'BVN not found or invalid.'
                        ]);
                        exit;
                    }
                } else {
                    echo json_encode([
                        "success" => false,
                        "message" => "Failed to connect to BVN verification service."
                    ]);
                    exit;
                }
            } else {
                echo json_encode(["success" => false, "message" => 'KYC verification failed.']);
                exit;
            }
        }
    }
} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}
