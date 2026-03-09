<?php
include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote

$createTable = "CREATE TABLE `goblingizmos_users` (
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
";


$insertData = "INSERT INTO `goblingizmos_users` (`user_id`, `first_name`, `last_name`, `username`, `password`, `access_level`, `user_email`, `user_pfp`, `user_bio`) VALUES
(1, 'Jenna', 'Blubaugh', 'JennaGoblinGizmos', '24322698b9de7a4ccb3bdaa68555c60e', 'admin', 'emailExample@goblingizmos.com', 'uploads/goblinDeskMe.PNG', 'Hey there! I\'m the lead engineer Goblin for Goblin Gizmos!'),
(2, 'Jenna', 'Blubaugh', 'WeakestGoblinLink', '4a418c42449aec2c9107f130c510ad18', 'user', 'emailExample@goblingizmos.com', NULL, 'Hello my Goblins'),
(11, 'John', 'John', 'JohnMan', '527bd5b5d689e2c32ae974c6229ff785', 'user', 'john@john.com', 'uploads/apple8john.jpg', 'I do love me an apple');
";


if ($mysqli->query($createTable)) {
    echo "Table created successfully!";
} else {
    echo "Error creating table: " . $mysqli->error;
}



$mysqli->query($insertData);

$mysqli->close();
?>

?>