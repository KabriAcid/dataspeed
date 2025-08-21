-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 17, 2025 at 05:17 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_balance`
--

INSERT INTO `account_balance` (`account_id`, `user_id`, `email`, `phone_number`, `wallet_balance`, `updated_at`) VALUES
(1, 136, '', '', 34457.00, '2025-07-30 14:54:20'),
(2, 187, '', '', 21150.00, '2025-06-23 16:51:21'),
(4, 222, 'abdulsalamiismaila@gmail.com', '07033398766', 12000.00, '2025-07-12 20:18:30'),
(5, 236, 'weqyrod@gmail.com', '09062128726', 4950.00, '2025-06-26 05:44:39'),
(6, 139, 'haje@gmail.com', '09048448693', 14050.00, '2025-06-26 13:15:00'),
(7, 242, 'jepacibet@gmail.com', '08095784833', 3450.00, '2025-07-13 18:53:15'),
(8, 244, 'zzetim@gmail.com', '08041375606', 0.00, '2025-07-16 07:10:37'),
(9, 246, 'johik@gmail.com', '09090779344', 0.00, '2025-07-25 05:07:47'),
(10, 219, 'rademu910@gmail.com', '09045144840', 0.00, '2025-07-31 08:22:53'),
(11, 258, 'zuruvo@gmail.com', '08028596499', 901.00, '2025-08-15 05:57:42'),
(12, 259, 'abdullahikabri@gmail.com', '09113369958', 100.00, '2025-08-14 03:52:06');

-- --------------------------------------------------------

--
-- Table structure for table `account_complaints`
--

CREATE TABLE `account_complaints` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` enum('pending','resolved','rejected') DEFAULT 'pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_complaints`
--

INSERT INTO `account_complaints` (`id`, `user_id`, `reason`, `status`, `created_at`, `updated_at`) VALUES
(1, 136, 'Forgot PIN', 'rejected', '2025-07-12 20:30:01', '2025-07-12 21:12:37'),
(5, 136, 'Forgot PIN', 'rejected', '2025-07-12 21:48:30', '2025-07-12 21:48:30'),
(6, 136, 'Multiple failed PIN attempts', 'resolved', '2025-07-12 21:52:31', '2025-07-16 06:14:33'),
(7, 136, 'Multiple failed PIN attempts', 'pending', '2025-07-16 06:20:38', '2025-07-16 06:20:38'),
(8, 258, 'Forgot PIN', 'pending', '2025-08-15 04:33:14', '2025-08-15 04:33:14'),
(9, 258, 'Forgot PIN', 'pending', '2025-08-15 04:34:21', '2025-08-15 04:34:21'),
(10, 259, 'Forgot PIN', 'pending', '2025-08-16 21:20:03', '2025-08-16 21:20:03');

-- --------------------------------------------------------

--
-- Table structure for table `account_reset_tokens`
--

