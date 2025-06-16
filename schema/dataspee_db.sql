-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2025 at 10:16 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dataspee_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `account_balance`
--

CREATE TABLE `account_balance` (
  `account_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `wallet_balance` decimal(15,2) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_balance`
--

INSERT INTO `account_balance` (`account_id`, `user_id`, `wallet_balance`, `updated_at`, `email`, `phone_number`) VALUES
(1, 136, 74227.00, '2025-06-15 19:55:42', 'kabriacid01@gmail.com', '07037943396'),
(2, 187, 37150.00, '2025-06-15 19:55:42', '', ''),
(3, 133, 35075.00, '2025-06-15 05:59:24', 'musajidder@gmail.com', '07038943396'),
(4, 196, 0.00, '2025-06-15 03:58:56', 'abdullahikabri@gmail.com', '07078701331');

-- --------------------------------------------------------

--
-- Table structure for table `forgot_password`
--

CREATE TABLE `forgot_password` (
  `id` int(11) NOT NULL,
  `email` varchar(50) NOT NULL,
  `token` varchar(32) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `nigerian_states`
--

CREATE TABLE `nigerian_states` (
  `id` int(11) NOT NULL,
  `state_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `nigerian_states`
--

INSERT INTO `nigerian_states` (`id`, `state_name`) VALUES
(1, 'Abia'),
(2, 'Adamawa'),
(3, 'Akwa Ibom'),
(4, 'Anambra'),
(5, 'Bauchi'),
(6, 'Bayelsa'),
(7, 'Benue'),
(8, 'Borno'),
(9, 'Cross River'),
(10, 'Delta'),
(11, 'Ebonyi'),
(12, 'Edo'),
(13, 'Ekiti'),
(14, 'Enugu'),
(15, 'FCT'),
(16, 'Gombe'),
(17, 'Imo'),
(18, 'Jigawa'),
(19, 'Kaduna'),
(20, 'Kano'),
(21, 'Katsina'),
(22, 'Kebbi'),
(23, 'Kogi'),
(24, 'Kwara'),
(25, 'Lagos'),
(26, 'Nasarawa'),
(27, 'Niger'),
(28, 'Ogun'),
(29, 'Ondo'),
(30, 'Osun'),
(31, 'Oyo'),
(32, 'Plateau'),
(33, 'Rivers'),
(34, 'Sokoto'),
(35, 'Taraba'),
(36, 'Yobe'),
(37, 'Zamfara');

-- --------------------------------------------------------

--
-- Table structure for table `notifications`
--

CREATE TABLE `notifications` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) NOT NULL,
  `message` text DEFAULT NULL,
  `type` varchar(20) NOT NULL DEFAULT 'default',
  `icon` varchar(50) NOT NULL,
  `color` varchar(20) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `icon`, `color`, `is_read`, `created_at`) VALUES
(93, 136, 'Data Purchase Successful', 'You purchased ₦1,000.00 data for 08011111111 on MTN.', 'data_purchase', 'ni ni-wifi', 'text-success', 0, '2025-06-15 03:29:31'),
(94, 136, 'Password Changed', 'Your account password was changed successfully.', 'security', 'ni ni-lock-circle-open', 'text-primary', 0, '2025-06-15 03:31:42'),
(95, 136, 'Transfer Sent', 'You sent ₦10,000.00 to musajidder@gmail.com.', 'Money Transferred', 'ni ni-send', 'text-warning', 0, '2025-06-15 03:44:32'),
(96, 133, 'Transfer Received', 'You received ₦10,000.00 from user musajidder@gmail.com.', 'Money Received', 'ni ni-send', 'text-success', 0, '2025-06-15 03:44:32'),
(97, 133, 'Transfer Sent', 'You sent ₦10,000.00 to Abdullahi Kabri.', 'Money Transferred', 'ni ni-send', 'text-warning', 0, '2025-06-15 03:55:22'),
(98, 136, 'Transfer Received', 'You received ₦10,000.00 from Musa Jidder.', 'Money Received', 'ni ni-money-coins', 'text-success', 0, '2025-06-15 03:55:22'),
(99, 196, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'ni ni-building', 'text-success', 0, '2025-06-15 03:58:56'),
(100, 196, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'ni ni-key-25', 'text-warning', 0, '2025-06-15 03:58:56'),
(101, 136, 'Transfer Sent', 'You sent ₦29,993.00 to Musa Jidder.', 'Money Transferred', 'ni ni-send', 'text-warning', 0, '2025-06-15 05:59:24'),
(102, 133, 'Transfer Received', 'You received ₦29,993.00 from Abdullahi Kabri.', 'Money Received', 'ni ni-money-coins', 'text-success', 0, '2025-06-15 05:59:24'),
(103, 136, 'Security Setting Updated', 'Session expiry is now disabled. You will remain logged in unless you log out.', 'security', 'ni ni-lock-circle-open', 'text-warning', 0, '2025-06-15 06:26:41'),
(104, 136, 'Security Setting Updated', 'Session expiry is now enabled. Your account will require re-authentication after 10 minutes of inactivity.', 'security', 'ni ni-lock-circle-open', 'text-success', 0, '2025-06-15 06:26:43'),
(105, 136, 'Money Transfer failed', 'Jamal Jugulde must complete registration before receiving any funds.', 'transfer_fail', 'ni ni-fat-remove', 'text-warning', 0, '2025-06-15 19:52:02'),
(106, 136, 'Money Transfer failed', 'Jamal Jugulde must complete registration before receiving any funds.', 'transfer_fail', 'ni ni-fat-remove', 'text-warning', 0, '2025-06-15 19:52:05'),
(107, 136, 'Transfer Sent', 'You sent ₦20,000.00 to Jamal Jugulde.', 'Money Transferred', 'ni ni-send', 'text-warning', 0, '2025-06-15 19:55:42'),
(108, 187, 'Transfer Received', 'You received ₦20,000.00 from Abdullahi Kabri.', 'Money Received', 'ni ni-money-coins', 'text-success', 0, '2025-06-15 19:55:42');

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `last_resend_time` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `email`, `otp_code`, `expires_at`, `last_resend_time`, `created_at`) VALUES
(124, 'caxu@gmail.com', '343465', '2025-06-02 03:09:46', '0000-00-00 00:00:00', '2025-06-02 01:45:40'),
(127, 'hojygywa@gmail.com', '896385', '2025-06-03 22:44:08', '0000-00-00 00:00:00', '2025-06-03 21:34:08'),
(128, 'bomysil@gmail.com', '703164', '2025-06-03 22:44:27', '0000-00-00 00:00:00', '2025-06-03 21:34:27'),
(129, 'kuxadama@gmail.com', '829605', '2025-06-03 22:44:56', '0000-00-00 00:00:00', '2025-06-03 21:34:56'),
(133, 'bariraumar@gmail.com', '387111', '2025-06-13 05:03:48', '0000-00-00 00:00:00', '2025-06-13 03:53:48'),
(134, 'fatimaibrahimsani@gmail.com', '999294', '2025-06-13 05:06:45', '0000-00-00 00:00:00', '2025-06-13 03:56:45'),
(135, 'quxively@gmail.com', '336405', '2025-06-13 05:07:41', '0000-00-00 00:00:00', '2025-06-13 03:57:41'),
(138, 'vebec@gmail.com', '756306', '2025-06-14 21:53:48', '0000-00-00 00:00:00', '2025-06-14 20:43:48');

-- --------------------------------------------------------

--
-- Table structure for table `referral_reward`
--

CREATE TABLE `referral_reward` (
  `referral_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `referee_email` varchar(100) NOT NULL,
  `reward` decimal(10,2) NOT NULL,
  `status` enum('claimed','pending') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referral_reward`
--

INSERT INTO `referral_reward` (`referral_id`, `user_id`, `referee_email`, `reward`, `status`, `created_at`) VALUES
(9, 133, '', 100.00, 'claimed', '2025-05-20 04:53:16'),
(10, 136, '', 100.00, 'claimed', '2025-05-28 04:34:36'),
(11, 136, 'musajidder9@gmail.com', 100.00, 'claimed', '2025-06-14 22:12:34'),
(12, 136, 'musajidder9@gmail.com', 100.00, 'claimed', '2025-06-14 22:13:52');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Data', 'data', '2025-05-15 21:12:19'),
(2, 'Airtime', 'airtime', '2025-05-15 21:12:19'),
(3, 'Electricity', 'electricity', '2025-05-30 20:44:35'),
(4, 'Bills', 'bills', '2025-05-30 20:44:35'),
(5, 'Deposits', 'deposits', '2025-06-12 23:31:12');

-- --------------------------------------------------------

--
-- Table structure for table `service_plans`
--

CREATE TABLE `service_plans` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `service_identifier` varchar(100) DEFAULT NULL,
  `volume` varchar(100) NOT NULL,
  `variation_code` varchar(100) DEFAULT NULL,
  `price` decimal(10,2) NOT NULL,
  `validity` varchar(50) NOT NULL,
  `type` enum('daily','weekly','monthly') DEFAULT 'daily',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_plans`
--

INSERT INTO `service_plans` (`id`, `service_id`, `provider_id`, `service_identifier`, `volume`, `variation_code`, `price`, `validity`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'mtn-data', '500MB', 'mtn500mb', 150.00, '7 Days', 'daily', 1, '2025-06-15 02:22:29', '2025-06-15 02:22:29'),
(2, 1, 1, 'mtn-data', '1.5GB', 'mtn1500mb', 1000.00, '30 Days', 'monthly', 1, '2025-06-15 02:22:29', '2025-06-15 02:22:29'),
(3, 1, 1, 'mtn-data', '3GB', 'mtn3000mb', 2000.00, '30 Days', 'monthly', 1, '2025-06-15 02:22:29', '2025-06-15 02:22:29');

-- --------------------------------------------------------

-- DStv Plans
INSERT INTO service_plans (service_id, provider_id, service_identifier, volume, variation_code, price, validity, type, is_active, created_at, updated_at) VALUES
(4, 10, 'dstv-tv', 'DStv Padi', 'dstv-padi', 2500.00, '30 Days', 'monthly', 1, NOW(), NOW()),
(4, 10, 'dstv-tv', 'DStv Yanga', 'dstv-yanga', 3500.00, '30 Days', 'monthly', 1, NOW(), NOW()),
(4, 10, 'dstv-tv', 'DStv Confam', 'dstv-confam', 5300.00, '30 Days', 'monthly', 1, NOW(), NOW()),
(4, 10, 'dstv-tv', 'DStv Compact', 'dstv-compact', 7900.00, '30 Days', 'monthly', 1, NOW(), NOW()),
(4, 10, 'dstv-tv', 'DStv Premium', 'dstv-premium', 18400.00, '30 Days', 'monthly', 1, NOW(), NOW());

-- GOtv Plans
INSERT INTO service_plans (service_id, provider_id, service_identifier, volume, variation_code, price, validity, type, is_active, created_at, updated_at) VALUES
(4, 11, 'gotv-tv', 'GOtv Smallie', 'gotv-smallie', 1100.00, '30 Days', 'monthly', 1, NOW(), NOW()),
(4, 11, 'gotv-tv', 'GOtv Jinja', 'gotv-jinja', 2250.00, '30 Days', 'monthly', 1, NOW(), NOW()),
(4, 11, 'gotv-tv', 'GOtv Jolli', 'gotv-jolli', 3600.00, '30 Days', 'monthly', 1, NOW(), NOW()),
(4, 11, 'gotv-tv', 'GOtv Max', 'gotv-max', 4850.00, '30 Days', 'monthly', 1, NOW(), NOW()),
(4, 11, 'gotv-tv', 'GOtv Supa', 'gotv-supa', 6400.00, '30 Days', 'monthly', 1, NOW(), NOW());

-- Showmax Plans
INSERT INTO service_plans (service_id, provider_id, service_identifier, volume, variation_code, price, validity, type, is_active, created_at, updated_at) VALUES
(4, 12, 'showmax-tv', 'Showmax Mobile', 'showmax-mobile', 1200.00, '30 Days', 'monthly', 1, NOW(), NOW()),
(4, 12, 'showmax-tv', 'Showmax Pro Mobile', 'showmax-pro-mobile', 3200.00, '30 Days', 'monthly', 1, NOW(), NOW()),
(4, 12, 'showmax-tv', 'Showmax Standard', 'showmax-standard', 2900.00, '30 Days', 'monthly', 1, NOW(), NOW()),
(4, 12, 'showmax-tv', 'Showmax Pro', 'showmax-pro', 6300.00, '30 Days', 'monthly', 1, NOW(), NOW()),
(4, 12, 'showmax-tv', 'Showmax Bundle', 'showmax-bundle', 5000.00, '30 Days', 'monthly', 1, NOW(), NOW());

--
-- Table structure for table `service_providers`
--

CREATE TABLE `service_providers` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` int(11) NOT NULL,
  `brand_color` varchar(20) DEFAULT '#F4F4F4',
  `slug` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_providers`
--

INSERT INTO `service_providers` (`id`, `service_id`, `name`, `code`, `brand_color`, `slug`) VALUES
(1, 1, 'MTN', 1001, '#FFCB05', 'mtn'),
(2, 1, 'Airtel', 1002, '#ED1C24', 'airtel'),
(3, 1, 'Glo', 1003, '#1DBA54', 'glo'),
(4, 1, '9mobile', 1004, '#B5D334', '9mobile');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `reference` varchar(200) NOT NULL,
  `type` varchar(50) NOT NULL,
  `direction` enum('credit','debit') NOT NULL DEFAULT 'debit',
  `amount` decimal(10,2) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` enum('success','fail') NOT NULL DEFAULT 'fail',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `icon` varchar(50) DEFAULT NULL,
  `color` varchar(20) DEFAULT NULL,
  `description` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `service_id`, `provider_id`, `plan_id`, `reference`, `type`, `direction`, `amount`, `email`, `status`, `created_at`, `icon`, `color`, `description`) VALUES
(53, 136, 2, 1, 0, 'data_684e3c81766df5.45769003', 'Data Purchase', 'debit', 2000.00, 'kabriacid01@gmail.com', 'success', '2025-06-15 03:22:50', 'ni ni-wifi', 'text-success', 'You purchased ₦2,000.00 data for 08011111111 on MTN.'),
(54, 136, 2, 1, 0, 'data_684e3e134dee73.20309613', 'Data Purchase', 'debit', 1000.00, 'kabriacid01@gmail.com', 'success', '2025-06-15 03:29:31', 'ni ni-wifi', 'text-success', 'You purchased ₦1,000.00 data for 08011111111 on MTN.'),
(55, 136, 0, NULL, NULL, 'transfer_684e41a01cb531.18329633', 'Money Transfer', 'debit', 10000.00, 'kabriacid01@gmail.com', 'success', '2025-06-15 03:44:32', 'ni ni-send', 'text-warning', 'Transfer to musajidder@gmail.com'),
(56, 133, 0, NULL, NULL, 'transfer_684e41a01cb531.18329633', 'Money Transfer', 'credit', 10000.00, 'musajidder@gmail.com', 'success', '2025-06-15 03:44:32', 'ni ni-send', 'text-success', 'Received transfer from user 136'),
(57, 133, 0, NULL, NULL, 'transfer_684e442a952363.24207035', 'Money Transfer', 'debit', 10000.00, 'musajidder@gmail.com', 'success', '2025-06-15 03:55:22', 'ni ni-send', 'text-warning', 'Transfer to Abdullahi Kabri'),
(58, 136, 0, NULL, NULL, 'transfer_684e442a952363.24207035', 'Money Transfer', 'credit', 10000.00, 'kabriacid01@gmail.com', 'success', '2025-06-15 03:55:22', 'ni ni-money-coins', 'text-success', 'Received transfer from Musa Jidder'),
(59, 136, 0, NULL, NULL, 'transfer_684e613ced0c12.49641111', 'Money Transfer', 'debit', 29993.00, 'kabriacid01@gmail.com', 'success', '2025-06-15 05:59:24', 'ni ni-send', 'text-warning', 'Transfer to Musa Jidder'),
(60, 133, 0, NULL, NULL, 'transfer_684e613ced0c12.49641111', 'Money Received', 'credit', 29993.00, 'musajidder@gmail.com', 'success', '2025-06-15 05:59:24', 'ni ni-money-coins', 'text-success', 'Received transfer from Abdullahi Kabri'),
(61, 136, 0, NULL, NULL, 'transfer_684f253ef2ebe7.30001164', 'Money Transfer', 'debit', 20000.00, 'kabriacid01@gmail.com', 'success', '2025-06-15 19:55:42', 'ni ni-send', 'text-warning', 'Transfer to Jamal Jugulde'),
(62, 187, 0, NULL, NULL, 'transfer_684f253ef2ebe7.30001164', 'Money Received', 'credit', 20000.00, 'majugulde03@gmail.com', 'success', '2025-06-15 19:55:42', 'ni ni-money-coins', 'text-success', 'Received transfer from Abdullahi Kabri');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `virtual_account` varchar(20) DEFAULT NULL,
  `account_name` varchar(50) NOT NULL,
  `bank_name` varchar(50) NOT NULL,
  `billstack_ref` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `w_bank_name` varchar(50) NOT NULL,
  `w_account_number` varchar(10) NOT NULL,
  `txn_pin` varchar(255) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `state` varchar(15) NOT NULL,
  `country` varchar(15) NOT NULL,
  `city` varchar(20) NOT NULL,
  `photo` varchar(255) NOT NULL DEFAULT 'uploads/default.png',
  `updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `registration_id` varchar(50) NOT NULL,
  `referral_code` varchar(10) DEFAULT NULL,
  `referral_link` varchar(100) NOT NULL,
  `referred_by` varchar(10) DEFAULT NULL,
  `registration_status` enum('incomplete','complete') DEFAULT 'incomplete',
  `account_status` enum('Active','Inactive','Banned','Frozen') NOT NULL DEFAULT 'Active',
  `kyc_value` varchar(11) DEFAULT NULL,
  `kyc_status` enum('verified','unverified','locked','banned') NOT NULL DEFAULT 'unverified',
  `kyc_type` enum('NIN','BVN') DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `virtual_account`, `account_name`, `bank_name`, `billstack_ref`, `first_name`, `last_name`, `email`, `phone_number`, `password`, `w_bank_name`, `w_account_number`, `txn_pin`, `address`, `state`, `country`, `city`, `photo`, `updated_at`, `created_at`, `registration_id`, `referral_code`, `referral_link`, `referred_by`, `registration_status`, `account_status`, `kyc_value`, `kyc_status`, `kyc_type`) VALUES
(115, '2147483647', 'BillStack/VTU-Katelyn', 'PalmPay', 'R-FPAWMNYEDW', 'Katelyn', 'Guzman', 'zoxalon@gmail.com', '08011737992', '$2y$10$s.QwmCU.4t91qmIomXOJXemvFFObyah/RsIWLQmiUerezM3N6PIG.', '', '', '1111', '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', 'afdf227ba83dff6e5951c53c73bdf0ec', 'UXIZW9T1GK', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(131, '5761210754', 'VTU-Hiroko Mclaughlin', '9PSB Bank', 'R-PRZTYEMAHS', 'Hiroko', 'Mclaughlin', 'xehemexa@gmail.com', '08085311846', '$2y$10$eHvzv7/KbZmv0f0ZQUNEJeptrbE46oNkw.CZlDYOh7Z38bBNkSxYm', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', 'b1c1be0f5a2f3971b0e14a074ebd70e4', 'H41SILO96F', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(132, '5761207770', 'VTU-Orson Head', '9PSB Bank', 'R-VTPXCKSJYV', 'Rebecca', 'Dickerson', 'muhammadmjidder@gmail.com', '08038851880', '$2y$10$HbwMQiD0.N2mdHvSGSvHDOricGNaKwB0S.8UqgIkHII6hHLZu/2D2', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', '81c6536766fc6acaea9ba82fd4d548b2', '0KLFPDQ43H', '', 'H41SILO96F', 'complete', 'Active', '', 'unverified', NULL),
(133, '5761212820', 'VTU-Britanni Downs', '9PSB Bank', 'R-SDYNQCBSZV', 'Musa', 'Jidder', 'musajidder@gmail.com', '08076574147', '$2y$10$HPgBE21jeE5LYkvpTDXu7OZ2e4P.Jqa6LNNYDn/fB2UDnATvKNl4S', '', '', '$2y$10$8WMr3lH1WjgxYW0SuHRpaePG/5suHLonAVmTz25tumqm3GeLjwU.m', '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', 'ebaeaaeac1ae51166c60916c1e85b765', '1Q9764VM5R', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(135, '5761221301', 'VTU-Colette Chase', '9PSB Bank', 'R-WEHVXNMRDG', 'Colette', 'Chase', 'musa@gmail.com', '08040993201', '$2y$10$hXdg2zGjrosN0/Yy.5qXWOuuA11h2dxxTu47Vg0a7S44oOTZlnSkC', '', '', '$2y$10$Du4Jg.Y8yC5DSExH.DeJP.kO9pz.KyMXYCzkNVnXzYlviDqqcCcji', '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', '9e1948165924f8bbef7b11ed0c9cc37a', 'NBDJXG2K10', '', '1Q9764VM5R', 'complete', 'Active', '', 'unverified', NULL),
(136, '5761257050', 'VTU-Jenna Marshall', '9PSB Bank', 'R-ZUNTBSDTMW', 'Abdullahi', 'Kabri', 'kabriacid01@gmail.com', '07037943396', '$2y$10$ciN4GIfOPpu.fchuEk0Puujz6L0AZdlBJkcF6/XWEt1252l5QHW0i', 'Opay', '7037943396', '$2y$10$NKkg/aRtaNJonZHny1V43eRRESCgCi7J/B0lxaW4hLK6f.W3k86IW', '846 Rocky First Boulevard', 'Kaduna', '', 'Kaduna South', 'uploads/default.png', NULL, '2025-06-15 03:31:42', '949394aeb0a04a78486fd806ca7c24f1', 'XL5ZJWK4DO', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(139, '5761257555', 'VTU-Chastity Obrien', '9PSB Bank', 'R-FRZBBQJNXG', 'Chastity', 'Obrien', 'vunota@gmail.com', '08023983839', '$2y$10$R8v1oCjJaMYq79lcbor5WeaT2MZxUX9gnMncA2zPeoGazeBHhe/d6', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', 'cf75622cfd811a09a420ba5fa329fec3', 'B1X69UVJKP', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(140, '5761257603', 'VTU-Gabriel Odonnell', '9PSB Bank', 'R-TSUTFSVWPK', 'Gabriel', 'Odonnell', 'dyjo@gmail.com', '08012721760', '$2y$10$tzHdRsqtRRlBeIyaH91yWOiX46sGdEcYH9pqqtGr2bfDIl2pSdACu', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', '9f010b4dc7cc9774ec06d275248db1a9', 'VROJ2E4ULB', '', 'B1X69UVJKP', 'complete', 'Active', '', 'unverified', NULL),
(144, '5761409749', 'VTU-Muhammad Bappayo', '9PSB Bank', 'R-OPRCVBEEKV', 'Muhammad', 'Bappayo', 'alhpeace001@gmail.com', '08064509234', '$2y$10$aLDbre5oxj.cL7I3UUfYKe/e9XLx4ziDeikYJlTO.OU6v6rkDUibu', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', 'a8a785096dae3b93285c371f69851959', '5HRGYIZFW2', '', 'XL5ZJWK4DO', 'complete', 'Active', '', 'unverified', NULL),
(145, '5761490639', 'VTU-Abdulsalami Ismaila', '9PSB Bank', 'R-ZBPLNOVZJC', 'Abdulsalami', 'Ismaila', 'abdulsalamiismaila8@gmail.com', '09029202858', '$2y$10$dSQTzRZsSGnqPK.h5TXXh.TH/zcPtX0z2FJdqzrFlV/m8hzuKBY1e', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', 'af9922d9cfb14a9da86829f8485134f2', 'GA3C2T1FN8', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(179, '5761525724', 'VTU-Stacy Spencer', '9PSB Bank', 'R-JPQMONHLWF', 'Stacy', 'Spencer', 'duvuba@gmail.com', '09005648888', '$2y$10$u7yByZ0ZDGb0F6aDbwfOUOXQcGGbqjmyVVGYpXw9Ryy4GStCHVRQ6', 'Opay', '9876543456', '$2y$10$r/GtFOrjTjGCEp7//xoTbelkErl6FD/eMyv74ob/9nNZYHpDRkHFm', '923 White Old Boulevard', 'Kogi', '', 'Ijumu', 'uploads/default.png', NULL, '2025-06-14 21:10:25', '3668197e736c6474910dcf53083b3f9f', 'OYFGEV9KQM', '', 'XL5ZJWK4DO', 'complete', 'Banned', '', 'unverified', NULL),
(187, '5761552748', 'VTU-Jamal Jugulde', '9PSB Bank', 'R-SPSNWVUCTU', 'Jamal', 'Jugulde', 'majugulde03@gmail.com', '07012589879', '$2y$10$BJyFCfypbUzymQbf7/Y/D.Rb5eDjtH27WxIIRuImoKML.CmomzhqC', 'Opay', '7012589879', '$2y$10$QPFHHNxqJk1vF7P31J8lLuZsfk0b8OiDnCNybXl2YKpMe52xkdJdO', 'Samunaka Junction, Jalingo', 'Taraba', '', 'Sardauna', 'uploads/default.png', NULL, '2025-06-15 19:53:57', 'e6fcfac8a582baa3d5ebe083c1fba0ae', '79SHT013JG', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(192, NULL, '', '', '', '', '', 'conah@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 20:19:57', 'a25376e60525da2753b34f4a925cf535', NULL, '', NULL, 'incomplete', 'Active', NULL, 'unverified', NULL),
(193, '5761865848', 'VTU-Amanda Morgan', '9PSB Bank', 'R-VDUDLQTHYH', 'Amanda', 'Morgan', 'boze@gmail.com', '08053015847', '$2y$10$U8Q4Ma3WmvXzIUiA9JChTuP1Aqny9aeIywVuexArCy5WzsoSB.xb6', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', '2bb3a830f3f307adf52479cafa08e8f6', 'Z91UXPK0SR', '', NULL, 'complete', 'Active', NULL, 'unverified', NULL),
(194, NULL, '', '', '', '', '', 'vebec@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 20:43:48', '5a84a2e7e88f151ab72a8f427adf21ef', NULL, '', NULL, 'incomplete', 'Active', NULL, 'unverified', NULL),
(195, '6652566487', 'BillStack/VTU-Eve', 'PalmPay', 'R-DCGHWVUNTB', 'Eve', 'Mcbride', 'coci@gmail.com', '08045551473', '$2y$10$3V63jqgKYg5JDqCGrR5azObelo.0bcZ78SOAt9QtmNKlsTubNCJ0u', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:06:21', '85921dae64d7582397209c223f31d551', 'OS9K7V1QJF', '', NULL, 'complete', 'Active', NULL, 'unverified', NULL),
(196, '6628545646', 'BillStack/VTU-Abdullahi', 'PalmPay', 'R-MBCERHNFVX', 'Abdullahi', 'Kabri', 'abdullahikabri@gmail.com', '07078701331', '$2y$10$v9rc961VyLF5ljQiWLBQP..Qx.SbmW7/DoaY5U.bi2dQwDRX4E9Rm', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-15 03:58:56', 'e22655019cc033cf9a5a4be9ed94d5ff', 'KMTJAUNZ3F', '', NULL, 'complete', 'Active', NULL, 'unverified', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `user_id` int(11) NOT NULL,
  `biometrics_enabled` tinyint(1) DEFAULT 0,
  `hide_balance` tinyint(1) DEFAULT 0,
  `session_expiry_enabled` tinyint(1) DEFAULT 1,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`user_id`, `biometrics_enabled`, `hide_balance`, `session_expiry_enabled`, `updated_at`, `ip_address`) VALUES
(136, 0, 0, 1, '2025-06-15 06:26:43', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `variations`
--

CREATE TABLE `variations` (
  `id` int(11) NOT NULL,
  `service_id` varchar(50) DEFAULT NULL,
  `variation_code` varchar(50) DEFAULT NULL,
  `name` varchar(100) DEFAULT NULL,
  `amount` decimal(10,2) DEFAULT NULL,
  `fixed_price` tinyint(1) DEFAULT 1,
  `provider_id` int(11) DEFAULT NULL,
  `validity` varchar(50) DEFAULT NULL,
  `updated_at` datetime DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_balance`
--
ALTER TABLE `account_balance`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `forgot_password`
--
ALTER TABLE `forgot_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `nigerian_states`
--
ALTER TABLE `nigerian_states`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notifications`
--
ALTER TABLE `notifications`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `referral_reward`
--
ALTER TABLE `referral_reward`
  ADD PRIMARY KEY (`referral_id`);

--
-- Indexes for table `services`
--
ALTER TABLE `services`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `slug` (`slug`);

--
-- Indexes for table `service_plans`
--
ALTER TABLE `service_plans`
  ADD PRIMARY KEY (`id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `provider_id` (`provider_id`);

--
-- Indexes for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_service_providers` (`service_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `plan_id` (`plan_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `user_settings`
--
ALTER TABLE `user_settings`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `variations`
--
ALTER TABLE `variations`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_balance`
--
ALTER TABLE `account_balance`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `forgot_password`
--
ALTER TABLE `forgot_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `nigerian_states`
--
ALTER TABLE `nigerian_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=109;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=141;

--
-- AUTO_INCREMENT for table `referral_reward`
--
ALTER TABLE `referral_reward`
  MODIFY `referral_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `service_plans`
--
ALTER TABLE `service_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=63;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;

--
-- AUTO_INCREMENT for table `variations`
--
ALTER TABLE `variations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

