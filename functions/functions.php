<?php
// A function for retrieving user referral details from the database
function getUserReferralDetails($pdo, $user_id) {
    try {
        $stmt = $pdo->prepare("SELECT * FROM referral_reward WHERE user_id = ?");
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
    }
}