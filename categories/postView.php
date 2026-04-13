<?php
session_start();
//make sure to have the closer at the end of html


/*DO NOT DELETE THESE */

//include("../db-connect.php");
//include(__DIR__ . "/../db-connect.php");
//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//WAIT THIS ONE WORKED??
//local

//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote


//DOCKER CONNECTION DO NOT TOUCH ARF ARF
require '../db_connectionGG.php';




$row = null;
$viewedPost = null;

if (
    isset($_SESSION['logged_in']) &&
    $_SESSION['logged_in'] === true &&
    isset($_SESSION['user_id'])
) {

    $query_user_info_on_pages = "SELECT * FROM goblingizmos_users WHERE user_id = ?";

    $stmt = $mysqli->prepare($query_user_info_on_pages);
    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();

    $resultPFP = $stmt->get_result();

    if ($resultPFP && $resultPFP->num_rows > 0) {
        $row = $resultPFP->fetch_array(MYSQLI_ASSOC);
    }

    $stmt->close();
}


if (isset($_GET['post_id'])) {

    $query_some = "
        SELECT
        post_id,
        goblingizmos_postsbounties.user_id,
        post_or_bounty,
        post_category,
        post_condition,
        post_boxCondition,
        post_price,
        post_location,
        post_description,
        post_img,
        post_sfw_nsfw,
        post_creation_date,
        goblingizmos_users.username,
        goblingizmos_users.user_pfp

        FROM goblingizmos_postsbounties

        INNER JOIN goblingizmos_users
        ON goblingizmos_postsbounties.user_id = goblingizmos_users.user_id

        WHERE post_id = ?
        LIMIT 1
    ";

    $stmt = $mysqli->prepare($query_some);

    $postId = (int) $_GET['post_id'];

    $stmt->bind_param("i", $postId);

    $stmt->execute();

    $resultUser = $stmt->get_result();

    if ($resultUser && $resultUser->num_rows > 0) {
        $viewedPost = $resultUser->fetch_array(MYSQLI_ASSOC);
    }

    $stmt->close();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Goblin Gizmos - Post</title>

    <link rel="stylesheet" type="text/css" href="../css/style.css">

    <script src="../js/goblinScript.js"></script>
</head>

<body>

    <div class="page-wrap">

        <header>

            <div class="headerGrid">

                <div class="headerGridItem" id="logoFlex">
                    <img class="logoImage" src="../img/goblinLogo.png" alt="a goblin face in a coin; the logo">
                </div>

                <div class="headerGridItem">
                    <h1>Goblin Gizmos</h1>
                </div>

                <div class="headerGridItem">
                    <nav>
                        <ol>

                            <li><a class="titleLink" href="../index.php">Home</a></li>
                            <li>
                                <p>|</p>
                            </li>

                            <li><a class="titleLink" href="../community.php">Community</a></li>
                            <li>
                                <p>|</p>
                            </li>

                            <li><a class="titleLink" href="../search.php">Search</a></li>
                            <li>
                                <p>|</p>
                            </li>

                            <li><a class="titleLink" href="../categories.php">Categories</a></li>

                        </ol>
                    </nav>
                </div>

                <div class="headerGridItem">

                    <?php

                    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && $row) {

                        if (!empty($row['user_pfp'])) {

                            print "<a href=\"../userProfile.php\"><img src=\"../" . htmlspecialchars($row['user_pfp']) . "\" class=\"userIconImageForSmaller\" alt=\"User's chosen profile picture\" onerror=\"this.src='../img/PFP.png';\"></a>";

                        } else {

                            print "<a href=\"../userProfile.php\"><img src=\"../img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";

                        }

                    } else {

                        print "<a href=\"../userProfile.php\"><img src=\"../img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";

                    }

                    ?>

                </div>

            </div>

        </header>


        <?php

        if ($viewedPost) {

            print "<div class=\"boxesForEachPost\">";

            print "<div class=\"gridItemForPostBox3\"><p>" . htmlspecialchars($viewedPost['username']) . "</p></div>";

            print "<div class=\"gridItemForPostBox4\">";

            print "<a href=\"../userProfileView.php?user_id=" . urlencode($viewedPost['user_id']) . "\">";

            if (!empty($viewedPost['user_pfp'])) {

                print "<img src=\"../" . htmlspecialchars($viewedPost['user_pfp']) . "\" alt=\"profile image\" onerror=\"this.src='../img/PFP.png';\">";

            } else {

                print "<img src=\"../img/PFP.png\" alt=\"profile image\">";

            }

            print "</a>";

            print "</div>";


            if (!empty($viewedPost['post_price'])) {

                print "<div class=\"gridItemForPostBox9\"><p>$" . htmlspecialchars($viewedPost['post_price']) . "</p></div>";

            }

            print "<div class=\"gridItemForPostBox11\"><p>" . htmlspecialchars($viewedPost['post_description']) . "</p></div>";

            if (!empty($viewedPost['post_img'])) {

                print "<div class=\"gridItemForPostBox12\"><img src=\"../" . htmlspecialchars($viewedPost['post_img']) . "\" alt=\"post image\"></div>";

            }

            if (!empty($viewedPost['post_sfw_nsfw'])) {

                print "<div class=\"gridItemForPostBox13\"><p>" . htmlspecialchars($viewedPost['post_sfw_nsfw']) . "</p></div>";

            }

            print "<div class=\"gridItemForPostBox14\"><p>" . htmlspecialchars($viewedPost['post_creation_date']) . "</p></div>";

            print "</div>";



            if (!empty($viewedPost['post_condition'])) {

                print "<div class=\"postDetailBox\">";
                print "<p>Condition: " . htmlspecialchars($viewedPost['post_condition']) . "</p>";
                print "</div>";

            }

            if (!empty($viewedPost['post_boxCondition'])) {

                print "<div class=\"postDetailBox\">";
                print "<p>Box Condition: " . htmlspecialchars($viewedPost['post_boxCondition']) . "</p>";
                print "</div>";

            }

            if (!empty($viewedPost['post_location'])) {

                print "<div class=\"postDetailBox\">";
                print "<p>Post Location: " . htmlspecialchars($viewedPost['post_location']) . "</p>";
                print "</div>";

            }

        } else {

            print "<div class=\"signUpForms\">";
            print "<p>Post not found.</p>";
            print "</div>";

        }

        ?>

    </div>

    <footer>

        <img src="../img/goblinLogo.png" id="bottomLogo" alt="logo">

        <div>

            <div class="footerFlex">

                <nav>

                    <div class="footerGrid">

                        <div class="footerGridItem1">

                            <ol>

                                <li><a href="../support.php" class="titleLink">Support</a></li>
                                <li>
                                    <p class="titleLink"> |</p>
                                </li>
                                <li><a href="../tos.php" class="titleLink">Terms of Service</a></li>

                            </ol>

                        </div>

                        <div class="footerGridItem2">

                            <ol>

                                <li><a href="https://x.com/"><img src="../img/TwitterLogo.png" class="iconImg"
                                            alt="X logo"></a></li>

                                <li><a href="https://www.instagram.com/"><img src="../img/instagram.png" class="iconImg"
                                            alt="Instagram"></a></li>

                                <li><a href="https://www.facebook.com/"><img src="../img/facebook.png" class="iconImg"
                                            alt="Facebook"></a></li>

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