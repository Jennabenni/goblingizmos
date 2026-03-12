<?php
include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote

/*
$createTable = "CREATE TABLE `goblingizmos_users` (
  `user_id` int(11) NOT NULL PRIMARY KEY,
  `first_name` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `access_level` varchar(20) NOT NULL,
  `user_email` varchar(255) NOT NULL,
  `user_pfp` varchar(255) DEFAULT NULL,
  `user_bio` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci";



$insertData = "INSERT INTO `goblingizmos_users` (`user_id`, `first_name`, `last_name`, `username`, `password`, `access_level`, `user_email`, `user_pfp`, `user_bio`) VALUES
(1, 'Jenna', 'Blubaugh', 'JennaGoblinGizmos', '24322698b9de7a4ccb3bdaa68555c60e', 'admin', 'emailExample@goblingizmos.com', 'uploads/goblinDeskMe.PNG', 'Hey there! I am the lead engineer Goblin for Goblin Gizmos!!'),
(2, 'Jenna', 'Blubaugh', 'WeakestGoblinLink', '4a418c42449aec2c9107f130c510ad18', 'user', 'emailExample@goblingizmos.com', NULL, 'Hello my Goblins!'),
(11, 'John', 'John', 'JohnMan', '527bd5b5d689e2c32ae974c6229ff785', 'user', 'john@john6.com', 'uploads/apple8john.jpg', 'This message proves that I am awesome'),
(13, 'Talia', 'Talia', 'TaliaLovesTrinkets', '52a5c652aab1da1e7efaaffb91f2d908', 'user', 'talia@talia.com', NULL, 'This is such a great website'),
(14, 'Carter', 'G', 'CarterGoblinGizmos', '8863dd046298b1569bd6f9cc9d0f9aaf', 'admin', 'carter@goblins.com', NULL, NULL),
(15, 'Amy', 'N', 'AmyGoblinGizmos', '65d05e1d56d94198ae29d262448b2b5e', 'admin', 'goblins@goblins.com', NULL, NULL),
(16, 'Isaac', 'I', 'IsaacGoblinGizmos', '2ae8045f453c78854122252708f70c97', 'admin', 'goblins@goblins.com', NULL, NULL),
(17, 'Jenny', 'V', 'JennyGoblinGizmos', 'e40d8ffa5bacf655ce39e568faf091e1', 'admin', 'goblins@goblins.com', NULL, NULL),
(18, 'Dan', 'N', 'ProfessorGoblinGizmos', 'ce42cdb588f2b12df3797616142fd319', 'admin', 'goblins@goblins.com', NULL, NULL)";



if ($mysqli->query($createTable)) {
    echo "Table created successfully!";
} else {
    echo "Error creating table: " . $mysqli->error;
}




$mysqli->query($insertData);

*/


$thisIsAnnoying = "ALTER TABLE `goblingizmos_users` CHANGE `user_id` `user_id` INT(11) NOT NULL AUTO_INCREMENT";


$mysqli->query($thisIsAnnoying);

$mysqli->close();



?>