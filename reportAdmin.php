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



if (isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {

    $query_user_info_on_pages = "SELECT * FROM `goblingizmos_users` WHERE user_id = '" . $_SESSION['user_id'] . "'";

    //honestly all of this could be used

    $result = $mysqli->query($query_user_info_on_pages);



}



?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Reports</title>
</head>

<body>
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


                if (isset($_SESSION['logged_in'])) {
                    $row = $result->fetch_array(MYSQLI_ASSOC);

                    if ($row['user_pfp'] != '') {
                        print "<a href=\"userProfile.php\"><img src=\"" . $row['user_pfp'] . "\" class=\"userIconImageForSmaller\" alt=\"User's chosen profile picture\"></a>";
                    } else if ($row['user_pfp'] == '') {
                        print "<a href=\"userProfile.php\"> <img src=\"img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                    }
                }


                if (!isset($_SESSION['logged_in'])) {
                    print "<a href=\"userProfile.php\"> <img src=\"img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                }





                ?>

            </div>

        </div>
    </header>


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
$mysqli->close();
?>