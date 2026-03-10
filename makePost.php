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
include("/Applications/XAMPP/htdocs/dig3134c/db-connect.php");
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


if (!isset($_SESSION['logged_in'])) {
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




    if (isset($_POST['submit'])) {

        //these are the only ones listed bc everything else can be blank


        if (
            isset($_POST['post_or_bounty']) && isset($_POST['post_category']) && isset($_POST['post_description']) &&


            (!empty($_POST['post_or_bounty'])) && (!empty($_POST['post_category'])) && (!empty($_POST['post_description']))

        ) {

            $postBounty = $_POST['post_or_bounty'];

            if ($postBounty == 'post') {
                $postBountyOptionSelected = "post";
            } else if ($postBounty == 'bounty') {
                $postBountyOptionSelected = "bounty";
            }

            //IT WORKED HOLY FUCK
            /*To anyone reading this, this is about to be so very long */
            //HEHE IT WOOORKS

            $postCategory = $_POST['post_category'];

            if ($postCategory == 'autographs') {
                $postCategoryOptionSelected = "autographs";
            } else if ($postCategory == 'books') {
                $postCategoryOptionSelected = "books";
            } else if ($postCategory == 'caps') {
                $postCategoryOptionSelected = "caps";
            } else if ($postCategory == 'cans') {
                $postCategoryOptionSelected = "cans";
            } else if ($postCategory == 'charms') {
                $postCategoryOptionSelected = "charms";
            } else if ($postCategory == 'coins') {
                $postCategoryOptionSelected = "coins";
            } else if ($postCategory == 'figures') {
                $postCategoryOptionSelected = "figures";
            } else if ($postCategory == 'jewelry') {
                $postCategoryOptionSelected = "jewelry";
            } else if ($postCategory == 'magnets') {
                $postCategoryOptionSelected = "magnets";
            } else if ($postCategory == 'minerals') {
                $postCategoryOptionSelected = "minerals";
            } else if ($postCategory == 'perfume') {
                $postCategoryOptionSelected = "perfume";
            } else if ($postCategory == 'plates') {
                $postCategoryOptionSelected = "plates";
            } else if ($postCategory == 'cards') {
                $postCategoryOptionSelected = "cards";
            } else if ($postCategory == 'plushies') {
                $postCategoryOptionSelected = "plushies";
            } else if ($postCategory == 'prints') {
                $postCategoryOptionSelected = "prints";
            } else if ($postCategory == 'stamps') {
                $postCategoryOptionSelected = "stamps";
            } else if ($postCategory == 'tickets') {
                $postCategoryOptionSelected = "tickets";
            } else if ($postCategory == 'games') {
                $postCategoryOptionSelected = "games";
            } else if ($postCategory == 'vinyls') {
                $postCategoryOptionSelected = "vinyls";
            } else if ($postCategory == 'other') {
                $postCategoryOptionSelected = "other";
            }


            if (!isset($_POST['post_condition'])) {
                $postConditionOptionSelected = NULL;
            }

            if (isset($_POST['post_condition'])) {


                if ($postCondition == 'new') {
                    $postConditionOptionSelected = "new";
                } else if ($postCondition == 'likeNew') {
                    $postConditionOptionSelected = "like new";
                } else if ($postCondition == 'used') {
                    $postConditionOptionSelected = "used";
                } else if ($postCondition == 'damaged') {
                    $postConditionOptionSelected = "damaged";
                }
            }


            if (!isset($_POST['post_boxCondition'])) {
                $postBoxConditionOptionSelected = NULL;
            }


            if (isset($_POST['post_boxCondition'])) {


                if ($postBoxCondition == 'boxnew') {
                    $postBoxConditionOptionSelected = "new";
                } else if ($postBoxCondition == 'boxlikeNew') {
                    $postBoxConditionOptionSelected = "like new";
                } else if ($postBoxCondition == 'boxused') {
                    $postBoxConditionOptionSelected = "used";
                } else if ($postBoxCondition == 'boxdamaged') {
                    $postBoxConditionOptionSelected = "damaged";
                }

            }


            //POST PRICE.  MY BITCH WIFE
            //THIS WAS THE ISSUE
            //STUPID ASS NUMBER

            if (isset($_POST['post_price']) && trim($_POST['post_price']) !== '') {
                $postPrice = (int) $_POST['post_price']; // or (float) / filter_var
            }
            $postPriceSQL = $postPrice;








            if (!isset($_POST['post_sfw_nsfw'])) {
                $postSfwNsfwOptionSelected = NULL;
            }


            if (isset($_POST['post_sfw_nsfw'])) {

                if ($postSFWNSFW == 'sfw') {
                    $postSfwNsfwOptionSelected = "sfw";
                } else if ($postSFWNSFW == 'nsfw') {
                    $postSfwNsfwOptionSelected = "nsfw";
                }
            }




            //$target_dir = "/home/ad/je686804/public_html/goblingizmos/uploads/";
            //uhhh remote??


            $target_file = NULL;
            if (!empty($_FILES['post_img']) && $_FILES['post_img']['error'] === UPLOAD_ERR_OK) {
                $target_dir = "uploads/";
                $target_file = $target_dir . basename(str_replace(' ', '_', $_FILES['post_img']["name"]));
                $uploadOk = 1;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));



                //checks if file is an image or if its fake (whatever that means)
                if (!empty($_FILES['post_img'])) {
                    $check = getimagesize($_FILES["post_img"]["tmp_name"]);
                    if ($check !== false) {
                        echo "File is an image - " . $check["mime"] . ".";
                        $uploadOk = 1;
                    } else {
                        echo "File is not an image.";
                        $uploadOk = 0;
                    }
                }
                if ($_FILES['post_img']["size"] > 800000) {
                    echo "Your file is too large.";
                    $uploadOk = 0;
                }

                if (file_exists($target_file)) {
                    echo "This file already exists";
                    $uploadOk = 0;
                }


                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                    echo "Please choose an image that is either JPG, JPEG, or a PNG file.";
                    $uploadOk = 0;
                }

                if ($uploadOk == 0) {
                    echo "There was an issue with your file";
                } else {
                    if (move_uploaded_file($_FILES['post_img']["tmp_name"], $target_file)) {
                        echo "The file was uploaded.";

                    }
                }


            }


            if ((($target_file != NULL) && ($uploadOk == 1)) || $target_file == NULL) {

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
                $stmt->bind_param(
                    "isssssssss",
                    $_SESSION['user_id'],
                    $postBountyOptionSelected,
                    $postCategoryOptionSelected,
                    $postConditionOptionSelected,
                    $postBoxConditionOptionSelected,
                    $postPriceSQL,
                    $_POST['post_location'],
                    $_POST['post_description'],
                    $target_file,
                    $postSfwNsfwOptionSelected
                );

                $stmt->execute();
                $stmt->close();







                if (($postCategoryOptionSelected == 'autographs') && ($postBountyOptionSelected == 'post')) {
                    //so this is saying that if its a category and also an autograph, itll take you
                    //straight to it

                    header("Location: categories/autographsCategory.php");
                    //okay this works and still submits
                    //yay
                } else if (($postCategoryOptionSelected == 'books') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/booksCategories.php");
                } else if (($postCategoryOptionSelected == 'caps') && ($postBountyOptionSelected == 'post')) {

                    header("Location: categories/bottleCapsCategories.php");
                } else if (($postCategoryOptionSelected == 'cans') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/cansCategories.php");
                } else if (($postCategoryOptionSelected == 'charms') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/charmsCategories.php");
                } else if (($postCategoryOptionSelected == 'coins') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/coinsCategories.php");
                } else if (($postCategoryOptionSelected == 'figures') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/figuresCategories.php");
                } else if (($postCategoryOptionSelected == 'jewelry') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/jewelryCategories.php");
                } else if (($postCategoryOptionSelected == 'magnets') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/magnetsCategories.php");
                } else if (($postCategoryOptionSelected == 'minerals') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/mineralsCategories.php");
                } else if (($postCategoryOptionSelected == 'perfume') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/perfumeCategories.php");
                } else if (($postCategoryOptionSelected == 'plates') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/platesCategories.php");
                } else if (($postCategoryOptionSelected == 'cards') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/cardsCategories.php");
                } else if (($postCategoryOptionSelected == 'plushies') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/plushiesCategories.php");
                } else if (($postCategoryOptionSelected == 'prints') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/printsCategories.php");
                } else if (($postCategoryOptionSelected == 'stamps') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/stampsCategories.php");
                } else if (($postCategoryOptionSelected == 'tickets') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/ticketsCategories.php");
                } else if (($postCategoryOptionSelected == 'games') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/videoGamesCategories.php");
                } else if (($postCategoryOptionSelected == 'vinyls') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/vinylsCategories.php");
                } else if (($postCategoryOptionSelected == 'other') && ($postBountyOptionSelected == 'post')) {


                    header("Location: categories/otherCategories.php");
                } else if ($postBountyOptionSelected == 'bounty') {


                    header("Location: search.php");

                    //all bounties lead to search
                    //get it? like all drains lead to the ocean?
                    //or all roads lead to Rome??
                    //guys please laugh
                }
            } else if ($uploadOk == 0) {
                //nothin.
            }






        } else if ((isset($_POST['submit'])) && empty($_POST['post_or_bounty']) || empty($_POST['post_category']) || empty($_POST['post_description'])) {

            print "<p>Please make sure that the 'post or bounty' box, the 'post category' box, and the 'post desctiption' box are filled out.  </p>";

        }



    }






}

















