<?php
session_start();
//make sure to have the closer at the end of html


/*
Okay so php not handling apostrophes in strings is common

if ' or " replace with \' or \"
that should be a simple enough fix
*/


/*
ini_set('display_errors', 1);
error_reporting(E_ALL);
*/

/*DO NOT DELETE THESE */

//include("../db-connect.php");
//include(__DIR__ . "/db-connect.php");
//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//WAIT THIS ONE WORKED??
//local

//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote



//DOCKER CONNECTION DO NOT TOUCH ARF ARF
require 'db_connectionGG.php';






/* FIX: initialize variables */
$userRow = null;
$userPostsResult = null;
$formMessage = "";

if (
    isset($_SESSION['logged_in']) &&
    $_SESSION['logged_in'] === true &&
    isset($_SESSION['user_id'])
) {


    /* FIX: use prepared statement instead of direct SQL concatenation */
    $query_user_info_on_pages = "SELECT * FROM `goblingizmos_users` WHERE user_id = ?";

    //it was the apostrophe

    //honestly all of this could be used

    $stmt = $mysqli->prepare($query_user_info_on_pages);

    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $resultPFP = $stmt->get_result();

    if ($resultPFP && $resultPFP->num_rows > 0) {
        $userRow = $resultPFP->fetch_array(MYSQLI_ASSOC);
    }

    $stmt->close();
}



if (
    isset($_SESSION['access_level']) &&
    ($_SESSION['access_level'] == "admin" || $_SESSION['access_level'] == "user") &&
    isset($_SESSION['user_id'])
) {
    /* FIX: original condition was always true because of || "user" */
    $query_all = "SELECT post_id, goblingizmos_postsbounties.user_id, post_or_bounty, post_category, post_condition, post_boxCondition, post_price, post_location, post_description, post_img, post_sfw_nsfw, post_creation_date, goblingizmos_users.username
    FROM `goblingizmos_postsbounties`
    INNER JOIN goblingizmos_users ON goblingizmos_postsbounties.user_id = goblingizmos_users.user_id
    WHERE goblingizmos_postsbounties.user_id = ?
    ORDER BY post_creation_date DESC";

    //this is showing the posts for each person

    $stmtPosts = $mysqli->prepare($query_all);

    if (!$stmtPosts) {
        $formMessage = "Unable to load posts: " . $mysqli->error;
    } else {
        $stmtPosts->bind_param("i", $_SESSION['user_id']);
        $stmtPosts->execute();
        $userPostsResult = $stmtPosts->get_result();
        $stmtPosts->close();
    }
}

if (
    isset($_POST['submit']) &&
    isset($_SESSION['access_level']) &&
    ($_SESSION['access_level'] == "admin" || $_SESSION['access_level'] == "user") &&
    isset($_SESSION['user_id'])
) {
    //this is the submit button for updating the user's profile

    //I won't take credit for this:

    $usersNewFirstName = trim($_POST['first_name'] ?? "");
    $usersNewLastName = trim($_POST['last_name'] ?? "");
    $usersNewUsername = trim($_POST['uname'] ?? "");
    $usersNewBio = trim($_POST['bio'] ?? "");
    $usersNewEmail = trim($_POST['user_email'] ?? "");
    $userId = $_SESSION['user_id'];

    /* FIX: validate required profile fields */
    if ($usersNewFirstName === "" || $usersNewLastName === "" || $usersNewUsername === "" || $usersNewEmail === "") {
        $formMessage = "First name, last name, username, and email cannot be empty.";
    } else {

        /* FIX: check for duplicate username/email owned by someone else */
        // AI-assisted: checking duplicate username only because current sample data contains repeated emails
        $checkDuplicateQuery = "SELECT user_id FROM `goblingizmos_users` WHERE username = ? AND user_id != ?";
        $checkStmt = $mysqli->prepare($checkDuplicateQuery);

        if (!$checkStmt) {
            die("Prepare failed: " . $mysqli->error);
        }

        $checkStmt->bind_param("si", $usersNewUsername, $userId);
        $checkStmt->execute();
        $duplicateResult = $checkStmt->get_result();

        if ($duplicateResult && $duplicateResult->num_rows > 0) {
            $formMessage = "That username is already in use.";
        } else {

            // Use a prepared statement
            $updateInfoOnProfile = "UPDATE `goblingizmos_users` SET `first_name`= ?, `last_name`= ?, `username`= ?, `user_email`= ?, `user_bio`= ? WHERE user_id=?";

            // Prepare the statement
            $stmt = $mysqli->prepare($updateInfoOnProfile);

            if (!$stmt) {
                die("Prepare failed: " . $mysqli->error);
            }

            // Bind the parameters
            // The values are passed separately, not concatenated into the query
            $stmt->bind_param("sssssi", $usersNewFirstName, $usersNewLastName, $usersNewUsername, $usersNewEmail, $usersNewBio, $userId);

            // Execute the query
            if ($stmt->execute()) {
                $stmt->close();
                $checkStmt->close();
                header("Location: userProfile.php");
                exit();
            } else {
                $formMessage = "Error updating profile: " . $stmt->error;
            }

            $stmt->close();

            //this was copilots doing
            //I needed it because apostrophes were a problem


            /*


            From a website

            $inputname = "O'Brien";
            $res = $mysqli->prepare("SELECT whatever from table1 where lastname = ? ";
            $res->bind->param("s", $inputname);
            $res->execute();





            */

            /* $updateInfoOnProfile = "UPDATE `goblingizmos_users` SET `username`= '$usersNewUsername',`user_pfp`='$target_file', `user_bio`=  '$usersNewBio' WHERE user_id='" . $_SESSION['user_id'] . "'";*/
        }

        $checkStmt->close();
    }

    //this acts as a reload
}





