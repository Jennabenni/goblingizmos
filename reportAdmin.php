<?php
//ADMINS ONLY!!!



session_start();
//make sure to have the closer at the end of html


/*DO NOT DELETE THESE */

//include("../db-connect.php");
include("/Applications/XAMPP/htdocs/dig3134c/db-connect.php");
//WAIT THIS ONE WORKED??
//local

//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote





?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Reports</title>
</head>

<body>

    <!--Admins only-->

    <?php
    if (($_SESSION['access_level'] == "user") || !isset($_SESSION['logged_in'])) {
        print "<p>This page does not exist!</p>";
        print "<a href='index.php'>Return to Home</a>";
    }

    if ($_SESSION['access_level'] == "admin") {
        print "<p>Hey sexy</p>";
    }

    /*This works now!! */

    ?>






</body>

</html>
<?php
$mysqli->close();
?>