CREATE TABLE `account_reset_tokens` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `token` varchar(255) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_reset_tokens`
--

INSERT INTO `account_reset_tokens` (`id`, `user_id`, `token`, `expires_at`, `created_at`) VALUES
(9, 258, 'aa0c94e87d4938ea00919e0f13bf9ca52d2c15c5aacabf1c3a12638e1a45763c', '2025-08-15 06:34:52', '2025-08-15 04:33:14'),
(10, 258, '796c5c757c725dffb774a4c11c94045d4fe125589d978fae69c416a36fcfdeec', '2025-08-15 06:34:52', '2025-08-15 04:34:21'),
(11, 259, 'bb2883d293783d8503ea69852b4bd1e828b27251409d44f888879edd0759e843', '2025-08-17 00:07:00', '2025-08-16 21:20:03');

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `username` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password` varchar(255) NOT NULL,
  `passphrase` varchar(255) DEFAULT NULL,
  `role` enum('superadmin','admin','support','auditor') NOT NULL DEFAULT 'admin',
  `status` enum('active','inactive','suspended') NOT NULL DEFAULT 'active',
  `otp_secret` varchar(64) DEFAULT NULL,
  `failed_attempts` int(11) NOT NULL DEFAULT 0,
  `locked_until` datetime DEFAULT NULL,
  `last_login_at` datetime DEFAULT NULL,
  `passphrase_updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `username`, `email`, `password`, `passphrase`, `role`, `status`, `otp_secret`, `failed_attempts`, `locked_until`, `last_login_at`, `passphrase_updated_at`, `created_at`, `updated_at`) VALUES
(1, 'System Admin', '', 'dataspeedcontact@gmail.com', '$2y$10$YD0G4qHhRjGwL5iYhNaWheim0v.bT19cNJb0ehPmlAOU842VCwgOW', '$2y$10$LT1HtNPp8k5GmPh5LF.YPOzO9kueTIp9BdhTOPxxKVJuijLvGB9he', 'superadmin', 'active', NULL, 0, NULL, '2025-08-16 16:43:42', NULL, '2025-08-14 00:57:02', '2025-08-16 15:43:42'),
(2, 'Gay Shields', '', 'wyfofycyx@gmail.com', '$2y$10$0Cbpubu/43k0st6PnIr5e.mR48o49SvMEov6ErDKZVoO.am3lgc3u', 'filashi', '', 'active', NULL, 0, NULL, NULL, NULL, '2025-08-16 03:12:00', '2025-08-16 03:56:20');

-- --------------------------------------------------------

--
-- Table structure for table `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `admin_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `entity_type` varchar(50) DEFAULT NULL,
  `entity_id` int(11) DEFAULT NULL,
  `details` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`details`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `admin_id`, `action`, `entity_type`, `entity_id`, `details`, `ip_address`, `created_at`) VALUES
(1, 1, 'login', 'admin_session', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/138.0.0.0 Safari\\/537.36\"}', '::1', '2025-08-14 00:59:11'),
(2, 1, 'login', 'admin_session', NULL, '{\"ip\":\"::1\",\"user_agent\":\"Mozilla\\/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit\\/537.36 (KHTML, like Gecko) Chrome\\/138.0.0.0 Safari\\/537.36\"}', '::1', '2025-08-14 04:48:42');

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

--
-- Dumping data for table `forgot_password`
--

INSERT INTO `forgot_password` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(6, 'kabriacid01@gmail.com', 'dd30087666de76a78b3530057faf3a37', '2025-06-19 11:53:07', '2025-06-19 10:43:07'),
(7, 'kabriacid01@gmail.com', 'ad1432598f8a6c4260e6383342c77011', '2025-06-19 12:31:16', '2025-06-19 11:21:16');

-- --------------------------------------------------------

--
-- Table structure for table `forgot_pin`
--

CREATE TABLE `forgot_pin` (
  `id` int(11) NOT NULL,
  `email` varchar(100) NOT NULL,
  `token` varchar(100) NOT NULL,
  `expires_at` datetime NOT NULL DEFAULT current_timestamp(),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `forgot_pin`
--

INSERT INTO `forgot_pin` (`id`, `email`, `token`, `expires_at`, `created_at`) VALUES
(2, 'kabriacid01@gmail.com', '482994acf13253173a06d68c9a0b1995', '2025-07-13 01:20:41', '2025-07-13 00:10:41'),
(3, 'kabriacid01@gmail.com', '01515987d70094e235734c2fb094ed13', '2025-07-13 01:22:11', '2025-07-13 00:12:11'),
(4, 'kabriacid01@gmail.com', '94d478ea5bdd561fdffbe1f8c75a1eec', '2025-07-13 01:24:40', '2025-07-13 00:14:40'),
(5, 'kabriacid01@gmail.com', '99341abe0e1c94e527137169acdb4ddd', '2025-07-13 01:26:11', '2025-07-13 00:16:11'),
(6, 'ademu0882@gmail.com', 'c7f86365d0b00a8120638a056c67fc9a', '2025-07-25 06:18:25', '2025-07-25 05:08:25');

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
  `color` varchar(50) NOT NULL,
  `icon` varchar(50) NOT NULL,
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
(37, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be a duplicate. Please check your transaction history before retrying.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-14 01:48:51'),
(38, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦5,000.00 for 00803063154 on AIRTEL failed. Reason: TRANSACTION FAILED', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-14 01:52:13'),
(39, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦2,000.00 for 07012589879 on MTN failed. Reason: TRANSACTION FAILED', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-14 01:54:08'),
(40, 136, 'Airtime Purchase Successful', 'You have purchased ₦5,000.00 airtime for 08011111111 on AIRTEL.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-06-14 01:55:52'),
(41, 136, 'Airtime Purchase Successful', 'You have purchased ₦1,350.00 airtime for 08011111111 on MTN.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-06-14 02:00:44'),
(42, 136, 'Airtime Purchase Successful', 'You have purchased ₦1,000.00 airtime for 08011111111 on MTN.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-06-14 02:32:33'),
(43, 136, 'Transfer Sent', 'You sent ₦1,000.00 to musajidder@gmail.com.', 'Money Transferred', 'text-warning', 'ni-send', 0, '2025-06-14 02:47:43'),
(44, 133, 'Transfer Received', 'You received ₦1,000.00 from user musajidder@gmail.com.', 'Money Received', 'text-success', 'ni-send', 0, '2025-06-14 02:47:43'),
(45, 136, 'Transfer Sent', 'You sent ₦1,000.00 to musajidder@gmail.com.', 'Money Transferred', 'text-warning', 'ni-send', 0, '2025-06-14 02:53:01'),
(46, 133, 'Transfer Received', 'You received ₦1,000.00 from user musajidder@gmail.com.', 'Money Received', 'text-success', 'ni-send', 0, '2025-06-14 02:53:01'),
(47, 136, 'Transfer Sent', 'You sent ₦3,000.00 to musajidder@gmail.com.', 'Money Transferred', 'text-warning', 'ni-send', 0, '2025-06-14 02:55:55'),
(48, 133, 'Transfer Received', 'You received ₦3,000.00 from user musajidder@gmail.com.', 'Money Received', 'text-success', 'ni-send', 0, '2025-06-14 02:55:55'),
(49, 136, 'Transfer Sent', 'You sent ₦59,553.00 to Musa Jidder.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-06-17 02:52:59'),
(50, 133, 'Transfer Received', 'You have received ₦59,553.00 from Abdullahi Kabri.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-06-17 02:52:59'),
(51, 222, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-06-17 22:36:12'),
(52, 222, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-06-17 22:36:12'),
(53, 136, 'Airtime Purchase Successful', 'You have purchased ₦1,000.00 airtime for 08011111111 on 9MOBILE.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-06-18 13:06:30'),
(54, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦5,000.00 for 07037943396 on MTN failed.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-18 13:32:24'),
(55, 136, 'Airtime Purchase Successful', 'You have purchased ₦5,000.00 airtime for 08011111111 on MTN.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-06-18 13:33:12'),
(56, 136, 'Data Purchase Failed', 'Data purchase of ₦550.00 for 07037943396 on MTN failed.', 'data_purchase_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-18 14:39:24'),
(57, 136, 'Data Purchase Successful', 'You purchased ₦550.00 data for 08011111111 on GLO.', 'data_purchase', 'text-success', 'ni ni-check-bold', 0, '2025-06-18 14:40:06'),
(58, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦5,000.00 for 07037943396 on MTN failed.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-18 14:54:27'),
(59, 136, 'TV Subscription Failed', 'TV subscription of ₦3,500.00 for IUC 9076567890 on DSTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-06-18 16:48:44'),
(60, 136, 'Transfer Sent', 'You sent ₦3,000.00 to Jamal Jugulde.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-06-18 16:57:16'),
(61, 187, 'Transfer Received', 'You have received ₦3,000.00 from Abdullahi Kabri.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-06-18 16:57:16'),
(62, 136, 'TV Subscription Failed', 'TV subscription of ₦3,500.00 for IUC 3456789854 on DSTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-06-19 04:35:40'),
(63, 136, 'TV Subscription Failed', 'TV subscription of ₦3,500.00 for IUC 3456789854 on DSTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-06-19 04:37:29'),
(64, 136, 'TV Subscription Failed', 'TV subscription of ₦3,500.00 for IUC 9876556789 on DSTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-06-19 04:54:41'),
(65, 136, 'TV Subscription Successful', 'You purchased ₦2,500.00 TV subscription for IUC 1212121212 on DSTV.', 'tv_subscription', 'text-success', 'ni ni-tv-2', 0, '2025-06-19 04:59:40'),
(66, 136, 'PIN Reset Successful', 'Your transaction PIN was reset successfully.', 'pin_reset', 'text-success', 'ni ni-key-55', 0, '2025-06-19 11:50:29'),
(67, 136, 'Data Purchase Failed', 'Data purchase of ₦550.00 for 08011111111 on MTN failed.', 'data_purchase_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-22 09:01:37'),
(68, 136, 'Address Updated', 'Your address details were updated successfully.', 'profile', 'text-dark', 'ni ni-pin-3', 0, '2025-06-22 09:20:03'),
(69, 136, 'Account Details Updated', 'Your account details were updated successfully.', 'profile', 'text-info', 'ni ni-single-02', 0, '2025-06-22 09:35:26'),
(70, 136, 'Transfer Sent', 'You sent ₦1,000.00 to Jamal Jugulde.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-06-23 16:51:21'),
(71, 187, 'Transfer Received', 'You have received ₦1,000.00 from Abdullahi Kabri.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-06-23 16:51:21'),
(72, 236, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-06-26 05:25:36'),
(73, 236, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-06-26 05:25:36'),
(74, 136, 'Transfer Sent', 'You sent ₦5,400.00 to Quemby Woodard.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-06-26 05:31:12'),
(75, 236, 'Transfer Received', 'You have received ₦5,400.00 from Abdullahi Kabri.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-06-26 05:31:12'),
(76, 236, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-06-26 05:33:59'),
(77, 236, 'TV Subscription Failed', 'TV subscription of ₦3,500.00 for IUC 1111111111 on DSTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-06-26 05:34:52'),
(78, 236, 'Data Purchase Successful', 'You purchased ₦250.00 data for 08011111111 on GLO.', 'data_purchase', 'text-success', 'ni ni-check-bold', 0, '2025-06-26 05:38:47'),
(79, 236, 'Airtime Purchase Successful', 'You have purchased ₦200.00 airtime for 08011111111 on MTN.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-06-26 05:44:39'),
(80, 236, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-06-26 05:59:52'),
(81, 236, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-06-26 11:35:53'),
(82, 236, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-06-26 11:36:50'),
(83, 237, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-06-26 12:49:20'),
(84, 237, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-06-26 12:49:21'),
(85, 139, 'Security Setting Updated', 'Session expiry is now disabled. You will remain logged in unless you log out.', 'security', 'text-warning', 'ni ni-lock-circle-open', 0, '2025-06-26 13:21:02'),
(86, 139, 'Security Setting Updated', 'Session expiry is now enabled. Your account will require re-authentication after 10 minutes of inactivity.', 'security', 'text-success', 'ni ni-lock-circle-open', 0, '2025-06-26 13:21:07'),
(87, 139, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-06-26 13:29:28'),
(88, 139, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-06-26 13:29:50'),
(89, 136, 'Account Details Updated', 'Your account details were updated successfully.', 'profile', 'text-info', 'ni ni-single-02', 0, '2025-07-11 06:36:22'),
(90, 136, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-07-11 06:37:51'),
(91, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦500.00 for 08011111111 on GLO failed.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-12 23:17:21'),
(92, 136, 'Data Purchase Failed', 'Data purchase of ₦250.00 for 07037943396 on MTN failed.', 'data_purchase_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-12 23:18:11'),
(93, 136, 'Data Purchase Successful', 'You purchased ₦150.00 data for 08011111111 on MTN.', 'data_purchase', 'text-success', 'ni ni-check-bold', 0, '2025-07-12 23:58:27'),
(94, 136, 'Data Purchase Failed', 'Data purchase of ₦150.00 for 07037943396 on MTN failed.', 'data_purchase_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-13 00:02:54'),
(95, 136, 'Data Purchase Successful', 'You purchased ₦550.00 data for 08011111111 on MTN.', 'data_purchase', 'text-success', 'ni ni-check-bold', 0, '2025-07-13 00:03:20'),
(96, 242, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-07-13 00:07:38'),
(97, 242, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-13 00:07:38'),
(98, 136, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-13 00:17:04'),
(99, 242, 'Home address Updated', 'Your address details were updated successfully.', 'profile', 'text-dark', 'ni ni-pin-3', 0, '2025-07-13 00:17:38'),
(100, 136, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-07-13 00:18:37'),
(101, 136, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-13 00:20:37'),
(102, 136, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-13 00:50:25'),
(103, 136, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-07-13 00:58:43'),
(104, 136, 'Transfer Sent', 'You sent ₦5,000.00 to Ray Solis.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-07-13 01:05:14'),
(105, 242, 'Transfer Received', 'You have received ₦5,000.00 from Abdullahi Kabri.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-07-13 01:05:14'),
(106, 136, 'Transfer Sent', 'You sent ₦3,500.00 to Ray Solis.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-07-13 01:15:33'),
(107, 242, 'Transfer Received', 'You have received ₦3,500.00 from Abdullahi Kabri.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-07-13 01:15:33'),
(108, 136, 'Transfer Sent', 'You sent ₦300.00 to Ray Solis.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-07-13 03:31:58'),
(109, 242, 'Transfer Received', 'You have received ₦300.00 from Abdullahi Kabri.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-07-13 03:31:58'),
(110, 136, 'Transfer Sent', 'You sent ₦300.00 to Ray Solis.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-07-13 03:32:57'),
(111, 242, 'Transfer Received', 'You have received ₦300.00 from Abdullahi Kabri.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-07-13 03:32:57'),
(112, 242, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-13 03:37:32'),
(113, 242, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-13 03:37:34'),
(114, 242, 'Transfer Sent', 'You sent ₦3,000.00 to Abdullahi Kabri.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-07-13 03:37:58'),
(115, 136, 'Transfer Received', 'You have received ₦3,000.00 from Ray Solis.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-07-13 03:37:59'),
(116, 242, 'Transfer Sent', 'You sent ₦200.00 to Abdullahi Kabri.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-07-13 03:44:51'),
(117, 136, 'Transfer Received', 'You have received ₦200.00 from Ray Solis.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-07-13 03:44:51'),
(118, 242, 'Transfer Sent', 'You sent ₦1,000.00 to Abdullahi Kabri.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-07-13 10:48:31'),
(119, 136, 'Transfer Received', 'You have received ₦1,000.00 from Ray Solis.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-07-13 10:48:31'),
(120, 242, 'Data Purchase Successful', 'You purchased ₦250.00 data for 08011111111 on MTN.', 'data_purchase', 'text-success', 'ni ni-check-bold', 0, '2025-07-13 10:49:36'),
(121, 242, 'Airtime Purchase Successful', 'You have purchased ₦1,000.00 airtime for 08011111111 on MTN.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-07-13 10:50:11'),
(122, 136, 'TV Subscription Failed', 'TV subscription of ₦1,900.00 for IUC 1111111111 on GOTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-07-13 11:19:33'),
(123, 242, 'TV Subscription Failed', 'TV subscription of ₦3,500.00 for IUC 1111111111 on DSTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-07-13 17:27:58'),
(124, 136, 'TV Subscription Failed', 'TV subscription of ₦2,500.00 for IUC 1111111111 on DSTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-07-13 17:45:38'),
(125, 136, 'TV Subscription Successful', 'You purchased ₦2,500.00 TV subscription for IUC 1212121212 on DSTV.', 'tv_subscription', 'text-success', 'ni ni-tv-2', 0, '2025-07-13 17:46:29'),
(126, 136, 'TV Subscription Successful', 'You purchased ₦2,500.00 TV subscription for IUC 1212121212 on DSTV.', 'tv_subscription', 'text-success', 'ni ni-tv-2', 0, '2025-07-13 17:54:43'),
(127, 242, 'Airtime Purchase Failed', 'Airtime purchase of ₦2,000.00 for 08095784833 on GLO failed.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-13 18:52:09'),
(128, 242, 'Airtime Purchase Failed', 'Airtime purchase of ₦2,000.00 for 08095784833 on GLO failed.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-13 18:52:29'),
(129, 242, 'Airtime Purchase Successful', 'You have purchased ₦200.00 airtime for 08011111111 on MTN.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-07-13 18:53:15'),
(130, 242, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-07-13 18:54:03'),
(131, 242, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-07-13 18:56:40'),
(132, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦5,000.00 for 07037943396 on MTN failed.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-16 02:24:40'),
(133, 136, 'TV Subscription Failed', 'TV subscription of ₦4,500.00 for IUC 2222222222 on SHOWMAX failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-07-16 02:33:41'),
(134, 136, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-07-16 03:25:19'),
(135, 136, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-07-16 06:10:30'),
(136, 244, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-07-16 07:10:37'),
(137, 244, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-16 07:10:37'),
(138, 246, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-07-25 05:07:47'),
(139, 246, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-25 05:07:47'),
(140, 136, 'Airtime Purchase Failed', 'No route was found matching the URL and request method.', 'airtime_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-28 11:27:46'),
(141, 136, 'Airtime Purchase Failed', 'No route was found matching the URL and request method.', 'airtime_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-28 11:28:13'),
(142, 136, 'Airtime Purchase Failed', 'No route was found matching the URL and request method.', 'airtime_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-28 11:29:44'),
(143, 136, 'Airtime Purchase Failed', 'MISSING PARAMETER. Please, submit all required parameters including request_id, service_id, phone and amount.', 'airtime_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-28 11:34:53'),
(144, 136, 'Airtime Purchase Failed', 'INVALID SERVICE ID. Please enter a valid service ID (mtn, airtel, glo, 9mobile).', 'airtime_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-28 11:40:15'),
(145, 136, 'Airtime Purchase Failed', 'Your wallet balance (NGN7.25) is insufficient to make this airtime purchase of NGN200', 'airtime_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-28 11:41:49'),
(146, 136, 'Airtime Purchase Failed', 'Your wallet balance (NGN7.25) is insufficient to make this airtime purchase of NGN200', 'airtime_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-28 11:53:10'),
(147, 136, 'Airtime Purchase Failed', 'BELOW ALLOWED MINIMUM AMOUNT OF NGN10. Please, enter an amount equal or above the minimum amount.', 'airtime_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-28 11:57:30'),
(148, 136, 'Data Purchase Failed', 'Data purchase of ₦150.00 for 07037943396 on MTN failed.', 'data_purchase_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-28 12:04:11'),
(149, 136, 'Transfer Sent', 'You sent ₦1,000.00 to Musa Jidder.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-07-28 12:50:10'),
(150, 133, 'Transfer Received', 'You have received ₦1,000.00 from Shelley Wheeler.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-07-28 12:50:10'),
(151, 136, 'Airtime Purchase Successful', 'You purchased ₦100.00 airtime for 07037943396 on MTN', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-07-30 09:59:03'),
(152, 136, 'Airtime Purchase Successful', 'You purchased ₦100.00 airtime for 08132936452 on MTN', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-07-30 10:01:36'),
(153, 136, 'Airtime Purchase Failed', 'DUPLICATE ORDER. Please wait for 3 minutes before placing another airtime order of the same amount to the same phone number.', 'airtime_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-30 10:01:43'),
(154, 136, 'Airtime Purchase Successful', 'You purchased ₦100.00 airtime for 08102516052 on MTN', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-07-30 10:21:19'),
(155, 136, 'Data Purchase Failed', 'INVALID VARIATION ID. Please enter a valid variation_id for MTN.', 'data_failed', 'text-danger', 'ni ni-world-2', 0, '2025-07-30 10:35:11'),
(156, 136, 'Data Purchase Successful', 'You purchased ₦99.00 data for 07037943396 on MTN', 'data_purchase', 'text-success', 'ni ni-world-2', 0, '2025-07-30 14:19:05'),
(157, 136, 'Data Purchase Successful', 'You purchased ₦99.00 data for 08062365769 on MTN', 'data_purchase', 'text-success', 'ni ni-world-2', 0, '2025-07-30 14:21:28'),
(158, 136, 'Data Purchase Failed', 'INVALID MTN NUMBER. Please enter a valid MTN number.', 'data_failed', 'text-danger', 'ni ni-world-2', 0, '2025-07-30 14:32:07'),
(159, 136, 'Airtime Purchase Successful', 'You purchased ₦600.00 airtime for 07037943396 on MTN', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-07-30 14:53:03'),
(160, 136, 'Data Purchase Successful', 'You purchased ₦499.00 data for 07037943396 on MTN', 'data_purchase', 'text-success', 'ni ni-world-2', 0, '2025-07-30 14:54:20'),
(161, 136, 'Account Details Updated', 'Your account details were updated successfully.', 'profile', 'text-info', 'ni ni-single-02', 0, '2025-07-30 16:50:21'),
(162, 136, 'Account Details Updated', 'Your account details were updated successfully.', 'profile', 'text-info', 'ni ni-single-02', 0, '2025-07-30 16:55:42'),
(163, 221, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-30 19:01:48'),
(164, 219, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-07-31 08:22:53'),
(165, 219, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-31 08:22:53'),
(166, 258, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-08-14 02:11:52'),
(167, 258, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-08-14 02:11:52'),
(168, 258, 'Deposit Received', '₦1,000.00 has been credited to your wallet from Browser Tester.', 'deposit', 'text-success', 'ni ni-money-coins', 0, '2025-08-14 03:06:11'),
(169, 258, 'Deposit Received', '₦100.00 has been credited to your wallet from Browser Tester.', 'deposit', 'text-success', 'ni ni-money-coins', 0, '2025-08-14 03:08:45'),
(170, 258, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-08-14 03:20:49'),
(171, 259, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-08-14 03:40:27'),
(172, 259, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-08-14 03:40:28'),
(173, 258, 'Referral Reward Earned', 'You have earned a referral reward! Go to your rewards page to claim it.', 'referral', 'text-info', 'ni ni-diamond', 0, '2025-08-14 03:40:28'),
(174, 259, 'Deposit Received', '₦100.00 credited from 5764363369 (****3369). Ref: R-MBCERHNFVX', 'deposit', 'text-success', 'ni ni-money-coins', 0, '2025-08-14 03:52:06'),
(175, 258, 'Deposit Received', '₦100.00 credited from 5764363053 (****3053). Ref: R-BRFGDOBMGS', 'deposit', 'text-success', 'ni ni-money-coins', 0, '2025-08-14 03:53:16'),
(176, 258, 'Airtime Purchase Failed', 'INVALID MTN NUMBER. Please enter a valid MTN number.', 'airtime_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-08-14 21:04:06'),
(177, 258, 'Data Purchase Failed', 'INVALID MTN NUMBER. Please enter a valid MTN number.', 'data_failed', 'text-danger', 'ni ni-world-2', 0, '2025-08-14 21:05:38'),
(178, 258, 'Data Purchase Successful', 'You purchased ₦299.00 data for 08028596499 on AIRTEL', 'data_purchase', 'text-success', 'ni ni-world-2', 0, '2025-08-14 21:07:09'),
(179, 258, 'Referral Reward Claimed', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral', 'text-info', 'ni ni-trophy', 0, '2025-08-15 04:55:09'),
(180, 258, 'Money Transfer failed', '  must complete registration before receiving any funds.', 'transfer_fail', 'text-danger', 'ni ni-fat-remove', 0, '2025-08-15 05:24:30'),
(181, 258, 'Money Transfer failed', '  must complete registration before receiving any funds.', 'transfer_fail', 'text-danger', 'ni ni-fat-remove', 0, '2025-08-15 05:24:34'),
(182, 258, 'Airtime Purchase Successful', 'You purchased ₦100.00 airtime for 07069525470 on MTN', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-08-15 05:57:42'),
(183, 258, 'Money Transfer failed', '  must complete registration before receiving any funds.', 'transfer_fail', 'text-danger', 'ni ni-fat-remove', 0, '2025-08-15 06:02:25'),
(184, 259, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-08-16 17:30:40'),
(185, 259, 'Account Details Updated', 'Your account details were updated successfully.', 'profile', 'text-info', 'ni ni-single-02', 0, '2025-08-16 20:25:32'),
(186, 259, 'Account Details Updated', 'Your account details were updated successfully.', 'profile', 'text-info', 'ni ni-single-02', 0, '2025-08-16 20:25:33'),
(187, 259, 'Home address Updated', 'Your address details were updated successfully.', 'profile', 'text-dark', 'ni ni-pin-3', 0, '2025-08-16 20:27:11'),
(188, 259, 'Password Changed', 'Your account password was changed successfully.', 'security', 'text-primary', 'ni ni-lock-circle-open', 0, '2025-08-16 21:07:50'),
(189, 259, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-08-16 21:14:47');

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
(135, 'abdulsalamiismaila@gmail.com', '359241', '2025-06-17 23:44:43', '0000-00-00 00:00:00', '2025-06-17 22:34:43'),
(136, 'kabriacid01@gmail.comd', '846499', '2025-06-19 06:36:34', '0000-00-00 00:00:00', '2025-06-19 05:26:34'),
(137, 'mustyabukur@gmail.com', '848034', '2025-06-19 20:22:31', '0000-00-00 00:00:00', '2025-06-19 19:12:31'),
(138, 'muhammadbappayo14@gmail.com', '863810', '2025-06-19 20:26:49', '0000-00-00 00:00:00', '2025-06-19 19:16:49'),
(139, 'muhammadbappayo14@gmail.com', '857649', '2025-06-19 20:32:17', '0000-00-00 00:00:00', '2025-06-19 19:22:17'),
(140, 'abdulseeker@gmail.com', '804888', '2025-06-19 20:33:25', '0000-00-00 00:00:00', '2025-06-19 19:23:25'),
(141, 'kilaxal@gmail.com', '617134', '2025-06-19 20:36:43', '0000-00-00 00:00:00', '2025-06-19 19:26:43'),
(142, 'xyvezovopy@gmail.com', '298833', '2025-06-19 20:43:23', '0000-00-00 00:00:00', '2025-06-19 19:33:23'),
(143, 'muhammadbappayo14@gmail.com', '923984', '2025-06-19 20:46:00', '0000-00-00 00:00:00', '2025-06-19 19:36:00'),
(144, 'ss@gmail.com', '541999', '2025-06-19 20:49:24', '0000-00-00 00:00:00', '2025-06-19 19:39:24'),
(145, 'gejahu@gmail.com', '917542', '2025-06-19 20:52:23', '0000-00-00 00:00:00', '2025-06-19 19:42:23'),
(146, 'saflearn@flexisaf.com', '270822', '2025-06-19 20:53:32', '0000-00-00 00:00:00', '2025-06-19 19:43:32'),
(147, 'aishamansur@gmail.com', '292474', '2025-06-22 02:02:46', '0000-00-00 00:00:00', '2025-06-22 00:52:47'),
(150, 'weqyrod@gmail.com', '410853', '2025-06-26 06:31:43', '0000-00-00 00:00:00', '2025-06-26 05:21:43'),
(151, 'haje@gmail.com', '848273', '2025-06-26 13:57:55', '0000-00-00 00:00:00', '2025-06-26 12:47:56'),
(152, 'wile@gmail.com', '525989', '2025-06-26 14:20:29', '0000-00-00 00:00:00', '2025-06-26 13:10:29'),
(153, 'valegywak@gmail.com', '127665', '2025-06-26 14:26:00', '0000-00-00 00:00:00', '2025-06-26 13:16:00'),
(154, 'dobaf@gmail.com', '261052', '2025-06-26 14:29:22', '0000-00-00 00:00:00', '2025-06-26 13:19:22'),
(155, 'sesal@gmail.com', '117257', '2025-07-11 12:20:44', '0000-00-00 00:00:00', '2025-07-11 11:10:44'),
(156, 'jepacibet@gmail.com', '831996', '2025-07-13 01:16:42', '0000-00-00 00:00:00', '2025-07-13 00:06:42'),
(157, 'koxob@gmail.com', '247568', '2025-07-16 03:11:01', '0000-00-00 00:00:00', '2025-07-16 02:01:01'),
(159, 'zzetim@gmail.com', '266158', '2025-07-16 08:19:52', '0000-00-00 00:00:00', '2025-07-16 07:09:52'),
(160, 'dahosatyba@gmail.com', '820160', '2025-07-16 08:22:47', '0000-00-00 00:00:00', '2025-07-16 07:12:47'),
(161, 'kabriacid01@gmail.com', '191504', '2025-07-31 13:07:10', '2025-07-31 12:57:10', '2025-07-25 00:14:58'),
(162, 'johik@gmail.com', '732836', '2025-07-25 06:16:51', '0000-00-00 00:00:00', '2025-07-25 05:06:51'),
(163, 'sadikdahiru419@yahoo.com', '741929', '2025-07-26 11:54:02', '0000-00-00 00:00:00', '2025-07-26 10:44:02'),
(164, 'dikaga@gmail.com', '214422', '2025-07-30 17:37:53', '0000-00-00 00:00:00', '2025-07-30 16:27:53'),
(165, 136, 'Airtime Purchase Failed', 'No route was found matching the URL and request method.', 'airtime_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-30 10:01:43'),
(166, 136, 'Airtime Purchase Successful', 'You purchased ₦100.00 airtime for 08102516052 on MTN', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-07-30 10:21:19'),
(167, 136, 'Data Purchase Failed', 'INVALID VARIATION ID. Please enter a valid variation_id for MTN.', 'data_failed', 'text-danger', 'ni ni-world-2', 0, '2025-07-30 10:35:11'),
(168, 136, 'Data Purchase Successful', 'You purchased ₦99.00 data for 07037943396 on MTN', 'data_purchase', 'text-success', 'ni ni-world-2', 0, '2025-07-30 14:19:05'),
(169, 136, 'Data Purchase Successful', 'You purchased ₦99.00 data for 08062365769 on MTN', 'data_purchase', 'text-success', 'ni ni-world-2', 0, '2025-07-30 14:21:28'),
(170, 136, 'Data Purchase Failed', 'INVALID MTN NUMBER. Please enter a valid MTN number.', 'data_failed', 'text-danger', 'ni ni-world-2', 0, '2025-07-30 14:32:07'),
(171, 136, 'Airtime Purchase Successful', 'You purchased ₦600.00 airtime for 07037943396 on MTN', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-07-30 14:53:03'),
(172, 136, 'Data Purchase Successful', 'You purchased ₦499.00 data for 07037943396 on MTN', 'data_purchase', 'text-success', 'ni ni-world-2', 0, '2025-07-30 14:54:20'),
(173, 136, 'Account Details Updated', 'Your account details were updated successfully.', 'profile', 'text-info', 'ni ni-single-02', 0, '2025-07-30 16:50:21'),
(174, 136, 'Account Details Updated', 'Your account details were updated successfully.', 'profile', 'text-info', 'ni ni-single-02', 0, '2025-07-30 16:55:42'),
(175, 221, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-30 19:01:48'),
(176, 219, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-07-31 08:22:53'),
(177, 219, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-31 08:22:53'),
(178, 258, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-08-14 02:11:52'),
(179, 258, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-08-14 02:11:52'),
(180, 258, 'Deposit Received', '₦1,000.00 has been credited to your wallet from Browser Tester.', 'deposit', 'text-success', 'ni ni-money-coins', 0, '2025-08-14 03:06:11'),
(181, 258, 'Deposit Received', '₦100.00 has been credited to your wallet from Browser Tester.', 'deposit', 'text-success', 'ni ni-money-coins', 0, '2025-08-14 03:08:45'),
(182, 258, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-08-14 03:20:49'),
(183, 259, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-08-14 03:40:27'),
(184, 259, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-08-14 03:40:28'),
(185, 258, 'Referral Reward Earned', 'You have earned a referral reward! Go to your rewards page to claim it.', 'referral', 'text-info', 'ni ni-diamond', 0, '2025-08-14 03:40:28'),
(186, 259, 'Deposit Received', '₦100.00 credited from 5764363369 (****3369). Ref: R-MBCERHNFVX', 'deposit', 'text-success', 'ni ni-money-coins', 0, '2025-08-14 03:52:06'),
(187, 258, 'Deposit Received', '₦100.00 credited from 5764363053 (****3053). Ref: R-BRFGDOBMGS', 'deposit', 'text-success', 'ni ni-money-coins', 0, '2025-08-14 03:53:16'),
(188, 258, 'Airtime Purchase Failed', 'INVALID MTN NUMBER. Please enter a valid MTN number.', 'airtime_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-08-14 21:04:06'),
(189, 258, 'Data Purchase Failed', 'INVALID MTN NUMBER. Please enter a valid MTN number.', 'data_failed', 'text-danger', 'ni ni-world-2', 0, '2025-08-14 21:05:38'),
(190, 258, 'Data Purchase Successful', 'You purchased ₦299.00 data for 08028596499 on AIRTEL', 'data_purchase', 'text-success', 'ni ni-world-2', 0, '2025-08-14 21:07:09'),
(191, 258, 'Referral Reward Claimed', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral', 'text-info', 'ni ni-trophy', 0, '2025-08-15 04:55:09'),
(192, 258, 'Money Transfer failed', '  must complete registration before receiving any funds.', 'transfer_fail', 'text-danger', 'ni ni-fat-remove', 0, '2025-08-15 05:24:30'),
(193, 258, 'Money Transfer failed', '  must complete registration before receiving any funds.', 'transfer_fail', 'text-danger', 'ni ni-fat-remove', 0, '2025-08-15 05:24:34'),
(194, 258, 'Airtime Purchase Successful', 'You purchased ₦100.00 airtime for 07069525470 on MTN', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-08-15 05:57:42'),
(195, 258, 'Money Transfer failed', '  must complete registration before receiving any funds.', 'transfer_fail', 'text-danger', 'ni ni-fat-remove', 0, '2025-08-15 06:02:25'),
(196, 259, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-08-16 17:30:40'),
(197, 259, 'Account Details Updated', 'Your account details were updated successfully.', 'profile', 'text-info', 'ni ni-single-02', 0, '2025-08-16 20:25:32'),
(198, 259, 'Account Details Updated', 'Your account details were updated successfully.', 'profile', 'text-info', 'ni ni-single-02', 0, '2025-08-16 20:25:33'),
(199, 259, 'Home address Updated', 'Your address details were updated successfully.', 'profile', 'text-dark', 'ni ni-pin-3', 0, '2025-08-16 20:27:11'),
(200, 259, 'Password Changed', 'Your account password was changed successfully.', 'security', 'text-primary', 'ni ni-lock-circle-open', 0, '2025-08-16 21:07:50'),
(201, 259, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-08-16 21:14:47');

-- --------------------------------------------------------

--
-- Table structure for table `referral_reward`
--

CREATE TABLE `referral_reward` (
  `referral_id` int(11) NOT NULL,
  `reward` decimal(10,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `referee_email` varchar(100) NOT NULL,
  `status` enum('claimed','pending') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referral_reward`
