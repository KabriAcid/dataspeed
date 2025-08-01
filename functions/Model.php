<?php
function getUserInfo(PDO $pdo, int $user_id): array|false
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE user_id = ?");
        $stmt->execute([$user_id]);

        if ($stmt->rowCount() === 0) {
            return false;
        }

        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        return $user;
    } catch (PDOException $th) {
        error_log("Error fetching user info: " . $th->getMessage());
        throw $th;
    }
}

//  A function to get user settings from the database
function getUserSettings($pdo, int $user_id): array|false
{
    $stmt = $pdo->prepare("SELECT * FROM user_settings WHERE user_id = ?");
    $stmt->execute([$user_id]);

    if ($stmt->rowCount() === 0) {
        return false;
    }

    return $stmt->fetch(PDO::FETCH_ASSOC);
}
function getUserBalance($pdo, $user_id)
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

function getUserInfoByEmail($pdo, $email)
{
    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$email]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

function getTransactions($pdo, $user_id, $limit = 5)
{
    try {
        $limit = (int)$limit;
        $stmt = $pdo->prepare("SELECT id, user_id, service_id, type, direction, amount, status, created_at, icon, color, description FROM transactions WHERE user_id = ? ORDER BY created_at DESC LIMIT $limit");
        $stmt->execute([$user_id]);
        $transactions = $stmt->fetchAll();

        return $transactions ?: [];
    } catch (PDOException $e) {
        return [];
    }
}

function getServiceProvider(PDO $pdo, string $type): array
{
    $type = strtolower($type);

    try {
        $stmt = $pdo->prepare("SELECT * FROM service_providers WHERE type = ?");
        $stmt->execute([$type]);
        $providers = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return $providers ?: [];
    } catch (PDOException $th) {
        echo $th->getMessage();
        return [];
    }
}

function getRecentBalanceChangePercent(PDO $pdo, int $user_id): array
{
    // 1. Get current wallet balance
    $currentBalance = (float)getUserBalance($pdo, $user_id);

    // 2. Fetch most recent transaction
    $stmt = $pdo->prepare("SELECT amount, direction FROM transactions WHERE user_id = ? ORDER BY created_at DESC, id DESC LIMIT 1");
    $stmt->execute([$user_id]);
    $txn = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$txn) {
        return ['percent' => 0, 'direction' => 'credit', 'valid' => false];
    }

    $amount = (float)$txn['amount'];
    $direction = $txn['direction'];

    // 3. Estimate previous balance
    $previousBalance = $direction === 'debit'
        ? $currentBalance + $amount
        : $currentBalance - $amount;

    if ($previousBalance <= 0) {
        return ['percent' => 0, 'direction' => $direction, 'valid' => false];
    }

    // 4. Calculate change and percent
    $change = $currentBalance - $previousBalance;
    $percent = ($change / $previousBalance) * 100;

    return [
        'percent' => round($percent, 2),
        'direction' => $direction,
        'valid' => true
    ];
}


function getTransactionIcon($description)
{
    $map = [
        // Keyword         => [icon, color]
        'airtime'        => ['ni-mobile-button', 'text-primary'],
        'data'           => ['ni-wifi', 'text-info'],
        'deposit'        => ['ni-credit-card', 'text-success'],
        'withdrawal'     => ['ni-briefcase-24', 'text-danger'],
        'transfer'       => ['ni-send', 'text-warning'],
        'bill'           => ['ni-credit-card', 'text-info'],
        'electricity'    => ['ni-bulb-61', 'text-warning'],
        'tv'             => ['ni-tv-2', 'text-primary'],
        'reward'         => ['ni-gift', 'text-success'],
        'failed'         => ['ni-fat-remove', 'text-danger'],
        'success'        => ['ni-check-bold', 'text-success'],
    ];

    $desc = strtolower($description);

    foreach ($map as $keyword => [$icon, $color]) {
        if (strpos($desc, $keyword) !== false) {
            return "<i class='ni $icon $color'></i>";
        }
    }

    // Default icon
    return "<i class='ni ni-credit-card text-secondary'></i>";
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
function pushNotification(PDO $pdo, int $user_id, string $title, string $message = '', string $type = "default", string $icon = 'default', string $color = 'text-info', string $is_read = '0'): bool
{
    try {
        $stmt = $pdo->prepare("INSERT INTO notifications (user_id, title, message, type, icon, color, is_read) VALUES (?, ?, ?, ?, ?, ?, ?)");
        return $stmt->execute([$user_id, $title, $message, $type, $icon, $color, $is_read]);
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

function fetchNigerianStates($pdo)
{
    try {
        $stmt = $pdo->prepare("SELECT * FROM nigerian_states;");
        $stmt->execute();
        $result = $stmt->fetchAll();

        return $result;
    } catch (PDOException $e) {
        echo $e->getMessage();
    }
}

function getUserIdByAccount($accountNumber)
{
    global $pdo;
    $stmt = $pdo->prepare("SELECT user_id FROM users WHERE account_number = ?");
    $stmt->execute([$accountNumber]);
    $row = $stmt->fetch();
    return $row ? $row['user_id'] : null;
}

// Add this helper before your try-catch block
function safeRollback($pdo)
{
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
}
