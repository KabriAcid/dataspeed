-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 30, 2025 at 12:30 PM
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
  `wallet_balance` decimal(15,2) DEFAULT NULL,
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_balance`
--

INSERT INTO `account_balance` (`account_id`, `user_id`, `wallet_balance`, `updated_at`) VALUES
(1, 133, 300.00, '2025-05-22 22:40:29');

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
(0, 'kabriacid01@gmail.com', 'abe5ec01479174bab654220d8f072986', '2025-05-26 21:17:39', '2025-05-26 20:07:39'),
(0, 'kabriacid01@gmail.com', '2ea183631115aa627a3f6728988cac3a', '2025-05-28 05:36:59', '2025-05-28 04:26:59'),
(0, 'kabriacid01@gmail.com', '08e1cf7263e979679adcc30c25c7f441', '2025-05-28 09:03:44', '2025-05-28 07:53:44'),
(0, 'kabriacid01@gmail.com', '1f4718d03f048f55eb3f4c7c2a5b5f70', '2025-05-28 09:04:00', '2025-05-28 07:54:00'),
(0, 'kabriacid01@gmail.com', 'b62f01fc435872e115283aa9ac5de70c', '2025-05-28 09:04:27', '2025-05-28 07:54:27'),
(0, 'kabriacid01@gmail.com', 'a194bb12534692b1df6455cc702fe5fe', '2025-05-28 09:04:32', '2025-05-28 07:54:32'),
(0, 'kabriacid01@gmail.com', '83d407283a98320ad6b4a3b5d76d3dfc', '2025-05-28 09:04:37', '2025-05-28 07:54:37'),
(0, 'kabriacid01@gmail.com', 'ecf0e12bd6d85faad90440914d33e5f7', '2025-05-28 09:04:41', '2025-05-28 07:54:41'),
(0, 'kabriacid01@gmail.com', 'fdb52fa2cc3aa1053bbf9afb048b2dce', '2025-05-28 09:05:02', '2025-05-28 07:55:02'),
(0, 'kabriacid01@gmail.com', 'c9665fddc4f550f38cce62e85b87e5d1', '2025-05-28 09:05:23', '2025-05-28 07:55:23');

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
  `is_read` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `notifications`
--

INSERT INTO `notifications` (`id`, `user_id`, `title`, `message`, `type`, `icon`, `is_read`, `created_at`) VALUES
(1, 139, 'Virtual Account Created', 'Congratulations! Virtual account has been created successfully', 'virtual_account', 'fa-account', 0, '2025-05-20 00:05:07'),
(2, 139, 'Referral Bonus', 'You have successfully redeemed your referral bonus and was added to your balance.', 'refferal_bonus', 'fa-coins', 0, '2025-05-20 00:56:00'),
(3, 144, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'fa-home', 0, '2025-05-26 20:45:38'),
(4, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 01:44:19'),
(5, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 01:45:17'),
(6, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 01:45:49'),
(7, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 01:50:06'),
(8, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 01:59:01'),
(9, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:00:26'),
(10, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:10:56'),
(11, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:12:58'),
(12, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:16:22'),
(13, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:24:48'),
(14, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:26:41'),
(15, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:29:12'),
(16, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:29:51'),
(17, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:41:35'),
(18, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:46:00'),
(19, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:48:25'),
(20, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:51:02'),
(21, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:55:36'),
(22, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:59:25');

-- --------------------------------------------------------

--
-- Table structure for table `otp_codes`
--

CREATE TABLE `otp_codes` (
  `id` int(11) NOT NULL,
  `email` varchar(255) NOT NULL,
  `otp_code` varchar(6) NOT NULL,
  `expires_at` datetime NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `otp_codes`
--

INSERT INTO `otp_codes` (`id`, `email`, `otp_code`, `expires_at`, `created_at`) VALUES
(3, 'zycuk@gmail.com', '843743', '2025-05-18 00:57:14', '2025-05-17 22:47:14'),
(14, 'piquguc@gmail.com', '812642', '2025-05-18 03:11:52', '2025-05-18 01:01:52'),
(40, 'fipari@gmail.com', '139422', '2025-05-18 07:06:57', '2025-05-18 04:56:57'),
(41, 'lymamyt@gmail.com', '660999', '2025-05-18 07:10:09', '2025-05-18 05:00:09'),
(42, 'vape@gmail.com', '401924', '2025-05-18 07:13:56', '2025-05-18 05:03:56'),
(43, 'xuvy@gmail.com', '599665', '2025-05-18 07:14:12', '2025-05-18 05:04:12'),
(45, 'muhammadmjidder8@gmail.com', '431441', '2025-05-18 08:20:42', '2025-05-18 06:10:42'),
(46, 'muhammadmjidder8@gmail.com', '831680', '2025-05-18 08:26:20', '2025-05-18 06:16:20'),
(47, 'muhammadmjidder8@gmail.com', '366689', '2025-05-18 08:27:18', '2025-05-18 06:17:18'),
(53, 'musajidda1@gmail.com', '508980', '2025-05-18 10:00:20', '2025-05-18 07:50:20'),
(60, 'musajidda@gmail.com', '861574', '2025-05-18 16:46:25', '2025-05-18 14:36:25'),
(64, 'sesugamys@gmail.com', '356700', '2025-05-20 01:09:50', '2025-05-19 22:59:50'),
(69, 'muhammadbappayo14@gmail.com', '792982', '2025-05-26 21:48:34', '2025-05-26 20:38:34'),
(70, 'muhammadbappyo14@gmail.com', '830058', '2025-05-26 21:50:58', '2025-05-26 20:40:58'),
(71, 'alhajipeace001@gmail.com', '561185', '2025-05-26 21:51:57', '2025-05-26 20:41:57'),
(74, 'muhammadmjidder8@gmail.com', '247014', '2025-05-30 06:31:15', '2025-05-30 05:21:15');

-- --------------------------------------------------------

--
-- Table structure for table `referrals`
--

CREATE TABLE `referrals` (
  `referral_id` int(11) NOT NULL,
  `reward` decimal(10,2) NOT NULL,
  `user_id` int(11) NOT NULL,
  `referral_code` varchar(10) NOT NULL,
  `referral_link` varchar(255) NOT NULL,
  `status` enum('claimed','pending') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `referrals`
--

INSERT INTO `referrals` (`referral_id`, `reward`, `user_id`, `referral_code`, `referral_link`, `status`, `created_at`) VALUES
(9, 100.00, 133, '1Q9764VM5R', 'https://dataspeed.com.ng/public/pages/backend/register.php?referral_code=B1X69UVJKP', 'claimed', '2025-05-20 04:53:16'),
(10, 100.00, 136, 'XL5ZJWK4DO', 'https://dataspeed.com.ng/public/pages/backend/register.php?referral_code=XL5ZJWK4DO', 'claimed', '2025-05-28 04:34:36');

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
(2, 'Airtime', 'airtime', '2025-05-15 21:12:19');

-- --------------------------------------------------------

--
-- Table structure for table `service_plans`
--

CREATE TABLE `service_plans` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `provider_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `api_id` varchar(50) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `type` varchar(50) DEFAULT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `service_providers`
--

