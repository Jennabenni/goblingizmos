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
-- Table structure for table `goblingizmos_users`
--

CREATE TABLE `goblingizmos_users` (
  `user_id` int(11) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access_level` varchar(20) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_pfp` varchar(255) DEFAULT NULL,
  `user_bio` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `goblingizmos_users`
--

INSERT INTO `goblingizmos_users` (`user_id`, `first_name`, `last_name`, `username`, `password`, `access_level`, `user_email`, `user_pfp`, `user_bio`) VALUES
(1, 'Jenna', 'B', 'JennaGoblinGizmos', '24322698b9de7a4ccb3bdaa68555c60e', 'admin', 'emailExample@goblingizmos.com', 'uploads/goblinDeskMe.PNG', 'Hey there! I am the lead engineer Goblin for Goblin Gizmos!!'),
(2, 'Jenna', 'B', 'WeakestGoblinLink', '4a418c42449aec2c9107f130c510ad18', 'user', 'emailExample@goblingizmos.com', NULL, 'Hello my Goblins!'),
(11, 'John', 'John', 'JohnMan', '527bd5b5d689e2c32ae974c6229ff785', 'user', 'john@john6.com', 'uploads/apple8john.jpg', 'This message proves that I am awesome'),
(14, 'Carter', 'G', 'CarterGoblinGizmos', '8863dd046298b1569bd6f9cc9d0f9aaf', 'admin', 'carter@goblins.com', NULL, NULL),
(15, 'Amy', 'N', 'AmyGoblinGizmos', '65d05e1d56d94198ae29d262448b2b5e', 'admin', 'goblins@goblins.com', NULL, NULL),
(16, 'Isaac', 'I', 'IsaacGoblinGizmos', '2ae8045f453c78854122252708f70c97', 'admin', 'goblins@goblins.com', NULL, NULL),
(17, 'Jenny', 'V', 'JennyGoblinGizmos', 'e40d8ffa5bacf655ce39e568faf091e1', 'admin', 'goblins@goblins.com', NULL, NULL),
(18, 'Dan', 'N', 'ProfessorGoblinGizmos', 'ce42cdb588f2b12df3797616142fd319', 'admin', 'goblins@goblins.com', NULL, 'I should give the Goblins an A...  This really is incredible work if I do say so myself'),
(19, 'Cynthia', 'Bob', 'CynthiaBeauty', 'bob', 'user', 'cynthia@cynthia.com', 'uploads/magnets2.jpg', 'I exist to never ruin code with apostrophes again.  I will not ever use apostrophes.  They can be created thru GG, but not thru phpmyadmin.  Keep that in mind kids.'),
(20, 'Talia', 'Lady', 'TaliaLady', 'lady', 'user', 'talia@talia.talia.com', 'uploads/videogames2.jpg', 'I also will not use quotation marks.  The lead developer has reprogrammed me.  There is no war in Ba Sing Se');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `goblingizmos_users`
--
ALTER TABLE `goblingizmos_users`
  ADD PRIMARY KEY (`user_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `goblingizmos_users`
--
ALTER TABLE `goblingizmos_users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
