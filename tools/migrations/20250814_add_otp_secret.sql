-- Adds otp_secret column to users table if missing, plus role/status if desired
-- Safe to run multiple times

ALTER TABLE `users`
  ADD COLUMN IF NOT EXISTS `otp_secret` varchar(64) NULL AFTER `password`;

-- Optional: uncomment if you also need these in your environment
-- ALTER TABLE `users`
--   ADD COLUMN IF NOT EXISTS `role` ENUM('admin','user') NOT NULL DEFAULT 'user' AFTER `email`,
--   ADD COLUMN IF NOT EXISTS `status` ENUM('active','inactive') NOT NULL DEFAULT 'active' AFTER `role`;

-- Optional: create audit_logs table used by admin/login.php logging
CREATE TABLE IF NOT EXISTS `audit_logs` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `admin_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `details` json DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;
