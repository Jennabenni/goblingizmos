<?php
session_start();
//make sure to have the closer at the end of html


/*DO NOT DELETE THESE */

//include("../db-connect.php");
//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//WAIT THIS ONE WORKED??
//local

include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote


/*WHY DO THESE ALL HAVE BUTTONS FUCK */


ini_set('display_errors', 1);
error_reporting(E_ALL);






/* FIX: initialize row so it exists even if no query runs */
$row = null;
$viewedCompostComments = null;
//This was in postView but personally I don't know how to code like that

if (
    isset($_SESSION['logged_in']) &&
    $_SESSION['logged_in'] === true &&
    isset($_SESSION['user_id'])
) {


    /* FIX: use prepared statement instead of direct SQL concatenation */
    $query_user_info_on_pages = "SELECT * FROM `goblingizmos_users` WHERE user_id = ?";



    $stmt = $mysqli->prepare($query_user_info_on_pages);

    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
    }

    $stmt->close();
}







/* FIX: select poster username and profile picture from joined users table */
$query_compost = "SELECT goblingizmos_community.user_id, goblingizmos_community.compost_id, goblingizmos_community.compost_img, goblingizmos_community.compost_category, goblingizmos_community.compost_description, goblingizmos_community.compost_sfw_nsfw, goblingizmos_community.compost_likes, goblingizmos_community.compost_creation_date, goblingizmos_users.username, goblingizmos_users.user_pfp FROM `goblingizmos_community` INNER JOIN goblingizmos_users ON goblingizmos_community.user_id = goblingizmos_users.user_id ORDER BY goblingizmos_community.compost_id DESC";



$resultCompost = $mysqli->query($query_compost);

/* FIX: avoid hard crash if local table is missing */
if (!$resultCompost) {
    $resultCompost = null;
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/goblinScript.js"></script>

    <title>Goblin Gizmos - Community</title>
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
                        isset($row)
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


        if ($resultCompost) {

            /*





            */



            print "<div class=\"boxesForEachPost\">";



            print "<div class=\"gridItemForPostBox3\">" . "<p>" . htmlspecialchars($row['username']) . "</p>" . "</div>";
            print "<div class=\"gridItemForPostBox4\">";

            print "<div class=\"gridItemForPostBox5\">" . "<p>" . htmlspecialchars($row['compost_category']) . "</p>" . "</div>";


            print "<a href=\"userProfileView.php?user_id=" . urlencode($row['user_id']) . "\">";
            if (!empty($row['user_pfp'])) {
                print "<img src=\"" . htmlspecialchars($row['user_pfp']) . "\" alt=\"profile image of user\" onerror=\"this.src='img/PFP.png';\">";
            } else {
                print "<img src=\"img/PFP.png\" alt=\"profile image of user\">";
            }
            print "</a>";

            print "</div>";

            print "<div class=\"gridItemForPostBox11\">" . "<p>" . htmlspecialchars($row['compost_description']) . "</p>" . "</div>";



            if (!empty($row['compost_img'])) {
                print "<div class=\"gridItemForPostBox12\">" . "<img src=\"" . htmlspecialchars($row['compost_img']) . "\" alt=\"community post image\">" . "</div>";

            }

            if (!empty($row['compost_sfw_nsfw'])) {
                print "<div class=\"gridItemForPostBox13\">" . "<p>" . htmlspecialchars($row['compost_sfw_nsfw']) . "</p>" .
                    "</div>";

            }


            print "<div class=\"gridItemForPostBox14\">" . "<p>" . $row['compost_creation_date'] . "</p>" . "</div>";





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