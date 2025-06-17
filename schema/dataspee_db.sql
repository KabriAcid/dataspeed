-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 14, 2025 at 04:59 AM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.1.6

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
  `email` varchar(255) NOT NULL,
  `phone_number` varchar(11) NOT NULL,
  `wallet_balance` decimal(15,2) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `account_balance`
--

INSERT INTO `account_balance` (`account_id`, `user_id`, `email`, `phone_number`, `wallet_balance`, `updated_at`) VALUES
(1, 136, '', '', '94000.00', '2025-06-14 02:55:55'),
(2, 187, '', '', '17150.00', '2025-06-10 01:37:33');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `nigerian_states`
--

CREATE TABLE `nigerian_states` (
  `id` int(11) NOT NULL,
  `state_name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

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
  `color` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `color`, `icon`, `is_read`, `created_at`) VALUES
(1, 139, 'Virtual Account Created', 'Congratulations! Virtual account has been created successfully', 'virtual_account', '', 'fa-account', 0, '2025-05-20 00:05:07'),
(2, 139, 'Referral Bonus', 'You have successfully redeemed your referral bonus and was added to your balance.', 'refferal_bonus', '', 'fa-coins', 0, '2025-05-20 00:56:00'),
(3, 144, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', '', 'fa-home', 0, '2025-05-26 20:45:38'),
(22, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'text-success', 'ni ni-diamond', 0, '2025-05-28 02:59:25'),
(23, 145, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', '', 'fa-home', 0, '2025-05-30 12:04:36'),
(24, 179, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', '', 'fa-home', 0, '2025-06-01 08:26:44'),
(25, 187, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', '', 'fa-home', 0, '2025-06-09 22:45:01'),
(26, 187, 'Airtime Purchase Successful', 'You have purchased airtime for &#8358;5000', 'airtime_purchase', '', 'fa-check', 0, '2025-06-10 01:31:22'),
(27, 187, 'Airtime Purchase Successful', 'You have purchased airtime for 500', 'airtime_purchase', '', 'fa-check', 0, '2025-06-10 01:33:50'),
(28, 187, 'Airtime Purchase Successful', 'You have purchased airtime for 500', 'airtime_purchase', '', 'fa-check', 0, '2025-06-10 01:34:31'),
(29, 187, 'Airtime Purchase Successful', 'You have purchased airtime for 50', 'airtime_purchase', '', 'fa-check', 0, '2025-06-10 01:37:33'),
(30, 136, 'Airtime Purchase Failed', 'Your airtime purchase of ₦1,000.00 for 07037943396 on GLO was not successful. Reason: TRANSACTION FAILED', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-14 01:37:00'),
(31, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦500.00 for 07037943396 on AIRTEL failed. Reason: TRANSACTION FAILED', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-14 01:47:03'),
(32, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: TRANSACTION FAILED', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-14 01:48:46'),
(33, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be a duplicate. Please check your transaction history before retrying.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-14 01:48:47'),
(34, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be a duplicate. Please check your transaction history before retrying.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-14 01:48:48'),
(35, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be a duplicate. Please check your transaction history before retrying.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-14 01:48:49'),
(36, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be a duplicate. Please check your transaction history before retrying.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-14 01:48:50'),
(37, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦5,000.00 for 00803063154 on AIRTEL failed. Reason: TRANSACTION FAILED', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-14 01:52:13'),
(38, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦2,000.00 for 07012589879 on MTN failed. Reason: TRANSACTION FAILED', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-14 01:54:08'),
(39, 136, 'Airtime Purchase Successful', 'You have purchased ₦5,000.00 airtime for 08011111111 on AIRTEL.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-06-14 01:55:52'),
(40, 136, 'Airtime Purchase Successful', 'You have purchased ₦1,350.00 airtime for 08011111111 on MTN.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-06-14 02:00:44'),
(41, 136, 'Airtime Purchase Successful', 'You have purchased ₦1,000.00 airtime for 08011111111 on MTN.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-06-14 02:32:33'),
(42, 136, 'Transfer Sent', 'You sent ₦1,000.00 to musajidder@gmail.com.', 'Money Transferred', 'text-warning', 'ni-send', 0, '2025-06-14 02:47:43'),
(43, 133, 'Transfer Received', 'You received ₦1,000.00 from user musajidder@gmail.com.', 'Money Received', 'text-success', 'ni-send', 0, '2025-06-14 02:47:43'),
(44, 136, 'Transfer Sent', 'You sent ₦1,000.00 to musajidder@gmail.com.', 'Money Transferred', 'text-warning', 'ni-send', 0, '2025-06-14 02:53:01'),
(45, 133, 'Transfer Received', 'You received ₦1,000.00 from user musajidder@gmail.com.', 'Money Received', 'text-success', 'ni-send', 0, '2025-06-14 02:53:01'),
(46, 136, 'Transfer Sent', 'You sent ₦3,000.00 to musajidder@gmail.com.', 'Money Transferred', 'text-warning', 'ni-send', 0, '2025-06-14 02:55:55'),
(47, 133, 'Transfer Received', 'You received ₦3,000.00 from user musajidder@gmail.com.', 'Money Received', 'text-success', 'ni-send', 0, '2025-06-14 02:55:55');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `email`, `otp_code`, `expires_at`, `last_resend_time`, `created_at`) VALUES
(124, 'caxu@gmail.com', '343465', '2025-06-02 03:09:46', '0000-00-00 00:00:00', '2025-06-02 01:45:40'),
(127, 'hojygywa@gmail.com', '896385', '2025-06-03 22:44:08', '0000-00-00 00:00:00', '2025-06-03 21:34:08'),
(128, 'bomysil@gmail.com', '703164', '2025-06-03 22:44:27', '0000-00-00 00:00:00', '2025-06-03 21:34:27'),
(129, 'kuxadama@gmail.com', '829605', '2025-06-03 22:44:56', '0000-00-00 00:00:00', '2025-06-03 21:34:56');

-- --------------------------------------------------------

--
-- Table structure for table `referral_reward`
--

CREATE TABLE `referral_reward` (
  `referral_id` int(11) NOT NULL,
  `reward` decimal(10,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` enum('claimed','pending') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `referral_reward`
--

INSERT INTO `referral_reward` (`referral_id`, `reward`, `user_id`, `status`, `created_at`) VALUES
(9, '100.00', 133, 'claimed', '2025-05-20 04:53:16'),
(10, '100.00', 136, 'claimed', '2025-05-28 04:34:36'),
(11, '100.00', 136, 'claimed', '2025-06-01 08:49:40');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE TABLE `services` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `slug`, `created_at`) VALUES
(1, 'Data', 'data', '2025-05-15 21:12:19'),
(2, 'Airtime', 'airtime', '2025-05-15 21:12:19'),
(3, 'Electricity', 'electricity', '2025-05-30 20:44:35'),
(4, 'TV', 'bills', '2025-05-30 20:44:35');

