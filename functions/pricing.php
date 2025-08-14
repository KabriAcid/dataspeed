<?php
require_once '../config/config.php';

function getServicePlans($service_slug = null, $provider_id = null) {
    global $pdo;
    
    $where = [];
    $params = [];
    
    if ($service_slug) {
        $where[] = "s.slug = ?";
        $params[] = $service_slug;
    }
    
    if ($provider_id) {
        $where[] = "sp.provider_id = ?";
        $params[] = $provider_id;
    }
    
    $whereClause = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    
    $query = "SELECT sp.*, s.name as service_name, p.name as provider_name, s.slug as service_slug
              FROM service_plans sp
              LEFT JOIN services s ON sp.service_id = s.id
              LEFT JOIN providers p ON sp.provider_id = p.id
              $whereClause
              ORDER BY s.name, p.name, sp.name";
    
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function updatePlanPrice($plan_id, $price) {
    global $pdo;
    
    $stmt = $pdo->prepare("UPDATE service_plans SET price = ? WHERE id = ?");
    return $stmt->execute([$price, $plan_id]);
}

function togglePlanStatus($plan_id, $is_active) {
    global $pdo;
    
    $stmt = $pdo->prepare("UPDATE service_plans SET is_active = ? WHERE id = ?");
    return $stmt->execute([$is_active, $plan_id]);
}

function getProviders() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM providers ORDER BY name");
    return $stmt->fetchAll();
}

function getServices() {
    global $pdo;
    
    $stmt = $pdo->query("SELECT * FROM services ORDER BY name");
    return $stmt->fetchAll();
}

// Airtime markup functions
function getAirtimeMarkup($provider_id) {
    global $pdo;
    
    // Try pricing_rules table first
    $stmt = $pdo->prepare("
        SELECT markup_type, markup_value 
        FROM pricing_rules 
        WHERE service_slug = 'airtime' AND provider_id = ? AND is_active = 1
    ");
    $stmt->execute([$provider_id]);
    $result = $stmt->fetch();
    
    if ($result) {
        return $result;
    }
    
    // Fallback to app_settings
    $stmt = $pdo->prepare("SELECT value FROM app_settings WHERE `key` = ?");
    $stmt->execute(["airtime_markup_$provider_id"]);
    $value = $stmt->fetchColumn();
    
    if ($value) {
        list($type, $amount) = explode(':', $value);
        return ['markup_type' => $type, 'markup_value' => $amount];
    }
    
    return ['markup_type' => 'percent', 'markup_value' => 0];
}

function setAirtimeMarkup($provider_id, $markup_type, $markup_value) {
    global $pdo;
    
    // Try to insert/update in pricing_rules table
    try {
        $stmt = $pdo->prepare("
            INSERT INTO pricing_rules (service_slug, provider_id, markup_type, markup_value, is_active)
            VALUES ('airtime', ?, ?, ?, 1)
            ON DUPLICATE KEY UPDATE
            markup_type = VALUES(markup_type),
            markup_value = VALUES(markup_value),
            is_active = 1
        ");
        
        return $stmt->execute([$provider_id, $markup_type, $markup_value]);
    } catch (Exception $e) {
        // Fallback to app_settings
        $key = "airtime_markup_$provider_id";
        $value = "$markup_type:$markup_value";
        
        $stmt = $pdo->prepare("
            INSERT INTO app_settings (`key`, value) 
            VALUES (?, ?) 
            ON DUPLICATE KEY UPDATE value = VALUES(value)
        ");
        
        return $stmt->execute([$key, $value]);
    }
}

function bulkUpdatePricing($plan_ids, $operation, $value) {
    global $pdo;
    
    if (empty($plan_ids)) return false;
    
    $placeholders = str_repeat('?,', count($plan_ids) - 1) . '?';
    
    if ($operation === 'percent') {
        $query = "UPDATE service_plans SET price = price * (1 + ? / 100) WHERE id IN ($placeholders)";
        $params = array_merge([$value], $plan_ids);
    } elseif ($operation === 'fixed') {
        $query = "UPDATE service_plans SET price = price + ? WHERE id IN ($placeholders)";
        $params = array_merge([$value], $plan_ids);
    } else {
        return false;
    }
    
    $stmt = $pdo->prepare($query);
    return $stmt->execute($params);
}