-- phpMyAdmin SQL Dump
-- version 5.0.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Apr 30, 2020 at 08:35 AM
-- Server version: 10.4.11-MariaDB
-- PHP Version: 7.4.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `tasklist-api`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_log`
--

CREATE TABLE `activity_log` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `old_rp` int(11) NOT NULL,
  `new_rp` int(11) NOT NULL,
  `notes` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `capabilities`
--

CREATE TABLE `capabilities` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `exchange_offers`
--

CREATE TABLE `exchange_offers` (
  `id` int(11) NOT NULL,
  `from_user_id` int(11) NOT NULL,
  `to_user_id` int(11) NOT NULL,
  `respect_points` int(11) NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `migration_versions`
--

CREATE TABLE `migration_versions` (
  `version` varchar(14) COLLATE utf8mb4_unicode_ci NOT NULL,
  `executed_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `migration_versions`
--

INSERT INTO `migration_versions` (`version`, `executed_at`) VALUES
('20200413042759', '2020-04-19 06:40:37');

-- --------------------------------------------------------

--
-- Table structure for table `needs`
--

CREATE TABLE `needs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `task`
--

CREATE TABLE `task` (
  `id` int(11) NOT NULL,
  `accepted_by_id` int(11) DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `title` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `due_date` date NOT NULL,
  `respect_points` int(11) NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `task`
--

INSERT INTO `task` (`id`, `accepted_by_id`, `user_id`, `title`, `description`, `due_date`, `respect_points`, `status`, `deleted_at`) VALUES
(1, NULL, 2, 'Task Title 2', 'Task Description 2', '2020-04-19', 96, 'Added', NULL),
(2, NULL, 3, 'Task Title 3', 'Task Description 3', '2020-04-19', 123, 'Added', NULL),
(3, NULL, 4, 'Task Title 4', 'Task Description 4', '2020-04-19', 184, 'Added', NULL),
(4, NULL, 5, 'Task Title 5', 'Task Description 5', '2020-04-19', 215, 'Added', NULL),
(5, NULL, 6, 'Task Title 6', 'Task Description 6', '2020-04-19', 300, 'Added', NULL),
(6, NULL, 7, 'Task Title 7', 'Task Description 7', '2020-04-19', 91, 'Added', NULL),
(7, NULL, 8, 'Task Title 8', 'Task Description 8', '2020-04-19', 320, 'Added', NULL),
(8, NULL, 9, 'Task Title 9', 'Task Description 9', '2020-04-19', 117, 'Added', NULL),
(9, NULL, 10, 'Task Title 10', 'Task Description 10', '2020-04-19', 430, 'Added', NULL),
(10, NULL, 11, 'Task Title 11', 'Task Description 11', '2020-04-19', 462, 'Added', NULL),
(11, NULL, 12, 'Task Title 12', 'Task Description 12', '2020-04-19', 120, 'Added', NULL),
(12, NULL, 13, 'Task Title 13', 'Task Description 13', '2020-04-19', 390, 'Added', NULL),
(13, NULL, 14, 'Task Title 14', 'Task Description 14', '2020-04-19', 518, 'Added', NULL),
(14, NULL, 15, 'Task Title 15', 'Task Description 15', '2020-04-19', 345, 'Added', NULL),
(15, NULL, 16, 'Task Title 16', 'Task Description 16', '2020-04-19', 528, 'Added', NULL),
(16, NULL, 17, 'Task Title 17', 'Task Description 17', '2020-04-19', 323, 'Added', NULL),
(17, NULL, 18, 'Task Title 18', 'Task Description 18', '2020-04-19', 450, 'Added', NULL),
(18, NULL, 19, 'Task Title 19', 'Task Description 19', '2020-04-19', 475, 'Added', NULL),
(19, NULL, 20, 'Task Title 20', 'Task Description 20', '2020-04-19', 840, 'Added', NULL),
(20, NULL, 21, 'Task Title 21', 'Task Description 21', '2020-04-19', 693, 'Added', NULL),
(21, NULL, 22, 'Task Title 22', 'Task Description 22', '2020-04-19', 462, 'Added', NULL),
(22, NULL, 23, 'Task Title 23', 'Task Description 23', '2020-04-19', 322, 'Added', NULL),
(23, NULL, 24, 'Task Title 24', 'Task Description 24', '2020-04-19', 648, 'Added', NULL),
(24, NULL, 25, 'Task Title 25', 'Task Description 25', '2020-04-19', 325, 'Added', NULL),
(25, NULL, 26, 'Task Title 26', 'Task Description 26', '2020-04-19', 1274, 'Added', NULL),
(26, NULL, 27, 'Task Title 27', 'Task Description 27', '2020-04-19', 621, 'Added', NULL),
(27, NULL, 28, 'Task Title 28', 'Task Description 28', '2020-04-19', 420, 'Added', NULL),
(28, NULL, 29, 'Task Title 29', 'Task Description 29', '2020-04-19', 696, 'Added', NULL),
(29, NULL, 30, 'Task Title 30', 'Task Description 30', '2020-04-19', 600, 'Added', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `respect_point` int(11) NOT NULL,
  `api_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `deleted_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `email`, `roles`, `password`, `name`, `respect_point`, `api_token`, `deleted_at`) VALUES
(1, 'admin@gmail.com', '[\"ROLE_ADMIN\"]', '$2y$13$kUZWrrRaeh9PiAtlCekcT.PyzhlTKYWyc2XPKwpO/xPew7hkMjnG.', 'John Doe', 0, NULL, NULL),
(2, 'user2@gmail.com', '[\"ROLE_USER\"]', '$2y$13$.ojIzdpUCnUSSKfFbijQtupDBkEls3zAECMDo8Du6cAGbklBrCBmO', 'User Name 2', 0, NULL, NULL),
(3, 'user3@gmail.com', '[\"ROLE_USER\"]', '$2y$13$qciZQlo102cwK2pQzz8wS.UuymAkGR9whhIKeS2wuhjslvFKuRoti', 'User Name 3', 0, NULL, NULL),
(4, 'user4@gmail.com', '[\"ROLE_USER\"]', '$2y$13$huhl7uFTfqYUZ/yH7g3ebeH4RfqnC02x.5/QaiWAvI/rivLadTnne', 'User Name 4', 0, NULL, NULL),
(5, 'user5@gmail.com', '[\"ROLE_USER\"]', '$2y$13$JOuV06fKKNf/liSIjKIUKOtU52Lzhs59MtLrfcvGB1E.XEi8OSvGy', 'User Name 5', 0, NULL, NULL),
(6, 'user6@gmail.com', '[\"ROLE_USER\"]', '$2y$13$aGluathtckxg3CE9mwBxeOMU9/AyE.nOPBZbJ/58xMNP1yDfb.hEC', 'User Name 6', 0, NULL, NULL),
(7, 'user7@gmail.com', '[\"ROLE_USER\"]', '$2y$13$lkg1J5l6QoTBUYHMO2uvVeTplc83m1P8ewoqWii61kF6.i.Wuck9y', 'User Name 7', 0, NULL, NULL),
(8, 'user8@gmail.com', '[\"ROLE_USER\"]', '$2y$13$W21XWU1z09nz/JJpx382jOzLknliMp1Jpdhse2axxymPPJWVAK9SW', 'User Name 8', 0, NULL, NULL),
(9, 'user9@gmail.com', '[\"ROLE_USER\"]', '$2y$13$kXSpZ59gwRUK9OIkOZQgceGl/72XJmqPfEgVPoe5gErzwHD.TKXxm', 'User Name 9', 0, NULL, NULL),
(10, 'user10@gmail.com', '[\"ROLE_USER\"]', '$2y$13$pE38QJmD476TPd8k5uF4SuuXO2ALAVjXiM82jjYx3OnaC5i2Sa/FC', 'User Name 10', 0, NULL, NULL),
(11, 'user11@gmail.com', '[\"ROLE_USER\"]', '$2y$13$ekMp5LEeazzbTGoxOeKQqehbNQ6kmU5k1ePCVIyDYjULLln0KZfgC', 'User Name 11', 0, NULL, NULL),
(12, 'user12@gmail.com', '[\"ROLE_USER\"]', '$2y$13$tEz2/gajvd/1Rg9XvrRn0.XbU5QAeTjwAoDmZ2AkPCbe1E7ScpGcC', 'User Name 12', 0, NULL, NULL),
(13, 'user13@gmail.com', '[\"ROLE_USER\"]', '$2y$13$BRuIUOcurLKaoJKzen/5/e3HqD8QCkJmZa3jNrOhvMXJIg1vAZmYq', 'User Name 13', 0, NULL, NULL),
(14, 'user14@gmail.com', '[\"ROLE_USER\"]', '$2y$13$RPHHcS6bj6pNzvlGObuJEeX9MVro5Xu8kBXQ14PRB1DC52U8VaZKm', 'User Name 14', 0, NULL, NULL),
(15, 'user15@gmail.com', '[\"ROLE_USER\"]', '$2y$13$W2ZoxOBl98rVC39V4CeGle8p6Y0S3Zt2ei1fpjEQrlEvwGcIfCVgS', 'User Name 15', 0, NULL, NULL),
(16, 'user16@gmail.com', '[\"ROLE_USER\"]', '$2y$13$lXteTfuAhqfJn5/HbzEtEO3VOR4LzSSz1R6b1c9aRZEYHkUsds.xy', 'User Name 16', 0, NULL, NULL),
(17, 'user17@gmail.com', '[\"ROLE_USER\"]', '$2y$13$qN3cmNtXzz0ikFGG/S9a.eUJs8.axtCLlU/Nmr62N.ncCLRbvQ4Q.', 'User Name 17', 0, NULL, NULL),
(18, 'user18@gmail.com', '[\"ROLE_USER\"]', '$2y$13$qqkOTNMvAnjXzuwIWOykpuRtuN1lMQenuiv9jf5KZVYWOHSupkFxO', 'User Name 18', 0, NULL, NULL),
(19, 'user19@gmail.com', '[\"ROLE_USER\"]', '$2y$13$4Y5aD8bQMlAZRW4fQ8cyx.R/xGTpxVgeJHykzB.HmmlLRKeqakbZm', 'User Name 19', 0, NULL, NULL),
(20, 'user20@gmail.com', '[\"ROLE_USER\"]', '$2y$13$xi7Ik9nr1o2yhr5t6QGZFukNAgCBjmtjDwNxJ..7MVo2of0nxF.qu', 'User Name 20', 0, NULL, NULL),
(21, 'user21@gmail.com', '[\"ROLE_USER\"]', '$2y$13$hbdLuDnT9hKyCyNvS7ttfe96K3BiV1Y/NYuIftWJZB3iBGemZWAoa', 'User Name 21', 0, NULL, NULL),
(22, 'user22@gmail.com', '[\"ROLE_USER\"]', '$2y$13$MX7EX1pxpGznHceML7fu5.QqycwAG6Z.FxJupJOhhr6f57Afm2iB6', 'User Name 22', 0, NULL, NULL),
(23, 'user23@gmail.com', '[\"ROLE_USER\"]', '$2y$13$C2L9VoEzjBMhoVKss5ZZaOmqgQGR7xn7R70ZeC5VMizeACfcUkLqW', 'User Name 23', 0, NULL, NULL),
(24, 'user24@gmail.com', '[\"ROLE_USER\"]', '$2y$13$hOHmxP7McXeIki5chujsa.LjumjQ20CUbilFJbDxHo30wjZp5YH2y', 'User Name 24', 0, NULL, NULL),
(25, 'user25@gmail.com', '[\"ROLE_USER\"]', '$2y$13$oABD5XF75yDCaQbz0RZdAOUpSPITGUCfM7bjND.DOGEMuUa1kJsCC', 'User Name 25', 0, NULL, NULL),
(26, 'user26@gmail.com', '[\"ROLE_USER\"]', '$2y$13$ebj3jv/xAsuFUOfYE6HcDesd5DXQStc.I2kq7KZDThUmdAhzmI90i', 'User Name 26', 0, NULL, NULL),
(27, 'user27@gmail.com', '[\"ROLE_USER\"]', '$2y$13$RsJudSxsUFJa2QYSwdIWYuuuWwNVBUMd0U7yVhAUztzNCsSkN3GYS', 'User Name 27', 0, NULL, NULL),
(28, 'user28@gmail.com', '[\"ROLE_USER\"]', '$2y$13$37GX/0ytf7ZQ3ksK5.5GNe9Pyd8LwvaPxoJxyeuLJSb/VGhtXf44O', 'User Name 28', 0, NULL, NULL),
(29, 'user29@gmail.com', '[\"ROLE_USER\"]', '$2y$13$DvF9NirDo9w7297VqG7T.OMD4AVNHq3phCCpVWUg5V12AzWDlv1rS', 'User Name 29', 0, NULL, NULL),
(30, 'user30@gmail.com', '[\"ROLE_USER\"]', '$2y$13$j5.8leKDMq3N.W2ITVUXwuFFsisuZ0LpDIHKt8xk9qD5JEV2q1NAq', 'User Name 30', 0, NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_FD06F647A76ED395` (`user_id`);

