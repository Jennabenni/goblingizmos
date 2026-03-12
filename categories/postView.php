<?php
session_start();
//make sure to have the closer at the end of html


/*DO NOT DELETE THESE */

//include("../db-connect.php");
//include("/Applications/XAMPP/htdocs/dig3134c/db-connect.php");
//WAIT THIS ONE WORKED??
//local

include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote




if (isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {


    $query_user_info_on_pages = "SELECT * FROM `goblingizmos_users` WHERE user_id = '" . $_SESSION['user_id'] . "'";


    //honestly all of this could be used

    $resultPFP = $mysqli->query($query_user_info_on_pages);



}


if (isset($_GET['post_id'])) {
    $query_some = "SELECT post_id, goblingizmos_postsbounties.user_id, post_or_bounty, post_category, post_condition, post_boxCondition, post_price, post_location, post_description, post_img, post_sfw_nsfw, post_creation_date, goblingizmos_users.username, goblingizmos_users.user_pfp FROM `goblingizmos_postsbounties` INNER JOIN goblingizmos_users ON goblingizmos_postsbounties.user_id=goblingizmos_users.user_id WHERE post_id = '" . $_GET['post_id'] . "'";

    //yaaaay


    $resultUser = $mysqli->query($query_some);




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


                    if (isset($_SESSION['logged_in'])) {
                        $row = $resultPFP->fetch_array(MYSQLI_ASSOC);

                        if ($row['user_pfp'] != '') {
                            print "<a href=\"../userProfile.php\"><img src=\"../" . $row['user_pfp'] . "\" class=\"userIconImageForSmaller\" alt=\"User's chosen profile picture\"></a>";
                        } else if ($row['user_pfp'] == '') {
                            print "<a href=\"../userProfile.php\"> <img src=\"../img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                        }
                    }


                    if (!isset($_SESSION['logged_in'])) {
                        print "<a href=\"../userProfile.php\"> <img src=\"../img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                    }






                    ?>
                </div>

            </div>
        </header>


        <?php
        if ($resultUser) {
            while ($row2 = $resultUser->fetch_array(MYSQLI_ASSOC)) {




                print "<div class=\"boxesForEachPost\">";
                print "<div class=\"gridItemForPostBox3\">" . "<p>" . $row2['username'] . "</p>" . "</div>";
                print "<div class=\"gridItemForPostBox4\">";
                if ($row2['user_pfp'] != NULL) {
                    print "<img src=\"../" . $row2['user_pfp'] . "\" alt=\"profile image of user\">";
                } else if ($row2['user_pfp'] == NULL) {
                    print "<img src=\"../uploads/goblin.png\" alt=\"goblin image of user\">";

                }
                print "</div>";
                if (!empty($row2['post_price'])) {
                    print "<div class=\"gridItemForPostBox9\">" . "<p>$" . $row2['post_price'] . "</p>" . "</div>";
                }

                print "<div class=\"gridItemForPostBox11\">" . "<p>" . $row2['post_description'] . "</p>" . "</div>";
                //this always exists
        
                if (!empty($row2['post_img'])) {
                    print "<div class=\"gridItemForPostBox12\">" . "<img src=\"../" . $row2['post_img'] . "\">" . "</div>";
                    //doesn't always exist
                }

                if (!empty($row2['post_sfw_nsfw'])) {
                    print "<div class=\"gridItemForPostBox13\">" . "<p>" . $row2['post_sfw_nsfw'] . "</p>" .
                        "</div>";
                    //doesn't always exist
                }


                print "<div class=\"gridItemForPostBox14\">" . "<p>" . $row2['post_creation_date'] . "</p>" . "</div>";
                //always exists
        

                print "</div>";




                //post cond, box con, location,
        
                if (!empty($row2['post_condition'])) {
                    print "<div class=\"FAQ\">";
                    print "<div class=\"gridItemForPostBox7\">" . "<p>Condition: " . $row2['gridItemForPostBox13'] . "</p>" .
                        "</div>";
                    //doesn't always exist
                    print "</div>";
                }




                if (!empty($row2['post_boxCondition'])) {
                    print "<div class=\"FAQ\">";
                    print "<div class=\"gridItemForPostBox8\">" . "<p>Box Condition: " . $row2['post_boxCondition'] . "</p>" .
                        "</div>";
                    //doesn't always exist
                    print "</div>";
                }



                if (!empty($row2['post_location'])) {
                    print "<div class=\"FAQ\">";
                    print "<div class=\"gridItemForPostBox10\">" . "<p>Post Location: " . $row2['post_location'] . "</p>" .
                        "</div>";
                    //doesn't always exist
                    print "</div>";
                }


                print "</div>";



            }
        } else if (!isset($_SESSION['access_level'])) {
            print "<p>Log in to see more details about this post</p>";
        }






        ?>








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
                                    <p class="/titleLink"> |</p>
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
$mysqli->close();
?>