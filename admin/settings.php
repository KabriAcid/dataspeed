<?php
session_start();

// Check authentication
if (empty($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}

include 'includes/header.php';
?>

<body class="admin-body">
    <?php include 'includes/topbar.php'; ?>
    <?php include 'includes/sidebar.php'; ?>

    <main class="main-content">
        <div class="container-fluid">
            <div class="page-header">
                <h1 class="page-title">Settings</h1>
                <p class="page-subtitle">Manage platform settings and configuration</p>
            </div>

            <div class="row g-4">
                <!-- General Settings -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">General Settings</h5>
                        </div>
                        <div class="card-body" id="generalSettings">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Transaction Settings -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Transaction Settings</h5>
                        </div>
                        <div class="card-body" id="transactionSettings">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Feature Toggles -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">Feature Toggles</h5>
                        </div>
                        <div class="card-body" id="featureSettings">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- API Settings -->
                <div class="col-lg-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title">API Settings</h5>
                        </div>
                        <div class="card-body" id="apiSettings">
                            <div class="text-center py-4">
                                <div class="spinner-border text-primary" role="status">
                                    <span class="visually-hidden">Loading...</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Settings Modal -->
    <div class="modal fade" id="settingsModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="settingsModalTitle">Edit Setting</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="settingsForm">
                    <div class="modal-body">
                        <input type="hidden" name="key" id="settingKey">

                        <div class="mb-3">
                            <label for="settingValue" class="form-label" id="settingLabel">Value</label>
                            <input type="text" class="form-control" name="value" id="settingValue" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-primary">Save Setting</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/scripts.php'; ?>

    <script>
        let allSettings = {};

        document.addEventListener('DOMContentLoaded', function() {
            topbarInit();
            loadSettings();

            // Bind modal form
            const settingsModal = document.getElementById('settingsModal');
            bindModalForm(settingsModal, {
                endpoint: 'api/settings.php',
                onSuccess: () => {
                    loadSettings();
                }
            });
        });

        async function loadSettings() {
            try {
                const response = await apiFetch('api/settings.php?action=get');

                if (response.success) {
                    allSettings = response.data;
                    renderSettings();
                } else {
                    showToasted(response.message || 'Failed to load settings', 'error');
                }

            } catch (error) {
                showToasted('Error loading settings', 'error');
            }
        }

        function renderSettings() {
            // General Settings
            const generalContainer = document.getElementById('generalSettings');
            const generalSettings = [
                'site_name',
                'site_description',
                'support_email',
                'support_phone'
            ];

            generalContainer.innerHTML = renderSettingsGroup(generalSettings);

            // Transaction Settings
            const transactionContainer = document.getElementById('transactionSettings');
            const transactionSettings = [
                'min_funding_amount',
                'max_funding_amount',
                'transaction_fee',
                'withdrawal_fee'
            ];

            transactionContainer.innerHTML = renderSettingsGroup(transactionSettings);

            // Feature Settings
            const featureContainer = document.getElementById('featureSettings');
            const featureSettings = [
                'enable_data_purchase',
                'enable_airtime_purchase',
                'enable_bill_payment',
                'enable_user_registration'
            ];

            featureContainer.innerHTML = renderFeatureToggles(featureSettings);

            // API Settings
            const apiContainer = document.getElementById('apiSettings');
            const apiSettings = [
                'api_timeout',
                'api_retry_attempts',
                'webhook_url'
            ];

            apiContainer.innerHTML = renderSettingsGroup(apiSettings);
        }

        function renderSettingsGroup(settingKeys) {
            return settingKeys.map(key => {
                const setting = allSettings[key];
                const value = setting ? setting.value : 'Not set';

                return `
                    <div class="setting-item mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-start">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${formatSettingKey(key)}</h6>
                                <div class="fw-semibold">${value}</div>
                            </div>
                            <button class="btn btn-outline-primary btn-sm" onclick="editSetting('${key}')">
                                <i class="ni ni-ruler-pencil"></i>
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function renderFeatureToggles(settingKeys) {
            return settingKeys.map(key => {
                const setting = allSettings[key];
                const value = setting ? setting.value : 'false';
                const isEnabled = value === 'true' || value === '1';

                return `
                    <div class="setting-item mb-3 p-3 border rounded">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="flex-grow-1">
                                <h6 class="mb-1">${formatSettingKey(key)}</h6>
                            </div>
                            <div class="form-check form-switch">
                                <input class="form-check-input" type="checkbox" 
                                       ${isEnabled ? 'checked' : ''} 
                                       onchange="toggleFeature('${key}', this.checked)">
                            </div>
                        </div>
                    </div>
                `;
            }).join('');
        }

        function formatSettingKey(key) {
            return key.split('_').map(word =>
                word.charAt(0).toUpperCase() + word.slice(1)
            ).join(' ');
        }

        function editSetting(key) {
            const setting = allSettings[key];
            const value = setting ? setting.value : '';

            document.getElementById('settingKey').value = key;
            document.getElementById('settingValue').value = value;
            document.getElementById('settingLabel').textContent = formatSettingKey(key);
            document.getElementById('settingsModalTitle').textContent = `Edit ${formatSettingKey(key)}`;

            openModal('settingsModal');
        }

        async function toggleFeature(key, enabled) {
            try {
                const response = await apiFetch('api/settings.php', {
                    method: 'POST',
                    body: JSON.stringify({
                        action: 'update',
                        settings: {
                            [key]: enabled ? 'true' : 'false'
                        }
                    })
                });

                if (response.success) {
                    showToasted('Feature toggle updated successfully', 'success');
                    // Update local settings
                    if (allSettings[key]) {
                        allSettings[key].value = enabled ? 'true' : 'false';
                    }
                } else {
                    showToasted(response.message, 'error');
                    // Revert the toggle
                    loadSettings();
                }
            } catch (error) {
                showToasted('Error updating feature toggle', 'error');
                loadSettings();
            }
        }

        // Override form submission to handle settings updates
        document.getElementById('settingsForm').addEventListener('submit', function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            const key = formData.get('key');
            const value = formData.get('value');

            const submitBtn = this.querySelector('button[type="submit"]');
            const originalText = submitBtn.textContent;

            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Saving';

            apiFetch('api/settings.php', {
                method: 'POST',
                body: JSON.stringify({
                    action: 'update',
                    settings: {
                        [key]: value
                    }
                })
            }).then(response => {
                if (response.success) {
                    showToasted('Setting updated successfully!', 'success');
                    bootstrap.Modal.getInstance(document.getElementById('settingsModal')).hide();
                    loadSettings();
                } else {
                    showToasted(response.message, 'error');
                }
            }).catch(error => {
                showToasted('An error occurred. Please try again.', 'error');
            }).finally(() => {
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            });
        });
    </script>
</body>

</html>