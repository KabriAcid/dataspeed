-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: localhost:3306
-- Generation Time: Aug 14, 2025 at 12:00 AM
-- Server version: 10.11.13-MariaDB-cll-lve
-- PHP Version: 8.4.10

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
(10, 219, 'rademu910@gmail.com', '09045144840', 0.00, '2025-07-31 08:22:53');

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
(7, 136, 'Multiple failed PIN attempts', 'pending', '2025-07-16 06:20:38', '2025-07-16 06:20:38');

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
(47, 133, 'Transfer Received', 'You received ₦3,000.00 from user musajidder@gmail.com.', 'Money Received', 'text-success', 'ni-send', 0, '2025-06-14 02:55:55'),
(48, 136, 'Transfer Sent', 'You sent ₦59,553.00 to Musa Jidder.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-06-17 02:52:59'),
(49, 133, 'Transfer Received', 'You have received ₦59,553.00 from Abdullahi Kabri.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-06-17 02:52:59'),
(50, 222, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-06-17 22:36:12'),
(51, 222, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-06-17 22:36:12'),
(52, 136, 'Airtime Purchase Successful', 'You have purchased ₦1,000.00 airtime for 08011111111 on 9MOBILE.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-06-18 13:06:30'),
(53, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦5,000.00 for 07037943396 on MTN failed.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-18 13:32:24'),
(54, 136, 'Airtime Purchase Successful', 'You have purchased ₦5,000.00 airtime for 08011111111 on MTN.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-06-18 13:33:12'),
(55, 136, 'Data Purchase Failed', 'Data purchase of ₦550.00 for 07037943396 on MTN failed.', 'data_purchase_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-18 14:39:24'),
(56, 136, 'Data Purchase Successful', 'You purchased ₦550.00 data for 08011111111 on GLO.', 'data_purchase', 'text-success', 'ni ni-check-bold', 0, '2025-06-18 14:40:06'),
(57, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦5,000.00 for 07037943396 on MTN failed.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-18 14:54:27'),
(58, 136, 'TV Subscription Failed', 'TV subscription of ₦3,500.00 for IUC 9076567890 on DSTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-06-18 16:48:44'),
(59, 136, 'Transfer Sent', 'You sent ₦3,000.00 to Jamal Jugulde.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-06-18 16:57:16'),
(60, 187, 'Transfer Received', 'You have received ₦3,000.00 from Abdullahi Kabri.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-06-18 16:57:16'),
(61, 136, 'TV Subscription Failed', 'TV subscription of ₦3,500.00 for IUC 3456789854 on DSTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-06-19 04:35:40'),
(62, 136, 'TV Subscription Failed', 'TV subscription of ₦3,500.00 for IUC 3456789854 on DSTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-06-19 04:37:29'),
(63, 136, 'TV Subscription Failed', 'TV subscription of ₦3,500.00 for IUC 9876556789 on DSTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-06-19 04:54:41'),
(64, 136, 'TV Subscription Successful', 'You purchased ₦2,500.00 TV subscription for IUC 1212121212 on DSTV.', 'tv_subscription', 'text-success', 'ni ni-tv-2', 0, '2025-06-19 04:59:40'),
(65, 136, 'PIN Reset Successful', 'Your transaction PIN was reset successfully.', 'pin_reset', 'text-success', 'ni ni-key-55', 0, '2025-06-19 11:50:29'),
(66, 136, 'Data Purchase Failed', 'Data purchase of ₦550.00 for 08011111111 on MTN failed.', 'data_purchase_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-06-22 09:01:37'),
(67, 136, 'Address Updated', 'Your address details were updated successfully.', 'profile', 'text-dark', 'ni ni-pin-3', 0, '2025-06-22 09:20:03'),
(68, 136, 'Account Details Updated', 'Your account details were updated successfully.', 'profile', 'text-info', 'ni ni-single-02', 0, '2025-06-22 09:35:26'),
(69, 136, 'Transfer Sent', 'You sent ₦1,000.00 to Jamal Jugulde.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-06-23 16:51:21'),
(70, 187, 'Transfer Received', 'You have received ₦1,000.00 from Abdullahi Kabri.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-06-23 16:51:21'),
(71, 236, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-06-26 05:25:36'),
(72, 236, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-06-26 05:25:36'),
(73, 136, 'Transfer Sent', 'You sent ₦5,400.00 to Quemby Woodard.', 'Money Transferred', 'text-success', 'ni ni-send', 0, '2025-06-26 05:31:12'),
(74, 236, 'Transfer Received', 'You have received ₦5,400.00 from Abdullahi Kabri.', 'Money Received', 'text-success', 'ni ni-money-coins', 0, '2025-06-26 05:31:12'),
(75, 236, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-06-26 05:33:59'),
(76, 236, 'TV Subscription Failed', 'TV subscription of ₦3,500.00 for IUC 1111111111 on DSTV failed.', 'tv_subscription_fail', 'text-danger', 'ni ni-tv-2', 0, '2025-06-26 05:34:52'),
(77, 236, 'Data Purchase Successful', 'You purchased ₦250.00 data for 08011111111 on GLO.', 'data_purchase', 'text-success', 'ni ni-check-bold', 0, '2025-06-26 05:38:47'),
(78, 236, 'Airtime Purchase Successful', 'You have purchased ₦200.00 airtime for 08011111111 on MTN.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-06-26 05:44:39'),
(79, 236, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-06-26 05:59:52'),
(80, 236, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-06-26 11:35:53'),
(81, 236, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-06-26 11:36:50'),
(82, 237, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'text-success', 'ni ni-building', 0, '2025-06-26 12:49:20'),
(83, 237, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-06-26 12:49:21'),
(84, 139, 'Security Setting Updated', 'Session expiry is now disabled. You will remain logged in unless you log out.', 'security', 'text-warning', 'ni ni-lock-circle-open', 0, '2025-06-26 13:21:02'),
(85, 139, 'Security Setting Updated', 'Session expiry is now enabled. Your account will require re-authentication after 10 minutes of inactivity.', 'security', 'text-success', 'ni ni-lock-circle-open', 0, '2025-06-26 13:21:07'),
(86, 139, 'PIN Changed', 'Your transaction PIN was changed successfully.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-06-26 13:29:28'),
(87, 139, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-06-26 13:29:50'),
(88, 136, 'Account Details Updated', 'Your account details were updated successfully.', 'profile', 'text-info', 'ni ni-single-02', 0, '2025-07-11 06:36:22'),
(89, 136, 'Account Frozen', 'Your account has been frozen due to multiple failed PIN attempts.', 'security', 'text-danger', 'ni ni-lock-circle-open', 0, '2025-07-11 06:37:51'),
(90, 136, 'Airtime Purchase Failed', 'Airtime purchase of ₦500.00 for 08011111111 on GLO failed.', 'airtime_purchase_fai', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-12 23:17:21'),
(91, 136, 'Data Purchase Failed', 'Data purchase of ₦250.00 for 07037943396 on MTN failed.', 'data_purchase_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-12 23:18:11'),
(92, 136, 'Data Purchase Successful', 'You purchased ₦150.00 data for 08011111111 on MTN.', 'data_purchase', 'text-success', 'ni ni-check-bold', 0, '2025-07-12 23:58:27'),
(93, 136, 'Data Purchase Failed', 'Data purchase of ₦150.00 for 07037943396 on MTN failed.', 'data_purchase_failed', 'text-danger', 'ni ni-mobile-button', 0, '2025-07-13 00:02:54'),
(94, 136, 'Data Purchase Successful', 'You purchased ₦550.00 data for 08011111111 on MTN.', 'data_purchase', 'text-success', 'ni ni-check-bold', 0, '2025-07-13 00:03:20'),
(95, 136, 'Airtime Purchase Successful', 'You have purchased ₦5,000.00 airtime for 08011111111 on MTN.', 'airtime_purchase', 'text-success', 'ni ni-mobile-button', 0, '2025-07-13 00:04:12'),
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
(165, 219, 'Set Your Transaction PIN', 'For your security, please set your transaction PIN to enable transactions.', 'security', 'text-warning', 'ni ni-key-25', 0, '2025-07-31 08:22:53');

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
(165, 'kabriacid01@gmail.com', '191504', '2025-07-31 13:07:10', '2025-07-31 12:57:10', '2025-07-30 16:31:40'),
(166, 'kabriacid01@gmail.com', '191504', '2025-07-31 13:07:10', '2025-07-31 12:57:10', '2025-07-30 16:32:01'),
(167, 'kabriacid01@gmail.com', '191504', '2025-07-31 13:07:10', '2025-07-31 12:57:10', '2025-07-30 16:46:08'),
(168, 'kabriacid01@gmail.com', '191504', '2025-07-31 13:07:10', '2025-07-31 12:57:10', '2025-07-30 16:46:14'),
(169, 'lociryca@gmail.com', '324475', '2025-07-30 18:06:18', '0000-00-00 00:00:00', '2025-07-30 16:56:18'),
(170, 'lociryca@gmail.com', '470225', '2025-07-30 18:08:29', '0000-00-00 00:00:00', '2025-07-30 16:58:29'),
(171, 'kabriacid01@gmail.com', '191504', '2025-07-31 13:07:10', '2025-07-31 12:57:10', '2025-07-30 16:58:57'),
(172, 'kabriacid01@gmail.com', '191504', '2025-07-31 13:07:10', '2025-07-31 12:57:10', '2025-07-30 16:59:56'),
(175, 'memu@gmail.com', '653775', '2025-07-31 12:42:03', '0000-00-00 00:00:00', '2025-07-31 11:32:03'),
(176, 'kabriacid01@gmail.com', '191504', '2025-07-31 13:07:10', '2025-07-31 12:57:10', '2025-07-31 11:32:57'),
(177, 'kabriacid01@gmail.com', '191504', '2025-07-31 13:07:10', '2025-07-31 12:57:10', '2025-07-31 11:38:08'),
(178, 'kabriacid01@gmail.com', '191504', '2025-07-31 13:07:10', '2025-07-31 12:57:10', '2025-07-31 11:47:14'),
(179, 'eced235731@students.umyu.edu.ng', '424356', '2025-08-01 23:55:24', '0000-00-00 00:00:00', '2025-08-01 22:45:24');

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
(11, 100.00, 136, '', 'claimed', '2025-06-01 08:49:40');

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
  `volume` varchar(10) NOT NULL,
  `reseller_price` decimal(10,2) DEFAULT 0.00,
  `type` enum('daily','weekly','monthly','tv','bulk','other') DEFAULT 'other',
  `validity` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_plans`
--

INSERT INTO `service_plans` (`id`, `service_id`, `provider_id`, `variation_code`, `plan_name`, `price`, `volume`, `reseller_price`, `type`, `validity`, `is_active`, `created_at`) VALUES
(1, 1, 1, '354376', '450GB (Broadband) - 90 Days', 74999.00, '450GB', 74249.01, 'bulk', '90 Days', 1, '2025-07-30 13:57:54'),
(2, 1, 1, '354377', '480GB - 90 Days', 89999.00, '480GB', 89099.01, 'bulk', '90 Days', 1, '2025-07-30 13:57:54'),
(3, 1, 1, '354378', '200GB - 60 Days', 49999.00, '200GB', 49499.01, 'bulk', '60 Days', 1, '2025-07-30 13:57:54'),
(4, 1, 1, '354379', '150GB - 60 Days', 39999.00, '150GB', 39599.01, 'bulk', '60 Days', 1, '2025-07-30 13:57:54'),
(5, 1, 1, '354380', '120GB - 30 Days', 23999.00, '120GB', 23759.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(6, 1, 1, '354381', '75GB - 30 Days', 17999.00, '75GB', 17819.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(7, 1, 1, '354382', '65GB - 30 Days', 15999.00, '65GB', 15839.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(8, 1, 1, '354383', '60GB (HyNetFlex) - 30 Days', 14499.00, '60GB', 14354.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(9, 1, 1, '354384', '36GB - 30 Days', 10999.00, '36GB', 10889.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(10, 1, 1, '354385', '30GB (Broadband) - 30 Days', 8999.00, '30GB', 8909.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(11, 1, 1, '354386', '25GB - 30 Days', 8999.00, '25GB', 8909.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(12, 1, 1, '354387', '20GB - 30 Days', 7499.00, '20GB', 7424.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(13, 1, 1, '354388', '16.5GB - 30 Days', 6499.00, '16.5GB', 6434.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(14, 1, 1, '354389', '12.5GB - 30 Days', 5499.00, '12.5GB', 5444.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(15, 1, 1, '354390', '10GB + 10 mins - 30 Days', 4499.00, '10GB', 4454.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(16, 1, 1, '354391', '7GB - 30 Days', 3499.00, '7GB', 3464.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(17, 1, 1, '354392', '3.5GB + 5 mins - 30 Days', 2499.00, '3.5GB', 2474.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(18, 1, 1, '354393', '2.7GB + 2 mins + 2GB All Night Streaming + 200MB YouTube Music - 30 Days', 1999.00, '2.7GB', 1979.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(19, 1, 1, '354394', '2GB + 2 mins - 30 Days', 1499.00, '2GB', 1484.01, 'monthly', '30 Days', 1, '2025-07-30 13:57:54'),
(20, 1, 1, '354395', '12.5GB + 36 mins + 15 SMS - 7 Days', 5499.00, '12.5GB', 5444.01, 'weekly', '7 Days', 1, '2025-07-30 13:57:54'),
(21, 1, 1, '354396', '11GB - 7 Days', 3499.00, '11GB', 3464.01, 'weekly', '7 Days', 1, '2025-07-30 13:57:54'),
(22, 1, 1, '354397', '6GB - 7 Days', 2499.00, '6GB', 2474.01, 'weekly', '7 Days', 1, '2025-07-30 13:57:54'),
(23, 1, 1, '354398', '3.5GB - 7 Days', 1499.00, '3.5GB', 1484.01, 'weekly', '7 Days', 1, '2025-07-30 13:57:54'),
(24, 1, 1, '354399', '1.8GB + 6 mins + 5 SMS - 7 Days', 1499.00, '1.8GB', 1484.01, 'weekly', '7 Days', 1, '2025-07-30 13:57:54'),
(25, 1, 1, '354400', '1.5GB - 7 Days', 999.00, '1.5GB', 989.01, 'weekly', '7 Days', 1, '2025-07-30 13:57:54'),
(26, 1, 1, '354401', '1GB + 5 mins - 7 Days', 799.00, '1GB', 791.01, 'weekly', '7 Days', 1, '2025-07-30 13:57:54'),
(27, 1, 1, '354402', '3.2GB - 2 Days', 999.00, '3.2GB', 989.01, 'weekly', '2 Days', 1, '2025-07-30 13:57:54'),
(28, 1, 1, '354403', '2.5GB - 2 Days', 899.00, '2.5GB', 890.01, 'weekly', '2 Days', 1, '2025-07-30 13:57:54'),
(29, 1, 1, '354404', '2GB - 2 Days', 749.00, '2GB', 741.51, 'weekly', '2 Days', 1, '2025-07-30 13:57:54'),
(30, 1, 1, '354405', '1.5GB - 2 Days', 599.00, '1.5GB', 593.01, 'weekly', '2 Days', 1, '2025-07-30 13:57:54'),
(31, 1, 1, '354406', '1GB + 1.5 mins - 1 Day', 499.00, '1GB', 494.01, 'daily', '1 Day', 1, '2025-07-30 13:57:54'),
(32, 1, 1, '354407', '500MB - 1 Day', 349.00, '500MB', 345.51, 'daily', '1 Day', 1, '2025-07-30 13:57:54'),
(33, 1, 1, '354408', '110MB - 1 Day', 99.00, '110MB', 98.01, 'daily', '1 Day', 1, '2025-07-30 13:57:54'),
(34, 1, 1, '2683', '500MB (SME) - 30 Days', 399.00, '500MB', 399.00, 'monthly', '30 Days', 0, '2025-07-30 13:57:54'),
(35, 1, 1, '2682', '1GB (SME) - 30 Days', 699.00, '1GB', 699.00, 'monthly', '30 Days', 0, '2025-07-30 13:57:54'),
(36, 1, 1, '2681', '2GB (SME) - 30 Days', 1399.00, '2GB', 1399.00, 'monthly', '30 Days', 0, '2025-07-30 13:57:54'),
(37, 1, 1, '2679', '3GB (SME) - 30 Days', 2099.00, '3GB', 2099.00, 'monthly', '30 Days', 0, '2025-07-30 13:57:54'),
(38, 1, 1, '2678', '5GB (SME) - 30 Days', 3499.00, '5GB', 3499.00, 'monthly', '30 Days', 0, '2025-07-30 13:57:54'),
(39, 1, 1, '2677', '10GB (SME) - 30 Days', 6999.00, '10GB', 6999.00, 'monthly', '30 Days', 0, '2025-07-30 13:57:54'),
(40, 1, 2, '354417', '400GB - 365 Days', 99999.00, '400GB', 98999.01, 'bulk', '365 Days', 1, '2025-07-30 14:30:22'),
(41, 1, 2, '354418', '350GB - 120 Days', 59999.00, '350GB', 59399.01, 'bulk', '120 Days', 1, '2025-07-30 14:30:22'),
(42, 1, 2, '354419', '200GB - 90 Days', 49999.00, '200GB', 49499.01, 'bulk', '90 Days', 1, '2025-07-30 14:30:22'),
(43, 1, 2, '354420', 'Unlimited 60MBPS (Router Only) - 30 Days', 49999.00, '', 49499.01, 'monthly', '30 Days', 1, '2025-07-30 14:30:22'),
(44, 1, 2, '354421', 'Unlimited 20MBPS (Router Only) - 30 Days', 29999.00, '', 29699.01, 'monthly', '30 Days', 1, '2025-07-30 14:30:22'),
(45, 1, 2, '354422', '150GB - 30 Days', 29999.00, '150GB', 29699.01, 'monthly', '30 Days', 1, '2025-07-30 14:30:22'),
(46, 1, 2, '354423', '100GB - 30 Days', 19999.00, '100GB', 19799.01, 'monthly', '30 Days', 1, '2025-07-30 14:30:22'),
(47, 1, 2, '354424', '60GB - 30 Days', 14999.00, '60GB', 14849.01, 'monthly', '30 Days', 1, '2025-07-30 14:30:22'),
(48, 1, 2, '354425', '35GB - 30 Days', 9999.00, '35GB', 9899.01, 'monthly', '30 Days', 1, '2025-07-30 14:30:22'),
(49, 1, 2, '354426', '25GB - 30 Days', 7999.00, '25GB', 7919.01, 'monthly', '30 Days', 1, '2025-07-30 14:30:22'),
(50, 1, 2, '354427', '18GB - 30 Days', 5999.00, '18GB', 5939.01, 'monthly', '30 Days', 1, '2025-07-30 14:30:22'),
(51, 1, 2, '354428', '10GB - 30 Days', 3999.00, '10GB', 3959.01, 'monthly', '30 Days', 1, '2025-07-30 14:30:22'),
(52, 1, 2, '354429', '6GB - 30 Days', 2999.00, '6GB', 2969.01, 'monthly', '30 Days', 1, '2025-07-30 14:30:22'),
(53, 1, 2, '354430', '3GB - 30 Days', 1999.00, '3GB', 1979.01, 'monthly', '30 Days', 1, '2025-07-30 14:30:22'),
(54, 1, 2, '354431', '2GB - 30 Days', 1499.00, '2GB', 1484.01, 'monthly', '30 Days', 1, '2025-07-30 14:30:22'),
(55, 1, 2, '354432', '18GB - 7 Days', 4999.00, '18GB', 4949.01, 'weekly', '7 Days', 1, '2025-07-30 14:30:22'),
(56, 1, 2, '354433', '8.5GB - 7 Days', 2999.00, '8.5GB', 2969.01, 'weekly', '7 Days', 1, '2025-07-30 14:30:22'),
(57, 1, 2, '354434', '6GB - 7 Days', 2499.00, '6GB', 2474.01, 'weekly', '7 Days', 1, '2025-07-30 14:30:22'),
(58, 1, 2, '354435', '3GB - 7 Days', 1499.00, '3GB', 1484.01, 'weekly', '7 Days', 1, '2025-07-30 14:30:22'),
(59, 1, 2, '354436', '1.5GB - 7 Days', 999.00, '1.5GB', 989.01, 'weekly', '7 Days', 1, '2025-07-30 14:30:22'),
(60, 1, 2, '354437', '1GB - 7 Days', 799.00, '1GB', 791.01, 'weekly', '7 Days', 1, '2025-07-30 14:30:22'),
(61, 1, 2, '354438', '1.5GB (Social) - 7 Days', 499.00, '1.5GB', 494.01, 'weekly', '7 Days', 1, '2025-07-30 14:30:22'),
(62, 1, 2, '354439', '500MB - 7 Days', 499.00, '500MB', 494.01, 'weekly', '7 Days', 1, '2025-07-30 14:30:22'),
(63, 1, 2, '354440', '1GB (Social) - 3 Days', 299.00, '1GB', 296.01, 'weekly', '3 Days', 1, '2025-07-30 14:30:22'),
(64, 1, 2, '354441', '5GB (Binge) - 2 Days', 1499.00, '5GB', 1484.01, 'weekly', '2 Days', 1, '2025-07-30 14:30:22'),
(65, 1, 2, '354442', '3GB (Binge) - 2 Days', 999.00, '3GB', 989.01, 'weekly', '2 Days', 1, '2025-07-30 14:30:22'),
(66, 1, 2, '354443', '1.5GB (Binge) - 2 Days', 599.00, '1.5GB', 593.01, 'weekly', '2 Days', 1, '2025-07-30 14:30:22'),
(67, 1, 2, '354444', '300MB - 1 Day', 299.00, '300MB', 296.01, 'daily', '1 Day', 1, '2025-07-30 14:30:22'),
(68, 1, 2, '354445', '100MB - 1 Day', 99.00, '100MB', 98.01, 'daily', '1 Day', 1, '2025-07-30 14:30:22'),
(69, 1, 2, '245982', '500MB (SME) - 30 Days', 429.00, '500MB', 419.00, 'monthly', '30 Days', 0, '2025-07-30 14:30:22'),
(70, 1, 2, '245973', '1GB (SME) - 30 Days', 729.00, '1GB', 719.00, 'monthly', '30 Days', 0, '2025-07-30 14:30:22'),
(71, 1, 2, '245972', '2GB (SME) - 30 Days', 1459.00, '2GB', 1439.00, 'monthly', '30 Days', 0, '2025-07-30 14:30:22'),
(72, 1, 2, '245971', '5GB (SME) - 30 Days', 3649.00, '5GB', 3599.00, 'monthly', '30 Days', 0, '2025-07-30 14:30:22'),
(73, 1, 2, '245970', '10GB (SME) - 30 Days', 7299.00, '10GB', 7199.00, 'monthly', '30 Days', 0, '2025-07-30 14:30:22'),
(74, 1, 2, '245969', '15GB (SME) - 30 Days', 10939.00, '15GB', 10789.00, 'monthly', '30 Days', 0, '2025-07-30 14:30:22'),
(75, 1, 2, '245968', '20GB (SME) - 30 Days', 14589.00, '20GB', 14389.00, 'monthly', '30 Days', 0, '2025-07-30 14:30:22'),
(76, 1, 3, '354446', '475GB - 90 Days', 74999.00, '475GB', 73499.02, 'bulk', '90 Days', 1, '2025-07-30 14:30:51'),
(77, 1, 3, '354447', '110GB - 30 Days', 19999.00, '110GB', 19599.02, 'monthly', '30 Days', 1, '2025-07-30 14:30:51'),
(78, 1, 3, '354448', '40GB - 30 Days', 9999.00, '40GB', 9799.02, 'monthly', '30 Days', 1, '2025-07-30 14:30:51'),
(79, 1, 3, '354449', '18GB - 30 Days', 4999.00, '18GB', 4899.02, 'monthly', '30 Days', 1, '2025-07-30 14:30:51'),
(80, 1, 3, '354450', '11GB - 30 Days', 2999.00, '11GB', 2939.02, 'monthly', '30 Days', 1, '2025-07-30 14:30:51'),
(81, 1, 3, '354451', '7.5GB - 30 Days', 2499.00, '7.5GB', 2449.02, 'monthly', '30 Days', 1, '2025-07-30 14:30:51'),
(82, 1, 3, '354452', '5GB - 30 Days', 1499.00, '5GB', 1469.02, 'monthly', '30 Days', 1, '2025-07-30 14:30:51'),
(83, 1, 3, '354453', '2.6GB - 30 Days', 999.00, '2.6GB', 979.02, 'monthly', '30 Days', 1, '2025-07-30 14:30:51'),
(84, 1, 3, '354454', '1.5GB - 14 Days', 499.00, '1.5GB', 489.02, 'monthly', '14 Days', 1, '2025-07-30 14:30:51'),
(85, 1, 3, '354455', '2.2GB - Weekend', 499.00, '2.2GB', 489.02, 'weekly', 'Weekend', 1, '2025-07-30 14:30:51'),
(86, 1, 3, '354456', '1.75GB - Sunday', 199.00, '1.75GB', 195.02, 'daily', 'Sunday', 1, '2025-07-30 14:30:52'),
(87, 1, 3, '354457', '125MB - 1 Day', 99.00, '125MB', 97.02, 'daily', '1 Day', 1, '2025-07-30 14:30:52'),
(88, 1, 3, '258990', '500MB (SME) - 30 Days', 299.00, '500MB', 269.10, 'monthly', '30 Days', 1, '2025-07-30 14:30:52'),
(89, 1, 3, '258989', '1GB (SME) - 30 Days', 499.00, '1GB', 449.10, 'monthly', '30 Days', 1, '2025-07-30 14:30:52'),
(90, 1, 3, '258988', '2GB (SME) - 30 Days', 999.00, '2GB', 899.10, 'monthly', '30 Days', 1, '2025-07-30 14:30:52'),
(91, 1, 3, '258987', '3GB (SME) - 30 Days', 1499.00, '3GB', 1349.10, 'monthly', '30 Days', 1, '2025-07-30 14:30:52'),
(92, 1, 3, '258985', '10GB (SME) - 30 Days', 4999.00, '10GB', 4499.10, 'monthly', '30 Days', 1, '2025-07-30 14:30:52'),
(93, 1, 3, '258986', '5GB (SME) - 30 Days', 2499.00, '5GB', 2249.10, 'monthly', '30 Days', 1, '2025-07-30 14:30:52'),
(94, 1, 4, '354467', '650MB - 1 Day', 499.00, '650MB', 489.02, 'daily', '1 Day', 1, '2025-07-30 14:41:18'),
(95, 1, 4, '354466', '1.4GB - 30 Days', 1199.00, '1.4GB', 1175.02, 'monthly', '30 Days', 0, '2025-07-30 14:41:18'),
(96, 1, 4, '354465', '2.44GB - 30 Days', 1999.00, '2.44GB', 1959.02, 'monthly', '30 Days', 1, '2025-07-30 14:41:18'),
(97, 1, 4, '354464', '3.91GB - 30 Days', 2999.00, '3.91GB', 2939.02, 'monthly', '30 Days', 1, '2025-07-30 14:41:18'),
(98, 1, 4, '354463', '5.10GB - 30 Days', 3999.00, '5.10GB', 3919.02, 'monthly', '30 Days', 1, '2025-07-30 14:41:18'),
(99, 1, 4, '354462', '16GB - 30 Days', 11999.00, '16GB', 11759.02, 'monthly', '30 Days', 1, '2025-07-30 14:41:18'),
(100, 1, 4, '354461', '26.5GB - 30 Days', 19999.00, '26.5GB', 19599.02, 'monthly', '30 Days', 1, '2025-07-30 14:41:18'),
(101, 1, 4, '354460', '39GB - 60 Days', 29999.00, '39GB', 29399.02, 'bulk', '60 Days', 1, '2025-07-30 14:41:18'),
(102, 1, 4, '354459', '78GB - 90 Days', 59999.00, '78GB', 58799.02, 'bulk', '90 Days', 1, '2025-07-30 14:41:18'),
(103, 1, 4, '354458', '190GB - 180 Days', 149999.00, '190GB', 146999.02, 'bulk', '180 Days', 1, '2025-07-30 14:41:18'),
(104, 3, 5, '3719', 'Padi', 4400.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(105, 3, 5, '3718', 'Yanga', 6000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(106, 3, 5, '3717', 'Confam', 11000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(107, 3, 5, '3715', 'Compact', 19000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(108, 3, 5, '3714', 'Compact Plus', 30000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(109, 3, 5, '3713', 'Premium', 44500.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(110, 3, 5, '3708', 'Padi + ExtraView', 10400.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(111, 3, 5, '3709', 'Yanga + ExtraView', 12000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(112, 3, 5, '3710', 'Confam + ExtraView', 17000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(113, 3, 5, '3706', 'Compact + ExtraView', 25000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(114, 3, 5, '3698', 'Compact Plus + ExtraView', 36000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(115, 3, 5, '3704', 'Premium + ExtraView', 50500.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(116, 3, 5, '2699', 'ExtraView', 6000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(117, 3, 5, '2703', 'French Touch', 7000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(118, 3, 5, '3716', 'Asia', 14900.00, '', 0.00, 'tv', '', 0, '2025-07-30 15:03:50'),
(119, 3, 5, '353580', 'Access', 2000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(120, 3, 5, '2701', 'French Plus', 24500.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(121, 3, 5, '353579', 'Family', 4000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:03:50'),
(122, 3, 6, '2697', 'Smallie', 1900.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:20:03'),
(123, 3, 6, '2696', 'Jinja', 3900.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:20:04'),
(124, 3, 6, '2695', 'Jolli', 5800.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:20:04'),
(125, 3, 6, '2694', 'Max', 8500.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:20:04'),
(126, 3, 6, '13802', 'Supa', 11400.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:20:04'),
(127, 3, 6, '354075', 'Supa Plus', 16800.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:20:04'),
(129, 3, 8, '354084', 'Mobile Only', 1600.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:20:46'),
(130, 3, 8, '354083', 'Full', 3500.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:20:46'),
(131, 3, 8, '354081', 'Sports Mobile Only', 4000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:20:46'),
(132, 3, 8, '354082', 'Sports Only', 3200.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:20:46'),
(133, 3, 8, '354080', 'Full Sports Mobile Only', 5400.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:20:46'),
(134, 3, 7, '2693', 'Nova (Antenna)', 2100.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:21:02'),
(135, 3, 7, '2692', 'Nova (Dish)', 2100.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:21:02'),
(136, 3, 7, '2691', 'Basic (Antenna)', 4000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:21:02'),
(137, 3, 7, '2690', 'Basic (Dish)', 5100.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:21:02'),
(138, 3, 7, '2689', 'Classic (Antenna)', 6000.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:21:02'),
(139, 3, 7, '354076', 'Classic (Dish)', 7400.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:21:02'),
(140, 3, 7, '354077', 'Super (Antenna)', 9500.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:21:02'),
(141, 3, 7, '354078', 'Super (Dish)', 9800.00, '', 0.00, 'tv', '', 1, '2025-07-30 15:21:02');

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

--
-- Dumping data for table `service_providers`
--

INSERT INTO `service_providers` (`id`, `service_id`, `name`, `slug`, `type`, `brand_color`, `icon`, `is_active`, `created_at`) VALUES
(1, 2, 'MTN', 'mtn', 'network', '#ffcb05', 'mtn.svg', 1, '2025-06-17 17:10:16'),
(2, 2, 'Airtel', 'airtel', 'network', '#eb1922', 'airtel-1.png', 1, '2025-06-17 17:10:16'),
(3, 2, 'Glo', 'glo', 'network', '#50b651', 'glo.svg', 1, '2025-06-17 17:10:16'),
(4, 2, '9mobile', '9mobile', 'network', '#d6e806', '9mobile.svg', 1, '2025-06-17 17:10:16'),
(5, 3, 'DSTV', 'dstv', 'tv', '#00206C', 'dstv.svg', 1, '2025-06-17 17:10:16'),
(6, 3, 'GOTV', 'gotv', 'tv', '#A1C823', 'gotv.svg', 1, '2025-06-17 17:10:16'),
(7, 3, 'Startimes', 'startimes', 'tv', '#f9a825', 'startimes.png', 1, '2025-06-17 17:10:16'),
(8, 3, 'Showmax', 'showmax', 'tv', '#f9a825', 'showmax.svg', 1, '2025-06-17 17:10:16');

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
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `service_id`, `provider_id`, `plan_id`, `type`, `icon`, `color`, `direction`, `description`, `amount`, `email`, `reference`, `status`, `created_at`) VALUES
(1, 136, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', 200.00, NULL, '0', 'success', '2025-06-09 22:41:31'),
(2, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', 5000.00, NULL, '0', 'success', '2025-06-09 22:48:45'),
(3, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', 100.00, NULL, '0', 'success', '2025-06-10 00:10:20'),
(4, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', 200.00, NULL, '0', 'success', '2025-06-10 00:13:16'),
(5, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', 500.00, NULL, '0', 'success', '2025-06-10 01:27:59'),
(6, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', 1000.00, 'majugulde03@gmail.com', '0', 'success', '2025-06-10 01:30:21'),
(7, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', 5000.00, 'majugulde03@gmail.com', '0', 'success', '2025-06-10 01:31:22'),
(8, 187, 2, NULL, NULL, 'Airtime Other', '', '', 'debit', '', 500.00, 'majugulde03@gmail.com', '0', 'success', '2025-06-10 01:33:50'),
(9, 187, 2, NULL, NULL, 'Airtime Other', '', '', 'debit', '', 500.00, 'majugulde03@gmail.com', '0', 'success', '2025-06-10 01:34:31'),
(10, 187, 2, NULL, NULL, 'Airtime Self', '', '', 'debit', '', 50.00, 'majugulde03@gmail.com', '0', 'success', '2025-06-10 01:37:33'),
(12, 136, 1, 3, NULL, 'Airtime self', 'ni ni-default', 'text-dark', 'debit', '', 1000.00, 'kabriacid01@gmail.com', 'airtime_684cd23036ff32.62873582', '', '2025-06-14 01:37:00'),
(13, 136, 1, 2, NULL, 'Airtime self', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦500.00 for 07037943396 on AIRTEL failed. Reason: TRANSACTION FAILED', 500.00, 'kabriacid01@gmail.com', 'airtime_684cd48acafc64.07731635', '', '2025-06-14 01:47:03'),
(14, 136, 1, 3, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: TRANSACTION FAILED', 2000.00, 'kabriacid01@gmail.com', 'airtime_684cd4f1f256e9.50043283', '', '2025-06-14 01:48:45'),
(15, 136, 1, 3, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be ', 2000.00, 'kabriacid01@gmail.com', 'airtime_684cd4fe627216.82721378', '', '2025-06-14 01:48:47'),
(16, 136, 1, 3, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be ', 2000.00, 'kabriacid01@gmail.com', 'airtime_684cd4ff6b2a25.40973241', '', '2025-06-14 01:48:48'),
(17, 136, 1, 3, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be ', 2000.00, 'kabriacid01@gmail.com', 'airtime_684cd5008c1f13.52555147', '', '2025-06-14 01:48:49'),
(18, 136, 1, 3, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦2,000.00 for 00803063154 on GLO failed. Reason: This transaction appears to be ', 2000.00, 'kabriacid01@gmail.com', 'airtime_684cd5019273b4.47253417', '', '2025-06-14 01:48:50'),
(19, 136, 1, 2, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦5,000.00 for 00803063154 on AIRTEL failed. Reason: TRANSACTION FAILED', 5000.00, 'kabriacid01@gmail.com', 'airtime_684cd5c1721009.05128222', '', '2025-06-14 01:52:13'),
(20, 136, 1, 1, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-danger', 'debit', 'Airtime purchase of ₦2,000.00 for 07012589879 on MTN failed. Reason: TRANSACTION FAILED', 2000.00, 'kabriacid01@gmail.com', 'airtime_684cd6343752b7.90314628', '', '2025-06-14 01:54:08'),
(21, 136, 1, 2, NULL, 'Airtime self', 'ni ni-mobile-button', 'text-success', 'debit', 'You have purchased ₦5,000.00 airtime for 08011111111 on AIRTEL.', 5000.00, 'kabriacid01@gmail.com', 'airtime_684cd6a046ba31.61032287', 'success', '2025-06-14 01:55:52'),
(22, 136, 1, 1, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-success', 'debit', 'You have purchased ₦1,350.00 airtime for 08011111111 on MTN.', 1350.00, 'kabriacid01@gmail.com', 'airtime_684cd7c36313b3.54202473', 'success', '2025-06-14 02:00:44'),
(23, 136, 1, 1, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-success', 'debit', 'You have purchased ₦1,000.00 airtime for 08011111111 on MTN.', 1000.00, 'kabriacid01@gmail.com', 'airtime_684cdf38324b21.86687482', 'success', '2025-06-14 02:32:33'),
(24, 136, 0, NULL, NULL, 'Money Transfer', 'ni-send', 'text-warning', 'debit', 'Transfer to musajidder@gmail.com', 1000.00, NULL, '', 'success', '2025-06-14 02:47:43'),
(25, 133, 0, NULL, NULL, 'Money Transfer', 'ni-send', 'text-success', 'credit', 'Received transfer from user 136', 1000.00, NULL, '', 'success', '2025-06-14 02:47:43'),
(26, 136, 0, NULL, NULL, 'Money Transfer', 'ni-send', 'text-warning', 'debit', 'Transfer to musajidder@gmail.com', 1000.00, 'kabriacid01@gmail.com', 'transfer_684ce40d8a2972.68551337', 'success', '2025-06-14 02:53:01'),
(27, 133, 0, NULL, NULL, 'Money Transfer', 'ni-send', 'text-success', 'credit', 'Received transfer from user 136', 1000.00, 'musajidder@gmail.com', 'transfer_684ce40d8a2972.68551337', 'success', '2025-06-14 02:53:01'),
(28, 136, 0, NULL, NULL, 'Money Transfer', 'ni-send', 'text-warning', 'debit', 'Transfer to musajidder@gmail.com', 3000.00, 'kabriacid01@gmail.com', 'transfer_684ce4bb2ffc09.96864282', 'success', '2025-06-14 02:55:55'),
(29, 133, 0, NULL, NULL, 'Money Transfer', 'ni-send', 'text-success', 'credit', 'Received transfer from user 136', 3000.00, 'musajidder@gmail.com', 'transfer_684ce4bb2ffc09.96864282', 'success', '2025-06-14 02:55:55'),
(30, 136, 0, NULL, NULL, 'Money Transfer', 'ni ni-send', 'text-success', 'debit', 'Transfer to Musa Jidder', 59553.00, 'kabriacid01@gmail.com', 'tf_6850d88bb88d38.21696666', 'success', '2025-06-17 02:52:59'),
(31, 133, 0, NULL, NULL, 'Money Received', 'ni ni-money-coins', 'text-success', 'credit', 'Received transfer from Abdullahi Kabri', 59553.00, 'musajidder@gmail.com', 'tf_6850d88bb88d38.21696666', 'success', '2025-06-17 02:52:59'),
(32, 136, 1, 4, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-success', 'debit', 'You have purchased ₦1,000.00 airtime for 08011111111 on 9MOBILE.', 1000.00, 'kabriacid01@gmail.com', 'airtime_6852b9cc5e3772.99029728', 'success', '2025-06-18 13:06:30'),
(33, 136, 1, 4, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-success', 'debit', 'You have purchased ₦5,000.00 airtime for 08011111111 on MTN.', 5000.00, 'kabriacid01@gmail.com', '17502535822826', 'success', '2025-06-18 13:33:12'),
(34, 136, 2, 1, 2, 'Data Purchase', 'ni ni-mobile-button', 'text-danger', 'debit', 'Data purchase of ₦550.00 for 07037943396 on MTN failed.', 550.00, 'kabriacid01@gmail.com', 'data_6852cf8f731fc9.95002805', '', '2025-06-18 14:39:23'),
(35, 136, 2, 3, 2, 'Data Purchase', 'ni ni-check-bold', 'text-success', 'debit', 'You purchased ₦550.00 data for 08011111111 on GLO.', 550.00, 'kabriacid01@gmail.com', 'data_6852cfbcb3dd09.88486009', 'success', '2025-06-18 14:40:06'),
(36, 136, 3, 5, 30, 'TV Subscription', 'ni ni-tv-2', 'text-danger', 'debit', 'TV subscription of ₦3,500.00 for IUC 9076567890 on DSTV failed.', 3500.00, 'kabriacid01@gmail.com', '17502653223137', '', '2025-06-18 16:48:43'),
(37, 136, 0, NULL, NULL, 'Money Transfer', 'ni ni-send', 'text-success', 'debit', 'Transfer to Jamal Jugulde', 3000.00, 'kabriacid01@gmail.com', 'tf_6852efec417d91.94578376', 'success', '2025-06-18 16:57:16'),
(38, 187, 0, NULL, NULL, 'Money Received', 'ni ni-money-coins', 'text-success', 'credit', 'Received transfer from Abdullahi Kabri', 3000.00, 'majugulde03@gmail.com', 'tf_6852efec417d91.94578376', 'success', '2025-06-18 16:57:16'),
(39, 136, 3, 5, 34, 'TV Subscription', 'ni ni-tv-2', 'text-danger', 'debit', 'TV subscription of ₦3,500.00 for IUC 3456789854 on DSTV failed.', 3500.00, 'kabriacid01@gmail.com', '17503077373859', '', '2025-06-19 04:35:40'),
(40, 136, 3, 5, 34, 'TV Subscription', 'ni ni-tv-2', 'text-danger', 'debit', 'TV subscription of ₦3,500.00 for IUC 3456789854 on DSTV failed.', 3500.00, 'kabriacid01@gmail.com', '17503078492833', '', '2025-06-19 04:37:29'),
(41, 136, 3, 5, 43, 'TV Subscription', 'ni ni-tv-2', 'text-danger', 'debit', 'TV subscription of ₦3,500.00 for IUC 9876556789 on DSTV failed.', 3500.00, 'kabriacid01@gmail.com', '17503088797603', '', '2025-06-19 04:54:40'),
(42, 136, 3, 5, 0, 'TV Subscription', 'ni ni-tv-2', 'text-success', 'debit', 'You purchased ₦2,500.00 TV subscription for IUC 1212121212 on DSTV.', 2500.00, 'kabriacid01@gmail.com', '17503091727718', 'success', '2025-06-19 04:59:40'),
(43, 136, 2, 1, 0, 'Data Purchase', 'ni ni-mobile-button', 'text-danger', 'debit', 'Data purchase of ₦550.00 for 08011111111 on MTN failed.', 550.00, 'kabriacid01@gmail.com', '17505828969473', '', '2025-06-22 09:01:36'),
(44, 136, 0, NULL, NULL, 'Money Transfer', 'ni ni-send', 'text-success', 'debit', 'Transfer to Jamal Jugulde', 1000.00, 'kabriacid01@gmail.com', 'tf_68598609bbfcb7.77936497', 'success', '2025-06-23 16:51:21'),
(45, 187, 0, NULL, NULL, 'Money Received', 'ni ni-money-coins', 'text-success', 'credit', 'Received transfer from Abdullahi Kabri', 1000.00, 'majugulde03@gmail.com', 'tf_68598609bbfcb7.77936497', 'success', '2025-06-23 16:51:21'),
(46, 136, 0, NULL, NULL, 'Money Transfer', 'ni ni-send', 'text-success', 'debit', 'Transfer to Quemby Woodard', 5400.00, 'kabriacid01@gmail.com', 'tf_685cdb20d1b840.47445070', 'success', '2025-06-26 05:31:12'),
(47, 236, 0, NULL, NULL, 'Money Received', 'ni ni-money-coins', 'text-success', 'credit', 'Received transfer from Abdullahi Kabri', 5400.00, 'weqyrod@gmail.com', 'tf_685cdb20d1b840.47445070', 'success', '2025-06-26 05:31:12'),
(48, 236, 3, 5, 0, 'TV Subscription', 'ni ni-tv-2', 'text-danger', 'debit', 'TV subscription of ₦3,500.00 for IUC 1111111111 on DSTV failed.', 3500.00, 'weqyrod@gmail.com', '17509160778441', '', '2025-06-26 05:34:52'),
(49, 236, 2, 3, 0, 'Data Purchase', 'ni ni-check-bold', 'text-success', 'debit', 'You purchased ₦250.00 data for 08011111111 on GLO.', 250.00, 'weqyrod@gmail.com', '17509163184019', 'success', '2025-06-26 05:38:47'),
(50, 236, 1, 1, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-success', 'debit', 'You have purchased ₦200.00 airtime for 08011111111 on MTN.', 200.00, 'weqyrod@gmail.com', '17509166721339', 'success', '2025-06-26 05:44:39'),
(51, 136, 2, 1, 0, 'Data Purchase', 'ni ni-mobile-button', 'text-danger', 'debit', 'Data purchase of ₦250.00 for 07037943396 on MTN failed.', 250.00, 'kabriacid01@gmail.com', '17523622695977', '', '2025-07-12 23:18:11'),
(52, 136, 2, 1, 0, 'Data Purchase', 'ni ni-check-bold', 'text-success', 'debit', 'You purchased ₦150.00 data for 08011111111 on MTN.', 150.00, 'kabriacid01@gmail.com', '17523646949942', 'success', '2025-07-12 23:58:27'),
(53, 136, 2, 1, 0, 'Data Purchase', 'ni ni-mobile-button', 'text-danger', 'debit', 'Data purchase of ₦150.00 for 07037943396 on MTN failed.', 150.00, 'kabriacid01@gmail.com', '17523649633942', '', '2025-07-13 00:02:54'),
(54, 136, 2, 1, 0, 'Data Purchase', 'ni ni-check-bold', 'text-success', 'debit', 'You purchased ₦550.00 data for 08011111111 on MTN.', 550.00, 'kabriacid01@gmail.com', '17523649894478', 'success', '2025-07-13 00:03:20'),
(55, 136, 1, 1, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-success', 'debit', 'You have purchased ₦5,000.00 airtime for 08011111111 on MTN.', 5000.00, NULL, '17523650445336', 'success', '2025-07-13 00:04:12'),
(56, 136, 0, NULL, NULL, 'Money Transfer', 'ni ni-send', 'text-success', 'debit', 'Ray Solis', 5000.00, 'kabriacid01@gmail.com', 'tf_6873064aa484d1.12239992', 'success', '2025-07-13 01:05:14'),
(57, 242, 0, NULL, NULL, 'Money Received', 'ni ni-money-coins', 'text-success', 'credit', 'Abdullahi Kabri', 5000.00, 'jepacibet@gmail.com', 'tf_6873064aa484d1.12239992', 'success', '2025-07-13 01:05:14'),
(58, 136, 0, NULL, NULL, 'Money Transfer', 'ni ni-send', 'text-success', 'debit', 'Ray Solis', 3500.00, 'kabriacid01@gmail.com', 'tf_687308b5245a48.26665807', 'success', '2025-07-13 01:15:33'),
(59, 242, 0, NULL, NULL, 'Money Received', 'ni ni-money-coins', 'text-success', 'credit', 'Abdullahi Kabri', 3500.00, 'jepacibet@gmail.com', 'tf_687308b5245a48.26665807', 'success', '2025-07-13 01:15:33'),
(60, 136, 0, NULL, NULL, 'Money Transfer', 'ni ni-send', 'text-success', 'debit', 'Ray Solis', 300.00, 'kabriacid01@gmail.com', 'tf_687328aedcc1e4.61878518', 'success', '2025-07-13 03:31:58'),
(61, 242, 0, NULL, NULL, 'Money Received', 'ni ni-money-coins', 'text-success', 'credit', 'Abdullahi Kabri', 300.00, 'jepacibet@gmail.com', 'tf_687328aedcc1e4.61878518', 'success', '2025-07-13 03:31:58'),
(62, 136, 0, NULL, NULL, 'Money Transfer', 'ni ni-send', 'text-success', 'debit', 'Ray Solis', 300.00, 'kabriacid01@gmail.com', 'tf_687328e986e6d8.94009617', 'success', '2025-07-13 03:32:57'),
(63, 242, 0, NULL, NULL, 'Money Received', 'ni ni-money-coins', 'text-success', 'credit', 'Abdullahi Kabri', 300.00, 'jepacibet@gmail.com', 'tf_687328e986e6d8.94009617', 'success', '2025-07-13 03:32:57'),
(64, 242, 0, NULL, NULL, 'Money Transfer', 'ni ni-send', 'text-success', 'debit', 'Abdullahi Kabri', 3000.00, 'jepacibet@gmail.com', 'tf_68732a16937062.13521658', 'success', '2025-07-13 03:37:58'),
(65, 136, 0, NULL, NULL, 'Money Received', 'ni ni-money-coins', 'text-success', 'credit', 'Ray Solis', 3000.00, 'kabriacid01@gmail.com', 'tf_68732a16937062.13521658', 'success', '2025-07-13 03:37:58'),
(66, 242, 0, NULL, NULL, 'Money Transfer', 'ni ni-send', 'text-success', 'debit', 'Abdullahi Kabri', 200.00, 'jepacibet@gmail.com', 'tf_68732bb3e6d995.97643602', 'success', '2025-07-13 03:44:51'),
(67, 136, 0, NULL, NULL, 'Money Received', 'ni ni-money-coins', 'text-success', 'credit', 'Ray Solis', 200.00, 'kabriacid01@gmail.com', 'tf_68732bb3e6d995.97643602', 'success', '2025-07-13 03:44:51'),
(68, 242, 0, NULL, NULL, 'Money Transfer', 'ni ni-send', 'text-success', 'debit', 'Abdullahi Kabri', 1000.00, 'jepacibet@gmail.com', 'tf_68738eff9f7ff4.14835483', 'success', '2025-07-13 10:48:31'),
(69, 136, 0, NULL, NULL, 'Money Received', 'ni ni-money-coins', 'text-success', 'credit', 'Ray Solis', 1000.00, 'kabriacid01@gmail.com', 'tf_68738eff9f7ff4.14835483', 'success', '2025-07-13 10:48:31'),
(70, 242, 2, 1, 0, 'Data Purchase', 'ni ni-check-bold', 'text-success', 'debit', 'You purchased ₦250.00 data for 08011111111 on MTN.', 250.00, 'jepacibet@gmail.com', '17524037667726', 'success', '2025-07-13 10:49:36'),
(71, 242, 1, 1, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-success', 'debit', 'You have purchased ₦1,000.00 airtime for 08011111111 on MTN.', 1000.00, NULL, '17524038024142', 'success', '2025-07-13 10:50:11'),
(72, 136, 3, 5, 0, 'TV Subscription', 'ni ni-tv-2', 'text-success', 'debit', 'You purchased ₦2,500.00 TV subscription for IUC 1212121212 on DSTV.', 2500.00, 'kabriacid01@gmail.com', '17524287764315', 'success', '2025-07-13 17:46:29'),
(73, 136, 3, 5, 0, 'TV Subscription', 'ni ni-tv-2', 'text-success', 'debit', 'You purchased ₦2,500.00 TV subscription for IUC 1212121212 on DSTV.', 2500.00, 'kabriacid01@gmail.com', '17524292753401', 'success', '2025-07-13 17:54:43'),
(74, 242, 1, 1, NULL, 'Airtime others', 'ni ni-mobile-button', 'text-success', 'debit', 'You have purchased ₦200.00 airtime for 08011111111 on MTN.', 200.00, NULL, '17524327851082', 'success', '2025-07-13 18:53:15'),
(75, 136, 2, 1, 0, 'Data Purchase', 'ni ni-mobile-button', 'text-danger', 'debit', 'Data purchase of ₦150.00 for 07037943396 on MTN failed.', 150.00, 'kabriacid01@gmail.com', 'AT-136-1753704240-6374', '', '2025-07-28 12:04:11'),
(76, 136, 0, NULL, NULL, 'Money Transfer', 'ni ni-send', 'text-success', 'debit', 'Musa Jidder', 1000.00, 'kabriacid01@gmail.com', 'tf_68877202e11233.40585025', 'success', '2025-07-28 12:50:10'),
(77, 133, 0, NULL, NULL, 'Money Received', 'ni ni-money-coins', 'text-success', 'credit', 'Shelley Wheeler', 1000.00, 'musajidder@gmail.com', 'tf_68877202e11233.40585025', 'success', '2025-07-28 12:50:10'),
(78, 136, 1, 1, NULL, 'Airtime Purchase', 'ni ni-mobile-button', 'text-success', 'debit', 'You purchased ₦100.00 airtime for 07037943396 on MTN', 100.00, 'kabriacid01@gmail.com', 'AT-136-1753869524-3394', 'success', '2025-07-30 09:59:00'),
(79, 136, 1, 1, NULL, 'Airtime Purchase', 'ni ni-mobile-button', 'text-success', 'debit', 'You purchased ₦100.00 airtime for 08132936452 on MTN', 100.00, 'kabriacid01@gmail.com', 'AT-136-1753869664-4926', 'success', '2025-07-30 10:01:36'),
(80, 136, 1, 1, NULL, 'Airtime Purchase', 'ni ni-mobile-button', 'text-success', 'debit', 'You purchased ₦100.00 airtime for 08102516052 on MTN', 100.00, 'kabriacid01@gmail.com', 'AT-136-1753870853-9277', 'success', '2025-07-30 10:21:15'),
(81, 136, 2, 1, 354408, 'Data Purchase', 'ni ni-world-2', 'text-success', 'debit', 'You purchased ₦99.00 data for 07037943396 on MTN', 99.00, 'kabriacid01@gmail.com', 'DT-136-1753885133-9475', 'success', '2025-07-30 14:19:04'),
(82, 136, 2, 1, 354408, 'Data Purchase', 'ni ni-world-2', 'text-success', 'debit', 'You purchased ₦99.00 data for 08062365769 on MTN', 99.00, 'kabriacid01@gmail.com', 'DT-136-1753885277-4963', 'success', '2025-07-30 14:21:28'),
(83, 136, 1, 1, NULL, 'Airtime Purchase', 'ni ni-mobile-button', 'text-success', 'debit', 'You purchased ₦600.00 airtime for 07037943396 on MTN', 600.00, 'kabriacid01@gmail.com', 'AT-136-1753887173-2161', 'success', '2025-07-30 14:53:03'),
(84, 136, 2, 1, 354406, 'Data Purchase', 'ni ni-world-2', 'text-success', 'debit', 'You purchased ₦499.00 data for 07037943396 on MTN', 499.00, 'kabriacid01@gmail.com', 'DT-136-1753887249-7917', 'success', '2025-07-30 14:54:20');

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

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `virtual_account`, `account_name`, `bank_name`, `billstack_ref`, `user_name`, `first_name`, `last_name`, `email`, `phone_number`, `password`, `w_bank_name`, `w_account_number`, `iuc_number`, `txn_pin`, `address`, `state`, `country`, `city`, `photo`, `registration_id`, `referral_code`, `referral_link`, `referred_by`, `registration_status`, `account_status`, `failed_attempts`, `kyc_value`, `kyc_status`, `kyc_type`, `created_at`, `updated_at`) VALUES
(115, '2147483647', 'BillStack/VTU-Katelyn', 'PalmPay', 'R-FPAWMNYEDW', '', 'Katelyn', 'Guzman', 'zoxalon@gmail.com', '8011737992', '$2y$10$s.QwmCU.4t91qmIomXOJXemvFFObyah/RsIWLQmiUerezM3N6PIG.', '', '', '', '1111', '', '', '', '', 'uploads/default.png', 'afdf227ba83dff6e5951c53c73bdf0ec', 'UXIZW9T1GK', '', NULL, 'complete', '101', 0, '', 'unverified', NULL, '2025-06-17 17:24:40', NULL),
(131, '5761210754', 'VTU-Hiroko Mclaughlin', '9PSB Bank', 'R-PRZTYEMAHS', '', 'Hiroko', 'Mclaughlin', 'xehemexa@gmail.com', '8085311846', '$2y$10$eHvzv7/KbZmv0f0ZQUNEJeptrbE46oNkw.CZlDYOh7Z38bBNkSxYm', '', '', '', NULL, '', '', '', '', 'uploads/default.png', 'b1c1be0f5a2f3971b0e14a074ebd70e4', 'H41SILO96F', '', NULL, 'complete', '101', 0, '', 'unverified', NULL, '2025-06-17 17:24:40', NULL),
(132, '5761207770', 'VTU-Orson Head', '9PSB Bank', 'R-VTPXCKSJYV', '', 'Rebecca', 'Dickerson', 'muhammadmjidder@gmail.com', '8038851880', '$2y$10$HbwMQiD0.N2mdHvSGSvHDOricGNaKwB0S.8UqgIkHII6hHLZu/2D2', '', '', '', NULL, '', '', '', '', 'uploads/default.png', '81c6536766fc6acaea9ba82fd4d548b2', '0KLFPDQ43H', '', 'H41SILO96F', 'complete', '101', 0, '', 'unverified', NULL, '2025-06-17 17:24:40', NULL),
(133, '5761212820', 'VTU-Britanni Downs', '9PSB Bank', 'R-SDYNQCBSZV', '', 'Musa', 'Jidder', 'musajidder@gmail.com', '8076574147', '$2y$10$HPgBE21jeE5LYkvpTDXu7OZ2e4P.Jqa6LNNYDn/fB2UDnATvKNl4S', '', '', '', '1090', '', '', '', '', 'uploads/default.png', 'ebaeaaeac1ae51166c60916c1e85b765', '1Q9764VM5R', '', NULL, 'complete', '101', 0, '', 'unverified', NULL, '2025-06-17 17:24:40', NULL),
(135, '5761221301', 'VTU-Colette Chase', '9PSB Bank', 'R-WEHVXNMRDG', '', 'Colette', 'Chase', 'musa@gmail.com', '8040993201', '$2y$10$hXdg2zGjrosN0/Yy.5qXWOuuA11h2dxxTu47Vg0a7S44oOTZlnSkC', '', '', '', '$2y$10$Du4Jg.Y8yC5DSExH.DeJP.kO9pz.KyMXYCzkNVnXzYlviDqqcCcji', '', '', '', '', 'uploads/default.png', '9e1948165924f8bbef7b11ed0c9cc37a', 'NBDJXG2K10', '', '1Q9764VM5R', 'complete', '101', 0, '', 'unverified', NULL, '2025-06-17 17:24:40', NULL),
(139, '5761257555', 'VTU-Chastity Obrien', '9PSB Bank', 'R-FRZBBQJNXG', 'Chastity', 'Chastity', 'Obrien', 'vunota@gmail.com', '8023983839', '$2y$10$R8v1oCjJaMYq79lcbor5WeaT2MZxUX9gnMncA2zPeoGazeBHhe/d6', '', '', '', '$2y$10$jih0/RLy4PFz..0ApjgHuOtqkftxcy/zA4O80KmsiZbsHqDOhclGu', '', '', '', '', 'uploads/default.png', 'cf75622cfd811a09a420ba5fa329fec3', 'B1X69UVJKP', '', NULL, 'complete', '101', 0, '', 'unverified', NULL, '2025-07-12 23:15:21', NULL),
(140, '5761257603', 'VTU-Gabriel Odonnell', '9PSB Bank', 'R-TSUTFSVWPK', '', 'Gabriel', 'Odonnell', 'dyjo@gmail.com', '8012721760', '$2y$10$tzHdRsqtRRlBeIyaH91yWOiX46sGdEcYH9pqqtGr2bfDIl2pSdACu', '', '', '', NULL, '', '', '', '', 'uploads/default.png', '9f010b4dc7cc9774ec06d275248db1a9', 'VROJ2E4ULB', '', 'B1X69UVJKP', 'complete', '101', 0, '', 'unverified', NULL, '2025-06-17 17:24:40', NULL),
(144, '5761409749', 'VTU-Muhammad Bappayo', '9PSB Bank', 'R-OPRCVBEEKV', '', 'Muhammad', 'Bappayo', 'alhpeace001@gmail.com', '8064509234', '$2y$10$aLDbre5oxj.cL7I3UUfYKe/e9XLx4ziDeikYJlTO.OU6v6rkDUibu', '', '', '', NULL, '', '', '', '', 'uploads/default.png', 'a8a785096dae3b93285c371f69851959', '5HRGYIZFW2', '', 'XL5ZJWK4DO', 'complete', '101', 0, '', 'unverified', NULL, '2025-06-17 17:24:40', NULL),
(145, '5761490639', 'VTU-Abdulsalami Ismaila', '9PSB Bank', 'R-ZBPLNOVZJC', '', 'Abdulsalami', 'Ismaila', 'abdulsalamiismaila8@gmail.com', '9029202858', '$2y$10$dSQTzRZsSGnqPK.h5TXXh.TH/zcPtX0z2FJdqzrFlV/m8hzuKBY1e', '', '', '', NULL, '', '', '', '', 'uploads/default.png', 'af9922d9cfb14a9da86829f8485134f2', 'GA3C2T1FN8', '', NULL, 'complete', '101', 0, '', 'unverified', NULL, '2025-06-17 17:24:40', NULL),
(179, '5761525724', 'VTU-Stacy Spencer', '9PSB Bank', 'R-JPQMONHLWF', '', 'Stacy', 'Spencer', 'duvuba@gmail.com', '9005648888', '$2y$10$u7yByZ0ZDGb0F6aDbwfOUOXQcGGbqjmyVVGYpXw9Ryy4GStCHVRQ6', 'Opay', '9876543456', '', '$2y$10$r/GtFOrjTjGCEp7//xoTbelkErl6FD/eMyv74ob/9nNZYHpDRkHFm', '923 White Old Boulevard', 'Kogi', '', 'Ijumu', 'uploads/default.png', '3668197e736c6474910dcf53083b3f9f', 'OYFGEV9KQM', '', 'XL5ZJWK4DO', 'complete', '101', 0, '', 'unverified', NULL, '2025-06-17 17:24:40', NULL),
(187, '5761552748', 'VTU-Jamal Jugulde', '9PSB Bank', 'R-SPSNWVUCTU', '', 'Jamal', 'Jugulde', 'majugulde03@gmail.com', '7012589879', '$2y$10$BJyFCfypbUzymQbf7/Y/D.Rb5eDjtH27WxIIRuImoKML.CmomzhqC', 'Opay', '7012589879', '', '$2y$10$QPFHHNxqJk1vF7P31J8lLuZsfk0b8OiDnCNybXl2YKpMe52xkdJdO', 'Samunaka Junction, Jalingo', 'Taraba', '', 'Sardauna', 'uploads/default.png', 'e6fcfac8a582baa3d5ebe083c1fba0ae', '79SHT013JG', '', NULL, 'complete', '101', 0, '', 'unverified', NULL, '2025-06-17 17:24:40', NULL),
(188, NULL, '', '', '', '', 'Keegan', 'Snow', 'watulipe@gmail.com', '8052351598', '', '', '', '', NULL, '', '', '', '', 'uploads/default.png', '43aae076197f597aac9937edf6f6d601', NULL, '', NULL, 'incomplete', '101', 0, NULL, 'unverified', NULL, '2025-06-17 17:24:40', NULL),
(189, NULL, '', '', '', '', '', '', 'zainababdullahi2003@gmail.com', '9076567895', '', '', '', '', NULL, '', '', '', '', 'uploads/default.png', 'b9819072e7bfd0a001969cc3c0c0775d', NULL, '', NULL, 'incomplete', '101', 0, NULL, 'unverified', NULL, '2025-06-17 17:24:40', NULL),
(210, '6646625932', 'BillStack/VTU-Hakeem', 'PalmPay', 'R-CLRTMENZEZ', 'xazuxywiz', 'Hakeem', 'Cameron', 'nykyqym@gmail.com', '08083842646', '$2y$10$UnyyP6b46zmxMsUH3/SC8.6t9iVRQensna3UFP0zwIP3.PI6UoQRq', '', '', '', '$2y$10$CUQwpvZoVGqIbT43mYQBEe42vFLXULcqZfphiGY5oDqzt44ZRG9lu', '', '', '', '', 'uploads/default.png', 'c6e1c7dcd79d9cbe1788a3ec3f19d0ee', 'H4Y8TBKF0U', '', NULL, 'complete', '101', 0, NULL, 'unverified', NULL, '2025-06-20 19:11:13', NULL),
(214, '6628651282', 'BillStack/VTU-Muhammad', 'PalmPay', 'R-VTPXCKSJYV', 'Mjidder', 'Muhammad', 'jidda', 'muhammadmjidder8@gmail.com', '08146785103', '$2y$10$3HWENXRoD8XSflOwBE.RXe5tPWK/GFHfBAj/8TrV6x7K1eICpHCxq', '', '', '', NULL, '', '', '', '', 'uploads/default.png', 'dd2bf6b66e8a547be99b82b2fa1c3368', 'A4O7SR9YWK', '', NULL, 'complete', '101', 0, NULL, 'unverified', NULL, '2025-06-20 19:11:13', NULL),
(218, '6654615891', 'BillStack/VTU-Rabiu', 'PalmPay', 'R-XTQJRDYPMK', 'muhammedRabiu', 'Rabiu', 'Muhammed', 'ademu0882@gmail.com', '08110237625', '$2y$10$t/3Lqq.DiRrgMRnEVva8vu78yxPNf1jSzowVvENTmlwFtnIb5Iw8a', '', '', '', '$2y$10$JoyFRNH2qhVQ3gf6r09ZTurtZJxZTzUxYCTg/QO.Te7OAm8F5tPf.', '', '', '', '', 'uploads/default.png', '50ed09409d987d8d5b7fa4b0268a400a', 'L40M5KXGC1', '', NULL, 'complete', '101', 0, NULL, 'unverified', NULL, '2025-06-20 19:11:13', NULL),
(219, '5761945036', 'VTU-Rabiu Muhammed', '9PSB Bank', 'R-GUAFBLKCFK', 'Rabiu', 'Ademu', 'Rabiu', 'rademu910@gmail.com', '09045144840', '$2y$10$UzWXb4FwAvUXaAj6dlXhluVTy9cQS9FlfU.WcXqvBj776n.j0SPRa', '', '', '', NULL, '', '', '', '', 'uploads/default.png', '406310d5aa2510d0822b677c4ba54b13', '038HRJ2PVZ', 'https://dataspeed.com.ng/public/pages/register.php?referral_code=038HRJ2PVZ', 'XL5ZJWK4DO', 'complete', '101', 0, NULL, 'unverified', NULL, '2025-07-31 08:22:53', NULL),
(220, '5761525346', 'VTU-Sadik Dahiru', '9PSB Bank', 'R-JWQUSYDTGM', 'Sadik_kabure', 'Sadik', 'Dahiru', 'sadikdahiru419@gmail.com', '09035124276', '$2y$10$2lEJS5ZDe/G5d5QfGuWPsOz5saBZtptq5/5Jp67x/OQo.ZLQ63zwW', '', '', '', '$2y$10$.99Sq1IgxDEy22DoJXXOxuxB2FvDSqsun373PbAgM2gMP6Med9dk.', '', '', '', '', 'uploads/default.png', '66dee1d2bcbe08dec9110d7e6f40d44d', '4ZDEY09RXN', '', NULL, 'complete', '101', 0, NULL, 'unverified', NULL, '2025-06-20 19:11:13', NULL),
(221, '5761960530', 'VTU-Aliyu Abba Suleiman', '9PSB Bank', 'R-ZHAWKRNSDT', 'AASuleiman', 'Aliyu Abba', 'Suleiman', 'aliyuabbasuleiman59@gmail.com', '09034212496', '$2y$10$unIiRQ.NqpKGkvpQ2MqtMeFSY/P3WcHYw5e6fvlcfNhT/nUkuFFQW', 'Opay', '9034212496', '', '$2y$10$p4AOdFN044PUOcaqTGQIMOkxacjRXfxmciKyRYaMSebmzY/0M77Qi', 'Gulma Road', 'Kebbi', '', 'Argungu', 'uploads/default.png', 'fa3a47fc7cf5c6b9f80cfcb404c3ba3f', 'ETDXSF3RGQ', '', NULL, 'complete', '101', 0, NULL, 'unverified', NULL, '2025-07-30 19:01:47', '2025-07-30 20:01:47'),
(222, '5761968488', 'VTU-Abdussalam Abubakar', '9PSB Bank', 'R-KEYWQPNUHH', 'Abdul', 'Abdussalam', 'Abubakar', 'abdulsalamiismaila@gmail.com', '07033398766', '$2y$10$6H6EFOTsVRMWWvPebcGgjui9849MDbA5ajS64WYSwzsYMFozeXRsK', '', '', '', NULL, '', '', '', '', 'uploads/default.png', 'd40fccc258a5a19488909eacc1bf9cb3', '6ZGYH4UNWJ', '', NULL, 'complete', '101', 0, NULL, 'unverified', NULL, '2025-06-17 22:36:12', NULL),
(236, '5762443948', 'VTU-Quemby Woodard', '9PSB Bank', 'R-KJOGRJHBNQ', 'Bozyko', 'Quemby', 'Woodard', 'weqyrod@gmail.com', '09062128726', '$2y$10$fpLGC.KW6b03jleFTQXdMusUKbCZ19S6tDBUT.A9Kkn.mElOGP9Rm', '', '', '', '$2y$10$FWmMQDcfoXZXvfC/vWuw.O6XJ0a/rgmhzsbm9gaxrTTIOSLnmf1YC', '', '', '', '', 'uploads/default.png', '5e3c90bf85f82a884d26e390cc2c1454', 'UFSEPLKGD4', 'https://dataspeed.com.ng/public/pages/register.php?referral_code=UFSEPLKGD4', NULL, 'complete', '102', 5, NULL, 'unverified', NULL, '2025-06-26 11:36:50', NULL),
(237, '5762462378', 'VTU-Idola Bentley', '9PSB Bank', 'R-VOAXQEREHZ', 'Varitome', 'Idola', 'Bentley', 'haje@gmail.com', '09048448693', '$2y$10$bLnzCA39nKekPF0k.JWW3e6xWVNIAsXEHu659VMbtMn7ZG1mRJJSS', '', '', '', NULL, '', '', '', '', 'uploads/default.png', '1b2e43d24f383cc2a2df3aa2c6a9e6ac', '6EMK4XLPTH', 'https://dataspeed.com.ng/public/pages/register.php?referral_code=6EMK4XLPTH', NULL, 'complete', '101', 1, NULL, 'unverified', NULL, '2025-06-26 12:54:04', NULL),
(242, '5763327379', 'VTU-Ray Solis', '9PSB Bank', 'R-SEZKNMQYBM', 'Fuduluti', 'Ray', 'Solis', 'jepacibet@gmail.com', '08095784833', '$2y$10$o3GB04TQU6m5ld8g9G/OrulEVn8CTWIfU9q12/wb5ub4lvXYhc2/y', '', '', '', '$2y$10$hCvD93IJfQqXZrTZDgFYHuuND0MQwCo674Pc47obogXTZJjdIa4Ai', 'Samunaka Junction, Jalingo', 'Bauchi', '', 'Bogoro', 'uploads/default.png', '86503d3a702362a19b8889f2afced1f4', 'EH6VAOMB7S', 'https://dataspeed.com.ng/public/pages/register.php?referral_code=EH6VAOMB7S', NULL, 'complete', '102', 5, NULL, 'unverified', NULL, '2025-07-13 18:56:40', '2025-07-13 04:37:33'),
(243, NULL, '', '', '', '', '', '', 'koxob@gmail.com', NULL, '', '', '', '', NULL, '', '', '', '', 'uploads/default.png', 'f21c3b0a0530d6fffa094b48e3873c57', NULL, '', NULL, 'incomplete', '101', 0, NULL, 'unverified', NULL, '2025-07-16 02:00:59', NULL),
(244, '5763443237', 'VTU-Hyatt Obrien', '9PSB Bank', 'R-RSXYMJLRTM', 'Wodev', 'Hyatt', 'Obrien', 'zzetim@gmail.com', '08041375606', '$2y$10$lMj1aNU1ZF8wWu8s5x7mo.WCqRP5IQZVodviVDQfLWeZbehw9tdJe', '', '', '', NULL, '', '', '', '', 'uploads/default.png', 'dd8ed1e7b8dccb3592ce72b02e6abc8a', 'YXGCBWSZ7T', 'https://dataspeed.com.ng/public/pages/register.php?referral_code=YXGCBWSZ7T', NULL, 'complete', '101', 0, NULL, 'unverified', NULL, '2025-07-16 07:10:36', NULL),
(246, '5763744147', 'VTU-Lester Hawkins', '9PSB Bank', 'R-SXYWLTZJLZ', 'Renev', 'Lester', 'Hawkins', 'johik@gmail.com', '09090779344', '$2y$10$jfNgTKM6tdBJplChh90edOdpoS7CpfL5XsEhwS47iCQxgIYBLdE1K', '', '', '', NULL, '', '', '', '', 'uploads/default.png', '96050b9883be8f1d01cb20add7065f1c', '2M71KLXFVJ', 'https://dataspeed.com.ng/public/pages/register.php?referral_code=2M71KLXFVJ', NULL, 'complete', '101', 0, NULL, 'unverified', NULL, '2025-07-25 05:07:47', NULL),
(251, NULL, '', '', '', 'Kabure', 'Sadik', 'Dahiru', 'sadikdahiru419@yahoo.com', '08081514371', '', '', '', '', NULL, '', '', '', '', 'uploads/default.png', '2122f7201dbfc7dba43a57657fbefeeb', NULL, '', NULL, 'incomplete', '101', 0, NULL, 'unverified', NULL, '2025-07-31 11:17:20', NULL),
(256, NULL, '', '', '', '', '', '', 'eced235731@students.umyu.edu.ng', '07037943396', '', '', '', '', NULL, '', '', '', '', 'uploads/default.png', 'ad9fa5e67bc954120be095033f9c7553', NULL, '', NULL, 'incomplete', '101', 0, NULL, 'unverified', NULL, '2025-08-01 22:46:08', NULL);

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

--
-- Dumping data for table `user_settings`
--

INSERT INTO `user_settings` (`user_id`, `biometrics_enabled`, `hide_balance`, `session_expiry_enabled`, `account_locked`, `ip_address`) VALUES
(139, 0, 0, 1, 0, NULL),
(219, 0, 0, 1, 0, '105.112.112.242'),
(222, 0, 0, 1, 0, '105.112.226.25'),
(236, 0, 0, 1, 0, '::1'),
(237, 0, 0, 1, 0, '::1'),
(242, 0, 0, 1, 0, '::1'),
(244, 0, 0, 1, 0, '102.88.104.242'),
(246, 0, 0, 1, 0, '105.112.23.181');

-- --------------------------------------------------------

--
-- Table structure for table `variations`
--

CREATE TABLE `variations` (
  `id` bigint(20) NOT NULL,
  `service_id` varchar(50) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `vt_service_name` varchar(100) NOT NULL,
  `vt_service_id` varchar(100) NOT NULL,
  `convenience_fee` varchar(50) DEFAULT NULL,
  `variation_code` varchar(100) NOT NULL,
  `name` varchar(200) NOT NULL,
  `type` varchar(10) NOT NULL,
  `variation_amount` decimal(12,2) NOT NULL,
  `volume` varchar(10) NOT NULL,
  `fixed_price` tinyint(1) NOT NULL DEFAULT 0,
  `is_active` tinyint(4) NOT NULL DEFAULT 1,
  `validity` varchar(50) DEFAULT NULL,
  `updated_at` datetime NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
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
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `uq_service_variation` (`service_id`,`variation_code`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `account_balance`
--
ALTER TABLE `account_balance`
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `account_complaints`
--
ALTER TABLE `account_complaints`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `account_reset_tokens`
--
ALTER TABLE `account_reset_tokens`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `forgot_password`
--
ALTER TABLE `forgot_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `forgot_pin`
--
ALTER TABLE `forgot_pin`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `nigerian_states`
--
ALTER TABLE `nigerian_states`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=38;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=166;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=182;

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=142;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=85;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=258;

--
-- AUTO_INCREMENT for table `variations`
--
ALTER TABLE `variations`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `account_reset_tokens`
--
ALTER TABLE `account_reset_tokens`
  ADD CONSTRAINT `account_reset_tokens_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
