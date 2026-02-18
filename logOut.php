<?php

session_start();

if (isset($_SESSION['logged-in'])) {
    unset($_SESSION['logged-in']);
}


if (isset($_SESSION['user_logged_in'])) {
    unset($_SESSION['user_logged_in']);
}
if (isset($_SESSION['user_id'])) {
    unset($_SESSION['user_id']);
}
if (isset($_SESSION['access_level'])) {
    unset($_SESSION['access_level']);
}
header("Location: signIn.php");

?>
<?php
session_unset();
//HOORAY!!


?>