-- --------------------------------------------------------

--
-- Table structure for table `service_plans`
--

CREATE TABLE `service_plans` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `api_id` int(11) NOT NULL,
  `volume` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `validity` varchar(50) NOT NULL,
  `type` enum('daily','weekly','monthly') DEFAULT 'daily',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `service_plans`
--

INSERT INTO `service_plans` (`id`, `service_id`, `provider_id`, `api_id`, `volume`, `price`, `validity`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 1, '4GB', '550.00', '24hrs', 'daily', 1, '2025-05-30 20:43:09', '2025-06-09 00:41:21'),
(3, 1, 1, 1, '1GB', '250.00', '24hrs', 'daily', 1, '2025-05-30 20:43:09', '2025-06-09 00:42:07'),
(4, 1, 1, 1, '5GB', '1550.00', '', 'monthly', 1, '2025-05-30 20:43:09', '2025-06-06 05:55:51'),
-- MTN Data
(5, 1, 1, 1, '500MB', '150.00', '1 day', 'daily', 1, NOW(), NOW()),
(6, 1, 1, 1, '2GB', '500.00', '7 days', 'weekly', 1, NOW(), NOW()),
(7, 1, 1, 1, '10GB', '2500.00', '30 days', 'monthly', 1, NOW(), NOW()),
-- Airtel Data
(8, 1, 2, 2, '1.5GB', '300.00', '7 days', 'weekly', 1, NOW(), NOW()),
(9, 1, 2, 2, '3GB', '900.00', '30 days', 'monthly', 1, NOW(), NOW()),
-- Glo Data
(10, 1, 3, 3, '1GB', '200.00', '1 day', 'daily', 1, NOW(), NOW()),
(11, 1, 3, 3, '5GB', '1200.00', '14 days', 'weekly', 1, NOW(), NOW()),
-- 9mobile Data
(12, 1, 4, 4, '2GB', '700.00', '7 days', 'weekly', 1, NOW(), NOW()),
(13, 1, 4, 4, '11GB', '3000.00', '30 days', 'monthly', 1, NOW(), NOW()),