CREATE TABLE `service_providers` (
  `id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(50) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `service_providers`
--

INSERT INTO `service_providers` (`id`, `service_id`, `name`, `code`) VALUES
(1, 1, 'MTN', '1');

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `wallet_id` int(11) NOT NULL,
  `service_id` int(11) NOT NULL,
  `provider_id` int(11) DEFAULT NULL,
  `plan_id` int(11) DEFAULT NULL,
  `type` varchar(50) NOT NULL,
  `amount` decimal(10,2) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `status` varchar(50) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `account_id` int(11) NOT NULL,
  `virtual_account` varchar(20) DEFAULT NULL,
  `account_name` varchar(50) NOT NULL,
  `bank_name` varchar(50) NOT NULL,
  `billstack_ref` varchar(20) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(11) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `w_account_name` varchar(50) NOT NULL,
  `w_account_number` int(10) NOT NULL,
  `w_bank_name` varchar(50) NOT NULL,
  `txn_pin` int(4) NOT NULL,
  `address` varchar(100) NOT NULL,
  `state` varchar(15) NOT NULL,
  `country` varchar(15) NOT NULL,
  `city` varchar(20) NOT NULL,
  `photo` varchar(255) NOT NULL DEFAULT 'uploads/default.png',
  `updated_at` datetime DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `registration_id` varchar(50) NOT NULL,
  `referral_code` varchar(10) DEFAULT NULL,
  `referred_by` varchar(10) DEFAULT NULL,
  `registration_status` enum('incomplete','complete') DEFAULT 'incomplete',
  `account_status` enum('Active','Inactive','Banned','Frozen') NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `account_id`, `virtual_account`, `account_name`, `bank_name`, `billstack_ref`, `first_name`, `last_name`, `email`, `phone_number`, `password`, `w_account_name`, `w_account_number`, `w_bank_name`, `txn_pin`, `address`, `state`, `country`, `city`, `photo`, `updated_at`, `created_at`, `registration_id`, `referral_code`, `referred_by`, `registration_status`, `account_status`) VALUES
(115, 212, '2147483647', 'BillStack/VTU-Katelyn', 'PalmPay', 'R-FPAWMNYEDW', 'Katelyn', 'Guzman', 'zoxalon@gmail.com', '8011737992', '$2y$10$s.QwmCU.4t91qmIomXOJXemvFFObyah/RsIWLQmiUerezM3N6PIG.', '', 0, '', 1111, '', '', '', '', 'uploads/default.png', NULL, '2025-05-20 02:18:22', 'afdf227ba83dff6e5951c53c73bdf0ec', 'UXIZW9T1GK', NULL, 'complete', 'Active'),
(131, 100, '5761210754', 'VTU-Hiroko Mclaughlin', '9PSB Bank', 'R-PRZTYEMAHS', 'Hiroko', 'Mclaughlin', 'xehemexa@gmail.com', '8085311846', '$2y$10$eHvzv7/KbZmv0f0ZQUNEJeptrbE46oNkw.CZlDYOh7Z38bBNkSxYm', '', 0, '', 0, '', '', '', '', 'uploads/default.png', NULL, '2025-05-18 08:19:10', 'b1c1be0f5a2f3971b0e14a074ebd70e4', 'H41SILO96F', NULL, 'complete', 'Active'),
(132, 0, '5761207770', 'VTU-Orson Head', '9PSB Bank', 'R-VTPXCKSJYV', 'Rebecca', 'Dickerson', 'muhammadmjidder@gmail.com', '8038851880', '$2y$10$HbwMQiD0.N2mdHvSGSvHDOricGNaKwB0S.8UqgIkHII6hHLZu/2D2', '', 0, '', 0, '', '', '', '', 'uploads/default.png', NULL, '2025-05-19 22:46:52', '81c6536766fc6acaea9ba82fd4d548b2', '0KLFPDQ43H', 'H41SILO96F', 'complete', 'Active'),
(133, 1, '5761212820', 'VTU-Britanni Downs', '9PSB Bank', 'R-SDYNQCBSZV', 'Musa', 'Jidder', 'musajidder@gmail.com', '8076574147', '$2y$10$HPgBE21jeE5LYkvpTDXu7OZ2e4P.Jqa6LNNYDn/fB2UDnATvKNl4S', '', 0, '', 1090, '', '', '', '', 'uploads/default.png', NULL, '2025-05-22 23:50:12', 'ebaeaaeac1ae51166c60916c1e85b765', '1Q9764VM5R', NULL, 'complete', 'Active'),
(134, 0, NULL, '', '', '', '', '', 'musajidda@gmail.com', NULL, '', '', 0, '', 0, '', '', '', '', 'uploads/default.png', NULL, '2025-05-18 14:36:25', '274d231a31d8ebf026f6dfbeba2010d2', NULL, '1Q9764VM5R', 'incomplete', 'Active'),
(135, 0, '5761221301', 'VTU-Colette Chase', '9PSB Bank', 'R-WEHVXNMRDG', 'Colette', 'Chase', 'musa@gmail.com', '8040993201', '$2y$10$hXdg2zGjrosN0/Yy.5qXWOuuA11h2dxxTu47Vg0a7S44oOTZlnSkC', '', 0, '', 0, '', '', '', '', 'uploads/default.png', NULL, '2025-05-18 14:42:14', '9e1948165924f8bbef7b11ed0c9cc37a', 'NBDJXG2K10', '1Q9764VM5R', 'complete', 'Active'),
(136, 0, '5761257050', 'VTU-Jenna Marshall', '9PSB Bank', 'R-ZUNTBSDTMW', 'Abdullahi', 'Kabri', 'kabriacid01@gmail.com', '8087955382', '$2y$10$lYTdA5uFvfQKlsn0mTYd.uBuy2N7eY/NlgsBNVv7t1CZZVuldCCXq', 'Abdullahi Kabri', 703794339, 'Opay', 1111, '', '', '', '', 'uploads/default.png', NULL, '2025-05-30 05:33:22', '949394aeb0a04a78486fd806ca7c24f1', 'XL5ZJWK4DO', NULL, 'complete', 'Active'),
(137, 0, NULL, '', '', '', 'd', 'd', 'muhammadmjidder8@gmail.com', '9064345344', '', '', 0, '', 0, '', '', '', '', 'uploads/default.png', NULL, '2025-05-30 05:17:19', '40a9e8a9ee14321af4597ad9817fdf34', NULL, NULL, 'incomplete', 'Active'),
(138, 0, NULL, '', '', '', 'Alec', 'Riggs', 'sesugamys@gmail.com', '8085661678', '', '', 0, '', 0, '', '', '', '', 'uploads/default.png', NULL, '2025-05-19 23:00:28', '10375ef973623585f6533ee0aa93f667', NULL, NULL, 'incomplete', 'Active'),
(139, 1, '5761257555', 'VTU-Chastity Obrien', '9PSB Bank', 'R-FRZBBQJNXG', 'Chastity', 'Obrien', 'vunota@gmail.com', '8023983839', '$2y$10$R8v1oCjJaMYq79lcbor5WeaT2MZxUX9gnMncA2zPeoGazeBHhe/d6', '', 0, '', 0, '', '', '', '', 'uploads/default.png', NULL, '2025-05-20 02:18:15', 'cf75622cfd811a09a420ba5fa329fec3', 'B1X69UVJKP', NULL, 'complete', 'Active'),
(140, 0, '5761257603', 'VTU-Gabriel Odonnell', '9PSB Bank', 'R-TSUTFSVWPK', 'Gabriel', 'Odonnell', 'dyjo@gmail.com', '8012721760', '$2y$10$tzHdRsqtRRlBeIyaH91yWOiX46sGdEcYH9pqqtGr2bfDIl2pSdACu', '', 0, '', 0, '', '', '', '', 'uploads/default.png', NULL, '2025-05-19 23:06:15', '9f010b4dc7cc9774ec06d275248db1a9', 'VROJ2E4ULB', 'B1X69UVJKP', 'complete', 'Active'),
(141, 0, NULL, '', '', '', '', '', 'muhammadbappayo14@gmail.com', NULL, '', '', 0, '', 0, '', '', '', '', 'uploads/default.png', NULL, '2025-05-26 20:38:34', '54101a7d3ee82c2b734549aea7dbcdf5', NULL, 'XL5ZJWK4DO', 'incomplete', 'Active'),
(142, 0, NULL, '', '', '', '', '', 'muhammadbappyo14@gmail.com', NULL, '', '', 0, '', 0, '', '', '', '', 'uploads/default.png', NULL, '2025-05-26 20:40:58', '3c87b28dcc99c9c27ad726c7a51a9772', NULL, 'XL5ZJWK4DO', 'incomplete', 'Active'),
(143, 0, NULL, '', '', '', '', '', 'alhajipeace001@gmail.com', NULL, '', '', 0, '', 0, '', '', '', '', 'uploads/default.png', NULL, '2025-05-26 20:41:57', 'a67c12183d51b01279d6e6284a9601ee', NULL, 'XL5ZJWK4DO', 'incomplete', 'Active'),
(144, 0, '5761409749', 'VTU-Muhammad Bappayo', '9PSB Bank', 'R-OPRCVBEEKV', 'Muhammad', 'Bappayo', 'alhpeace001@gmail.com', '8064509234', '$2y$10$aLDbre5oxj.cL7I3UUfYKe/e9XLx4ziDeikYJlTO.OU6v6rkDUibu', '', 0, '', 0, '', '', '', '', 'uploads/default.png', NULL, '2025-05-26 20:45:38', 'a8a785096dae3b93285c371f69851959', '5HRGYIZFW2', 'XL5ZJWK4DO', 'complete', 'Active');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `account_balance`
--
ALTER TABLE `account_balance`
  ADD PRIMARY KEY (`account_id`);

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
-- Indexes for table `referrals`
--
ALTER TABLE `referrals`
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
  ADD KEY `service_id` (`service_id`);

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
  MODIFY `account_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `notifications`
--
ALTER TABLE `notifications`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=23;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
  MODIFY `referral_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `services`
--
ALTER TABLE `services`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `service_plans`
--
ALTER TABLE `service_plans`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `service_providers`
--
ALTER TABLE `service_providers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=145;

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
  ADD CONSTRAINT `service_providers_ibfk_1` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `transactions`
--
ALTER TABLE `transactions`
  ADD CONSTRAINT `transactions_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_2` FOREIGN KEY (`service_id`) REFERENCES `services` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_3` FOREIGN KEY (`provider_id`) REFERENCES `service_providers` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `transactions_ibfk_4` FOREIGN KEY (`plan_id`) REFERENCES `service_plans` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
