<?php
// Get credentials from environment variables or use defaults
$host = getenv('DB_HOST') ?: 'mysql';
$user = getenv('DB_USER') ?: 'user_for_gizmos';
$password = getenv('DB_PASSWORD') ?: 'imagoblinforsomegizmos!252';
$database = getenv('DB_NAME') ?: 'goblin_gizmos';

// Create connection
$mysqli = mysqli_connect($host, $user, $password, $database);


if (!$mysqli) {
    die("Connection failed: " . mysqli_connect_error());
}

// Set charset to utf8
mysqli_set_charset($mysqli, "utf8");
?>