/*
 UPDATE `goblingizmos_users` SET `user_id`='[value-1]',`first_name`='[value-2]',`last_name`='[value-3]',`username`='[value-4]',`password`='[value-5]',`access_level`='[value-6]',`user_email`='[value-7]',`user_pfp`='[value-8]',`user_bio`='[value-9]' WHERE 1
 */


?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goblin Gizmos - User Profile</title>

    <link rel="stylesheet" type="text/css" href="css/style.css">


    <script src="js/goblinScript.js">

    </script>
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
                        $userRow
                    ) {

                        if (!empty($userRow['user_pfp'])) {
                            print "<a href=\"userProfile.php\"><img src=\"" . htmlspecialchars($userRow['user_pfp']) . "\" class=\"userIconImageForSmaller\" alt=\"User's chosen profile picture\" onerror=\"this.src='img/PFP.png';\"></a>";
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

        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

            print "<div class=\"entireAreaUserProfile\">";

            if (!empty($formMessage)) {
                print "<p>" . htmlspecialchars($formMessage) . "</p>";
            }

            print "<div>";
            //User's profile image
        
            "</div>";

            print "<form method=\"POST\" action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" enctype=\"multipart/form-data\">";

            //start of form
        
            if ($userRow) {

                if (!empty($userRow['user_pfp'])) {
                    print "<img src=\"" . htmlspecialchars($userRow['user_pfp']) . "\" class=\"userIconImageForSmaller\" alt=\"User's chosen profile picture\" onerror=\"this.src='img/PFP.png';\">";
                } else {
                    print "<a href=\"userProfile.php\"> <img src=\"img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                }
            } else {
                print "<img src=\"img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\">";
            }



            /*
                        print "<div class='addBoxPost'>";
                        print "<img src=\"img/image.png\" class=\"iconImg\" alt=\"small picture box icon\"> ";
                        print "<label for=\"imagePost\">Change Profile Image</label>";
                        print "<input type=\"file\" id=\"imagePost\" name='post_img'>";
                        print " </div>";

            */



            print "<div class=\"smallerProfileBox\">";

            // FIRST NAME
            print "<div class=\"smallerLabelProfile\">";
            print "<label for=\"first_name\">";
            print "<h3>First Name</h3>";
            print "</label>";
            print "</div>";

            print "<div class=\"evenSmallerProfileBox\">";
            print "<input type=\"text\" id=\"first_name\" name=\"first_name\" value=\"" . htmlspecialchars($userRow['first_name'] ?? '', ENT_QUOTES, 'UTF-8') . "\">";
            print "<img src=\"img/pencilAndPaper.png\" class=\"iconImg\">";
            print "</div>";

            // LAST NAME
            print "<div class=\"smallerLabelProfile\">";
            print "<label for=\"last_name\">";
            print "<h3>Last Name</h3>";
            print "</label>";
            print "</div>";

            print "<div class=\"evenSmallerProfileBox\">";
            print "<input type=\"text\" id=\"last_name\" name=\"last_name\" value=\"" . htmlspecialchars($userRow['last_name'] ?? '', ENT_QUOTES, 'UTF-8') . "\">";
            print "<img src=\"img/pencilAndPaper.png\" class=\"iconImg\">";
            print "</div>";

            print "<div class=\"smallerLabelProfile\">";
            print "<label for=\"uname\">";

            print " <h3>Username</h3>";

            print " </label>";
            print " </div>";


            print " <div class=\"evenSmallerProfileBox\">";
            print "<input type=\"text\" id=\"uname\" name=\"uname\" value=\"" . htmlspecialchars($userRow['username'] ?? '', ENT_QUOTES, 'UTF-8') . "\">";


            print "<img src=\"img/pencilAndPaper.png\" class=\"iconImg\">";
            print "</div>";

            print "<div class=\"smallerLabelProfile\">";
            print "<h3>Change Email</h3>";
            print "</div>";
            print "<div class=\"evenSmallerProfileBox\">";
            print "<input type=\"text\" name=\"user_email\" value=\"" . htmlspecialchars($userRow['user_email'] ?? '', ENT_QUOTES, 'UTF-8') . "\">";
            print "<img src=\"img/pencilAndPaper.png\" class=\"iconImg\">";
            print "</div>";



            print "<div class=\"smallerLabelProfile\">";
            print " <label for=\"bio\">";
            print " <h3>Bio</h3>";
            print " </label>";
            print " </div>";
            print "<div class=\"evenSmallerProfileBox\">";

            print " <input type=\"text\" id=\"bio\" name=\"bio\" value=\"" . htmlspecialchars($userRow['user_bio'] ?? '', ENT_QUOTES, 'UTF-8') . "\">";


            print " <img src=\"img/pencilAndPaper.png\" class=\"iconImg\">";
            print " </div>";



            print "<div class=\"smallerLabelProfile\">";
            print "<h3>Top Categories</h3>";
            print "</div>";

            print "<div class=\"evenSmallerProfileBox\"></div>";

            print "<button type=\"submit\" class=\"goblinButtons\" id=\"holdingSpace\" name=\"submit\">Update</button>";

            //Update user profile
        

            print "</div>";

            print "</div>";

            print "</form>";
            //Oh god will this need JS to add the categories UGHHH-->
        

            print " <div>";



            /* <!--
         <a href="accessibility.php" class="goblinButtons">Accessibility</a>
         -->*/

            print " <a href=\"logOut.php\" class=\"goblinButtons\">Log Out</a>";
            print " <a href=\"settings.php\" class=\"goblinButtons\">Settings</a>";


            print "</div>";

            print " <div>";

            print " <h3>Posts/Bounties</h3>";
            print "</div>";

            print "<div class=\"specificCatItem5\">";

            if ($userRow && isset($userRow['user_id']) && $userRow['user_id'] == $_SESSION['user_id']) {

                if ($userPostsResult && $userPostsResult->num_rows > 0) {
                    while (($postRow = $userPostsResult->fetch_array(MYSQLI_ASSOC))) {

                        print "<div class=\"boxesForEachPost\">";

                        //print "<div class=\"gridItemForPostBox1\">" . $row2['post_id'] . "</div>";
                        //print "<div class=\"gridItemForPostBox2\">" . $row2['user_id'] . "</div>";
        

                        print "<div class=\"gridItemForPostBox3\"><p>" . htmlspecialchars($postRow['username']) . "</p></div>";

                        print "<div class=\"gridItemForPostBox5\"><p>" . htmlspecialchars($postRow['post_or_bounty']) . "</p></div>";

                        // print "<div class=\"gridItemForPostBox6\">" . $row['post_category'] . "</div>";
        

                        if (!empty($postRow['post_price'])) {
                            print "<div class=\"gridItemForPostBox9\"><p>$" . htmlspecialchars($postRow['post_price']) . "</p></div>";
                        }

                        if (!empty($postRow['post_location'])) {
                            print "<div class=\"gridItemForPostBox10\"><p>" . htmlspecialchars($postRow['post_location']) . "</p></div>";
                            //doesn't always exist
                        }


                        print "<div class=\"gridItemForPostBox11\"><p>" . htmlspecialchars($postRow['post_description']) . "</p></div>";
                        //this always exists
        

                        if (!empty($postRow['post_img'])) {
                            print "<div class=\"gridItemForPostBox12\"><img src=\"" . htmlspecialchars($postRow['post_img']) . "\" alt=\"post image\"></div>";
                            //doesn't always exist
                        }

                        if (!empty($postRow['post_sfw_nsfw'])) {
                            print "<div class=\"gridItemForPostBox13\"><p>" . htmlspecialchars($postRow['post_sfw_nsfw']) . "</p></div>";
                            //doesn't always exist
                        }


                        print "<div class=\"gridItemForPostBox14\"><p>" . htmlspecialchars($postRow['post_creation_date']) . "</p></div>";
                        //always exists
        

                        print "</div>";
                    }
                } else {
                    print "<p>No posts or bounties yet.</p>";
                }
            }

            print " </div>";

            print " </div>";

        } else {

            print "<div  class=\"signUpForms\">";
            print "<p>Please log in to see account information.</p>";
            print "<a href=\"signIn.php\">Sign In</a>";
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