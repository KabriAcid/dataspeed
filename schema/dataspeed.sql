-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/

--
-- Host: 127.0.0.1
-- Generation Time: Jun 10, 2025 at 12:53 AM
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
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `account_balance`
--

INSERT INTO `account_balance` (`account_id`, `user_id`, `wallet_balance`, `updated_at`) VALUES
(1, 136, 100.00, '2025-06-09 22:41:31'),
(2, 187, 25000.00, '2025-06-09 22:48:45');

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
(22, 136, 'Referral Reward', 'Congratulations! You have successfully claimed your ₦100.00 referral bonus.', 'referral_bonus', 'fa-referral', 0, '2025-05-28 02:59:25'),
(23, 145, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'fa-home', 0, '2025-05-30 12:04:36'),
(24, 179, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'fa-home', 0, '2025-06-01 08:26:44'),
(25, 187, 'Virtual Account Created', 'Congratulations! Your virtual account has been created successfully.', 'virtual_account', 'fa-home', 0, '2025-06-09 22:45:01');

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
(129, 'kuxadama@gmail.com', '829605', '2025-06-03 22:44:56', '0000-00-00 00:00:00', '2025-06-03 21:34:56');

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
(10, 100.00, 136, 'XL5ZJWK4DO', 'https://dataspeed.com.ng/public/pages/backend/register.php?referral_code=XL5ZJWK4DO', 'claimed', '2025-05-28 04:34:36'),
(11, 100.00, 136, 'XL5ZJWK4DO', 'https://dataspeed.com.ng/public/pages/register.php?referral_code=XL5ZJWK4DO', 'claimed', '2025-06-01 08:49:40');

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
(4, 'Bills', 'bills', '2025-05-30 20:44:35');

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

--
-- Dumping data for table `service_plans`
--

INSERT INTO `service_plans` (`id`, `service_id`, `provider_id`, `api_id`, `volume`, `price`, `validity`, `type`, `is_active`, `created_at`, `updated_at`) VALUES
(2, 1, 1, 1, '4GB', 550.00, '24hrs', 'daily', 1, '2025-05-30 20:43:09', '2025-06-09 00:41:21'),
(3, 1, 1, 1, '1GB', 250.00, '24hrs', 'daily', 1, '2025-05-30 20:43:09', '2025-06-09 00:42:07'),
(4, 1, 1, 1, '5GB', 1550.00, '', 'monthly', 1, '2025-05-30 20:43:09', '2025-06-06 05:55:51');

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
(1, 1, 'MTN', 1, '#F4F4F4', NULL);

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

--
-- Dumping data for table `transactions`
--

INSERT INTO `transactions` (`id`, `user_id`, `wallet_id`, `service_id`, `provider_id`, `plan_id`, `type`, `amount`, `email`, `status`, `created_at`) VALUES
(1, 136, 0, 2, NULL, NULL, 'Self', 200.00, NULL, 'completed', '2025-06-09 22:41:31'),
(2, 187, 0, 2, NULL, NULL, 'Self', 5000.00, NULL, 'completed', '2025-06-09 22:48:45');

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

