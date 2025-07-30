<?php
$endpoint = "https://ebills.africa/wp-json/api/v2/variations/data";
$selected_network = $_GET['network'] ?? '';

$response = @file_get_contents($endpoint);
$data = json_decode($response, true);

$networks = [];
$plans = [];

if ($data && isset($data['data'])) {
    foreach ($data['data'] as $plan) {
        $network = strtolower($plan['service_id']);
        $networks[$network] = strtoupper($network);

        $validity = '';
        if (strpos($plan['data_plan'], ' - ') !== false) {
            $parts = explode(' - ', $plan['data_plan']);
            $validity = trim(end($parts));
        }

        $plans[] = [
            'variation_id'   => $plan['variation_id'],
            'service_name'   => $plan['service_name'],
            'service_id'     => $plan['service_id'],
            'data_plan'      => $plan['data_plan'],
            'price'          => $plan['price'],
            'reseller_price' => $plan['reseller_price'],
            'availability'   => $plan['availability'],
            'validity'       => $validity
        ];
    }

    ksort($networks);

    if ($selected_network) {
        $plans = array_filter($plans, function ($plan) use ($selected_network) {
            return strtolower($plan['service_id']) === strtolower($selected_network);
        });
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>eBills Data Plans Viewer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
    <style>
        th, td { vertical-align: middle; }
    </style>
</head>
<body>
    <h2>eBills Africa – Data Plans</h2>

    <form method="GET">
        <label for="network">Filter by Network:</label>
        <select name="network" id="network" onchange="this.form.submit()">
            <option value="">-- All Networks --</option>
            <?php foreach ($networks as $network): ?>
                <option value="<?= $network ?>" <?= $network === $selected_network ? 'selected' : '' ?>>
                    <?= strtoupper($network) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <form method="POST" action="upload-selected-plans.php">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>S/N</th>
                    <th>Service</th>
                    <th>Variation ID</th>
                    <th>Plan</th>
                    <th>Price (₦)</th>
                    <th>Reseller Price (₦)</th>
                    <th>Validity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($plans)): ?>
                    <?php $count = 1; foreach ($plans as $plan): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_plans[]" value='<?= htmlspecialchars(json_encode($plan)) ?>'>
                            </td>
                            <td><?= $count++ ?></td>
                            <td><?= htmlspecialchars($plan['service_name']) ?></td>
                            <td><?= $plan['variation_id'] ?></td>
                            <td><?= htmlspecialchars($plan['data_plan']) ?></td>
                            <td><?= number_format($plan['price']) ?></td>
                            <td><?= number_format($plan['reseller_price']) ?></td>
                            <td><?= htmlspecialchars($plan['validity']) ?></td>
                            <td><?= htmlspecialchars($plan['availability']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr><td colspan="9">No plans found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (!empty($plans)): ?>
            <button type="submit">Upload Selected Plans</button>
        <?php endif; ?>
    </form>

    <script>
        document.getElementById('select-all').addEventListener('change', function () {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected_plans[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>
</html>
