<?php
require_once 'config/config.php'; // your DB connection file

// Validate required POST fields
if($_SERVER['REQUEST_METHOD'] == 'POST'){
    $required_fields = [
    'service_id',
    'provider_id',
    'service_name',
    'variation_code',
    'name',
    'variation_amount',
    'fixed_price'
];

foreach ($required_fields as $field) {
    if (!isset($_POST[$field]) || ($_POST[$field] === "" && $_POST[$field] !== "0")) {
        $message = "Error: Missing required field '$field'.";
        echo $message;
        exit;
    }
}

// Sanitize and validate inputs
$service_id        = htmlspecialchars(trim($_POST['service_id']));
$provider_id       = filter_var($_POST['provider_id'], FILTER_VALIDATE_INT);
$service_name      = htmlspecialchars(trim($_POST['service_name']));
$convenience_fee   = isset($_POST['convenience_fee']) ? htmlspecialchars(trim($_POST['convenience_fee'])) : null;
$variation_code    = htmlspecialchars(trim($_POST['variation_code']));
$name              = htmlspecialchars(trim($_POST['name']));
$variation_amount  = filter_var($_POST['variation_amount'], FILTER_VALIDATE_FLOAT);
$fixed_price       = ($_POST['fixed_price'] === "1") ? 1 : 0;
$validity          = isset($_POST['validity']) ? htmlspecialchars(trim($_POST['validity'])) : null;

// Validate numeric inputs
if ($provider_id === false || $variation_amount === false) {
    $message = "Error: Invalid number format for provider ID or variation amount.";
    echo $message;
    exit;
}

// Insert into database
$sql = "INSERT INTO variations (
            service_id, provider_id, service_name, convenience_fee,
            variation_code, name, variation_amount, fixed_price, validity, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

try {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        $service_id,
        $provider_id,
        $service_name,
        $convenience_fee,
        $variation_code,
        $name,
        $variation_amount,
        $fixed_price,
        $validity
    ]);

    $message = "✅ Variation inserted successfully.";
} catch (PDOException $e) {
    if ($e->errorInfo[1] == 1062) {
        $message = "⚠️ Duplicate entry: This variation code already exists for this service.";
    } else {
        $message = "❌ Error inserting variation: " . $e->getMessage();
    }
}

echo $message;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manual VTpass Variation Upload</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
    <style>
        button {
            background-color: #0076d1;
            color: white;
        }
    </style>
</head>

<body>
    <h2>Manually Upload a VTpass Variation</h2>

    <?php echo isset($message) ? $message : ''; ?>

    <form method="POST" action="">
        <label for="service_id">Service</label>
        <select id="service_id" name="service_id" required>
            <option value="">-- Select Service --</option>
            <option value="1">Data</option>
            <option value="3">TV</option>
            <option value="4">Electricity</option>
        </select>

        <label for="provider_id">Provider</label>
        <select id="provider_id" name="provider_id" required>
            <option value="">-- Select Provider --</option>
            <option value="1">MTN</option>
            <option value="2">Airtel</option>
            <option value="3">Glo</option>
            <option value="4">9mobile</option>
            <option value="5">DSTV</option>
            <option value="6">GOTV</option>
            <option value="7">Startimes</option>
            <option value="8">Showmax</option>
        </select>

        <label for="service_name">Service Name</label>
        <input type="text" id="service_name" name="service_name" required>

        <label for="convenience_fee">Convenience Fee</label>
        <input type="text" id="convenience_fee" name="convenience_fee">

        <label for="variation_code">Variation Code</label>
        <input type="text" id="variation_code" name="variation_code" required>

        <label for="name">Variation Name</label>
        <input type="text" id="name" name="name" required>

        <label for="variation_amount">Variation Amount (₦)</label>
        <input type="number" step="0.01" id="variation_amount" name="variation_amount" required>

        <fieldset>
            <legend>Fixed Price?</legend>
            <label>
                <input type="radio" name="fixed_price" value="1" required>
                Yes
            </label>
            <label>
                <input type="radio" name="fixed_price" value="0" required>
                No
            </label>
        </fieldset>

        <label for="validity">Validity (e.g., 1 Month, 7 Days)</label>
        <input type="text" id="validity" name="validity">

        <button type="submit">Save Variation</button>
    </form>
</body>

</html>