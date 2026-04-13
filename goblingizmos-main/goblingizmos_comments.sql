-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Apr 12, 2026 at 10:23 PM
-- Server version: 10.4.28-MariaDB
-- PHP Version: 8.2.4

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `dbnVtHbQPy8f`
--

-- --------------------------------------------------------

--
-- Table structure for table `goblingizmos_comments`
--

CREATE TABLE `goblingizmos_comments` (
  `user_id` int(255) NOT NULL,
  `comment_compost_id` int(255) NOT NULL,
  `compost_id` int(255) NOT NULL,
  `comment_text` varchar(255) NOT NULL,
  `comment_compost_likes` int(255) DEFAULT NULL,
  `comment_compost_creation_date` date NOT NULL DEFAULT (CURDATE())
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goblingizmos_comments`
--

INSERT INTO `goblingizmos_comments` (`user_id`, `comment_compost_id`, `compost_id`, `comment_text`, `comment_compost_likes`, `comment_compost_creation_date`) VALUES
(1, 1, 7, 'Are you stupid', NULL, '2026-04-12'),
(19, 2, 6, 'I do not understand this language.  Maybe go outside or something', NULL, '2026-04-06'),
(18, 3, 2, 'You know these posts make me want to pass the goblins with flying colors....', NULL, '2026-04-12'),
(15, 4, 4, 'Indeed', NULL, '2026-04-11'),
(20, 5, 5, 'Huh', NULL, '2026-04-12');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `goblingizmos_comments`
--
ALTER TABLE `goblingizmos_comments`
  ADD PRIMARY KEY (`comment_compost_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `goblingizmos_comments_ibfk_1` (`compost_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `goblingizmos_comments`
--
ALTER TABLE `goblingizmos_comments`
  MODIFY `comment_compost_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `goblingizmos_comments`
--
ALTER TABLE `goblingizmos_comments`
  ADD CONSTRAINT `goblingizmos_comments_ibfk_1` FOREIGN KEY (`compost_id`) REFERENCES `goblingizmos_community` (`compost_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
