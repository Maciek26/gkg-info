-- phpMyAdmin SQL Dump
-- version 5.1.3
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 18, 2024 at 10:24 PM
-- Server version: 10.4.24-MariaDB
-- PHP Version: 8.3.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `gkg_info`
--

-- --------------------------------------------------------

--
-- Table structure for table `kommentare`
--

CREATE TABLE `kommentare` (
  `id` int(10) UNSIGNED NOT NULL,
  `name_person` text NOT NULL,
  `id_thema` int(11) NOT NULL,
  `text` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `rated` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `kommentare`
--

INSERT INTO `kommentare` (`id`, `name_person`, `id_thema`, `text`, `rated`, `created_at`, `deleted_at`) VALUES
(104, 'zichks', 1, 'Eigentlich ganz gut nh', 5, '2024-03-18 20:31:13', NULL),
(105, 'zichks', 3, 'Noch nie so etwas gutes gesehen, wow!!!', 5, '2024-03-18 21:42:16', NULL),
(106, 'zichks', 3, 'wow wow wow einfach gut hey', 5, '2024-03-18 21:44:04', NULL),
(107, 'zichks', 4, 'schon eigentlich gut ', 5, '2024-03-18 21:44:28', NULL),
(108, 'zichks', 2, 'Lieber nicht lieber nicht', 1, '2024-03-18 21:44:42', NULL),
(109, 'zichks', 2, '\' AND 0=\'1', 3, '2024-03-18 22:01:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `themen`
--

CREATE TABLE `themen` (
  `id` int(11) NOT NULL,
  `name` text NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `active` tinyint(1) NOT NULL DEFAULT 0,
  `rating` int(11) NOT NULL DEFAULT 0,
  `NumRatings` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `themen`
--

INSERT INTO `themen` (`id`, `name`, `created_at`, `active`, `rating`, `NumRatings`) VALUES
(1, 'Schulhausfest', '2024-03-17', 1, 4, 4),
(2, 'Ai an der Schule', '2024-03-17', 1, 3, 5),
(3, 'Diese Webseite', '0000-00-00', 8, 5, 2),
(4, 'Mensa Essen', '0000-00-00', 6, 4, 2),
(5, 'test2', '0000-00-00', 0, 0, 0),
(6, 'test2', '0000-00-00', 0, 0, 0),
(7, 'test2', '0000-00-00', 0, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(10) NOT NULL,
  `email` varchar(255) NOT NULL,
  `passwort` varchar(255) NOT NULL,
  `vorname` varchar(255) NOT NULL DEFAULT '',
  `nachname` varchar(255) NOT NULL DEFAULT '',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `passwortcode` varchar(255) DEFAULT NULL,
  `passwortcode_time` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `email`, `passwort`, `vorname`, `nachname`, `created_at`, `updated_at`, `passwortcode`, `passwortcode_time`) VALUES
(1, 'awdawd@gmail.com', '$2y$10$JCIRaM3Plyg0RzFpbKv6oO..NDV6Var/M6ue/TV4q5k0xBoj3nXSi', 'mace', 'k', '2024-03-17 20:01:03', '2024-03-17 20:01:24', NULL, NULL),
(2, 'dawd@gmail.com', '$2y$10$yTbm1ubuo5CllaqbmQ8xtOpQc6rGCbIHCDZLwl1.fB2XwGmJGmVCW', 'test', 'test', '2024-03-17 20:12:59', '2024-03-17 22:07:44', NULL, NULL),
(6, 'uihuiih@gmail.com', '$2y$10$4lj6Obd3lQnidWsamGmaWeSVDAUeRlbZNXV7wzolfhBV8PUtHxYEe', 'zichks', 'oj', '2024-03-17 22:33:04', '2024-03-17 22:33:04', NULL, NULL),
(7, 'sdasd@gmail.com', '$2y$10$yyPQIKoOGrU5mRa5mNj8Yu9zkYhrfWmgDsZd0qsBuKLzUIFrAiOMi', 'test', 'test', '2024-03-18 22:02:25', '2024-03-18 22:10:38', NULL, NULL),
(8, 'mace@gmail.com', '$2y$10$co2HwzaOTaRHi9sTsNDDQOFSFfW8o.GZjAdm3frvUYeeXYzNfMKQ6', 'Mace', 'Mace', '2024-03-18 22:10:13', '2024-03-18 22:10:13', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `kommentare`
--
ALTER TABLE `kommentare`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `themen`
--
ALTER TABLE `themen`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `kommentare`
--
ALTER TABLE `kommentare`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=111;

--
-- AUTO_INCREMENT for table `themen`
--
ALTER TABLE `themen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