--

INSERT INTO `referral_reward` (`referral_id`, `reward`, `user_id`, `referee_email`, `status`, `created_at`) VALUES
(9, 100.00, 133, '', 'claimed', '2025-05-20 04:53:16'),
(10, 100.00, 136, '', 'claimed', '2025-05-28 04:34:36'),
(11, 100.00, 136, '', 'claimed', '2025-06-01 08:49:40'),
(12, 100.00, 258, 'abdullahikabri@gmail.com', 'claimed', '2025-08-15 04:55:08');

-- --------------------------------------------------------

--
-- Table structure for table `services`
--

CREATE ABLE `services` (
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
(3, 'TV', 'tv', '2025-05-30 20:44:35'),
(4, 'Electricity', 'electricity', '2025-05-30 20:44:35');

-- --------------------------------------------------------

--
-- Table structure for table `service_plans`
--

CREATE TABLE `service_plans` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `variation_code` varchar(100) NOT NULL,
  `plan_name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `base_price` decimal(10,2) DEFAULT NULL,
  `volume` varchar(10) NOT NULL,
  `reseller_price` decimal(10,2) DEFAULT 0.00,
  `type` enum('daily','weekly','monthly','tv','bulk','other') DEFAULT 'other',
  `validity` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_providers`
--

CREATE TABLE `service_providers` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `type` enum('network','tv','electricity','bills','other') NOT NULL,
  `brand_color` varchar(20) NOT NULL DEFAULT '#94241e',
  `icon` varchar(255) NOT NULL DEFAULT 'default-brand.svg',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `variation_code` varchar(100) DEFAULT NULL,
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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

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
  `user_name` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `w_bank_name` varchar(50) NOT NULL,
  `w_account_number` varchar(10) NOT NULL,
  `iuc_number` varchar(10) NOT NULL,
  `txn_pin` varchar(255) DEFAULT NULL,
  `address` varchar(100) NOT NULL,
  `state` varchar(15) NOT NULL,
  `country` varchar(15) NOT NULL,
  `city` varchar(20) NOT NULL,
  `photo` varchar(255) NOT NULL DEFAULT 'uploads/default.png',
  `registration_id` varchar(50) NOT NULL,
  `referral_code` varchar(10) DEFAULT NULL,
  `referral_link` varchar(100) NOT NULL,
  `referred_by` varchar(10) DEFAULT NULL,
  `registration_status` enum('incomplete','complete') DEFAULT 'incomplete',
  `account_status` enum('101','102','103','104') NOT NULL DEFAULT '101',
  `failed_attempts` int(11) DEFAULT 0,
  `kyc_value` varchar(11) DEFAULT NULL,
  `kyc_status` enum('verified','unverified','locked','banned') NOT NULL DEFAULT 'unverified',
  `kyc_type` enum('NIN','BVN') DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `user_settings`
--

CREATE TABLE `user_settings` (
  `user_id` int(11) NOT NULL,
  `biometrics_enabled` tinyint(1) DEFAULT 0,
  `hide_balance` tinyint(1) DEFAULT 0,
  `session_expiry_enabled` tinyint(1) DEFAULT 0,
  `account_locked` tinyint(1) NOT NULL DEFAULT 0,
  `ip_address` varchar(45) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_balance`
--
ALTER TABLE `account_balance`
  ADD PRIMARY KEY (`account_id`);

--
-- Indexes for table `account_complaints`
--
ALTER TABLE `account_complaints`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `account_reset_tokens`
--
ALTER TABLE `account_reset_tokens`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_admins_email` (`email`),
  ADD KEY `idx_admins_role` (`role`),
  ADD KEY `idx_admins_status` (`status`),
  ADD KEY `idx_admins_passphrase` (`passphrase`(10));

--
-- Indexes for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_audit_admin` (`admin_id`);

--
-- Indexes for table `forgot_password`
--
ALTER TABLE `forgot_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `forgot_pin`
--
ALTER TABLE `forgot_pin`
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
  ADD UNIQUE KEY `idx_unique_variation` (`variation_code`);

--
-- Indexes for table `service_providers`
--
ALTER TABLE `service_providers`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `service_id` (`service_id`),
  ADD KEY `provider_id` (`provider_id`),
  ADD KEY `idx_variation_code` (`variation_code`);

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_balance`
--
ALTER TABLE `account_balance`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `account_complaints`
--
ALTER TABLE `account_complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `account_reset_tokens`
--
ALTER TABLE `account_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1000;

--
-- AUTO_INCREMENT for table `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `forgot_password`
--
ALTER TABLE `forgot_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `forgot_pin`
--
ALTER TABLE `forgot_pin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `nigerian_states`
--
ALTER TABLE `nigerian_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `referral_reward`
--
ALTER TABLE `referral_reward`
  MODIFY `referral_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `service_plans`
--
ALTER TABLE `service_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=100;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2000;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_reset_tokens`
--
ALTER TABLE `account_reset_tokens`
  ADD CONSTRAINT `account_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;

--
-- Constraints for table `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `fk_audit_admin` FOREIGN KEY (`admin_id`) REFERENCES `admins` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
