<?php
session_start();
//make sure to have the closer at the end of html


/*DO NOT DELETE THESE */

//include("db-connect.php");
//include(__DIR__ . "/db-connect.php");
include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//WAIT THIS ONE WORKED??
//local

//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote


/* FIX: initialize variables */
$row = null;
$viewedUser = null;

if (
    isset($_SESSION['logged_in']) &&
    $_SESSION['logged_in'] === true &&
    isset($_SESSION['user_id'])
) {


    /* FIX: use prepared statement instead of direct SQL concatenation */
    $query_user_info_on_pages = "SELECT * FROM `goblingizmos_users` WHERE user_id = ?";


    //honestly all of this could be used

    $stmt = $mysqli->prepare($query_user_info_on_pages);

    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $resultPFP = $stmt->get_result();

    if ($resultPFP && $resultPFP->num_rows > 0) {
        $row = $resultPFP->fetch_array(MYSQLI_ASSOC);
    }

    $stmt->close();
}


if (isset($_GET['user_id']) && $_GET['user_id'] !== '') {
    /* FIX: use prepared statement for user_id from URL */
    $query_some = "SELECT `username`,`user_pfp`,`user_bio`,`user_email` FROM `goblingizmos_users` WHERE user_id = ? LIMIT 1";

    //yaaaay

    $stmtViewedUser = $mysqli->prepare($query_some);

    if (!$stmtViewedUser) {
        die("Prepare failed: " . $mysqli->error);
    }

    $viewedUserId = (int) $_GET['user_id'];
    $stmtViewedUser->bind_param("i", $viewedUserId);
    $stmtViewedUser->execute();
    $resultUser = $stmtViewedUser->get_result();

    if ($resultUser && $resultUser->num_rows > 0) {
        $viewedUser = $resultUser->fetch_array(MYSQLI_ASSOC);
    }

    $stmtViewedUser->close();
}




?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goblin Gizmos - User profile</title>
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/goblinScript.js"></script>
</head>

<body>

    <div class="page-wrap">

        <header>

            <div class="headerGrid">

                <!--This is the section with the logo, nav, and the user profile picture-->

                <div class="headerGridItem" id="logoFlex">
                    <img class="logoImage" src="img/goblinLogo.png" alt="a goblin face in a coin; the logo">



                </div>

                <div class="headerGridItem">
                    <h1>Goblin Gizmos</h1>
                </div>

                <div class="headerGridItem">
                    <nav>
                        <ol>
                            <li>
                                <a class="titleLink" href="index.php">Home</a>
                            </li>
                            <li>
                                <p>|</p>
                            </li>
                            <li>
                                <a class="titleLink" href="community.php">Community</a>
                            </li>
                            <li>
                                <p>|</p>
                            </li>
                            <li>
                                <a class="titleLink" href="search.php">Search</a>
                            </li>
                            <li>
                                <p>|</p>
                            </li>
                            <li>
                                <a class="titleLink" href="categories.php">Categories</a>
                            </li>
                        </ol>
                    </nav>
                </div>
                <div class="headerGridItem">

                    <?php


                    if (
                        isset($_SESSION['logged_in']) &&
                        $_SESSION['logged_in'] === true &&
                        $row
                    ) {

                        if (!empty($row['user_pfp'])) {
                            print "<a href=\"userProfile.php\"><img src=\"" . htmlspecialchars($row['user_pfp']) . "\" class=\"userIconImageForSmaller\" alt=\"User's chosen profile picture\" onerror=\"this.src='img/PFP.png';\"></a>";
                        } else {
                            print "<a href=\"userProfile.php\"> <img src=\"img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                        }
                    } else {
                        print "<a href=\"userProfile.php\"> <img src=\"img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                    }

                    ?>
                </div>

            </div>
        </header>


        <?php
        if ($viewedUser) {
            print "<div class=\"signUpForms\">";

            print "<div><h2>" . htmlspecialchars($viewedUser['username']) . "</h2></div>";

            print "<div>";

            if (!empty($viewedUser['user_pfp'])) {
                print "<img src=\"" . htmlspecialchars($viewedUser['user_pfp']) . "\" alt=\"profile image of user\" onerror=\"this.src='img/PFP.png';\">";
            } else {
                print "<img src=\"img/PFP.png\" alt=\"profile image of user\">";
            }

            if (!empty($viewedUser['user_bio'])) {
                print "<p>" . htmlspecialchars($viewedUser['user_bio']) . "</p>";
            } else {
                print "<p>No bio added yet.</p>";
            }

            if (!empty($viewedUser['user_email'])) {
                print "<p>" . htmlspecialchars($viewedUser['user_email']) . "</p>";
            }

            print "</div>";
            print "</div>";
        } else {
            print "<div class=\"signUpForms\">";
            print "<p>User not found.</p>";
            print "</div>";
        }
        ?>




    </div>

    <footer>

        <img src="img/goblinLogo.png" id="bottomLogo" alt="a goblin face in a coin; the logo">
        <!--PLACEHOLDER!! REPLACE LATER: LOGO-->

        <div>
            <div class="footerFlex">
                <!--Bottom left-->
                <nav>
                    <div class="footerGrid">
                        <div class="footerGridItem1">

                            <ol>

                                <li>
                                    <a href="support.php" class="titleLink">Support</a>
                                </li>
                                <li>
                                    <p class="titleLink"> |</p>
                                </li>
                                <li>
                                    <a href="tos.php" class="titleLink">Terms of Service</a>
                                </li>
                            </ol>

                        </div>

                        <div class="footerGridItem2">
                            <ol>


                                <li><a href="https://x.com/"> <img src="img/TwitterLogo.png" class="iconImg"
                                            alt="X logo"></a></li>

                                <li><a href="https://www.instagram.com/"> <img src="img/instagram.png" class="iconImg"
                                            alt="Instagram logo"></a>
                                </li>

                                <li> <a href="https://www.facebook.com/"> <img src="img/facebook.png" class="iconImg"
                                            alt="Facebook logo"></a>
                                </li>

                                <!--This works, and now I'm too scared to touch it-->

                            </ol>

                        </div>
                    </div>
                </nav>

            </div>

        </div>

    </footer>

</body>

</html>
<?php
if (isset($mysqli) && $mysqli) {
    $mysqli->close();
}
?>