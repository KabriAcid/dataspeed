<?php
require_once 'config/config.php'; // your DB connection file

// Validate required POST fields
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $required_fields = [
        'service_id',
        'provider_id',
        'vt_service_name',
        'vt_service_id',
        'variation_code',
        'name',
        'volume',
        'variation_amount',
        'fixed_price',
        'type'
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
    $vt_service_name      = htmlspecialchars(trim($_POST['vt_service_name']));
    $vt_service_id      = htmlspecialchars(trim($_POST['vt_service_id']));
    $convenience_fee   = isset($_POST['convenience_fee']) ? htmlspecialchars(trim($_POST['convenience_fee'])) : null;
    $variation_code    = htmlspecialchars(trim($_POST['variation_code']));
    $name              = htmlspecialchars(trim($_POST['name']));
    $volume           = htmlspecialchars(strtoupper(trim($_POST['volume'])));
    $variation_amount  = filter_var($_POST['variation_amount'], FILTER_VALIDATE_FLOAT);
    $fixed_price       = ($_POST['fixed_price'] === "1") ? 1 : 0;
    $validity          = isset($_POST['validity']) ? htmlspecialchars(trim($_POST['validity'])) : null;
    $type = isset($_POST['type']) ? htmlspecialchars(trim($_POST['type'])) : null;

    // Validate numeric inputs
    if ($provider_id === false || $variation_amount === false) {
        $message = "Error: Invalid number format for provider ID or variation amount.";
        echo $message;
        exit;
    }

    // Insert into database
    $sql = "INSERT INTO variations (
            service_id, provider_id, vt_service_name, vt_service_id, convenience_fee,
            variation_code, name, type, volume, variation_amount, fixed_price, validity, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())";

    try {
        $stmt = $pdo->prepare($sql);
        $stmt->execute([
            $service_id,
            $provider_id,
            $vt_service_name,
            $vt_service_id,
            $convenience_fee,
            $variation_code,
            $name,
            $type,
            $volume,
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
    <form method="POST" action="">
        <label for="service_id">Service</label>
        <select id="service_id" name="service_id" required>
            <option value="">-- Select Service --</option>
            <option value="1" selected>Data</option>
            <option value="3">TV</option>
            <option value="4">Electricity</option>
        </select>

        <label for="provider_id">Provider</label>
        <select id="provider_id" name="provider_id" required>
            <option value="">-- Select Provider --</option>
            <option value="1" selected>MTN</option>
            <option value="2">Airtel</option>
            <option value="3">Glo</option>
            <option value="4">9mobile</option>
            <option value="5">DSTV</option>
            <option value="6">GOTV</option>
            <option value="7">Startimes</option>
            <option value="8">Showmax</option>
        </select>

        <label for="vt_service_name">Service Name</label>
        <input type="text" id="vt_service_name" name="vt_service_name" value="MTN Data" required placeholder="e.g., MTN Data">

        <label for="vt_service_id">VT Service ID</label>
        <input type="text" id="vt_service_id" name="vt_service_id" value="mtn-data" required placeholder="e.g., mtn-data">

        <label for="convenience_fee">Convenience Fee</label>
        <input type="text" id="convenience_fee" name="convenience_fee" value="0" placeholder="e.g., 0">

        <label for="variation_code">Variation Code</label>
        <input type="text" id="variation_code" name="variation_code" required placeholder="e.g., dstv-yanga">

        <label for="name">Variation Name</label>
        <input type="text" id="name" name="name" required placeholder="DStv Yanga N2,565">

        <label for="volume">Volume</label>
        <input type="text" id="volume" name="volume" required placeholder="e.g., 1GB, 2GB, 5GB">

        <label for="type">Type (e.g., weekly, monthly)</label>
        <input type="text" id="type" name="type" placeholder="e.g. weekly">

        <label for="variation_amount">Variation Amount (₦)</label>
        <input type="number" step="0.01" id="variation_amount" name="variation_amount" required placeholder="e.g., 1000" min="0">

        <fieldset>
            <legend>Fixed Price?</legend>
            <label>
                <input checked type="radio" name="fixed_price" value="1" required>
                Yes
            </label>
            <label>
                <input type="radio" name="fixed_price" value="0" required>
                No
            </label>
        </fieldset>

        <label for="validity">Validity</label>
        <input type="text" id="validity" name="validity" placeholder="e.g., 30 Days, 7 Days">

        <button type="submit">Save Variation</button>
    </form>
</body>

</html>