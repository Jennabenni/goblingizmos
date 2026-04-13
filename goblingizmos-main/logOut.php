<?php

session_start();

/* Remove all session variables */
$_SESSION = [];

/* Destroy the session completely */
session_destroy();

/* Redirect user to sign-in page */
header("Location: signIn.php");
exit();

?>