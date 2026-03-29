<?php
session_start();
//make sure to have the closer at the end of html

/*
Error display stuff


ini_set('display_errors', 1);
error_reporting(E_ALL);
*/


/*DO NOT DELETE THESE */

//include("../db-connect.php");
//include(__DIR__ . "/db-connect.php");
include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//WAIT THIS ONE WORKED??
//local


//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote


/*

Okay.  I think the problem I'm having is that it's not grabbing the
proper info and submitting it properly.  These goddamn radio buttons.


would variables work?

like, if the variable == post, then
if $post_or_bounty == "post" then do smth??
if isset($post_or_bounty && $post_or_bounty == "post") THEN WHAT
maybe instead of $_POST['post_or_bounty], it has to be $_POST[$post_or_bounty]??? so its the
variable instead of.... the name of both radio buttons?? how do I know what the value is

what w3schools has
<input type="radio" name="gender" <?php if (isset($gender) && $gender=="other") echo "checked";?> value="other">Other


*/

//get rid of this before upload
//error_reporting(E_ALL);

ini_set('display_errors', 1);
error_reporting(E_ALL);

/* FIX: initialize row and message */
$row = null;
$formMessage = "";

/* FIX: load logged-in user safely for header icon */
if (
    isset($_SESSION['logged_in']) &&
    $_SESSION['logged_in'] === true &&
    isset($_SESSION['user_id'])
) {
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

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    //nothin, we block them down there in the form
} else {

    //does anyone else see a sexy bitch in this room or is it just me

    //these should be all the radio buttons and other freaks
    $postBounty = "";
    $postCategory = "";

    $postCondition = NULL;
    $postBoxCondition = NULL;
    $postSFWNSFW = NULL;

    //THE ISSUE IS A STUPID INTEGER
    $postPrice = 0;
    //Can't be null, since its not a string

    /* FIX: initialize option variables */
    $postBountyOptionSelected = NULL;
    $postCategoryOptionSelected = NULL;
    $postConditionOptionSelected = NULL;
    $postBoxConditionOptionSelected = NULL;
    $postSfwNsfwOptionSelected = NULL;

    /* FIX: whitelists */
    $allowedPostBounty = array("post", "bounty");
    $allowedCategories = array(
        "autographs",
        "books",
        "caps",
        "cans",
        "charms",
        "coins",
        "figures",
        "jewelry",
        "magnets",
        "minerals",
        "perfume",
        "plates",
        "cards",
        "plushies",
        "prints",
        "stamps",
        "tickets",
        "games",
        "vinyls",
        "other"
    );
    $allowedConditions = array(
        "new" => "new",
        "likeNew" => "like new",
        "used" => "used",
        "damaged" => "damaged"
    );
    $allowedBoxConditions = array(
        "boxnew" => "new",
        "boxlikeNew" => "like new",
        "boxused" => "used",
        "boxdamaged" => "damaged"
    );
    $allowedContentLevels = array(
        "sfw" => "sfw",
        "nsfw" => "nsfw"
    );

    if (isset($_POST['submit'])) {

        //these are the only ones listed bc everything else can be blank
        if (
            isset($_POST['post_or_bounty']) &&
            isset($_POST['post_category']) &&
            isset($_POST['post_description']) &&
            (!empty($_POST['post_or_bounty'])) &&
            (!empty($_POST['post_category'])) &&
            (!empty(trim($_POST['post_description'])))
        ) {

            $postBounty = $_POST['post_or_bounty'];
            if (in_array($postBounty, $allowedPostBounty, true)) {
                $postBountyOptionSelected = $postBounty;
            }

            //IT WORKED HOLY FUCK
            /*To anyone reading this, this is about to be so very long */
            //HEHE IT WOOORKS

            $postCategory = $_POST['post_category'];
            if (in_array($postCategory, $allowedCategories, true)) {
                $postCategoryOptionSelected = $postCategory;
            }

            if (isset($_POST['post_condition'])) {
                $postCondition = $_POST['post_condition'];

                if (isset($allowedConditions[$postCondition])) {
                    $postConditionOptionSelected = $allowedConditions[$postCondition];
                }
            }

            if (isset($_POST['post_boxCondition'])) {
                $postBoxCondition = $_POST['post_boxCondition'];

                if (isset($allowedBoxConditions[$postBoxCondition])) {
                    $postBoxConditionOptionSelected = $allowedBoxConditions[$postBoxCondition];
                }
            }

            //POST PRICE.  MY BITCH WIFE
            //THIS WAS THE ISSUE
            //STUPID ASS NUMBER

            if (isset($_POST['post_price']) && trim($_POST['post_price']) !== '') {
                $postPrice = (int) $_POST['post_price']; // or (float) / filter_var
            }
            $postPriceSQL = $postPrice;

            if (isset($_POST['post_sfw_nsfw'])) {
                $postSFWNSFW = $_POST['post_sfw_nsfw'];

                if (isset($allowedContentLevels[$postSFWNSFW])) {
                    $postSfwNsfwOptionSelected = $allowedContentLevels[$postSFWNSFW];
                }
            }

            //$target_dir = "/home/ad/je686804/public_html/goblingizmos/uploads/";
            //uhhh remote??

            $target_file = NULL;
            $uploadOk = 1;

            if (!empty($_FILES['post_img']) && $_FILES['post_img']['error'] === UPLOAD_ERR_OK) {
                $target_dir = "uploads/";

                /* FIX: create uploads folder if it does not exist */
                if (!is_dir($target_dir)) {
                    mkdir($target_dir, 0777, true);
                }

                /* FIX: unique filenames */
                $clean_file_name = str_replace(' ', '_', basename($_FILES['post_img']["name"]));
                $target_file = $target_dir . uniqid() . "_" . $clean_file_name;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                //checks if file is an image or if its fake (whatever that means)
                $check = getimagesize($_FILES["post_img"]["tmp_name"]);
                if ($check === false) {
                    $formMessage = "File is not an image.";
                    $uploadOk = 0;
                }

                if ($_FILES['post_img']["size"] > 800000) {
                    $formMessage = "Your file is too large.";
                    $uploadOk = 0;
                }

                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    $formMessage = "Please choose an image that is either JPG, JPEG, or a PNG file.";
                    $uploadOk = 0;
                }

                if ($uploadOk == 1) {
                    if (!move_uploaded_file($_FILES['post_img']["tmp_name"], $target_file)) {
                        $formMessage = "There was an issue with your file";
                        $uploadOk = 0;
                    }
                }
            }

            if (
                !empty($postBountyOptionSelected) &&
                !empty($postCategoryOptionSelected) &&
                ((($target_file != NULL) && ($uploadOk == 1)) || $target_file == NULL)
            ) {

                //IT WORKS
                //IMG IS OPTIONAL! IF THERES AN ISSUE IT DOESNT SUBMIT! LETS FUCKIN GO
                //Pardon my language
/*
                $insert_post_query = "INSERT INTO `goblingizmos_postsbounties` (`post_id`, `user_id`, `post_or_bounty`, `post_category`, `post_condition`, `post_boxCondition`, `post_price`, `post_location`, `post_description`,`post_img`, `post_sfw_nsfw`, `post_creation_date`) VALUES (NULL, '" . $_SESSION['user_id'] . "', '$postBountyOptionSelected', '$postCategoryOptionSelected','$postConditionOptionSelected', '$postBoxConditionOptionSelected',$postPriceSQL,'" . $_POST['post_location'] . "', '" . $_POST['post_description'] . "'," . ($target_file !== NULL ? "'" . $target_file . "'" : "NULL") . ", '$postSfwNsfwOptionSelected', CURRENT_TIMESTAMP)";

                /*The usual problem children:

                - post price (NUMBER it doesnt like strings)
                - file upload
                - Any of the radio buttons that are NOT postBounty,

                */


                //$mysqli->query($insert_post_query);


                //this should already send it..... so i shouldnt have to check for isset post submit


                //Previous code is above, adjusted code is below
//I'm keeping both because Copilot helped me with the stuff below
//I don't want to go past the point of no return

                $insert_post_query = "INSERT INTO `goblingizmos_postsbounties` (`post_id`, `user_id`, `post_or_bounty`, `post_category`, `post_condition`, `post_boxCondition`, `post_price`, `post_location`, `post_description`,`post_img`, `post_sfw_nsfw`, `post_creation_date`) VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, CURRENT_TIMESTAMP)";

                $stmt = $mysqli->prepare($insert_post_query);

                if (!$stmt) {
                    die("Prepare failed: " . $mysqli->error);
                }

                $postLocation = trim($_POST['post_location'] ?? "");
                $postDescription = trim($_POST['post_description'] ?? "");

                $stmt->bind_param(
                    "issssissss",
                    $_SESSION['user_id'],
                    $postBountyOptionSelected,
                    $postCategoryOptionSelected,
                    $postConditionOptionSelected,
                    $postBoxConditionOptionSelected,
                    $postPriceSQL,
                    $postLocation,
                    $postDescription,
                    $target_file,
                    $postSfwNsfwOptionSelected
                );

                if (!$stmt->execute()) {
                    $formMessage = "Post insert failed: " . $stmt->error;
                }

                $stmt->close();

                if (empty($formMessage)) {
                    /* FIX: redirect with a map instead of a huge else-if chain */
                    $postCategoryRedirects = array(
                        "autographs" => "categories/autographsCategory.php",
                        "books" => "categories/booksCategories.php",
                        "caps" => "categories/bottleCapsCategories.php",
                        "cans" => "categories/cansCategories.php",
                        "charms" => "categories/charmsCategories.php",
                        "coins" => "categories/coinsCategories.php",
                        "figures" => "categories/figuresCategories.php",
                        "jewelry" => "categories/jewelryCategories.php",
                        "magnets" => "categories/magnetsCategories.php",
                        "minerals" => "categories/mineralsCategories.php",
                        "perfume" => "categories/perfumeCategories.php",
                        "plates" => "categories/platesCategories.php",
                        "cards" => "categories/cardsCategories.php",
                        "plushies" => "categories/plushiesCategories.php",
                        "prints" => "categories/printsCategories.php",
                        "stamps" => "categories/stampsCategories.php",
                        "tickets" => "categories/ticketsCategories.php",
                        "games" => "categories/videoGamesCategories.php",
                        "vinyls" => "categories/vinylsCategories.php",
                        "other" => "categories/otherCategories.php"
                    );

                    if ($postBountyOptionSelected == 'bounty') {
                        header("Location: search.php");

                        //all bounties lead to search
                        //get it? like all drains lead to the ocean?
                        //or all roads lead to Rome??
                        //guys please laugh
                        exit();
                    } else if (isset($postCategoryRedirects[$postCategoryOptionSelected])) {
                        //so this is saying that if its a category and also an autograph, itll take you
                        //straight to it

                        header("Location: " . $postCategoryRedirects[$postCategoryOptionSelected]);
                        //okay this works and still submits
                        //yay
                        exit();
                    }
                }
            } else if ($uploadOk == 0) {
                //nothin.
            }
        } else if ((isset($_POST['submit'])) && (empty($_POST['post_or_bounty']) || empty($_POST['post_category']) || empty($_POST['post_description']))) {

            $formMessage = "Please make sure that the 'post or bounty' box, the 'post category' box, and the 'post desctiption' box are filled out.";

        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Make Post</title>

    <link rel="stylesheet" type="text/css" href="css/style.css">


    <script src="js/goblinScript.js"></script>


</head>

<!--

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
                <div class="headerGridItem">

                    <?php
                    /*
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
                    }*/

                    if (
                        isset($_SESSION['logged_in']) &&
                        $_SESSION['logged_in'] === true &&
                        $row
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

        <!--Post and Bounty are the same thing, the difference is INTENT-->

        <!--All of this will def need to be PHP but I can do front end stuff-->

        <div class="FAQ">

            <?php

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
            */

            if (!empty($formMessage)) {
                print "<p>" . htmlspecialchars($formMessage) . "</p>";
            }

            if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
                print "<p>To make a post, first make an account with us! It's free! </p>";
                print "<a href='signIn.php'>Make an Account</a>";
            }

            if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {

                print "<form method=\"POST\" action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" enctype=\"multipart/form-data\">";
                //the enctype allows for file uploads
            
                /* I'm making all these fucks input tags
                 */

                print "<h3>Is this a Category Post or a Bounty?</h3>";
                print "<p>Are you showing off (post) or looking for this item (bounty)?</p>";

                //lock in soldier
            
                print "<input type=\"radio\" id=\"postChecked\" name=\"post_or_bounty\" value=\"post\">";
                print "<label for=\"post\">Post</label>";

                print "<input type=\"radio\" id=\"bountyChecked\" name=\"post_or_bounty\" value=\"bounty\">";
                print "<label for=\"bounty\">Bounty</label>";

                print "<h3>Category</h3>";

                /*
                print "<input type=\"text\" id='post_category' placeholder=\"Temp cat placeholder\" name='post_category'>";
*/

                //good lord
                print " <label for=\"category\"></label>";
                print "<select name=\"post_category\" id=\"post_category\" class=\"searchBarItems\">";

                print "<option value=\"other\"></option>";
                //By default if they dont pick one, it goes to other ^
                print "<option value=\"autographs\">Autographs</option>";
                print "<option value=\"books\">Books</option>";
                print "<option value=\"caps\">Bottle Caps</option>";
                print "<option value=\"cans\">Cans</option>";
                print "<option value=\"charms\">Charms</option>";
                print "<option value=\"coins\">Coins</option>";
                print "<option value=\"figures\">Figures</option>";
                print "<option value=\"jewelry\">Jewelry</option>";
                print "<option value=\"magnets\">Magnets</option>";
                print "<option value=\"minerals\">Minerals</option>";
                print "<option value=\"perfume\">Perfume</option>";
                print "<option value=\"plates\">Plates</option>";
                print "<option value=\"cards\">Playing Cards</option>";
                print "<option value=\"plushies\">Plushies</option>";
                print "<option value=\"prints\">Prints</option>";
                print "<option value=\"stamps\">Stamps</option>";
                print "<option value=\"tickets\">Tickets</option>";
                print "<option value=\"games\">Video Games</option>";
                print "<option value=\"vinyls\">Vinyls</option>";
                print "<option value=\"other\">Other</option>";

                print "</select>";

                print "<h3>Item Condition</h3>";

                print "<input type=\"radio\" id=\"post_condition_new\" name=\"post_condition\" value=\"new\">";
                print "<label for=\"new\">New</label>";

                print "<input type=\"radio\" id=\"post_condition_likeNew\" name=\"post_condition\" value=\"likeNew\">";
                print "<label for=\"likeNew\">Like New</label>";

                print "<input type=\"radio\" id=\"post_condition_used\" name=\"post_condition\" value=\"used\">";
                print "<label for=\"used\">Used</label>";

                print "<input type=\"radio\" id=\"post_condition_damaged\" name=\"post_condition\" value=\"damaged\">";
                print "<label for=\"damaged\">Damaged</label>";

                /*Okay I originally had n/a but if there's no condition, put nothing??? */

                print "<h3>Box Condition</h3>";

                print "<input type=\"radio\" id=\"post_boxCondition_new\" name=\"post_boxCondition\" value=\"boxnew\">";
                print "<label for=\"boxnew\">New</label>";

                print "<input type=\"radio\" id=\"post_boxCondition_likeNew\" name=\"post_boxCondition\" value=\"boxlikeNew\">";
                print "<label for=\"boxlikeNew\">Like New</label>";

                print "<input type=\"radio\" id=\"post_boxCondition_used\" name=\"post_boxCondition\" value=\"boxused\">";
                print "<label for=\"boxused\">Used</label>";

                print "<input type=\"radio\" id=\"post_boxCondition_damaged\" name=\"post_boxCondition\" value=\"boxdamaged\">";
                print "<label for=\"boxdamaged\">Damaged</label>";

                print "<h3>Price/Currency (if one)</h3>";

                print "<label for='post_price'></label>";
                print "<input type=\"text\" id='post_price' name='post_price' placeholder='Enter Price'>";

                print "<h3>Location (Optional)</h3>";
                print "<input type=\"text\" id='post_location' placeholder=\"Enter Location\" name='post_location'>";

                print "<h3>Description</h3>";
                print "<textarea placeholder='Input Text' class='textAreaSize' rows='7' cols='50' id='post_description' name ='post_description'></textarea>";

                print "<h3>Image</h3>";

                /*This will need some love...... how in the hell
                do I put pictures from my computer to database?? */

                print "<div class='addBoxPost'>";
                print "<img src=\"img/image.png\" class=\"iconImg\" alt=\"small picture box icon\"> ";
                print "<label for=\"imagePost\"></label>";
                print "<input type=\"file\" id=\"imagePost\" name='post_img'>";
                print " </div>";

                /*
                                print "<input type=\"text\" id='post_img' placeholder=\"Temp img placeholder\" name='post_img'>";
                */
                print "<h3>Content Level</h3>";
                print "<p>Can this be shown to a child? (SFW = Safe for work)</p>";
                print "<p>If not, mark the post as 'NSFW' (NSFW = Not safe for work)</p>";

                print "<input type=\"radio\" id=\"post_sfw_nsfw_sfw\" name=\"post_sfw_nsfw\" value=\"sfw\"><label for=\"sfw\">SFW</label>";

                print "<input type=\"radio\" id=\"post_sfw_nsfw_nsfw\" name=\"post_sfw_nsfw\" value=\"nsfw\"><label for=\"sfw\">NSFW</label>";

                print "<div>";

                print " <button type=\"submit\" class=\"goblinButtons\" id=\"holdingSpace\" name=\"submit\">Post</button>";
                print "</div>";

                print "</form>";
                //UGH
            
            }
            ?>

        </div>
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