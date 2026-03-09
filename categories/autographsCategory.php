<?php
session_start();
//make sure to have the closer at the end of html


ini_set('display_errors', 1);
error_reporting(E_ALL);

ini_set('display_errors', 1);
error_reporting(E_ALL);

/*DO NOT DELETE THESE */

//include("../db-connect.php");
include("/Applications/XAMPP/htdocs/dig3134c/db-connect.php");
//WAIT THIS ONE WORKED??
//local

//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote




//pfp stuff
if (isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {


    $query_user_info_on_pages = "SELECT * FROM `goblingizmos_users` WHERE user_id = '" . $_SESSION['user_id'] . "'";


    //honestly all of this could be used

    $resultPFP = $mysqli->query($query_user_info_on_pages);



}




/*All IDS in table
            post_id
            user_id
            post_or_bounty
            post_category
            post_condition
            post_boxCondition
            post_price
            post_location
            post_description
            post_img
            post_sfw_nsfw
            post_creation_date





            so, I wanted to filter by autograph first, but I'll try dumping everything
            */


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



            <div class="specificCatItem4">
                <h3>Posts</h3>
            </div>


            <div class="specificCatItem5">







                <?php
                <?php

                if (isset($_SESSION['access_level']) && ($_SESSION['access_level'] == "admin")) {
                    while (($row = $result->fetch_array(MYSQLI_ASSOC))) {
                        //for admins



                        if (($row['post_category'] == 'autographs') && ($row['post_or_bounty'] == 'post')) {
                            //IT'S THAT EASY???
                            //Look how much thinking sleep can get ya
                            // who would've thought


                            print "<div class=\"boxesForEachPost\">";

                            print "<div class=\"gridItemForPostBox1\">" . "<p> Post Id:" . $row['post_id'] . "</p>" . "</div>";
                            print "<div class=\"gridItemForPostBox2\">" . "<p>User Id:" . $row['user_id'] . "</p>" . "</div>";
                            print "<div class=\"gridItemForPostBox3\">" . "<p>" . $row['username'] . "</p>" . "</div>";
                            print "<div class=\"gridItemForPostBox4\">";
                            print "<img src=\"../" . $row['user_pfp'] . "\" alt=\"profile image of user\">";
                            print "</div>";



                            // print "<div class=\"gridItemForPostBox5\">" . $row['post_or_bounty'] . "</div>";



                            // print "<div class=\"gridItemForPostBox6\">" . $row['post_category'] . "</div>";
                            /*
                                        if (!empty($row['post_condition'])) {
                                            print "<div class=\"gridItemForPostBox7\">" . $row['post_condition'] . "</div>";
                                            //doesn't always exist
                                        }

                                        if (!empty($row['post_boxCondition'])) {
                                            print "<div class=\"gridItemForPostBox8\">" . $row['post_boxCondition'] . "</div>";
                                            //doesn't always exist
                                        }
            */
                            if (!empty($row['post_price'])) {
                                print "<div class=\"gridItemForPostBox9\">" . "<p>$" . $row['post_price'] . "</p>" . "</div>";
                                //doesn't always exist
                            }
                            /*
                             if (!empty($row['post_location'])) {
                                print "<div class=\"gridItemForPostBox10\">" . $row['post_location'] . "</div>";
                                                            //doesn't always exist
                                                        }
                            */


                            print "<div class=\"gridItemForPostBox11\">" . "<p>" . $row['post_description'] . "</p>" . "</div>";
                            //this always exists




                            if (!empty($row['post_img'])) {
                                print "<div class=\"gridItemForPostBox12\">" . "<img src=\"../" . $row['post_img'] . "\">" . "</div>";
                                //doesn't always exist
                            }

                            if (!empty($row['post_sfw_nsfw'])) {
                                print "<div class=\"gridItemForPostBox13\">" . "<p>" . $row['post_sfw_nsfw'] . "</p>" . "</div>";
                                //doesn't always exist
                            }


                            print "<div class=\"gridItemForPostBox14\">" . "<p>" . $row['post_creation_date'] . "</p>" . "</div>";
                            //always exists


                            print "</div>";



                        }


                    }

                } else if (isset($_SESSION['access_level']) && (($_SESSION['access_level']) == "user")) {

                    while (($row2 = $resultUser->fetch_array(MYSQLI_ASSOC))) {
                        if (($row2['post_category'] == 'autographs') && ($row2['post_or_bounty'] == 'post')) {



                            print "<div class=\"boxesForEachPost\">";

                            //print "<div class=\"gridItemForPostBox1\">" . $row2['post_id'] . "</div>";
                            //print "<div class=\"gridItemForPostBox2\">" . $row2['user_id'] . "</div>";



                            print "<div class=\"gridItemForPostBox3\">" . "<p>" . $row2['username'] . "</p>" . "</div>";
                            print "<div class=\"gridItemForPostBox4\">";
                            print "<img src=\"../" . $row2['user_pfp'] . "\" alt=\"profile image of user\">";

                            print "</div>";






                            // print "<div class=\"gridItemForPostBox5\">" . $row['post_or_bounty'] . "</div>";



                            // print "<div class=\"gridItemForPostBox6\">" . $row['post_category'] . "</div>";

                            /* if (!empty($row2['post_condition'])) {
                                 print "<div class=\"gridItemForPostBox7\">" . $row2['post_condition'] . "</div>";
                                 //doesn't always exist
                             }*

                             if (!empty($row2['post_boxCondition'])) {
                                 print "<div class=\"gridItemForPostBox8\">" . $row2['post_boxCondition'] . "</div>";
                                 //doesn't always exist
                             }
 */
                            if (!empty($row2['post_price'])) {
                                print "<div class=\"gridItemForPostBox9\">" . "<p>$" . $row2['post_price'] . "</p>" . "</div>";
                                //doesn't always exist

                                //I made it in $ because I do NOT have the time to code in a whole other currency section
                                //Hate to be like that but oh well



                            }
                            /*
                                      if (!empty($row2['post_location'])) {
                         print "<div class=\"gridItemForPostBox10\">" . $row2['post_location'] . "</div>";
                         //doesn't always exist
                                                        }

                            */

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
                            print "</div>";

                        }

                    }




                }



                ?>







            </div>
            </div>
        </div>
    </div>






    <!--Something weird happened to footer; investigate later
    fixed

    when in doubt, add divs-->





    <!--Something weird happened to footer; investigate later
    fixed

    when in doubt, add divs-->

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