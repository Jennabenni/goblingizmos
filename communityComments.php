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

ini_set('display_errors', 1);
error_reporting(E_ALL);

//test




/* FIX: initialize row so it exists even if no query runs */
$row = null;
$viewedCompostComments = null;


if (
    isset($_SESSION['logged_in']) &&
    $_SESSION['logged_in'] === true &&
    isset($_SESSION['user_id'])
) {


    //This is for the pfp right??

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


if (isset($_GET['compost_id'])) {
    $query_compostSelect = "SELECT compost_id, goblingizmos_community.user_id, compost_category, compost_description, compost_img, compost_sfw_nsfw, compost_creation_date, goblingizmos_users.username, goblingizmos_users.user_pfp FROM `goblingizmos_community` INNER JOIN goblingizmos_users ON goblingizmos_community.user_id=goblingizmos_users.user_id WHERE compost_id = '" . $_GET['compost_id'] . "'";




    $resultCompostUser = $mysqli->query($query_compostSelect);

}
$commentLikeValue = "0";


if (($_SESSION['logged_in'] == 'true') && isset($_POST['submit']) && !empty($_POST['comment_text'])) {

    $commentPosting = "INSERT INTO `goblingizmos_comments` (`user_id`, `comment_compost_id`, `compost_id`, `comment_text`, `comment_compost_likes`, `comment_compost_creation_date`) VALUES ('', NULL, '" . $_GET['compost_id'] . "' , '', NULL, current_timestamp())";

    //ugh





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


        if ($resultCompostUser) {

            while ($row2 = $resultCompostUser->fetch_array(MYSQLI_ASSOC)) {


                print "<div class=\"boxesForEachPost\">";



                print "<div class=\"gridItemForPostBox3\">" . "<p>" . ($row2['username']) . "</p>" . "</div>";
                print "<div class=\"gridItemForPostBox4\">";

                print "<div class=\"gridItemForPostBox5\">" . "<p>" . ($row2['compost_category']) . "</p>" . "</div>";


                print "<a href=\"userProfileView.php?user_id=" . ($row2['user_id']) . "\">";
                if (!empty($row2['user_pfp'])) {
                    print "<img src=\"" . htmlspecialchars($row2['user_pfp']) . "\" alt=\"profile image of user\" onerror=\"this.src='img/PFP.png';\">";
                } else {
                    print "<img src=\"img/PFP.png\" alt=\"profile image of user\">";
                }
                print "</a>";
                print "</div>";



                print "<div class=\"gridItemForPostBox11\">" . "<p>" . $row2['compost_description'] . "</p>" . "</div>";



                if (!empty($row2['compost_img'])) {
                    print "<div class=\"gridItemForPostBox12\">" . "<img src=\"" . ($row2['compost_img']) . "\" alt=\"community post image\">" . "</div>";

                }

                if (!empty($row2['compost_sfw_nsfw'])) {
                    print "<div class=\"gridItemForPostBox13\">" . "<p>" . ($row2['compost_sfw_nsfw']) . "</p>" .
                        "</div>";

                }


                print "<div class=\"gridItemForPostBox14\">" . "<p>" . ($row2['compost_creation_date']) . "</p>" . "</div>";





                print "</div>";



            }



        }




        print "<div class = \"FAQ\">";

        /*

        Okay so the new table has

        - user_id (Foreign Key)
        - comment_compost_id (Primary key)
        - comment_text
        - comment_compost_likes
        - comment_compost_creation_date
         */

        print "<form method=\"POST\" action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\">";
        //didnt do the enctype because I didnt allow for media uploads
        
        //display user pfp
//textbox
//submit
        

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

        print "<textarea placeholder='Input Text' class='textAreaSize' rows='7' cols='50' id='comment_text' name ='comment_text'></textarea>";


        print " <button type=\"submit\" class=\"goblinButtons\" id=\"holdingSpace\" name=\"submit\">Comment</button>";


        print "</form>";





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