-- Electricity (service_id=3)
(14, 3, 1, 1, 'Prepaid - Ikeja Electric', '1000.00', 'N/A', 'monthly', 1, NOW(), NOW()),
(15, 3, 2, 2, 'Postpaid - Eko Electric', '2000.00', 'N/A', 'monthly', 1, NOW(), NOW()),
(16, 3, 3, 3, 'Prepaid - Abuja Electric', '1500.00', 'N/A', 'monthly', 1, NOW(), NOW()),

-- TV (service_id=4)
(17, 4, 1, 1, 'DSTV Padi', '2500.00', '30 days', 'monthly', 1, NOW(), NOW()),
(18, 4, 1, 1, 'DSTV Yanga', '3500.00', '30 days', 'monthly', 1, NOW(), NOW()),
(19, 4, 1, 1, 'GOTV Jolli', '2460.00', '30 days', 'monthly', 1, NOW(), NOW()),
(20, 4, 1, 1, 'Startimes Basic', '1700.00', '30 days', 'monthly', 1, NOW(), NOW());
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `service_providers`
--

INSERT INTO `service_providers` (`id`, `service_id`, `name`, `code`, `brand_color`, `slug`) VALUES
(1, 1, 'MTN', 1, '#F4F4F4', NULL);

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
  `type` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL DEFAULT 'ni ni-default',
  `color` varchar(50) NOT NULL DEFAULT 'text-dark',
  `direction` enum('credit','debit') NOT NULL DEFAULT 'debit',
  `description` varchar(100) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `reference` varchar(100) NOT NULL,
  `status` enum('success','fail') NOT NULL DEFAULT 'fail',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `service_id`, `provider_id`, `plan_id`, `type`, `icon`, `color`, `direction`, `description`, `amount`, `email`, `reference`, `status`, `created_at`) VALUES
