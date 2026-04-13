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
(76, 1, 'post', 'books', NULL, NULL, 0, '', 'It\'s broken but I do love it.  If there\'s something wrong with this book don\'t cancel me I didn\'t actually read it', 'uploads/book1.jpeg', NULL, '2026-03-10'),
(77, 1, 'bounty', 'books', NULL, NULL, 0, 'The crypt', 'This book isn\'t cursed I swear', 'uploads/bookB2.jpg', NULL, '2026-03-10'),
(78, 11, 'post', 'books', NULL, NULL, 3456, 'My house', 'I can\'t read.  ', 'uploads/book3.png', NULL, '2026-03-10'),
(79, 11, 'post', 'caps', NULL, NULL, 0, '', 'These used to be my grandfather\'s', 'uploads/caps2.jpg', NULL, '2026-03-10'),
(80, 11, 'bounty', 'caps', NULL, NULL, 0, '', 'Can anyone do this for me? My daughter wants one', 'uploads/caps2B.jpg', NULL, '2026-03-10'),
(81, 1, 'post', 'caps', NULL, NULL, 0, '', 'Sometimes I see myself in the reflection and wonder where it all went wrong', 'uploads/caps3.jpg', NULL, '2026-03-10'),
(82, 18, 'bounty', 'cans', 'new', NULL, 10000000, NULL, 'I am so fascinated and need to sell this can.  Offer is right there folks', 'uploads/cans3.jpg', NULL, '2026-02-17'),
(83, 17, 'post', 'charms', NULL, NULL, NULL, '', 'Got this for my lanyard!', 'uploads/charm1.JPEG', NULL, '2026-03-02'),
(84, 16, 'post', 'charms', NULL, NULL, NULL, NULL, 'Check this out', 'uploads/charms2.jpg', NULL, '2026-03-09'),
(85, 2, 'bounty', 'charms', NULL, NULL, NULL, NULL, 'looking for this', 'uploads/charms3.jpg', NULL, '2026-03-07'),
(86, 1, 'post', 'coins', NULL, NULL, NULL, NULL, 'Honestly it feels like everyone on this website was curated by the Goblin Gizmos engineering lead....', 'uploads/coin1.jpg', NULL, '2026-03-09'),
(87, 11, 'post', 'coins', NULL, NULL, NULL, NULL, 'I think it is funny how one can be at a loss for words when it comes to numerous categories', 'uploads/coin2.jpg', NULL, '2026-03-12'),
(88, 1, 'bounty', 'coins', NULL, NULL, NULL, NULL, 'Need this NOW', NULL, NULL, '2026-03-08'),
(89, 15, 'post', 'figures', NULL, NULL, NULL, NULL, 'Collected this recently', 'uploads/figures1.jpeg', NULL, '2026-03-09'),
(90, 1, 'post', 'figures', NULL, NULL, NULL, NULL, 'How many times can I type generic text before it becomes obvious I am not looking at the pictures', NULL, NULL, '2026-03-03'),
(91, 1, 'bounty', 'figures', NULL, NULL, 35, NULL, 'Selling this', 'uploads/figures3.jpg', 'sfw', '2026-03-08'),
(92, 11, 'post', 'jewelry', NULL, NULL, NULL, NULL, 'this belonged to my grandmother', 'uploads/jewelry1.jpg', NULL, '2026-03-08'),
(93, 1, 'post', 'jewelry', NULL, NULL, NULL, NULL, 'If you are seeing this, silver or gold jewelry? Or are you fancy with a rose gold', 'uploads/jewelry2.jpg', NULL, '2026-03-08'),
(94, 11, 'bounty', 'jewelry', NULL, NULL, NULL, NULL, 'I NEED THIS NOW', 'uploads/jewelry3.jpg', NULL, '2026-03-09'),
(95, 1, 'post', 'magnets', NULL, NULL, NULL, NULL, 'hey guys do we think this will stick to metal', 'uploads/magnet1.jpg', NULL, '2026-03-09'),
(96, 20, 'post', 'minerals', NULL, NULL, NULL, NULL, 'I think this rock is telling me it will be a good day.', 'uploads/minerals1.jpg', NULL, '2026-04-08'),
(97, 19, 'bounty', 'minerals', NULL, NULL, NULL, NULL, 'Hate the color of this rock.  Almost as much as I hate quotation marks.  Me and phpmyadmin have that in common', 'uploads/minerals2.jpg', NULL, '2026-04-11'),
(98, 11, 'post', 'minerals', NULL, NULL, NULL, NULL, 'Finally filled all the spots.  My partner will love this', 'uploads/minerals3.jpg', NULL, '2026-04-12'),
(99, 19, 'post', 'perfume', NULL, NULL, NULL, NULL, 'If anyone can guess what this smells like I will go out with them.  ', 'uploads/perfume1.jpg', NULL, '2026-04-06'),
(100, 18, 'post', 'plates', NULL, NULL, NULL, NULL, 'great for eating on', 'uploads/plates3.jpg', NULL, '2026-04-10'),
(101, 11, 'bounty', 'cards', NULL, NULL, NULL, NULL, 'I am collecting aces with different patterns.  Help me', 'uploads/playingcard2.jpg', NULL, '2026-04-10'),
(102, 20, 'post', 'plushies', NULL, NULL, NULL, NULL, 'I will always buy a plush anytime I see it', 'uploads/plushies3.jpg', NULL, '2026-04-04'),
(103, 14, 'post', 'prints', NULL, NULL, NULL, NULL, 'Just one of those days.  No I am not vagueposting', 'uploads/prints2.jpg', NULL, '2026-04-09');

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
  MODIFY `post_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

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