--
-- Indexes for table `capabilities`
--
ALTER TABLE `capabilities`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1D3B2DFDA76ED395` (`user_id`);

--
-- Indexes for table `exchange_offers`
--
ALTER TABLE `exchange_offers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_A25FFAC92130303A` (`from_user_id`),
  ADD KEY `IDX_A25FFAC929F6EE60` (`to_user_id`);

--
-- Indexes for table `migration_versions`
--
ALTER TABLE `migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indexes for table `needs`
--
ALTER TABLE `needs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_6A59BEEEA76ED395` (`user_id`);

--
-- Indexes for table `task`
--
ALTER TABLE `task`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_527EDB2520F699D9` (`accepted_by_id`),
  ADD KEY `IDX_527EDB25A76ED395` (`user_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_log`
--
ALTER TABLE `activity_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `capabilities`
--
ALTER TABLE `capabilities`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `exchange_offers`
--
ALTER TABLE `exchange_offers`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `needs`
--
ALTER TABLE `needs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `task`
--
ALTER TABLE `task`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=30;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=46;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `activity_log`
--
ALTER TABLE `activity_log`
  ADD CONSTRAINT `FK_FD06F647A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `capabilities`
--
ALTER TABLE `capabilities`
  ADD CONSTRAINT `FK_1D3B2DFDA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `exchange_offers`
--
ALTER TABLE `exchange_offers`
  ADD CONSTRAINT `FK_A25FFAC92130303A` FOREIGN KEY (`from_user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_A25FFAC929F6EE60` FOREIGN KEY (`to_user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `needs`
--
ALTER TABLE `needs`
  ADD CONSTRAINT `FK_6A59BEEEA76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints for table `task`
--
ALTER TABLE `task`
  ADD CONSTRAINT `FK_527EDB2520F699D9` FOREIGN KEY (`accepted_by_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_527EDB25A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