(1, 136, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', '200.00', NULL, '0', 'success', '2025-06-09 22:41:31'),
(2, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', '5000.00', NULL, '0', 'success', '2025-06-09 22:48:45'),
(3, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', '100.00', NULL, '0', 'success', '2025-06-10 00:10:20'),
(4, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', '200.00', NULL, '0', 'success', '2025-06-10 00:13:16'),
(5, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', '500.00', NULL, '0', 'success', '2025-06-10 01:27:59'),
(6, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', '1000.00', 'majugulde03@gmail.com', '0', 'success', '2025-06-10 01:30:21'),
(7, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', '5000.00', 'majugulde03@gmail.com', '0', 'success', '2025-06-10 01:31:22'),
(8, 187, 2, NULL, NULL, 'Airtime Other', '', '', 'debit', '', '500.00', 'majugulde03@gmail.com', '0', 'success', '2025-06-10 01:33:50'),
(9, 187, 2, NULL, NULL, 'Airtime Other', '', '', 'debit', '', '500.00', 'majugulde03@gmail.com', '0', 'success', '2025-06-10 01:34:31'),
(10, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', '50.00', 'majugulde03@gmail.com', '0', 'success', '2025-06-10 01:37:33'),
(12, 136, 1, 3, NULL, 'Airtime self', 'ni ni-default', 'text-dark', 'debit', '', '1000.00', 'kabriacid01@gmail.com', 'airtime_684cd23036ff32.62873582', '', '2025-06-14 01:37:00'),
(13, 136, 1, 2, NULL, 'Airtime self', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦500.00 for 07037943396 on AIRTEL failed. Reason: TRANSACTION FAILED', '500.00', 'kabriacid01@gmail.com', 'airtime_684cd48acafc64.07731635', '', '2025-06-14 01:47:03'),
(14, 136, 1, 3, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: TRANSACTION FAILED', '2000.00', 'kabriacid01@gmail.com', 'airtime_684cd4f1f256e9.50043283', '', '2025-06-14 01:48:45'),
(15, 136, 1, 3, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be ', '2000.00', 'kabriacid01@gmail.com', 'airtime_684cd4fe627216.82721378', '', '2025-06-14 01:48:47'),
(16, 136, 1, 3, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be ', '2000.00', 'kabriacid01@gmail.com', 'airtime_684cd4ff6b2a25.40973241', '', '2025-06-14 01:48:48'),
(17, 136, 1, 3, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be ', '2000.00', 'kabriacid01@gmail.com', 'airtime_684cd5008c1f13.52555147', '', '2025-06-14 01:48:49'),
(18, 136, 1, 3, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be ', '2000.00', 'kabriacid01@gmail.com', 'airtime_684cd5019273b4.47253417', '', '2025-06-14 01:48:50'),
(19, 136, 1, 2, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦5,000.00 for 00803063154 on AIRTEL failed. Reason: TRANSACTION FAILED', '5000.00', 'kabriacid01@gmail.com', 'airtime_684cd5c1721009.05128222', '', '2025-06-14 01:52:13'),
(20, 136, 1, 1, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦2,000.00 for 07012589879 on MTN failed. Reason: TRANSACTION FAILED', '2000.00', 'kabriacid01@gmail.com', 'airtime_684cd6343752b7.90314628', '', '2025-06-14 01:54:08'),
(21, 136, 1, 2, NULL, 'Airtime self', 'ni ni-mobile-button', 'text-success', 'debit', 'You have purchased ₦5,000.00 airtime for 08011111111 on AIRTEL.', '5000.00', 'kabriacid01@gmail.com', 'airtime_684cd6a046ba31.61032287', 'success', '2025-06-14 01:55:52'),
(22, 136, 1, 1, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-success', 'debit', 'You have purchased ₦1,350.00 airtime for 08011111111 on MTN.', '1350.00', 'kabriacid01@gmail.com', 'airtime_684cd7c36313b3.54202473', 'success', '2025-06-14 02:00:44'),
(23, 136, 1, 1, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-success', 'debit', 'You have purchased ₦1,000.00 airtime for 08011111111 on MTN.', '1000.00', 'kabriacid01@gmail.com', 'airtime_684cdf38324b21.86687482', 'success', '2025-06-14 02:32:33'),
(24, 136, 0, NULL, NULL, 'Money Transfer', 'ni-send', 'text-warning', 'debit', 'Transfer to musajidder@gmail.com', '1000.00', NULL, '', 'success', '2025-06-14 02:47:43'),
(25, 133, 0, NULL, NULL, 'Money Transfer', 'ni-send', 'text-success', 'credit', 'Received transfer from user 136', '1000.00', NULL, '', 'success', '2025-06-14 02:47:43'),
(26, 136, 0, NULL, NULL, 'Money Transfer', 'ni-send', 'text-warning', 'debit', 'Transfer to musajidder@gmail.com', '1000.00', 'kabriacid01@gmail.com', 'transfer_684ce40d8a2972.68551337', 'success', '2025-06-14 02:53:01'),
(27, 133, 0, NULL, NULL, 'Money Transfer', 'ni-send', 'text-success', 'credit', 'Received transfer from user 136', '1000.00', 'musajidder@gmail.com', 'transfer_684ce40d8a2972.68551337', 'success', '2025-06-14 02:53:01'),
(28, 136, 0, NULL, NULL, 'Money Transfer', 'ni-send', 'text-warning', 'debit', 'Transfer to musajidder@gmail.com', '3000.00', 'kabriacid01@gmail.com', 'transfer_684ce4bb2ffc09.96864282', 'success', '2025-06-14 02:55:55'),
(29, 133, 0, NULL, NULL, 'Money Transfer', 'ni-send', 'text-success', 'credit', 'Received transfer from user 136', '3000.00', 'musajidder@gmail.com', 'transfer_684ce4bb2ffc09.96864282', 'success', '2025-06-14 02:55:55');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `virtual_account`, `account_name`, `bank_name`, `billstack_ref`, `first_name`, `last_name`, `email`, `phone_number`, `password`, `w_bank_name`, `w_account_number`, `txn_pin`, `address`, `state`, `country`, `city`, `photo`, `updated_at`, `created_at`, `registration_id`, `referral_code`, `referral_link`, `referred_by`, `registration_status`, `account_status`, `kyc_value`, `kyc_status`, `kyc_type`) VALUES
(115, '2147483647', 'BillStack/VTU-Katelyn', 'PalmPay', 'R-FPAWMNYEDW', 'Katelyn', 'Guzman', 'zoxalon@gmail.com', '8011737992', '$2y$10$s.QwmCU.4t91qmIomXOJXemvFFObyah/RsIWLQmiUerezM3N6PIG.', '', '', '1111', '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'afdf227ba83dff6e5951c53c73bdf0ec', 'UXIZW9T1GK', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(131, '5761210754', 'VTU-Hiroko Mclaughlin', '9PSB Bank', 'R-PRZTYEMAHS', 'Hiroko', 'Mclaughlin', 'xehemexa@gmail.com', '8085311846', '$2y$10$eHvzv7/KbZmv0f0ZQUNEJeptrbE46oNkw.CZlDYOh7Z38bBNkSxYm', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'b1c1be0f5a2f3971b0e14a074ebd70e4', 'H41SILO96F', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(132, '5761207770', 'VTU-Orson Head', '9PSB Bank', 'R-VTPXCKSJYV', 'Rebecca', 'Dickerson', 'muhammadmjidder@gmail.com', '8038851880', '$2y$10$HbwMQiD0.N2mdHvSGSvHDOricGNaKwB0S.8UqgIkHII6hHLZu/2D2', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '81c6536766fc6acaea9ba82fd4d548b2', '0KLFPDQ43H', '', 'H41SILO96F', 'complete', 'Active', '', 'unverified', NULL),
(133, '5761212820', 'VTU-Britanni Downs', '9PSB Bank', 'R-SDYNQCBSZV', 'Musa', 'Jidder', 'musajidder@gmail.com', '8076574147', '$2y$10$HPgBE21jeE5LYkvpTDXu7OZ2e4P.Jqa6LNNYDn/fB2UDnATvKNl4S', '', '', '1090', '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'ebaeaaeac1ae51166c60916c1e85b765', '1Q9764VM5R', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(135, '5761221301', 'VTU-Colette Chase', '9PSB Bank', 'R-WEHVXNMRDG', 'Colette', 'Chase', 'musa@gmail.com', '8040993201', '$2y$10$hXdg2zGjrosN0/Yy.5qXWOuuA11h2dxxTu47Vg0a7S44oOTZlnSkC', '', '', '$2y$10$Du4Jg.Y8yC5DSExH.DeJP.kO9pz.KyMXYCzkNVnXzYlviDqqcCcji', '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '9e1948165924f8bbef7b11ed0c9cc37a', 'NBDJXG2K10', '', '1Q9764VM5R', 'complete', 'Active', '', 'unverified', NULL),
(136, '5761257050', 'VTU-Jenna Marshall', '9PSB Bank', 'R-ZUNTBSDTMW', 'Abdullahi', 'Kabri', 'kabriacid01@gmail.com', '7037943396', '$2y$10$O7E68yMPKY80cCx5yivgiunlTZnrHeoU6.XMbZkCQayG0nTX2NHHu', 'Opay', '8898997899', '$2y$10$NKkg/aRtaNJonZHny1V43eRRESCgCi7J/B0lxaW4hLK6f.W3k86IW', '30 White Cowley Freeway', 'FCT', '', 'Municipal Area Counc', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '949394aeb0a04a78486fd806ca7c24f1', 'XL5ZJWK4DO', '', NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(139, '5761257555', 'VTU-Chastity Obrien', '9PSB Bank', 'R-FRZBBQJNXG', 'Chastity', 'Obrien', 'vunota@gmail.com', '8023983839', '$2y$10$R8v1oCjJaMYq79lcbor5WeaT2MZxUX9gnMncA2zPeoGazeBHhe/d6', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'cf75622cfd811a09a420ba5fa329fec3', 'B1X69UVJKP', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(140, '5761257603', 'VTU-Gabriel Odonnell', '9PSB Bank', 'R-TSUTFSVWPK', 'Gabriel', 'Odonnell', 'dyjo@gmail.com', '8012721760', '$2y$10$tzHdRsqtRRlBeIyaH91yWOiX46sGdEcYH9pqqtGr2bfDIl2pSdACu', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '9f010b4dc7cc9774ec06d275248db1a9', 'VROJ2E4ULB', '', 'B1X69UVJKP', 'complete', 'Active', '', 'unverified', NULL),
(144, '5761409749', 'VTU-Muhammad Bappayo', '9PSB Bank', 'R-OPRCVBEEKV', 'Muhammad', 'Bappayo', 'alhpeace001@gmail.com', '8064509234', '$2y$10$aLDbre5oxj.cL7I3UUfYKe/e9XLx4ziDeikYJlTO.OU6v6rkDUibu', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'a8a785096dae3b93285c371f69851959', '5HRGYIZFW2', '', 'XL5ZJWK4DO', 'complete', 'Active', '', 'unverified', NULL),
(145, '5761490639', 'VTU-Abdulsalami Ismaila', '9PSB Bank', 'R-ZBPLNOVZJC', 'Abdulsalami', 'Ismaila', 'abdulsalamiismaila8@gmail.com', '9029202858', '$2y$10$dSQTzRZsSGnqPK.h5TXXh.TH/zcPtX0z2FJdqzrFlV/m8hzuKBY1e', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'af9922d9cfb14a9da86829f8485134f2', 'GA3C2T1FN8', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(179, '5761525724', 'VTU-Stacy Spencer', '9PSB Bank', 'R-JPQMONHLWF', 'Stacy', 'Spencer', 'duvuba@gmail.com', '9005648888', '$2y$10$u7yByZ0ZDGb0F6aDbwfOUOXQcGGbqjmyVVGYpXw9Ryy4GStCHVRQ6', 'Opay', '9876543456', '$2y$10$r/GtFOrjTjGCEp7//xoTbelkErl6FD/eMyv74ob/9nNZYHpDRkHFm', '923 White Old Boulevard', 'Kogi', '', 'Ijumu', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '3668197e736c6474910dcf53083b3f9f', 'OYFGEV9KQM', '', 'XL5ZJWK4DO', 'complete', 'Banned', '', 'unverified', NULL),
(187, '5761552748', 'VTU-Jamal Jugulde', '9PSB Bank', 'R-SPSNWVUCTU', 'Jamal', 'Jugulde', 'majugulde03@gmail.com', '7012589879', '$2y$10$BJyFCfypbUzymQbf7/Y/D.Rb5eDjtH27WxIIRuImoKML.CmomzhqC', 'Opay', '7012589879', '$2y$10$QPFHHNxqJk1vF7P31J8lLuZsfk0b8OiDnCNybXl2YKpMe52xkdJdO', 'Samunaka Junction, Jalingo', 'Taraba', '', 'Sardauna', 'uploads/default.png', NULL, '2025-06-10 01:43:15', 'e6fcfac8a582baa3d5ebe083c1fba0ae', '79SHT013JG', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(188, NULL, '', '', '', 'Keegan', 'Snow', 'watulipe@gmail.com', '8052351598', '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-12 17:21:27', '43aae076197f597aac9937edf6f6d601', NULL, '', NULL, 'incomplete', 'Active', NULL, 'unverified', NULL),
(189, NULL, '', '', '', '', '', 'zainababdullahi2003@gmail.com', '9076567895', '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-13 22:52:12', 'b9819072e7bfd0a001969cc3c0c0775d', NULL, '', NULL, 'incomplete', 'Active', NULL, 'unverified', NULL);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_balance`
--
ALTER TABLE `account_balance`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=48;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=134;

--
-- AUTO_INCREMENT for table `referral_reward`
--
ALTER TABLE `referral_reward`
  MODIFY `referral_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_plans`
--
ALTER TABLE `service_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=190;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `service_plans`
--
ALTER TABLE `service_plans`
  ADD CONSTRAINT `service_plans_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `service_plans_ibfk_2` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD CONSTRAINT `fk_service_providers` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