?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goblin Gizmos - User Profile</title>

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


            if (!isset($_SESSION['logged_in'])) {
                print "<p>To make a post, first make an account with us! It's free! </p>";
                print "<a href='signIn.php'>Make an Account</a>";
            }

            if (isset($_SESSION['logged_in'])) {

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

                print "<input type=\"radio\" id=\"post_condition\" name=\"post_condition\" value=\"new\">";
                print "<label for=\"new\">New</label>";


                print "<input type=\"radio\" id=\"post_condition\" name=\"post_condition\" value=\"likeNew\">";
                print "<label for=\"likeNew\">Like New</label>";

                print "<input type=\"radio\" id=\"post_condition\" name=\"post_condition\" value=\"used\">";
                print "<label for=\"used\">Used</label>";

                print "<input type=\"radio\" id=\"post_condition\" name=\"post_condition\" value=\"damaged\">";
                print "<label for=\"damaged\">Damaged</label>";

                /*Okay I originally had n/a but if there's no condition, put nothing??? */

                print "<h3>Box Condition</h3>";

                print "<input type=\"radio\" id=\"post_boxCondition\" name=\"post_boxCondition\" value=\"boxnew\">";
                print "<label for=\"boxnew\">New</label>";

                print "<input type=\"radio\" id=\"post_boxCondition\" name=\"post_boxCondition\" value=\"boxlikeNew\">";
                print "<label for=\"boxlikeNew\">Like New</label>";

                print "<input type=\"radio\" id=\"post_boxCondition\" name=\"post_boxCondition\" value=\"boxused\">";
                print "<label for=\"boxused\">Used</label>";

                print "<input type=\"radio\" id=\"post_boxCondition\" name=\"post_boxCondition\" value=\"boxdamaged\">";
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

                print "<input type=\"radio\" id=\"post_sfw_nsfw\" name=\"post_sfw_nsfw\" value=\"sfw\"><label for=\"sfw\">SFW</label>";

                print "<input type=\"radio\" id=\"post_sfw_nsfw\" name=\"post_sfw_nsfw\" value=\"nsfw\"><label for=\"sfw\">NSFW</label>";


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
$mysqli->close();
?>