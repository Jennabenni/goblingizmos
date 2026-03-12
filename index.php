<!--
Hello goblins, I'm gonna put my notes here to stay on track
You will be seeing a lot of these

---------------------------- ******
Main SQL priorities

1. Accounts (admin, user, nobody) (I will make admin accounts for all of us + novatnak)

2. Making posts (bounties + posts) (community) (reports)
Bounties and posts are together, and one of the checkboxes determines which one
it's filed away into

3. Account info that user sets (we also need regex for account setup)


So we'll need four to five data tables, I can get the format from them
should the main SQL stuff be on the login page despite users coming here first?







New start point: log in page
(I will keep some of the stuff here though)

-->


<?php

//http://localhost/goblingizmos/index.php
//this is what you copy and paste to open up the server
//does NOT need to be out of a comment




session_start();
//make sure to have the closer at the end of html




/*DO NOT DELETE THESE */

//include("../db-connect.php");
//include("/Applications/XAMPP/htdocs/dig3134c/db-connect.php");
//WAIT THIS ONE WORKED??
//local


include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote

/*ini_set('display_errors', 1);
error_reporting(E_ALL);*/


//change location for when on the server, these are my (Jenna's) credentials
//and this is the LOCAL copy to start, and then we dump the table into here



/*
That weird database thing was called phpMyAdmin!!

http://localhost/phpmyadmin/
//needs vpn


*/




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
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/goblinScript.js"></script>

    <title>Goblin Gizmos - Home</title>
</head>


<!--So, the back to top arrow, should it be fixed on the page?? Display: fixed
Note to self: don't forget to add header and footer to each document
holy goddamn div tabs Batman
okay.  Grid.
Three columns, three rows
-->

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
                <div class="headerGridItem" id="userIconGridItem">



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




                    <!--PLACEHOLDER!! REPLACE LATER:  USER ICON-->
                </div>

            </div>
        </header>



        <div class="megaGridOrFlex">


            <div id="homeGridItem1">
                <aside>
                    <!--
        <p>ADS</p>
        -->

                    <h4>Advertisements</h4>

                    <div class="adBoxes">
                        <p>Have you wondered what insurance can do for you?</p>
                        <p>Call xxx-xxx-xxxx</p>
                    </div>
                    <div class="adBoxes">
                        <p>Been in an accident? That's unfortunate</p>
                        <p>Call xxx-xxx-xxxx</p>
                    </div>
                    <div class="adBoxes">
                        <p>Your wife is hot.</p>
                        <p>Call xxx-xxx-xxxx</p>
                    </div>


                </aside>
            </div>


            <div id="homeGridItem2">
                <div class="biggerHomeBox">
                    <h2>Welcome to Goblin Gizmos!</h2>

                    <?php if (isset($_SESSION['logged_in'])) {
                        print "<p>You have successfully logged in</p>";
                    } ?>

                    <div id="infoBoxHome">
                        <p>Welcome to Goblin Gizmos! Whether you're a novice, experienced, or an expert in collecting
                            items,
                            there
                            is a
                            place
                            here for you in our community. Start by checking out what others are posting or explore one
                            of
                            our
                            many
                            categories!</p>
                    </div>
                </div>
            </div>
            <br>





            <div id="homeGridItem3">
                <a href="categories.php">
                    <h3 class="homeTabLinks">See Categories</h3>
                </a>
            </div>

            <div id="homeGridItem4">

                <a href="signIn.php">
                    <h3 class="homeTabLinks">Create an Account</h3>
                </a>

            </div>

            <br>




            <div id="homeGridItem5">
                <img src="img/homePennies.png" alt="image of pennies in a pile" class="homeImg">
            </div>
            <div id="homeGridItem6">
                <img src="img/turtle.png" alt="turtle charm" class="homeImg">
            </div>





        </div>
    </div>

    <br>



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