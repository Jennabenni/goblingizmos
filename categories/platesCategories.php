<?php
session_start();
//make sure to have the closer at the end of html


/*DO NOT DELETE THESE */

//include("../db-connect.php");
//include(__DIR__ . "/../db-connect.php");
include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//WAIT THIS ONE WORKED??
//local

//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote


/* FIX: initialize variables */
$row = null;
$result = null;
$resultUser = null;
$resultUserPartTwo = null;

if (
    isset($_SESSION['logged_in']) &&
    $_SESSION['logged_in'] === true &&
    isset($_SESSION['user_id'])
) {

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

if (isset($_SESSION['access_level']) && ($_SESSION['access_level'] == "admin")) {
    $query_all = "SELECT post_id, goblingizmos_postsbounties.user_id, post_or_bounty, post_category, post_condition, post_boxCondition, post_price, post_location, post_description, post_img, post_sfw_nsfw, post_creation_date, goblingizmos_users.username, goblingizmos_users.user_pfp FROM `goblingizmos_postsbounties` INNER JOIN goblingizmos_users ON goblingizmos_postsbounties.user_id=goblingizmos_users.user_id ORDER BY post_id DESC";

    //NOBODY MOVE DONT TOUCH THIS

    //Date format broke it??
    //Do we need times honestly

    $result = $mysqli->query($query_all);
    //This was hiding in my other code

} else if (isset($_SESSION['access_level']) && ($_SESSION['access_level'] == "user")) {
    $query_some = "SELECT post_id, goblingizmos_postsbounties.user_id, post_or_bounty, post_category, post_condition, post_boxCondition, post_price, post_location, post_description, post_img, post_sfw_nsfw, post_creation_date, goblingizmos_users.username, goblingizmos_users.user_pfp FROM `goblingizmos_postsbounties` INNER JOIN goblingizmos_users ON goblingizmos_postsbounties.user_id=goblingizmos_users.user_id ORDER BY post_id DESC";

    $resultUser = $mysqli->query($query_some);
}

if (isset($_SESSION['access_level'])) {

    $query_bounties = "SELECT post_id, goblingizmos_postsbounties.user_id, post_or_bounty, post_category, post_condition, post_boxCondition, post_price, post_location, post_description, post_img, post_sfw_nsfw, post_creation_date, goblingizmos_users.username, goblingizmos_users.user_pfp FROM `goblingizmos_postsbounties` INNER JOIN goblingizmos_users ON goblingizmos_postsbounties.user_id=goblingizmos_users.user_id ORDER BY post_id DESC";

    $resultUserPartTwo = $mysqli->query($query_bounties);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goblin Gizmos - Plates</title>
    <link rel="stylesheet" type="text/css" href="../css/style.css">
    <script src="../js/goblinScript.js"></script>
</head>

<body>

    <div class="page-wrap">

        <header>

            <div class="headerGrid">

                <!--This is the section with the logo, nav, and the user profile picture-->

                <div class="headerGridItem" id="logoFlex">
                    <img class="logoImage" src="../img/goblinLogo.png" alt="a goblin face in a coin; the logo">
                </div>

                <div class="headerGridItem">
                    <h1>Goblin Gizmos</h1>
                </div>

                <div class="headerGridItem">
                    <nav>
                        <ol>
                            <li>
                                <a class="titleLink" href="../index.php">Home</a>
                            </li>
                            <li>
                                <p>|</p>
                            </li>
                            <li>
                                <a class="titleLink" href="../community.php">Community</a>
                            </li>
                            <li>
                                <p>|</p>
                            </li>
                            <li>
                                <a class="titleLink" href="../search.php">Search</a>
                            </li>
                            <li>
                                <p>|</p>
                            </li>
                            <li>
                                <a class="titleLink" href="../categories.php">Categories</a>
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
                            print "<a href=\"../userProfile.php\"><img src=\"../" . htmlspecialchars($row['user_pfp']) . "\" class=\"userIconImageForSmaller\" alt=\"User's chosen profile picture\" onerror=\"this.src='../img/PFP.png';\"></a>";
                        } else {
                            print "<a href=\"../userProfile.php\"> <img src=\"../img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                        }
                    } else {
                        print "<a href=\"../userProfile.php\"> <img src=\"../img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                    }

                    ?>

                </div>

            </div>
        </header>



        <div class="specificCategoryLayoutGrid">

            <div class="specificCatItem1">
                <aside class="bountiesSection">
                    <h3>Bounties</h3>
                    <div class="bigBountyOnCat">
                        <?php

                        if (isset($_SESSION['access_level']) && $resultUserPartTwo) {

                            $bountyCounter = 0;

                            while (($row3 = $resultUserPartTwo->fetch_array(MYSQLI_ASSOC))) {
                                if (($row3['post_category'] == 'plates') && ($row3['post_or_bounty'] == 'bounty')) {

                                    print "<div class=\"boxesForEachBounty\">";
                                    print "<a href=\"../search.php\" class=\"bountyClickStyle\">";
                                    print "<div class=\"gridItemForBountyDisplayBox11\"><p>" . htmlspecialchars($row3['post_description']) . "</p></div>";

                                    if (!empty($row3['post_img'])) {
                                        print "<div class=\"gridItemForBountyDisplayBox12\"><img src=\"../" . htmlspecialchars($row3['post_img']) . "\" alt=\"bounty image\"></div>";
                                    }

                                    print "</a>";
                                    print "</div>";

                                    $bountyCounter++;
                                    if ($bountyCounter == 3) {
                                        break;
                                    }

                                }

                            }

                        }

                        ?>

                    </div>
                </aside>

            </div>

            <div class="specificCatItem2">
                <a href="../makePost.php" class="goblinButtons">Make a Post</a>
            </div>

            <div class="specificCatItem3">

                <div>
                    <img src="../img/plates.png" alt="several stacks of plates">
                </div>
                <div class="grayBoxInInfo">

                    <h2>Plates</h2>
                    <p>A plate is a smooth piece of material, most often used for placing food on top of. Often people
                        collect decorative plates and try to complete a set or achieve a theme.
                    </p>
                </div>

            </div>

            <!-- <div>

            <h3>Bounties</h3>
        </div>-->

            <div class="specificCatItem4">
                <h3>Posts</h3>
            </div>

            <div class="specificCatItem5">

                <?php

                if (isset($_SESSION['access_level']) && ($_SESSION['access_level'] == "admin") && $result) {
                    while (($row = $result->fetch_array(MYSQLI_ASSOC))) {

                        if (($row['post_category'] == 'plates') && ($row['post_or_bounty'] == 'post')) {

                            print "<div class=\"boxesForEachPost\">";

                            /* print "<div class=\"gridItemForPostBox1\"><p> Post Id:" . $row['post_id'] . "</p></div>"; */
                            /* print "<div class=\"gridItemForPostBox2\"><p>User Id:" . $row['user_id'] . "</p></div>"; */
                            print "<div class=\"gridItemForPostBox3\"><p>" . htmlspecialchars($row['username']) . "</p></div>";
                            print "<div class=\"gridItemForPostBox4\">";

                            print "<a href=\"../userProfileView.php?user_id=" . urlencode($row['user_id']) . "\">";
                            if (!empty($row['user_pfp'])) {
                                print "<img src=\"../" . htmlspecialchars($row['user_pfp']) . "\" alt=\"profile image of user\" onerror=\"this.src='../img/PFP.png';\">";
                            } else {
                                print "<img src=\"../img/PFP.png\" alt=\"profile image of user\">";
                            }
                            print "</a>";

                            print "</div>";

                            if (!empty($row['post_price'])) {
                                print "<div class=\"gridItemForPostBox9\"><p>$" . htmlspecialchars($row['post_price']) . "</p></div>";
                            }

                            print "<div class=\"gridItemForPostBox11\"><p>" . htmlspecialchars($row['post_description']) . "</p></div>";

                            if (!empty($row['post_img'])) {
                                print "<div class=\"gridItemForPostBox12\"><img src=\"../" . htmlspecialchars($row['post_img']) . "\" alt=\"post image\"></div>";
                            }

                            if (!empty($row['post_sfw_nsfw'])) {
                                print "<div class=\"gridItemForPostBox13\"><p>" . htmlspecialchars($row['post_sfw_nsfw']) . "</p></div>";
                            }

                            print "<div class=\"gridItemForPostBox14\"><p>" . htmlspecialchars($row['post_creation_date']) . "</p></div>";

                            print "<div class=\"gridItemForPostBoxViewPost\">";
                            print "<a href=\"../categories/postView.php?post_id=" . urlencode($row['post_id']) . "\">View Post</a>";
                            print "</div>";

                            print "</div>";

                        }

                    }

                } else if (isset($_SESSION['access_level']) && ($_SESSION['access_level'] == "user") && $resultUser) {

                    while (($row2 = $resultUser->fetch_array(MYSQLI_ASSOC))) {
                        if (($row2['post_category'] == 'plates') && ($row2['post_or_bounty'] == 'post')) {

                            print "<div class=\"boxesForEachPost\">";

                            print "<div class=\"gridItemForPostBox3\"><p>" . htmlspecialchars($row2['username']) . "</p></div>";
                            print "<div class=\"gridItemForPostBox4\">";

                            print "<a href=\"../userProfileView.php?user_id=" . urlencode($row2['user_id']) . "\">";
                            if (!empty($row2['user_pfp'])) {
                                print "<img src=\"../" . htmlspecialchars($row2['user_pfp']) . "\" alt=\"profile image of user\" onerror=\"this.src='../img/PFP.png';\">";
                            } else {
                                print "<img src=\"../img/PFP.png\" alt=\"profile image of user\">";
                            }
                            print "</a>";

                            print "</div>";

                            if (!empty($row2['post_price'])) {
                                print "<div class=\"gridItemForPostBox9\"><p>$" . htmlspecialchars($row2['post_price']) . "</p></div>";
                            }

                            print "<div class=\"gridItemForPostBox11\"><p>" . htmlspecialchars($row2['post_description']) . "</p></div>";

                            if (!empty($row2['post_img'])) {
                                print "<div class=\"gridItemForPostBox12\"><img src=\"../" . htmlspecialchars($row2['post_img']) . "\" alt=\"post image\"></div>";
                            }

                            if (!empty($row2['post_sfw_nsfw'])) {
                                print "<div class=\"gridItemForPostBox13\"><p>" . htmlspecialchars($row2['post_sfw_nsfw']) . "</p></div>";
                            }

                            print "<div class=\"gridItemForPostBox14\"><p>" . htmlspecialchars($row2['post_creation_date']) . "</p></div>";

                            print "<div class=\"gridItemForPostBoxViewPost\">";
                            print "<a href=\"../categories/postView.php?post_id=" . urlencode($row2['post_id']) . "\">View Post</a>";
                            print "</div>";

                            print "</div>";

                        }

                    }

                } else if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                    print "<div class=\"signUpForms\">";
                    print "<p>Please create an account with us to view these posts; It's free!</p>";
                    print "</div>";
                }

                ?>

            </div>

            <!--Bottom div for grid for reference-->
        </div>
    </div>

    <footer>

        <img src="../img/goblinLogo.png" id="bottomLogo" alt="a goblin face in a coin; the logo">
        <!--PLACEHOLDER!! REPLACE LATER: LOGO-->

        <div>
            <div class="footerFlex">
                <!--Bottom left-->
                <nav>
                    <div class="footerGrid">
                        <div class="footerGridItem1">

                            <ol>

                                <li>
                                    <a href="../support.php" class="titleLink">Support</a>
                                </li>
                                <li>
                                    <p class="titleLink"> |</p>
                                </li>
                                <li>
                                    <a href="../tos.php" class="titleLink">Terms of Service</a>
                                </li>
                            </ol>

                        </div>

                        <div class="footerGridItem2">
                            <ol>

                                <li><a href="https://x.com/"> <img src="../img/TwitterLogo.png" class="iconImg"
                                            alt="X logo"></a></li>

                                <li><a href="https://www.instagram.com/"> <img src="../img/instagram.png"
                                            class="iconImg" alt="Instagram logo"></a>
                                </li>

                                <li> <a href="https://www.facebook.com/"> <img src="../img/facebook.png" class="iconImg"
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