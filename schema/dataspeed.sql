-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jun 15, 2025 at 04:10 AM
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
(1, 136, 127370.00, '2025-06-14 23:53:15', 'kabriacid01@gmail.com', '07037943396'),
(2, 187, 17150.00, '2025-06-10 01:37:33', '', ''),
(3, 133, 5082.00, '2025-06-14 23:53:15', 'musajidder@gmail.com', '07038943396');

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
(1, 139, 'Virtual Account Created', 'Congratulations! Virtual account has been created successfully', 'virtual_account', 'ni-bell-55', 'text-light', 0, '2025-05-20 00:05:07'),
(27, 187, 'Airtime Purchase Successful', 'You have purchased airtime for 500', 'airtime_purchase', 'ni-bell-55', 'text-success', 0, '2025-06-10 01:33:50'),
(28, 187, 'Airtime Purchase Successful', 'You have purchased airtime for 500', 'airtime_purchase', 'ni-bell-55', 'text-success', 0, '2025-06-10 01:34:31'),
(29, 187, 'Airtime Purchase Successful', 'You have purchased airtime for 50', 'airtime_purchase', 'ni-bell-55', 'text-success', 0, '2025-06-10 01:37:33'),
(32, 136, 'Deposit Received', '₦2,000.00 credited to your wallet from John Doe.', 'deposit', 'ni-money-coins', 'text-success', 0, '2025-06-13 01:52:14'),
(33, 136, 'Password Changed', 'Your account password was changed successfully.', 'security', 'ni-lock-circle-open', 'text-primary', 0, '2025-06-13 01:52:14'),
(34, 136, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'ni-key-25', 'text-warning', 0, '2025-06-13 01:52:14'),
(35, 136, 'Profile Updated', 'Your profile details were updated successfully.', 'profile', 'ni-single-02', 'text-info', 0, '2025-06-13 01:52:14'),
(36, 136, 'Bank Account Updated', 'Your withdrawal bank account details were updated successfully.', 'profile', 'ni-building', 'text-info', 0, '2025-06-13 01:52:14'),
(37, 136, 'Address Updated', 'Your address details were updated successfully.', 'profile', 'ni-pin-3', 'text-dark', 0, '2025-06-13 01:52:14'),
(38, 136, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'ni-building', 'text-success', 0, '2025-06-13 01:52:14'),
(39, 136, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'ni-key-25', 'text-warning', 0, '2025-06-13 01:52:14'),
(40, 136, 'Referral Reward Earned', 'You have earned a referral reward! Go to your rewards page to claim it.', 'referral', 'ni-trophy', 'text-danger', 0, '2025-06-13 01:52:14'),
(41, 136, 'Referral Reward Claimed', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral', 'ni-trophy', 'text-success', 0, '2025-06-13 01:52:14'),
(42, 136, 'Password Changed', 'Your account password was changed successfully.', 'security', 'ni-lock-circle-open', 'text-primary', 0, '2025-06-13 02:57:26'),
(43, 136, 'Bank Account Updated', 'Your withdrawal bank account details were updated successfully.', 'profile', 'ni-building', 'text-info', 0, '2025-06-13 03:01:27'),
(44, 136, 'Referral Reward Claimed', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral', 'ni-trophy', 'text-danger', 0, '2025-06-13 03:20:32'),
(45, 136, 'Referral Reward Claimed', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral', 'ni-trophy', 'text-danger', 0, '2025-06-13 03:25:07'),
(46, 136, 'Transfer Sent', 'You sent ₦345.00 to musajidder@gmail.com.', 'Money Transfer', 'ni-send', 'text-info', 0, '2025-06-13 05:21:47'),
(47, 133, 'Transfer Received', 'You received ₦345.00 from user 136.', 'Money Transfer', 'ni-send', 'text-success', 0, '2025-06-13 05:21:47'),
(48, 136, 'Transfer Sent', 'You sent ₦293.00 to musajidder@gmail.com.', 'Money Transfer', 'ni-send', 'text-warning', 0, '2025-06-13 05:31:24'),
(49, 133, 'Transfer Received', 'You received ₦293.00 from user .', 'Money Transfer', 'ni-send', 'text-success', 0, '2025-06-13 05:31:24'),
(50, 136, 'Transfer Sent', 'You sent ₦780.00 to musajidder@gmail.com.', 'Money Transfer', 'ni-send', 'text-warning', 0, '2025-06-13 05:32:33'),
(51, 133, 'Transfer Received', 'You received ₦780.00 from user musajidder@gmail.com.', 'Money Transfer', 'ni-send', 'text-success', 0, '2025-06-13 05:32:33'),
(52, 133, 'Transfer Sent', 'You sent ₦333.00 to kabriacid01@gmail.com.', 'Money Transfer', 'ni-send', 'text-warning', 0, '2025-06-13 05:37:21'),
(53, 136, 'Transfer Received', 'You received ₦333.00 from user kabriacid01@gmail.com.', 'Money Transfer', 'ni-send', 'text-success', 0, '2025-06-13 05:37:21'),
(54, 136, 'Transfer Sent', 'You sent ₦4,500.00 to musajidder@gmail.com.', 'Money Transfer', 'ni-send', 'text-warning', 0, '2025-06-13 05:41:23'),
(55, 133, 'Transfer Received', 'You received ₦4,500.00 from user musajidder@gmail.com.', 'Money Transfer', 'ni-send', 'text-success', 0, '2025-06-13 05:41:23'),
(56, 133, 'Transfer Sent', 'You sent ₦665.00 to kabriacid01@gmail.com.', 'Money Transfer', 'ni-send', 'text-warning', 0, '2025-06-13 05:41:56'),
(57, 136, 'Transfer Received', 'You received ₦665.00 from user kabriacid01@gmail.com.', 'Money Transfer', 'ni-send', 'text-success', 0, '2025-06-13 05:41:56'),
(58, 136, 'Airtime Purchase Successful', 'You have purchased airtime for 820', 'airtime_purchase', 'ni-diamond', 'text-primary', 0, '2025-06-13 05:51:24'),
(59, 133, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'ni-key-25', 'text-warning', 0, '2025-06-13 16:10:38'),
(60, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦500.00 for 07037943396 on MTN failed. Reason: Airtime purchase failed.', 'airtime_purchase_fai', 'ni ni-mobile-button', 'text-danger', 0, '2025-06-14 21:57:10'),
(61, 136, 'Transfer Sent', 'You sent ₦10,000.00 to musajidder@gmail.com.', 'Money Transferred', 'ni-send', 'text-warning', 0, '2025-06-14 21:59:33'),
(62, 133, 'Transfer Received', 'You received ₦10,000.00 from user musajidder@gmail.com.', 'Money Received', 'ni-send', 'text-success', 0, '2025-06-14 21:59:33'),
(63, 136, 'Referral Reward Claimed', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral', 'ni-trophy', 'text-danger', 0, '2025-06-14 22:07:18'),
(64, 136, 'Referral Reward Claimed', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral', 'ni-trophy', 'text-info', 0, '2025-06-14 22:11:10'),
(65, 136, 'Referral Reward Claimed', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral', 'ni-trophy', 'text-info', 0, '2025-06-14 22:12:34'),
(66, 136, 'Referral Reward Claimed', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral', 'ni-trophy', 'text-info', 0, '2025-06-14 22:13:52'),
(67, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦200.00 for 07037943396 on MTN failed. Reason: Airtime purchase failed.', 'airtime_purchase_fai', 'ni ni-mobile-button', 'text-danger', 0, '2025-06-14 22:26:30'),
(68, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦200.00 for 07037943396 on MTN failed. Reason: Airtime purchase failed.', 'airtime_purchase_fai', 'ni ni-mobile-button', 'text-danger', 0, '2025-06-14 22:26:30'),
(69, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦200.00 for 07037943396 on MTN failed. Reason: Airtime purchase failed.', 'airtime_purchase_fai', 'ni ni-mobile-button', 'text-danger', 0, '2025-06-14 22:26:30'),
(70, 136, 'Money Transfer failed', 'rybavaga@gmail.com must complete registration before receiving any funds.', 'transfer_fail', 'ni-fat-remove', 'text-warning', 0, '2025-06-14 22:59:04'),
(71, 136, 'Money Transfer failed', 'vetyq@gmail.com must complete registration before receiving any funds.', 'transfer_fail', 'ni-fat-remove', 'text-warning', 0, '2025-06-14 22:59:41'),
(72, 136, 'Money Transfer failed', 'rore@gmail.com must complete registration before receiving any funds.', 'transfer_fail', 'ni-fat-remove', 'text-warning', 0, '2025-06-14 22:59:47'),
(73, 136, 'Money Transfer failed', 'movu@gmail.com must complete registration before receiving any funds.', 'transfer_fail', 'ni-fat-remove', 'text-warning', 0, '2025-06-14 23:01:03'),
(74, 136, 'Money Transfer failed', '  must complete registration before receiving any funds.', 'transfer_fail', 'ni-fat-remove', 'text-warning', 0, '2025-06-14 23:05:55'),
(75, 136, 'Money Transfer failed', '  must complete registration before receiving any funds.', 'transfer_fail', 'ni-fat-remove', 'text-warning', 0, '2025-06-14 23:08:19'),
(76, 136, 'Money Transfer failed', 'Jamal Jugulde must complete registration before receiving any funds.', 'transfer_fail', 'ni-fat-remove', 'text-warning', 0, '2025-06-14 23:11:52'),
(77, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦500.00 for 07037943396 on MTN failed. Reason: Airtime purchase failed.', 'airtime_purchase_fai', 'ni ni-mobile-button', 'text-danger', 0, '2025-06-14 23:14:28'),
(78, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦500.00 for 07037943396 on MTN failed. Reason: TRANSACTION FAILED', 'airtime_purchase_fai', 'ni ni-mobile-button', 'text-danger', 0, '2025-06-14 23:17:04'),
(79, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦500.00 for 07037943396 on AIRTEL failed. Reason: TRANSACTION FAILED', 'airtime_purchase_fai', 'ni ni-mobile-button', 'text-danger', 0, '2025-06-14 23:19:53'),
(80, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦500.00 for 07037943396 on AIRTEL failed. Reason: TRANSACTION FAILED', 'airtime_purchase_fai', 'ni ni-mobile-button', 'text-danger', 0, '2025-06-14 23:20:50'),
(81, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦500.00 for 07037943396 on AIRTEL failed. Reason: TRANSACTION FAILED', 'airtime_purchase_fai', 'ni ni-mobile-button', 'text-danger', 0, '2025-06-14 23:23:07'),
(82, 136, 'Transfer Sent', 'You sent ₦8,940.00 to musajidder@gmail.com.', 'Money Transferred', 'ni-send', 'text-warning', 0, '2025-06-14 23:27:00'),
(83, 133, 'Transfer Received', 'You received ₦8,940.00 from user musajidder@gmail.com.', 'Money Received', 'ni-send', 'text-success', 0, '2025-06-14 23:27:00'),
(84, 133, 'Transfer Sent', 'You sent ₦2,700.00 to kabriacid01@gmail.com.', 'Money Transferred', 'ni-send', 'text-warning', 0, '2025-06-14 23:49:14'),
(85, 136, 'Transfer Received', 'You received ₦2,700.00 from user kabriacid01@gmail.com.', 'Money Received', 'ni-send', 'text-success', 0, '2025-06-14 23:49:14'),
(86, 133, 'Transfer Sent', 'You sent ₦10,000.00 to kabriacid01@gmail.com.', 'Money Transferred', 'ni-send', 'text-warning', 0, '2025-06-14 23:50:09'),
(87, 136, 'Transfer Received', 'You received ₦10,000.00 from user kabriacid01@gmail.com.', 'Money Received', 'ni-send', 'text-success', 0, '2025-06-14 23:50:09'),
(88, 133, 'Transfer Sent', 'You sent ₦5,000.00 to kabriacid01@gmail.com.', 'Money Transferred', 'ni-send', 'text-warning', 0, '2025-06-14 23:53:15'),
(89, 136, 'Transfer Received', 'You received ₦5,000.00 from user kabriacid01@gmail.com.', 'Money Received', 'ni-send', 'text-success', 0, '2025-06-14 23:53:15');

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
  `api_id` int(11) NOT NULL,
  `volume` varchar(100) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `validity` varchar(50) NOT NULL,
  `type` enum('daily','weekly','monthly') DEFAULT 'daily',
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

CREATE TABLE `service_providers` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `code` int(11) NOT NULL,
  `brand_color` varchar(20) DEFAULT '#F4F4F4',
  `slug` varchar(50) DEFAULT NULL
)
--
-- Dumping data for table `service_plans`
--

INSERT INTO `service_plans` (`id`, `service_id`, `provider_id`, `api_id`, `volume`, `price`, `validity`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 1, '50MB', 50.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(2, 1, 1, 1, '100MB', 100.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(3, 1, 1, 1, '200MB', 200.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(4, 1, 1, 1, '500MB', 350.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(5, 1, 1, 1, '1GB', 500.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(6, 1, 1, 1, '2GB', 1000.00, '2 Days', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(7, 1, 1, 1, '1.5GB', 1000.00, '7 Days', 'weekly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(8, 1, 1, 1, '2GB', 1200.00, '7 Days', 'weekly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(9, 1, 1, 1, '3GB', 1500.00, '7 Days', 'weekly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(10, 1, 1, 1, '4.5GB', 2000.00, '30 Days', 'monthly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(11, 1, 1, 1, '6GB', 2500.00, '30 Days', 'monthly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(12, 1, 1, 1, '10GB', 3500.00, '30 Days', 'monthly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(13, 1, 2, 1, '50MB', 50.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(14, 1, 2, 1, '100MB', 100.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(15, 1, 2, 1, '200MB', 200.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(16, 1, 2, 1, '500MB', 350.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(17, 1, 2, 1, '1GB', 500.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(18, 1, 2, 1, '2GB', 1000.00, '2 Days', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(19, 1, 2, 1, '1.5GB', 1000.00, '7 Days', 'weekly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(20, 1, 2, 1, '2GB', 1200.00, '7 Days', 'weekly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(21, 1, 2, 1, '3GB', 1500.00, '7 Days', 'weekly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(22, 1, 2, 1, '4.5GB', 2000.00, '30 Days', 'monthly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(23, 1, 2, 1, '6GB', 2500.00, '30 Days', 'monthly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(24, 1, 2, 1, '10GB', 3500.00, '30 Days', 'monthly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(25, 1, 3, 1, '50MB', 50.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(26, 1, 3, 1, '100MB', 100.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(27, 1, 3, 1, '200MB', 200.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(28, 1, 3, 1, '500MB', 350.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(29, 1, 3, 1, '1GB', 500.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(30, 1, 3, 1, '2GB', 1000.00, '2 Days', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(31, 1, 3, 1, '1.5GB', 1000.00, '7 Days', 'weekly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(32, 1, 3, 1, '2GB', 1200.00, '7 Days', 'weekly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(33, 1, 3, 1, '3GB', 1500.00, '7 Days', 'weekly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(34, 1, 3, 1, '4.5GB', 2000.00, '30 Days', 'monthly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(35, 1, 3, 1, '6GB', 2500.00, '30 Days', 'monthly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(36, 1, 3, 1, '10GB', 3500.00, '30 Days', 'monthly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(37, 1, 4, 1, '50MB', 50.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(38, 1, 4, 1, '100MB', 100.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(39, 1, 4, 1, '200MB', 200.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(40, 1, 4, 1, '500MB', 350.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(41, 1, 4, 1, '1GB', 500.00, '1 Day', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(42, 1, 4, 1, '2GB', 1000.00, '2 Days', 'daily', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(43, 1, 4, 1, '1.5GB', 1000.00, '7 Days', 'weekly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(44, 1, 4, 1, '2GB', 1200.00, '7 Days', 'weekly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(45, 1, 4, 1, '3GB', 1500.00, '7 Days', 'weekly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(46, 1, 4, 1, '4.5GB', 2000.00, '30 Days', 'monthly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(47, 1, 4, 1, '6GB', 2500.00, '30 Days', 'monthly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28'),
(48, 1, 4, 1, '10GB', 3500.00, '30 Days', 'monthly', 1, '2025-06-10 03:50:28', '2025-06-10 03:50:28');

-- --------------------------------------------------------

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
(1, 1, 'MTN', 1, '#FFCC00', 'mtn'),
(2, 1, 'Airtel', 2, '#ED1C24', 'airtel'),
(3, 1, 'Glo', 3, '#1DBA54', 'glo'),
(4, 1, '9mobile', 4, '#B5D334', '9mobile');

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
(1, 136, 2, NULL, NULL, '', 'Airtime Self', 'debit', 200.00, NULL, 'success', '2025-06-09 22:41:31', NULL, NULL, NULL),
(2, 187, 2, NULL, NULL, '', 'Airtime Self', 'debit', 5000.00, NULL, 'success', '2025-06-09 22:48:45', NULL, NULL, NULL),
(3, 187, 2, NULL, NULL, '', 'Airtime Self', 'debit', 100.00, NULL, 'success', '2025-06-10 00:10:20', NULL, NULL, NULL),
(4, 187, 2, NULL, NULL, '', 'Airtime Self', 'debit', 200.00, NULL, 'success', '2025-06-10 00:13:16', NULL, NULL, NULL),
(5, 187, 2, NULL, NULL, '', 'Airtime Self', 'debit', 500.00, NULL, 'success', '2025-06-10 01:27:59', NULL, NULL, NULL),
(6, 187, 2, NULL, NULL, '', 'Airtime Self', 'debit', 1000.00, 'majugulde03@gmail.com', 'success', '2025-06-10 01:30:21', NULL, NULL, NULL),
(7, 187, 2, NULL, NULL, '', 'Airtime Self', 'debit', 5000.00, 'majugulde03@gmail.com', 'success', '2025-06-10 01:31:22', NULL, NULL, NULL),
(8, 187, 2, NULL, NULL, '', 'Airtime Other', 'debit', 500.00, 'majugulde03@gmail.com', 'success', '2025-06-10 01:33:50', NULL, NULL, NULL),
(9, 187, 2, NULL, NULL, '', 'Airtime Other', 'debit', 500.00, 'majugulde03@gmail.com', 'success', '2025-06-10 01:34:31', NULL, NULL, NULL),
(10, 187, 2, NULL, NULL, '', 'Airtime Self', 'debit', 50.00, 'majugulde03@gmail.com', 'success', '2025-06-10 01:37:33', NULL, NULL, NULL),
(13, 136, 2, NULL, NULL, '', 'Deposit', 'debit', 2000.00, NULL, 'success', '2025-06-13 02:15:59', 'ni-credit-card', 'text-success', 'Wallet funded via virtual account'),
(14, 136, 5, NULL, NULL, '', 'Referral Reward', 'debit', 100.00, NULL, 'success', '2025-06-13 03:25:07', 'ni-money-coins', 'text-info', 'referral'),
(15, 136, 0, NULL, NULL, '', 'Money transfer', 'debit', 345.00, NULL, 'success', '2025-06-13 05:21:47', 'ni-send', 'text-warning', 'Transfer to musajidder@gmail.com'),
(16, 133, 0, NULL, NULL, '', 'Money Transfer', 'debit', 345.00, NULL, 'success', '2025-06-13 05:21:47', 'ni-send', 'text-success', 'Received transfer from user 136'),
(17, 136, 0, NULL, NULL, '', 'Money Transfer', 'debit', 293.00, NULL, 'success', '2025-06-13 05:31:24', 'ni-send', 'text-warning', 'Transfer to musajidder@gmail.com'),
(18, 133, 0, NULL, NULL, '', 'Money Transfer', 'debit', 293.00, NULL, 'success', '2025-06-13 05:31:24', 'ni-send', 'text-success', 'Received transfer from user 136'),
(19, 136, 0, NULL, NULL, '', 'Money Transfer', 'debit', 780.00, NULL, 'success', '2025-06-13 05:32:33', 'ni-send', 'text-warning', 'Transfer to musajidder@gmail.com'),
(20, 133, 0, NULL, NULL, '', 'Money Transfer', 'debit', 780.00, NULL, 'success', '2025-06-13 05:32:33', 'ni-send', 'text-success', 'Received transfer from user 136'),
(21, 133, 0, NULL, NULL, '', 'Money Transfer', 'debit', 333.00, NULL, 'success', '2025-06-13 05:37:21', 'ni-send', 'text-warning', 'Transfer to kabriacid01@gmail.com'),
(22, 136, 0, NULL, NULL, '', 'Money Transfer', 'debit', 333.00, NULL, 'success', '2025-06-13 05:37:21', 'ni-send', 'text-success', 'Received transfer from user 133'),
(23, 136, 0, NULL, NULL, '', 'Money Transfer', 'debit', 4500.00, NULL, 'success', '2025-06-13 05:41:23', 'ni-send', 'text-warning', 'Transfer to musajidder@gmail.com'),
(24, 133, 0, NULL, NULL, '', 'Money Transfer', 'debit', 4500.00, NULL, 'success', '2025-06-13 05:41:23', 'ni-send', 'text-success', 'Received transfer from user 136'),
(25, 133, 0, NULL, NULL, '', 'Money Transfer', 'debit', 665.00, NULL, 'success', '2025-06-13 05:41:56', 'ni-send', 'text-warning', 'Transfer to kabriacid01@gmail.com'),
(26, 136, 0, NULL, NULL, '', 'Money Transfer', 'debit', 665.00, NULL, 'success', '2025-06-13 05:41:56', 'ni-send', 'text-success', 'Received transfer from user 133'),
(27, 136, 2, NULL, NULL, '', 'Airtime Other', 'debit', 820.00, 'kabriacid01@gmail.com', 'success', '2025-06-13 05:51:24', 'ni-mobile-button', 'text-danger', NULL),
(28, 136, 1, 1, NULL, 'airtime_684df036e9b018.92949852', 'Airtime self', 'debit', 500.00, 'kabriacid01@gmail.com', '', '2025-06-14 21:57:10', 'ni ni-mobile-button', 'text-danger', 'Airtime purchase of ₦500.00 for 07037943396 on MTN failed. Reason: Airtime purchase failed.'),
(29, 136, 0, NULL, NULL, 'transfer_684df0c511d169.95698458', 'Money Transfer', 'debit', 10000.00, 'kabriacid01@gmail.com', 'success', '2025-06-14 21:59:33', 'ni-send', 'text-warning', 'Transfer to musajidder@gmail.com'),
(30, 133, 0, NULL, NULL, 'transfer_684df0c511d169.95698458', 'Money Transfer', 'credit', 10000.00, 'musajidder@gmail.com', 'success', '2025-06-14 21:59:33', 'ni-send', 'text-success', 'Received transfer from user 136'),
(31, 136, 5, NULL, NULL, '', 'Referral Reward', 'credit', 100.00, NULL, 'success', '2025-06-14 22:07:18', 'ni-money-coins', 'text-info', 'referral'),
(32, 136, 5, NULL, NULL, '', 'Referral Reward', 'credit', 100.00, NULL, 'success', '2025-06-14 22:11:10', 'ni-money-coins', 'text-info', 'referral'),
(33, 136, 5, NULL, NULL, '', 'Referral Reward', 'credit', 100.00, NULL, 'success', '2025-06-14 22:12:34', 'ni-money-coins', 'text-info', 'referral'),
(34, 136, 5, NULL, NULL, '', 'Referral Reward', 'credit', 100.00, NULL, 'success', '2025-06-14 22:13:52', 'ni-money-coins', 'text-info', 'referral'),
(35, 136, 1, 1, NULL, 'airtime_684df7163723d9.60440633', 'Airtime self', 'debit', 200.00, 'kabriacid01@gmail.com', '', '2025-06-14 22:26:30', 'ni ni-mobile-button', 'text-danger', 'Airtime purchase of ₦200.00 for 07037943396 on MTN failed. Reason: Airtime purchase failed.'),
(36, 136, 1, 1, NULL, 'airtime_684df716501ad8.00505599', 'Airtime self', 'debit', 200.00, 'kabriacid01@gmail.com', '', '2025-06-14 22:26:30', 'ni ni-mobile-button', 'text-danger', 'Airtime purchase of ₦200.00 for 07037943396 on MTN failed. Reason: Airtime purchase failed.'),
(37, 136, 1, 1, NULL, 'airtime_684df71668c565.14055956', 'Airtime self', 'debit', 200.00, 'kabriacid01@gmail.com', '', '2025-06-14 22:26:30', 'ni ni-mobile-button', 'text-danger', 'Airtime purchase of ₦200.00 for 07037943396 on MTN failed. Reason: Airtime purchase failed.'),
(38, 136, 1, 1, NULL, 'airtime_684e02534e7077.26036413', 'Airtime self', 'debit', 500.00, 'kabriacid01@gmail.com', '', '2025-06-14 23:14:28', 'ni ni-mobile-button', 'text-danger', 'Airtime purchase of ₦500.00 for 07037943396 on MTN failed. Reason: Airtime purchase failed.'),
(39, 136, 1, 1, NULL, 'airtime_684e02e3705f62.36085965', 'Airtime self', 'debit', 500.00, 'kabriacid01@gmail.com', '', '2025-06-14 23:17:04', 'ni ni-mobile-button', 'text-danger', 'Airtime purchase of ₦500.00 for 07037943396 on MTN failed. Reason: TRANSACTION FAILED'),
(40, 136, 1, 2, NULL, 'airtime_684e038c82fb49.24696868', 'Airtime self', 'debit', 500.00, 'kabriacid01@gmail.com', '', '2025-06-14 23:19:53', 'ni ni-mobile-button', 'text-danger', 'Airtime purchase of ₦500.00 for 07037943396 on AIRTEL failed. Reason: TRANSACTION FAILED'),
(41, 136, 1, 2, NULL, 'airtime_684e03c6ac9068.89347477', 'Airtime self', 'debit', 500.00, 'kabriacid01@gmail.com', '', '2025-06-14 23:20:50', 'ni ni-mobile-button', 'text-danger', 'Airtime purchase of ₦500.00 for 07037943396 on AIRTEL failed. Reason: TRANSACTION FAILED'),
(42, 136, 1, 2, NULL, 'airtime_684e044dbb64a4.65119307', 'Airtime self', 'debit', 500.00, 'kabriacid01@gmail.com', '', '2025-06-14 23:23:07', 'ni ni-mobile-button', 'text-danger', 'Airtime purchase of ₦500.00 for 07037943396 on AIRTEL failed. Reason: TRANSACTION FAILED'),
(43, 136, 0, NULL, NULL, 'transfer_684e0544ac5739.45653376', 'Money Transfer', 'debit', 8940.00, 'kabriacid01@gmail.com', 'success', '2025-06-14 23:27:00', 'ni-send', 'text-warning', 'Transfer to musajidder@gmail.com'),
(44, 133, 0, NULL, NULL, 'transfer_684e0544ac5739.45653376', 'Money Transfer', 'credit', 8940.00, 'musajidder@gmail.com', 'success', '2025-06-14 23:27:00', 'ni-send', 'text-success', 'Received transfer from user 136'),
(45, 133, 0, NULL, NULL, 'transfer_684e0a7a5c9c53.88147685', 'Money Transfer', 'debit', 2700.00, 'musajidder@gmail.com', 'success', '2025-06-14 23:49:14', 'ni-send', 'text-warning', 'Transfer to kabriacid01@gmail.com'),
(46, 136, 0, NULL, NULL, 'transfer_684e0a7a5c9c53.88147685', 'Money Transfer', 'credit', 2700.00, 'kabriacid01@gmail.com', 'success', '2025-06-14 23:49:14', 'ni-send', 'text-success', 'Received transfer from user 133'),
(47, 133, 0, NULL, NULL, 'transfer_684e0ab16a2c32.66220787', 'Money Transfer', 'debit', 10000.00, 'musajidder@gmail.com', 'success', '2025-06-14 23:50:09', 'ni-send', 'text-warning', 'Transfer to kabriacid01@gmail.com'),
(48, 136, 0, NULL, NULL, 'transfer_684e0ab16a2c32.66220787', 'Money Transfer', 'credit', 10000.00, 'kabriacid01@gmail.com', 'success', '2025-06-14 23:50:09', 'ni-send', 'text-success', 'Received transfer from user 133'),
(49, 133, 0, NULL, NULL, 'transfer_684e0b6b0417f3.64828753', 'Money Transfer', 'debit', 5000.00, 'musajidder@gmail.com', 'success', '2025-06-14 23:53:15', 'ni-send', 'text-warning', 'Transfer to kabriacid01@gmail.com'),
(50, 136, 0, NULL, NULL, 'transfer_684e0b6b0417f3.64828753', 'Money Transfer', 'credit', 5000.00, 'kabriacid01@gmail.com', 'success', '2025-06-14 23:53:15', 'ni-send', 'text-success', 'Received transfer from user 133');

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
(136, '5761257050', 'VTU-Jenna Marshall', '9PSB Bank', 'R-ZUNTBSDTMW', 'Abdullahi', 'Kabri', 'kabriacid01@gmail.com', '07037943396', '$2y$10$kiDjyUg7WtDe28l2gUue1e4WRUGvUuU0q/nT0gR0LEMJGWX.nZuuC', 'Opay', '7037943396', '$2y$10$NKkg/aRtaNJonZHny1V43eRRESCgCi7J/B0lxaW4hLK6f.W3k86IW', '846 Rocky First Boulevard', 'Kaduna', '', 'Kaduna South', 'uploads/default.png', NULL, '2025-06-14 21:10:25', '949394aeb0a04a78486fd806ca7c24f1', 'XL5ZJWK4DO', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(139, '5761257555', 'VTU-Chastity Obrien', '9PSB Bank', 'R-FRZBBQJNXG', 'Chastity', 'Obrien', 'vunota@gmail.com', '08023983839', '$2y$10$R8v1oCjJaMYq79lcbor5WeaT2MZxUX9gnMncA2zPeoGazeBHhe/d6', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', 'cf75622cfd811a09a420ba5fa329fec3', 'B1X69UVJKP', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(140, '5761257603', 'VTU-Gabriel Odonnell', '9PSB Bank', 'R-TSUTFSVWPK', 'Gabriel', 'Odonnell', 'dyjo@gmail.com', '08012721760', '$2y$10$tzHdRsqtRRlBeIyaH91yWOiX46sGdEcYH9pqqtGr2bfDIl2pSdACu', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', '9f010b4dc7cc9774ec06d275248db1a9', 'VROJ2E4ULB', '', 'B1X69UVJKP', 'complete', 'Active', '', 'unverified', NULL),
(144, '5761409749', 'VTU-Muhammad Bappayo', '9PSB Bank', 'R-OPRCVBEEKV', 'Muhammad', 'Bappayo', 'alhpeace001@gmail.com', '08064509234', '$2y$10$aLDbre5oxj.cL7I3UUfYKe/e9XLx4ziDeikYJlTO.OU6v6rkDUibu', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', 'a8a785096dae3b93285c371f69851959', '5HRGYIZFW2', '', 'XL5ZJWK4DO', 'complete', 'Active', '', 'unverified', NULL),
(145, '5761490639', 'VTU-Abdulsalami Ismaila', '9PSB Bank', 'R-ZBPLNOVZJC', 'Abdulsalami', 'Ismaila', 'abdulsalamiismaila8@gmail.com', '09029202858', '$2y$10$dSQTzRZsSGnqPK.h5TXXh.TH/zcPtX0z2FJdqzrFlV/m8hzuKBY1e', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', 'af9922d9cfb14a9da86829f8485134f2', 'GA3C2T1FN8', '', NULL, 'complete', 'Active', '', 'unverified', NULL),
(179, '5761525724', 'VTU-Stacy Spencer', '9PSB Bank', 'R-JPQMONHLWF', 'Stacy', 'Spencer', 'duvuba@gmail.com', '09005648888', '$2y$10$u7yByZ0ZDGb0F6aDbwfOUOXQcGGbqjmyVVGYpXw9Ryy4GStCHVRQ6', 'Opay', '9876543456', '$2y$10$r/GtFOrjTjGCEp7//xoTbelkErl6FD/eMyv74ob/9nNZYHpDRkHFm', '923 White Old Boulevard', 'Kogi', '', 'Ijumu', 'uploads/default.png', NULL, '2025-06-14 21:10:25', '3668197e736c6474910dcf53083b3f9f', 'OYFGEV9KQM', '', 'XL5ZJWK4DO', 'complete', 'Banned', '', 'unverified', NULL),
(187, '5761552748', 'VTU-Jamal Jugulde', '9PSB Bank', 'R-SPSNWVUCTU', 'Jamal', 'Jugulde', 'majugulde03@gmail.com', '07012589879', '$2y$10$BJyFCfypbUzymQbf7/Y/D.Rb5eDjtH27WxIIRuImoKML.CmomzhqC', 'Opay', '7012589879', '$2y$10$QPFHHNxqJk1vF7P31J8lLuZsfk0b8OiDnCNybXl2YKpMe52xkdJdO', 'Samunaka Junction, Jalingo', 'Taraba', '', 'Sardauna', 'uploads/default.png', NULL, '2025-06-14 23:11:32', 'e6fcfac8a582baa3d5ebe083c1fba0ae', '79SHT013JG', '', NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(192, NULL, '', '', '', '', '', 'conah@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 20:19:57', 'a25376e60525da2753b34f4a925cf535', NULL, '', NULL, 'incomplete', 'Active', NULL, 'unverified', NULL),
(193, '5761865848', 'VTU-Amanda Morgan', '9PSB Bank', 'R-VDUDLQTHYH', 'Amanda', 'Morgan', 'boze@gmail.com', '08053015847', '$2y$10$U8Q4Ma3WmvXzIUiA9JChTuP1Aqny9aeIywVuexArCy5WzsoSB.xb6', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:10:25', '2bb3a830f3f307adf52479cafa08e8f6', 'Z91UXPK0SR', '', NULL, 'complete', 'Active', NULL, 'unverified', NULL),
(194, NULL, '', '', '', '', '', 'vebec@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 20:43:48', '5a84a2e7e88f151ab72a8f427adf21ef', NULL, '', NULL, 'incomplete', 'Active', NULL, 'unverified', NULL),
(195, '6652566487', 'BillStack/VTU-Eve', 'PalmPay', 'R-DCGHWVUNTB', 'Eve', 'Mcbride', 'coci@gmail.com', '08045551473', '$2y$10$3V63jqgKYg5JDqCGrR5azObelo.0bcZ78SOAt9QtmNKlsTubNCJ0u', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-14 21:06:21', '85921dae64d7582397209c223f31d551', 'OS9K7V1QJF', '', NULL, 'complete', 'Active', NULL, 'unverified', NULL);

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
(136, 0, 0, 1, '2025-06-15 00:38:33', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=90;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=140;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=49;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=196;

--
-- AUTO_INCREMENT for table `variations`
--
ALTER TABLE `variations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
