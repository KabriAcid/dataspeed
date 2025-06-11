<?php
function getUserInfo(PDO $pdo, int $userd): array|false
{
    $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
    $stmt->execute([$userd]);

    if ($stmt->rowCount() === 0) {
        return false;
    }

    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    if ($user['account_status'] != 'Active') {
        return false;
    }

    return $user;
}


function showBalance($pdo, $user_id)
{
    $stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ?");
    $stmt->execute([$user_id]);
    $balance = $stmt->fetch();

    // Check if the balance is null
    if ($balance === null) {
        return "0.00";
    } else {
        // Check if the balance is empty
        if (empty($balance['wallet_balance'])) {
            return "0.00";
        }
    }
    

    return number_format($balance['wallet_balance'], 2);
}

function getTransactions($pdo, $user_id)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT 5");
        $stmt->execute([$user_id]);
        $transactions = $stmt->fetchAll();

        // Check if the transactions are empty
        if (empty($transactions)) {
            return [];
        } else {
            // Check if the transactions are null
            if ($transactions === null) {
                return [];
            }
        }
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
        'Airtime Self' => '<i class="fa fa-phone text-primary"></i>',
        'Deposit' => '<i class="fa fa-credit-card text-success"></i>',
        'Withdrawal' => '<i class="fa fa-arrow-down text-danger"></i>',
        'Default' => '<i class="fa fa-credit-card"></i>'
    ];

    return $icons[$type] ?? $icons['Default'];
}

// A function for retrieving user referral details from the database
function getUserReferralDetails($pdo, $user_id)
{
    try {
        $stmt = $pdo->prepare("SELECT referral_code, referral_link, referred_by FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);

        // Check if the referral details are empty
        if ($stmt->rowCount() === 0) {
            return [];
        }
        
        return $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}

function getReferralRewards(PDO $pdo, int $userd): array
{
    $pendingStmt = $pdo->prepare("SELECT SUM(reward) AS total FROM referral_reward WHERE user_id = ? AND status = 'pending'");
    $pendingStmt->execute([$userd]);
    $pending = $pendingStmt->fetchColumn() ?? 0;

    $claimedStmt = $pdo->prepare("SELECT SUM(reward) AS total FROM referral_reward WHERE user_id = ? AND status = 'claimed'");
    $claimedStmt->execute([$userd]);
    $claimed = $claimedStmt->fetchColumn() ?? 0;

    return ['pending' => $pending, 'claimed' => $claimed];
}

function getReferralsByStatus(PDO $pdo, int $user_id, string $status): array
{
    $stmt = $pdo->prepare("SELECT * FROM referral_reward WHERE user_id = ? AND status = ? ORDER BY created_at DESC");
    $stmt->execute([$user_id, $status]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getReferralById(PDO $pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM referral_reward WHERE referral_id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}


// A function for rerieving user bank accout details from the database
function getUserAccountDetails($pdo, $user_id)
{
    try {
        $stmt = $pdo->prepare("SELECT virtual_account, account_name, bank_name FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);

        // Check if the account details are empty
        if ($stmt->rowCount() === 0) {
            return [];
        }
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
 * @param int $userd The ID of the user
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

function updateWalletBalance(PDO $pdo, int $user_id, float $amount, string $type, string $description = ''): bool
{
    try {
        // Start the transaction
        $pdo->beginTransaction();

        // Lock the user's wallet balance row for update (to prevent race conditions)
        $stmt = $pdo->prepare("SELECT wallet_balance FROM account_balance WHERE user_id = ? FOR UPDATE");
        $stmt->execute([$user_id]);
        $wallet = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$wallet) {
            throw new Exception("Wallet not found");
        }

        $currentBalance = (float)$wallet['wallet_balance'];

        if ($type === 'debit' && $currentBalance < $amount) {
            throw new Exception("Insufficient balance");
        }

        // Calculate new balance
        $newBalance = ($type === 'credit')
            ? $currentBalance + $amount
            : $currentBalance - $amount;

        // Update wallet balance
        $updateStmt = $pdo->prepare("UPDATE account_balance SET wallet_balance = ? WHERE user_id = ?");
        $updateStmt->execute([$newBalance, $user_id]);

        // Insert transaction record
        $insertTxn = $pdo->prepare("INSERT INTO transactions (user_id, amount, type, description) VALUES (?, ?, ?, ?)        ");
        $insertTxn->execute([$user_id, $amount, $type, $description]);

        // Commit all changes
        $pdo->commit();

        return true;
    } catch (Exception $e) {
        // Rollback in case of error
        $pdo->rollBack();
        error_log("Balance update failed: " . $e->getMessage());
        return false;
    }
}

function fetchNigerianStates($pdo){
   try {
    $stmt = $pdo->prepare("SELECT * FROM nigerian_states;");
    $stmt->execute();
    $result = $stmt->fetchAll();

    return $result;

   } catch (PDOException $e) {
        echo $e->getMessage();
   }
}