INSERT INTO `users` (`user_id`, `account_id`, `virtual_account`, `account_name`, `bank_name`, `billstack_ref`, `first_name`, `last_name`, `email`, `phone_number`, `password`, `w_bank_name`, `w_account_number`, `txn_pin`, `address`, `state`, `country`, `city`, `photo`, `updated_at`, `created_at`, `registration_id`, `referral_code`, `referred_by`, `registration_status`, `account_status`, `kyc_value`, `kyc_status`, `kyc_type`) VALUES
(115, 212, '2147483647', 'BillStack/VTU-Katelyn', 'PalmPay', 'R-FPAWMNYEDW', 'Katelyn', 'Guzman', 'zoxalon@gmail.com', '8011737992', '$2y$10$s.QwmCU.4t91qmIomXOJXemvFFObyah/RsIWLQmiUerezM3N6PIG.', '', '', '1111', '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'afdf227ba83dff6e5951c53c73bdf0ec', 'UXIZW9T1GK', NULL, 'complete', 'Active', '', 'unverified', NULL),
(131, 100, '5761210754', 'VTU-Hiroko Mclaughlin', '9PSB Bank', 'R-PRZTYEMAHS', 'Hiroko', 'Mclaughlin', 'xehemexa@gmail.com', '8085311846', '$2y$10$eHvzv7/KbZmv0f0ZQUNEJeptrbE46oNkw.CZlDYOh7Z38bBNkSxYm', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'b1c1be0f5a2f3971b0e14a074ebd70e4', 'H41SILO96F', NULL, 'complete', 'Active', '', 'unverified', NULL),
(132, 0, '5761207770', 'VTU-Orson Head', '9PSB Bank', 'R-VTPXCKSJYV', 'Rebecca', 'Dickerson', 'muhammadmjidder@gmail.com', '8038851880', '$2y$10$HbwMQiD0.N2mdHvSGSvHDOricGNaKwB0S.8UqgIkHII6hHLZu/2D2', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '81c6536766fc6acaea9ba82fd4d548b2', '0KLFPDQ43H', 'H41SILO96F', 'complete', 'Active', '', 'unverified', NULL),
(133, 1, '5761212820', 'VTU-Britanni Downs', '9PSB Bank', 'R-SDYNQCBSZV', 'Musa', 'Jidder', 'musajidder@gmail.com', '8076574147', '$2y$10$HPgBE21jeE5LYkvpTDXu7OZ2e4P.Jqa6LNNYDn/fB2UDnATvKNl4S', '', '', '1090', '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'ebaeaaeac1ae51166c60916c1e85b765', '1Q9764VM5R', NULL, 'complete', 'Active', '', 'unverified', NULL),
(134, 0, NULL, '', '', '', '', '', 'musajidda@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '274d231a31d8ebf026f6dfbeba2010d2', NULL, '1Q9764VM5R', 'incomplete', 'Active', '', 'unverified', NULL),
(135, 0, '5761221301', 'VTU-Colette Chase', '9PSB Bank', 'R-WEHVXNMRDG', 'Colette', 'Chase', 'musa@gmail.com', '8040993201', '$2y$10$hXdg2zGjrosN0/Yy.5qXWOuuA11h2dxxTu47Vg0a7S44oOTZlnSkC', '', '', '$2y$10$Du4Jg.Y8yC5DSExH.DeJP.kO9pz.KyMXYCzkNVnXzYlviDqqcCcji', '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '9e1948165924f8bbef7b11ed0c9cc37a', 'NBDJXG2K10', '1Q9764VM5R', 'complete', 'Active', '', 'unverified', NULL),
(136, 0, '5761257050', 'VTU-Jenna Marshall', '9PSB Bank', 'R-ZUNTBSDTMW', 'Abdullahi', 'Kabri', 'kabriacid01@gmail.com', '7037943396', '$2y$10$O7E68yMPKY80cCx5yivgiunlTZnrHeoU6.XMbZkCQayG0nTX2NHHu', 'Opay', '8898997899', '$2y$10$NKkg/aRtaNJonZHny1V43eRRESCgCi7J/B0lxaW4hLK6f.W3k86IW', '30 White Cowley Freeway', 'FCT', '', 'Municipal Area Counc', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '949394aeb0a04a78486fd806ca7c24f1', 'XL5ZJWK4DO', NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(138, 0, NULL, '', '', '', 'Alec', 'Riggs', 'sesugamys@gmail.com', '8085661678', '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '10375ef973623585f6533ee0aa93f667', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(139, 1, '5761257555', 'VTU-Chastity Obrien', '9PSB Bank', 'R-FRZBBQJNXG', 'Chastity', 'Obrien', 'vunota@gmail.com', '8023983839', '$2y$10$R8v1oCjJaMYq79lcbor5WeaT2MZxUX9gnMncA2zPeoGazeBHhe/d6', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'cf75622cfd811a09a420ba5fa329fec3', 'B1X69UVJKP', NULL, 'complete', 'Active', '', 'unverified', NULL),
(140, 0, '5761257603', 'VTU-Gabriel Odonnell', '9PSB Bank', 'R-TSUTFSVWPK', 'Gabriel', 'Odonnell', 'dyjo@gmail.com', '8012721760', '$2y$10$tzHdRsqtRRlBeIyaH91yWOiX46sGdEcYH9pqqtGr2bfDIl2pSdACu', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '9f010b4dc7cc9774ec06d275248db1a9', 'VROJ2E4ULB', 'B1X69UVJKP', 'complete', 'Active', '', 'unverified', NULL),
(141, 0, NULL, '', '', '', '', '', 'muhammadbappayo14@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '54101a7d3ee82c2b734549aea7dbcdf5', NULL, 'XL5ZJWK4DO', 'incomplete', 'Active', '', 'unverified', NULL),
(142, 0, NULL, '', '', '', '', '', 'muhammadbappyo14@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '3c87b28dcc99c9c27ad726c7a51a9772', NULL, 'XL5ZJWK4DO', 'incomplete', 'Active', '', 'unverified', NULL),
(143, 0, NULL, '', '', '', '', '', 'alhajipeace001@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'a67c12183d51b01279d6e6284a9601ee', NULL, 'XL5ZJWK4DO', 'incomplete', 'Active', '', 'unverified', NULL),
(144, 0, '5761409749', 'VTU-Muhammad Bappayo', '9PSB Bank', 'R-OPRCVBEEKV', 'Muhammad', 'Bappayo', 'alhpeace001@gmail.com', '8064509234', '$2y$10$aLDbre5oxj.cL7I3UUfYKe/e9XLx4ziDeikYJlTO.OU6v6rkDUibu', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'a8a785096dae3b93285c371f69851959', '5HRGYIZFW2', 'XL5ZJWK4DO', 'complete', 'Active', '', 'unverified', NULL),
(145, 0, '5761490639', 'VTU-Abdulsalami Ismaila', '9PSB Bank', 'R-ZBPLNOVZJC', 'Abdulsalami', 'Ismaila', 'abdulsalamiismaila8@gmail.com', '9029202858', '$2y$10$dSQTzRZsSGnqPK.h5TXXh.TH/zcPtX0z2FJdqzrFlV/m8hzuKBY1e', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'af9922d9cfb14a9da86829f8485134f2', 'GA3C2T1FN8', NULL, 'complete', 'Active', '', 'unverified', NULL),
(146, 0, NULL, '', '', '', '', '', 'kabriacid@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '26879b9de8b0adc3f0b90690cabb8887', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(147, 0, NULL, '', '', '', '', '', 'abdu@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'a4cd4f12245580edec9b209652447fd6', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(148, 0, NULL, '', '', '', '', '', 'kabri@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'a6631da1e90e8f9ea76d0f060cbe38a8', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(149, 0, NULL, '', '', '', '', '', 'pihysewu@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '4bef45ecaef96061c53294ddd3d6ab72', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(150, 0, NULL, '', '', '', '', '', 'kafozirego@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'b5c3e1a4a166376aeb6c005868b19922', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(151, 0, NULL, '', '', '', '', '', 'goxyxy@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'cb4e82ad86afef7d2c4092df15c545b3', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(152, 0, NULL, '', '', '', '', '', 'gameditar@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'cb4e82ad86afef7d2c4092df15c545b3', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(153, 0, NULL, '', '', '', '', '', 'popusul@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '9e3d25d81547311c5768028f9dd4016d', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(154, 0, NULL, '', '', '', '', '', 'veki@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '9e3d25d81547311c5768028f9dd4016d', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(155, 0, NULL, '', '', '', '', '', 'byxuto@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '9e3d25d81547311c5768028f9dd4016d', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(156, 0, NULL, '', '', '', '', '', 'late@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '9e3d25d81547311c5768028f9dd4016d', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(157, 0, NULL, '', '', '', '', '', 'rakihokid@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '282e569c94cffb6714cbdbc70e2ebe79', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(158, 0, NULL, '', '', '', '', '', 'wyxuc@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'f4b606bcd76239deea7a8a197ee3748e', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(159, 0, NULL, '', '', '', 'Sopoline', 'Potter', 'dataspeedcontact@gmail.com', '8091968956', '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '48e6d1af49e37ff61e9491d6af865a7f', NULL, 'XL5ZJWK4DO', 'incomplete', 'Active', '', 'unverified', NULL),
(160, 0, NULL, '', '', '', 'Sopoline', 'Potter', 'mibyhatet@gmail.com', '8091968956', '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '48e6d1af49e37ff61e9491d6af865a7f', NULL, 'XL5ZJWK4DO', 'incomplete', 'Active', '', 'unverified', NULL),
(161, 0, NULL, '', '', '', '', '', 'pypy@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '29e38d0ec467387bb25ebae748ea3f7a', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(162, 0, NULL, '', '', '', '', '', 'ligon@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '29e38d0ec467387bb25ebae748ea3f7a', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(163, 0, NULL, '', '', '', '', '', 'nilykux@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '29e38d0ec467387bb25ebae748ea3f7a', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(164, 0, NULL, '', '', '', '', '', 'mazygo@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '8df2722f8df7f36e5a606e61c174f789', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(165, 0, NULL, '', '', '', '', '', 'barat@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '93fd99b0af53a5de83563a9b1e80e24b', NULL, 'UXIZW9T1GK', 'incomplete', 'Active', '', 'unverified', NULL),
(166, 0, NULL, '', '', '', '', '', 'pafitopy@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '93fd99b0af53a5de83563a9b1e80e24b', NULL, 'UXIZW9T1GK', 'incomplete', 'Active', '', 'unverified', NULL),
(167, 0, NULL, '', '', '', '', '', 'sahyzij@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '93fd99b0af53a5de83563a9b1e80e24b', NULL, 'UXIZW9T1GK', 'incomplete', 'Active', '', 'unverified', NULL),
(168, 0, NULL, '', '', '', '', '', 'mute@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '93fd99b0af53a5de83563a9b1e80e24b', NULL, 'UXIZW9T1GK', 'incomplete', 'Active', '', 'unverified', NULL),
(169, 0, NULL, '', '', '', '', '', 'civykybuxo@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '5707f4ed90722e9fc971ade21be08bfb', NULL, 'UXIZW9T1GK', 'incomplete', 'Active', '', 'unverified', NULL),
(170, 0, NULL, '', '', '', '', '', 'wehydo@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '5707f4ed90722e9fc971ade21be08bfb', NULL, 'UXIZW9T1GK', 'incomplete', 'Active', '', 'unverified', NULL),
(171, 0, NULL, '', '', '', '', '', 'niha@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '5707f4ed90722e9fc971ade21be08bfb', NULL, 'UXIZW9T1GK', 'incomplete', 'Active', '', 'unverified', NULL),
(172, 0, NULL, '', '', '', '', '', 'vycaraqe@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '01347250c4a42653a6ca8b9e830aca84', NULL, 'UXIZW9T1GK', 'incomplete', 'Active', '', 'unverified', NULL),
(173, 0, NULL, '', '', '', '', '', 'mafetih@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '01347250c4a42653a6ca8b9e830aca84', NULL, 'UXIZW9T1GK', 'incomplete', 'Active', '', 'unverified', NULL),
(174, 0, NULL, '', '', '', '', '', 'punomazy@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '88bccbe3935cb4b1db8ebab26330b95f', NULL, 'UXIZW9T1GK', 'incomplete', 'Active', '', 'unverified', NULL),
(175, 0, NULL, '', '', '', '', '', 'cyvexudeji@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '88bccbe3935cb4b1db8ebab26330b95f', NULL, 'UXIZW9T1GK', 'incomplete', 'Active', '', 'unverified', NULL),
(176, 0, NULL, '', '', '', '', '', 'saqag@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '93cae5c61170f58d79492d0d6567c053', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(177, 0, NULL, '', '', '', '', '', 'lygyxoco@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '93cae5c61170f58d79492d0d6567c053', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(178, 0, NULL, '', '', '', 'Cassandra', 'Carpenter', 'bokyd@gmail.com', '9064145016', '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '5282a703c87f1366bba90cd142565206', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(179, 0, '5761525724', 'VTU-Stacy Spencer', '9PSB Bank', 'R-JPQMONHLWF', 'Stacy', 'Spencer', 'duvuba@gmail.com', '9005648888', '$2y$10$u7yByZ0ZDGb0F6aDbwfOUOXQcGGbqjmyVVGYpXw9Ryy4GStCHVRQ6', 'Opay', '9876543456', '$2y$10$r/GtFOrjTjGCEp7//xoTbelkErl6FD/eMyv74ob/9nNZYHpDRkHFm', '923 White Old Boulevard', 'Kogi', '', 'Ijumu', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '3668197e736c6474910dcf53083b3f9f', 'OYFGEV9KQM', 'XL5ZJWK4DO', 'complete', 'Banned', '', 'unverified', NULL),
(180, 0, NULL, '', '', '', 'Nola', 'Santiago', 'wezis@gmail.com', '9089867533', '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'ffe88f42a7c941e65b03022fdc55c847', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(181, 0, NULL, '', '', '', '', '', 'ronyzi@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'cccad79389b47b7b96db63f900496e5f', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(182, 0, NULL, '', '', '', '', '', 'caxu@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'f8130e1fdf6bc7eb2b3603b6a4baa37c', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(183, 0, NULL, '', '', '', '', '', 'hojygywa@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '995193bac1af7fef4eb76e4db6002859', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(184, 0, NULL, '', '', '', '', '', 'bomysil@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'c9379dbf37baa33daf5998b3eca61bc8', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(185, 0, NULL, '', '', '', '', '', 'kuxadama@gmail.com', NULL, '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '40980356e546a4efdeb960d962e60e9c', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(186, 0, NULL, '', '', '', 'Uta', 'Franco', 'fyti@gmail.com', '8079176685', '', '', '', NULL, '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', '77ffe8f2817c2d2502f9a572dc51b265', NULL, NULL, 'incomplete', 'Active', '', 'unverified', NULL),
(187, 0, '5761552748', 'VTU-Jamal Jugulde', '9PSB Bank', 'R-SPSNWVUCTU', 'Jamal', 'Jugulde', 'majugulde03@gmail.com', '7012589879', '$2y$10$BJyFCfypbUzymQbf7/Y/D.Rb5eDjtH27WxIIRuImoKML.CmomzhqC', '', '', '$2y$10$QPFHHNxqJk1vF7P31J8lLuZsfk0b8OiDnCNybXl2YKpMe52xkdJdO', '', '', '', '', 'uploads/default.png', NULL, '2025-06-09 22:52:18', 'e6fcfac8a582baa3d5ebe083c1fba0ae', '79SHT013JG', NULL, 'complete', 'Active', '', 'unverified', NULL);

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=132;

--
-- AUTO_INCREMENT for table `referrals`
--
ALTER TABLE `referrals`
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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=188;

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
