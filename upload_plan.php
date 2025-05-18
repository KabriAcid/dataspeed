<?php
// config.php (PDO connection)
$host = 'localhost';
$db   = 'dataspee_db';
$user = 'dataspee_root';
$pass = 'a2d21bcd349e15ec6779039a0aa4f1bc';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$db;charset=utf8mb4", $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("DB Connection Failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $service_id = $_POST['service_id'];
    $name       = $_POST['name'];
    $api_id     = $_POST['api_id'];
    $price      = $_POST['price'];
    $is_active  = $_POST['is_active'];

    $sql = "INSERT INTO service_plans (service_id, name, api_id, price, is_active)
            VALUES (:service_id, :name, :api_id, :price, :is_active)";
    
    $stmt = $pdo->prepare($sql);
    
    try {
        $stmt->execute([
            ':service_id' => $service_id,
            ':name'       => $name,
            ':api_id'     => $api_id,
            ':price'      => $price,
            ':is_active'  => $is_active
        ]);
        echo "<p>✅ Service Plan Added Successfully!</p>";
    } catch (PDOException $e) {
        echo "<p>❌ Error: " . $e->getMessage() . "</p>";
    }
}
?>