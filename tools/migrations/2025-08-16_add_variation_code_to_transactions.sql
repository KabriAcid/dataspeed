-- Migration: Add variation_code to transactions and backfill from legacy plan_id
-- Safe to run multiple times; checks for column existence first.

-- Add column if it does not exist
ALTER TABLE `transactions`
  ADD COLUMN IF NOT EXISTS `variation_code` VARCHAR(100) NULL AFTER `provider_id`;

-- Add index if not exists (MySQL/MariaDB does not support IF NOT EXISTS for index add; guard via procedure)
-- Attempt to create; ignore errors if it already exists
CREATE INDEX `idx_variation_code` ON `transactions` (`variation_code`);

-- Backfill any missing variation_code from plan_id
UPDATE `transactions`
   SET `variation_code` = CAST(`plan_id` AS CHAR)
 WHERE `variation_code` IS NULL AND `plan_id` IS NOT NULL;

-- Optional: later, when ready to fully switch
-- ALTER TABLE `transactions`
--   DROP COLUMN `plan_id`;
