-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 24, 2025 at 05:17 PM
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
-- Database: `dataspeed`
--

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
(19, 'jmusab78@gmail.com', 'b325e466d1c8e81d1b2a4736a4fba4cf', '2025-02-24 00:26:59', '2025-02-23 23:16:59'),
(20, 'jmusab78@gmail.com', 'aa4db2fb5d03e0e0a354854b514e30f4', '2025-02-24 00:27:05', '2025-02-23 23:17:05');

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
(95, 'dyxewoxy@mailinator.com', '379164', '2025-02-23 06:19:09', '2025-02-23 05:09:09'),
(97, 'ricafy@mailinadtor.com', '310554', '2025-02-23 07:00:58', '2025-02-23 05:50:58'),
(98, 'bifagidofy@mailinfator.com', '230213', '2025-02-23 07:05:41', '2025-02-23 05:55:41'),
(99, 'voqyle@mafilinator.com', '192065', '2025-02-23 07:05:53', '2025-02-23 05:55:53'),
(100, 'rivejiso@mailinatofr.com', '240092', '2025-02-23 07:06:41', '2025-02-23 05:56:41'),
(104, 'jibrilmusab78@gmail.com', '614580', '2025-02-23 22:58:41', '2025-02-23 21:48:41'),
(107, 'deviqehis@mailiator.com', '851427', '2025-02-24 16:19:43', '2025-02-24 15:09:43'),
(108, 'begow@mailinatr.com', '111603', '2025-02-24 16:22:24', '2025-02-24 15:12:24'),
(109, 'xateba@mailintor.com', '327997', '2025-02-24 16:23:11', '2025-02-24 15:13:11');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(20) NOT NULL,
  `last_name` varchar(20) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) NOT NULL,
  `password` varchar(255) NOT NULL,
  `pin` int not null,
  `city` varchar(20) NOT NULL,
  `photo` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `registration_id` varchar(50) NOT NULL,
  `registration_status` enum('incomplete','complete') DEFAULT 'incomplete'
) 

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `first_name`, `last_name`, `email`, `phone_number`, `password`, `city`, `photo`, `created_at`, `registration_id`, `registration_status`) VALUES
(60, 'Jamalia', 'Bennett', 'kabriacid01@gmail.com', '8025244319', '$2y$10$3bqE2hmaeFz2JnSnRvSvZ.Xm9vshpozKB4163y/YuFIjAVSbqZ0uO', '', '', '2025-02-24 16:02:40', '67baa9062f1bf', 'complete'),
(61, 'Mason', 'Gibson', 'jyhyroloji@mailinator.com', '8069425799', '$2y$10$Rwf6Lfuh.MCR8r2Y3AZjlOzrqc204FEXiKvuclG26jzuvaURtj1u6', '', '', '2025-02-23 05:06:45', '67baacaf4f1fc', 'complete'),
(64, 'Jolene', 'Berg', 'cufavuvyp@gmail.com', '8099409966', '$2y$10$3Rc/zjEeTL5vZcmSwhRoAu2ySBNtMWQikuCD/Tiq5cAKCyJ3UmDUq', '', '', '2025-02-23 07:14:33', '67baaf5d5c738', 'complete'),
(69, 'Caryn', 'Leach', 'adammuhammad@amka.com.ng', '8064124871', '$2y$10$HmZ3NjVQhjEzYpaZHnjBnOn9Os2MasROXzNwBVUdaWBwaY4S8xipO', '', '', '2025-02-23 07:15:46', '67bacaeb90926', 'complete'),
(70, 'Yoko', 'Cotton', 'xefafi@gmail.com', '8052501951', '$2y$10$4KJlXEC466Z9ZOJqlam37O1pLrkNk4osWc3ReZPKkOodzfw8YUx86', '', '', '2025-02-23 07:56:11', '67bad47f822cc', 'complete'),
(71, 'Abdullahi', 'Kabri', 'abdullahikabri@gmail.com', '7037943396', '$2y$10$TBxtlf07UC7PRAtgRnoedeq17O19kIGf/CzMzyg8Nh6r1G6acywry', '', '', '2025-02-24 16:11:03', '67bb97455a794', 'complete'),
(72, '', '', 'jibrilmusab78@gmail.com', '', '', '', '', '2025-02-23 21:48:41', '67bb97b97d4df', 'incomplete'),
(73, 'Jibril', 'Musab', 'jmusab78@gmail.com', '8140360277', '$2y$10$2qivbDi4F9z/.BtG.SbGwOmCr0zqcL9NJMElApt7O397BZb7q.79i', '', '', '2025-02-23 23:16:50', '67bb97ee58003', 'complete'),
(74, 'Conan', 'Schwartz', 'ishaqahmadrufai@gmail.com', '8006476643', '$2y$10$.rLRAB.IDdD5VCTh5JHxG.NdPqzeAqCg7Uf23.mn6VJFBa4tntIz.', '', '', '2025-02-24 15:04:00', '67bc89d937ce5', 'complete'),
(75, '', '', 'deviqehis@mailiator.com', '', '', '', '', '2025-02-24 15:09:43', '67bc8bb700557', 'incomplete'),
(76, '', '', 'begow@mailinatr.com', '', '', '', '', '2025-02-24 15:12:24', '67bc8c58c62f3', 'incomplete'),
(77, '', '', 'xateba@mailintor.com', '', '', '', '', '2025-02-24 15:13:11', '67bc8c8741eb2', 'incomplete'),
(78, 'Zoe', 'Talley', 'lepecuvire@yahoo.com', '8001121899', '$2y$10$9Fsq6kz0dKfOEV4jarnf4OJQdAFnpQWMs/S4MFAZHsZo66b8jNNBy', '', '', '2025-02-24 16:12:32', '67bc9a28c5726', 'complete');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `forgot_password`
--
ALTER TABLE `forgot_password`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `otp_codes`
--
ALTER TABLE `otp_codes`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`),
  ADD KEY `email_2` (`email`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`),
  ADD UNIQUE KEY `registration_id` (`registration_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `forgot_password`
--
ALTER TABLE `forgot_password`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT for table `otp_codes`
--
ALTER TABLE `otp_codes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=79;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
