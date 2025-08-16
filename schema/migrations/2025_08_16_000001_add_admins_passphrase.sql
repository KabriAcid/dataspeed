-- Migration: add passphrase column to admins and index
ALTER TABLE `admins`
  ADD COLUMN `passphrase` varchar(255) NULL AFTER `password`,
  ADD COLUMN `passphrase_updated_at` datetime NULL AFTER `last_login_at`;

-- Optional index for faster lookups
ALTER TABLE `admins`
  ADD KEY `idx_admins_passphrase` (`passphrase`(10));
