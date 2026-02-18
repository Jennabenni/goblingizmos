<?php
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
    <title>Goblin Gizmos - Autographs</title>
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

                    <a href="../userProfile.php"> <img src="../img/PFP.png" alt="Profile Picture"></a>
                    <!--PLACEHOLDER!! REPLACE LATER:  USER ICON-->
                </div>

            </div>
        </header>



        <div class="specificCategoryLayoutGrid">

            <div class="specificCatItem1">
                <aside class="bountiesSection">
                    <h3>Bounties</h3>
                    <div></div>
                </aside>

            </div>






            <div class="specificCatItem2"> <a href="../makePost.php">

                    <button type="button" class="goblinButtons">Make a Post</button>
                </a>
            </div>



            <div class="specificCatItem3">

                <div>
                    <img src="../img/autographs.png" alt="image of a signed paper">
                </div>
                <div class="grayBoxInInfo">


                    <h2>Autographs</h2>
                    <p>Autographs are a signature or dedication that usually is written by a celebrity that individuals
                        sell
                        or
                        collect due to sentimental value to the celebrity's work. An autograph increases in value
                        depending
                        on
                        who
                        signed it, how easily available it is to acquire the autograph, how legible the photograph is,
                        and
                        if
                        the
                        celebrity is popular on social media.
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
                <div>
                    <!--STUFF GOES HERE-->

                    <!--
                <img src="../img/placeholder.png">
                <img src="../img/placeholder.png">

            -->

                </div>

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