<?php
session_start();
header('Content-Type: application/json');

require __DIR__ . '/../../config/config.php';
// require __DIR__ . '/../../functions/Model.php';

if (!isset($_SESSION['user'])) {
    echo json_encode(["success" => false, "message" => 'Unauthorized']);
    exit;
}

$user_id = $_SESSION['user'];

try {
    if($_SERVER['REQUEST_METHOD'] == 'POST'){
        $pdo->beginTransaction();

        foreach ($_POST as $key => $value) {

            if(!$key){
                echo json_encode(["success" => false, "message" => 'KYC values not set']);
                exit;
            }
            if($key == 'nin'){
                $stmt = $pdo->prepare("UPDATE users SET kyc_value = ?, kyc_type = ?, kyc_status = ? WHERE user_id = ?");
                $stmt->execute([$value, 'NIN', 'verified']);

                // commit
                $pdo->commit();

                echo json_encode(["success" => true, "message" => 'NIN verification successful.', "type" => $value]);
            }
            elseif($key == 'bvn'){
                $stmt = $pdo->prepare("UPDATE users SET kyc_value = ?, kyc_type = ?, kyc_status = ? WHERE user_id = ?");
                $stmt->execute([$value, 'BVN', 'verified']);

                // commit
                $pdo->commit();
                
                echo json_encode(["success" => true, "message" => 'BVN verification successful.', "type" => $value]);
            }
            else {
                $pdo->rollback();
                echo json_encode(["success" => false, "message" => 'KYC verification failed.']);
                exit;
            }
        }
    }

} catch (Exception $e) {
    echo json_encode(["success" => false, "message" => $e->getMessage()]);
    exit;
}
