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
    <title>Goblin Gizmos - Support</title>

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


        <div class="FAQ">

            <h2 class="FAQHeading" id="stayHere">Frequently Asked Questions</h2>
            <!--Would this be DOM??-->

            <div>
                <div class="bordersForQuestions">
                    <a href="#stayHere" id="questionOneClick">Is the website only for advanced collectors? </a>

                </div>
                <div id="questionOneDOM" class="answerBox">


                </div>


                <!--The drop down may have to get a point on page where it wont bring user back to the top every time; Made a minor adjustment so that way it goes to the top of FAQ-->



                <div class="bordersForQuestions">
                    <a href="#stayHere" id="questionTwoClick">What am I allowed to post here? </a>
                </div>
                <div id="questionTwoDOM" class="answerBox">

                </div>


                <div class="bordersForQuestions">
                    <a href="#stayHere" id="questionThreeClick">Do I need an account to post here?</a>
                </div>
                <div id="questionThreeDOM" class="answerBox">

                </div>


                <div class="bordersForQuestions">
                    <a href="#stayHere" id="questionFourClick">Can I make transactions on the website? </a>
                </div>
                <div id="questionFourDOM" class="answerBox">

                </div>


                <div class="bordersForQuestions">
                    <a href="#stayHere" id="questionFiveClick">What do I do if someone scams me?</a>
                </div>
                <div id="questionFiveDOM" class="answerBox">

                </div>



            </div>
        </div>

        <div class="FAQ">
            <div class="FAQHeading">
                <h2>Contact Us</h2>
            </div>
            <div>

                <p>Have some feedback? Contact us through our email to ensure we can respond to you as soon as possible.
                    goblingizmos@goblins.net</p>
            </div>
        </div>

        <!--
        <div class="FAQ">

            <div class="FAQHeading">
                <h2>Report</h2>
            </div>

            <form action="/support.php">

                <div class="formStyleSupport">
                    <label for="fname">Your username</label>
                </div>


                <div class="formStyleSupport">

                    <input type="text" id="fname" name="fname">
                </div>

                <div class="formStyleSupport">
                    <label for="userreport">Username of person being reported</label>
                </div>


                <div class="formStyleSupport">
                    <input type="text" id="userreport" name="userreport">
                </div>

                <div class="formStyleSupport">
                    <label for="report">Reason for reporting</label>
                </div>


                <div class="formStyleSupport">
                    <select name="reportreason" id="report">
                        <option value="Hate">Hate speech or harmful language</option>
                        <option value="sexual">Sexual content</option>

                        <option value="scam">User is a scammer</option>

                        <option value="otheroption">Other (please write in box below)</option>

                    </select>
                </div>

                <div class="formStyleSupport">
                    <label for="text">Additional Details</label>
                </div>
                <div class="formStyleSupport">
                    <textarea name="information" rows="10" columns="30" id="text"></textarea>
                </div>


                <div class="formStyleSupport">
                    <label for="submitForm"></label>
                </div>
                <div class="formStyleSupport">
                    <input type="button" id="submitForm" value="Submit Report" class="goblinButtons">
                </div>



            </form>


        </div>
-->

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
$mysqli->close();
?>