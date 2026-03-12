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
-- Table structure for table `goblingizmos_postsbounties`
--

CREATE TABLE `goblingizmos_postsbounties` (
  `post_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `post_or_bounty` varchar(255) NOT NULL,
  `post_category` varchar(255) NOT NULL,
  `post_condition` varchar(255) DEFAULT NULL,
  `post_boxCondition` varchar(255) DEFAULT NULL,
  `post_price` int(100) DEFAULT NULL,
  `post_location` varchar(255) DEFAULT NULL,
  `post_description` varchar(255) NOT NULL,
  `post_img` varchar(255) DEFAULT NULL,
  `post_sfw_nsfw` varchar(255) DEFAULT NULL,
  `post_creation_date` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goblingizmos_postsbounties`
--

INSERT INTO `goblingizmos_postsbounties` (`post_id`, `user_id`, `post_or_bounty`, `post_category`, `post_condition`, `post_boxCondition`, `post_price`, `post_location`, `post_description`, `post_img`, `post_sfw_nsfw`, `post_creation_date`) VALUES
(69, 11, 'post', 'autographs', NULL, NULL, 0, 'USA', 'I\'m super excited for this.  Couldn\'t help but to share', 'uploads/autograph1.png', NULL, '2026-03-10'),
(70, 11, 'bounty', 'autographs', NULL, NULL, 0, '', 'Anyone have something like this?', 'uploads/autogrpahB5.jpg', NULL, '2026-03-10'),
(71, 1, 'post', 'autographs', NULL, NULL, 1000000, '', 'This is my passion', 'uploads/autograph2.jpg', NULL, '2026-03-10'),
(72, 1, 'bounty', 'autographs', NULL, NULL, 0, '', 'Not sure who this is.  Can anyone help?', 'uploads/autographB4.jpg', NULL, '2026-03-10'),
(73, 12, 'post', 'autographs', NULL, NULL, 0, '', 'I\'m shaking with excitement.  Nothing can take this away from me.  NOTHING', 'uploads/autograph3.png', NULL, '2026-03-10'),
(74, 12, 'post', 'books', NULL, NULL, 0, '', 'Love this book.  I feel so empowered', 'uploads/book2.png', NULL, '2026-03-10'),
(75, 12, 'bounty', 'books', NULL, NULL, 0, '', 'Does anyone know the book she\'s reading?? Thx', 'uploads/bookB1.jpg', NULL, '2026-03-10'),
(76, 1, 'post', 'books', NULL, NULL, 0, '', 'It\'s broken but I do love it.  If there\'s something wrong with this book don\'t cancel me I didn\'t actually read it', 'uploads/book1.jpeg', NULL, '2026-03-10'),
(77, 1, 'bounty', 'books', NULL, NULL, 0, 'The crypt', 'This book isn\'t cursed I swear', 'uploads/bookB2.jpg', NULL, '2026-03-10'),
(78, 11, 'post', 'books', NULL, NULL, 3456, 'My house', 'I can\'t read.  ', 'uploads/book3.png', NULL, '2026-03-10'),
(79, 11, 'post', 'caps', NULL, NULL, 0, '', 'These used to be my grandfather\'s', 'uploads/caps2.jpg', NULL, '2026-03-10'),
(80, 11, 'bounty', 'caps', NULL, NULL, 0, '', 'Can anyone do this for me? My daughter wants one', 'uploads/caps2B.jpg', NULL, '2026-03-10'),
(81, 1, 'post', 'caps', NULL, NULL, 0, '', 'Sometimes I see myself in the reflection and wonder where it all went wrong', 'uploads/caps3.jpg', NULL, '2026-03-10');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `goblingizmos_postsbounties`
--
ALTER TABLE `goblingizmos_postsbounties`
  ADD PRIMARY KEY (`post_id`),
  ADD KEY `user_id` (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `goblingizmos_postsbounties`
--
ALTER TABLE `goblingizmos_postsbounties`
  MODIFY `post_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=82;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `goblingizmos_postsbounties`
--
ALTER TABLE `goblingizmos_postsbounties`
  ADD CONSTRAINT `goblingizmos_postsbounties_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `goblingizmos_users` (`user_id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
