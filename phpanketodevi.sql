-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: May 07, 2023 at 12:45 PM
-- Server version: 8.0.31
-- PHP Version: 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `phpanketodevi`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

DROP TABLE IF EXISTS `admins`;
CREATE TABLE IF NOT EXISTS `admins` (
  `id` int NOT NULL AUTO_INCREMENT,
  `email` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `password` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `email`, `password`) VALUES
(1, 'yasar@gmail.com', '123456');

-- --------------------------------------------------------

--
-- Table structure for table `polls`
--

DROP TABLE IF EXISTS `polls`;
CREATE TABLE IF NOT EXISTS `polls` (
  `id` int NOT NULL AUTO_INCREMENT,
  `title` text NOT NULL,
  `description` text NOT NULL,
  `end_date` datetime DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `polls`
--

INSERT INTO `polls` (`id`, `title`, `description`, `end_date`) VALUES
(1, 'Favori web frameworkünüz nedir?', '', '2023-01-08 23:59:59'),
(2, 'Deneme anketi', 'deneme anketi açıklaması', '0000-00-00 00:00:00'),
(3, 'Deneme anketi 2', 'Deneme anketi 2 Açıklama', '0000-00-00 00:00:00'),
(4, 'Deneme anketi 4', 'Deneme anketi 4 Açıklama', '0000-00-00 00:00:00'),
(5, 'Hangi tarayıcıyı seçersiniz?', 'sevdiğiniz tarayıcıyı seçmeyi unutmayın', '0000-00-00 00:00:00');

-- --------------------------------------------------------

--
-- Table structure for table `poll_answers`
--

DROP TABLE IF EXISTS `poll_answers`;
CREATE TABLE IF NOT EXISTS `poll_answers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `poll_id` int NOT NULL,
  `title` text NOT NULL,
  `votes` int NOT NULL DEFAULT '0',
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=InnoDB AUTO_INCREMENT=81 DEFAULT CHARSET=utf8mb3;

--
-- Dumping data for table `poll_answers`
--

INSERT INTO `poll_answers` (`id`, `poll_id`, `title`, `votes`) VALUES
(5, 2, 'şık 1', 3),
(6, 2, 'şık 2', 1),
(7, 2, 'şık 3', 0),
(8, 2, 'şık 4', 0),
(17, 3, 'deneme 1', 1),
(18, 3, 'deneme 2', 0),
(19, 3, 'deneme 3', 0),
(20, 3, 'deneme 4', 0),
(21, 4, 'Deneme 1', 0),
(22, 4, 'Deneme 2', 0),
(23, 4, 'Deneme 3', 0),
(24, 4, 'Deneme 4', 0),
(49, 5, 'Chrome', 0),
(50, 5, 'Edge', 0),
(51, 5, 'Opera', 0),
(52, 5, 'Firefox', 0),
(77, 1, 'Laravel', 0),
(78, 1, 'ASP.NET', 0),
(79, 1, 'Django', 0),
(80, 1, 'Express', 0);

-- --------------------------------------------------------

--
-- Table structure for table `voters`
--

DROP TABLE IF EXISTS `voters`;
CREATE TABLE IF NOT EXISTS `voters` (
  `id` int NOT NULL AUTO_INCREMENT,
  `ip_adress` varchar(50) COLLATE utf8mb4_turkish_ci NOT NULL,
  `poll_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `poll_id` (`poll_id`)
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_turkish_ci;

--
-- Dumping data for table `voters`
--

INSERT INTO `voters` (`id`, `ip_adress`, `poll_id`) VALUES
(1, '::1', 1),
(2, '::1', 2),
(3, '::1', 1),
(4, '::1', 1),
(5, '::1', 3),
(6, '::1', 2);

--
-- Constraints for dumped tables
--

--
-- Constraints for table `poll_answers`
--
ALTER TABLE `poll_answers`
  ADD CONSTRAINT `poll_answers_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`);

--
-- Constraints for table `voters`
--
ALTER TABLE `voters`
  ADD CONSTRAINT `voters_ibfk_1` FOREIGN KEY (`poll_id`) REFERENCES `polls` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
