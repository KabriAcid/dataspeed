<?php
function showBalance($pdo, $user_id)
{
    $stmt = $pdo->prepare("SELECT wallet_balance FROM users WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $balance = $stmt->fetch();

    return number_format($balance['wallet_balance'], 2);
}

function getTransactions($pdo, $user_id)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$user_id]);
        $transactions = $stmt->fetchAll();
        return $transactions;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getTransactionIcon($type)
{
    $icons = [
        'Data' => '<i class="fa fa-wifi text-danger"></i>',
        'Airtime' => '<i class="fa fa-phone text-primary"></i>',
        'Deposit' => '<i class="fa fa-credit-card text-success"></i>',
        'Withdrawal' => '<i class="fa fa-arrow-down text-danger"></i>',
        'Default' => '<i class="fa fa-credit-card"></i>'
    ];

    return $icons[$type] ?? $icons['Default'];
}

function getReferrals($pdo, $user_id)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE referral_code = ?");
        $stmt->execute([$user_id]);
        $referrals = $stmt->fetchAll();
        return $referrals;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

// A function for retrieving user referral details from the database
function getUserReferralDetails($pdo, $user_id)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM referrals WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}
// A function for rerieving user bank accout details from the database
function getUserAccountDetails($pdo, $user_id)
{
    try {
        $stmt = $pdo->prepare("SELECT virtual_account, account_name, bank_name FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        return null;
    }
}

// A funtion for pushing notifications to the database

/**
 * Push a notification to a user
 *
 * @param PDO $pdo Database connection
 * @param int $userId The ID of the user
 * @param string $title Notification title
 * @param string $message Notification body
 * @return bool Success status
 */
function pushNotification(PDO $pdo, int $user_id, string $title, string $message = '', string $type = "default", string $icon = 'default', string $is_read = '0'): bool
{
    try {
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type, icon, is_read) VALUES (?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$user_id, $title, $message, $type, $icon, $is_read]);
    } catch (Exception $e) {
        error_log("Notification Error: " . $e->getMessage());
        return false;
    }
}


function getUserNotifications(PDO $pdo, int $user_id, int $limit = 10): array
{
    $limit = (int)$limit;

    $sql = "SELECT * FROM notifications WHERE user_id = ? ORDER BY created_at DESC LIMIT $limit";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([$user_id]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


function groupNotificationsByDate(array $notifications): array
{
    $grouped = [];

    foreach ($notifications as $notification) {
        $created = new DateTime($notification['created_at']);
        $today = new DateTime();
        $yesterday = (clone $today)->modify('-1 day');

        if ($created->format('Y-m-d') === $today->format('Y-m-d')) {
            $group = 'Today';
        } elseif ($created->format('Y-m-d') === $yesterday->format('Y-m-d')) {
            $group = 'Yesterday';
        } else {
            $group = $created->format('F j, Y');
        }

        $grouped[$group][] = $notification;
    }

    return $grouped;
}