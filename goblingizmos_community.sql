-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: localhost
-- Generation Time: Mar 10, 2026 at 11:35 PM
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
-- Table structure for table `goblingizmos_community`
--

CREATE TABLE `goblingizmos_community` (
  `user_id` int(100) NOT NULL,
  `compost_id` int(100) NOT NULL,
  `compost_img` varchar(255) DEFAULT NULL,
  `compost_category` varchar(255) NOT NULL,
  `compost_description` varchar(255) NOT NULL,
  `compost_sfw_nsfw` varchar(255) DEFAULT NULL,
  `compost_likes` int(255) DEFAULT 0,
  `compost_creation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goblingizmos_community`
--

INSERT INTO `goblingizmos_community` (`user_id`, `compost_id`, `compost_img`, `compost_category`, `compost_description`, `compost_sfw_nsfw`, `compost_likes`, `compost_creation_date`) VALUES
(1, 1, NULL, 'autographs', 'Honestly I could talk about autographs all day.  Go on.  Ask me about an autograph', NULL, 0, '2026-03-10'),
(1, 2, 'uploads/autograph.jpeg', 'books', 'I can read, contrary to popular belief', NULL, 0, '2026-03-10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `goblingizmos_community`
--
ALTER TABLE `goblingizmos_community`
  ADD PRIMARY KEY (`compost_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `goblingizmos_community`
--
ALTER TABLE `goblingizmos_community`
  MODIFY `compost_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `goblingizmos_community`
--
ALTER TABLE `goblingizmos_community`
  ADD CONSTRAINT `goblingizmos_community_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `goblingizmos_users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
