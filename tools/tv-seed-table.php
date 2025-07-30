<?php
$endpoint = "https://ebills.africa/wp-json/api/v2/variations/tv";
$selected_network = $_GET['network'] ?? '';

$response = @file_get_contents($endpoint);
$data = json_decode($response, true);

$networks = [];
$plans = [];

if ($data && isset($data['data'])) {
    foreach ($data['data'] as $plan) {
        $network = strtolower($plan['service_id']);
        $networks[$network] = strtoupper($network);

        $plans[] = [
            'variation_id'   => $plan['variation_id'],
            'service_name'   => $plan['service_name'],
            'service_id'     => $plan['service_id'],
            'plan_name'      => $plan['package_bouquet'] ?? '', // Use package_bouquet as plan name
            'price'          => isset($plan['price']) ? (float)$plan['price'] : 0,
            'reseller_price' => isset($plan['reseller_price']) ? (float)$plan['reseller_price'] : 0,
            'availability'   => $plan['availability'],
            'validity'       => $plan['validity'] ?? '',
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
    <title>eBills TV Plans Viewer</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/light.css">
    <style>
        th,
        td {
            vertical-align: middle;
        }
    </style>
</head>

<body>
    <h2>eBills Africa – TV Plans</h2>

    <form method="GET">
        <label for="network">Filter by Provider:</label>
        <select name="network" id="network" onchange="this.form.submit()">
            <option value="">-- All Providers --</option>
            <?php foreach ($networks as $network): ?>
                <option value="<?= $network ?>" <?= $network === $selected_network ? 'selected' : '' ?>>
                    <?= strtoupper($network) ?>
                </option>
            <?php endforeach; ?>
        </select>
    </form>

    <form method="POST" action="upload-selected-tv-plans.php">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>S/N</th>
                    <th>Provider</th>
                    <th>Variation ID</th>
                    <th>Plan Name</th>
                    <th>Price (₦)</th>
                    <th>Reseller Price (₦)</th>
                    <th>Validity</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (!empty($plans)): ?>
                    <?php $count = 1;
                    foreach ($plans as $plan): ?>
                        <tr>
                            <td>
                                <input type="checkbox" name="selected_tv_plans[]" value='<?= htmlspecialchars(json_encode($plan)) ?>'>
                            </td>
                            <td><?= $count++ ?></td>
                            <td><?= htmlspecialchars($plan['service_name']) ?></td>
                            <td><?= $plan['variation_id'] ?></td>
                            <td><?= htmlspecialchars($plan['plan_name']) ?></td>
                            <td>&#8358;<?= number_format($plan['price']) ?></td>
                            <td>&#8358;<?= number_format($plan['reseller_price']) ?></td>
                            <td><?= htmlspecialchars($plan['validity']) ?></td>
                            <td><?= htmlspecialchars($plan['availability']) ?></td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="10">No plans found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <?php if (!empty($plans)): ?>
            <button type="submit">Upload Selected TV Plans</button>
        <?php endif; ?>
    </form>

    <script>
        document.getElementById('select-all').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('input[type="checkbox"][name="selected_tv_plans[]"]');
            checkboxes.forEach(cb => cb.checked = this.checked);
        });
    </script>
</body>

</html>