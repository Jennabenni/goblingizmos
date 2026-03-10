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


/*WHY DO THESE ALL HAVE BUTTONS FUCK */


ini_set('display_errors', 1);
error_reporting(E_ALL);





if (isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {


    $query_user_info_on_pages = "SELECT * FROM `goblingizmos_users` WHERE user_id = '" . $_SESSION['user_id'] . "'";


    //honestly all of this could be used

    $result = $mysqli->query($query_user_info_on_pages);



}



$comSFWNSFW = NULL;
$compostCategory = "";
$compostLikes = 0;







if (isset($_POST['submit'])) {

    if (isset($_SESSION['logged_in']) && (isset($_POST['submit']))) {

        $compostCategory = $_POST['compost_category'];

        if ($compostCategory == 'autographs') {
            $compostCategoryOptionSelected = "autographs";
        } else if ($compostCategory == 'books') {
            $compostCategoryOptionSelected = "books";
        } else if ($compostCategory == 'caps') {
            $compostCategoryOptionSelected = "caps";
        } else if ($compostCategory == 'cans') {
            $compostCategoryOptionSelected = "cans";
        } else if ($compostCategory == 'charms') {
            $compostCategoryOptionSelected = "charms";
        } else if ($compostCategory == 'coins') {
            $compostCategoryOptionSelected = "coins";
        } else if ($compostCategory == 'figures') {
            $compostCategoryOptionSelected = "figures";
        } else if ($compostCategory == 'jewelry') {
            $compostCategoryOptionSelected = "jewelry";
        } else if ($compostCategory == 'magnets') {
            $compostCategoryOptionSelected = "magnets";
        } else if ($compostCategory == 'minerals') {
            $compostCategoryOptionSelected = "minerals";
        } else if ($compostCategory == 'perfume') {
            $compostCategoryOptionSelected = "perfume";
        } else if ($compostCategory == 'plates') {
            $compostCategoryOptionSelected = "plates";
        } else if ($compostCategory == 'cards') {
            $compostCategoryOptionSelected = "cards";
        } else if ($compostCategory == 'plushies') {
            $compostCategoryOptionSelected = "plushies";
        } else if ($compostCategory == 'prints') {
            $compostCategoryOptionSelected = "prints";
        } else if ($compostCategory == 'stamps') {
            $compostCategoryOptionSelected = "stamps";
        } else if ($compostCategory == 'tickets') {
            $compostCategoryOptionSelected = "tickets";
        } else if ($compostCategory == 'games') {
            $compostCategoryOptionSelected = "games";
        } else if ($compostCategory == 'vinyls') {
            $compostCategoryOptionSelected = "vinyls";
        } else if ($compostCategory == 'other') {
            $compostCategoryOptionSelected = "other";
        }

        $comSFWNSFW = $_POST['compost_sfw_nsfw'] ?? NULL;

        if (!isset($_POST['compost_sfw_nsfw'])) {
            $compostSfwNsfwOptionSelected = NULL;
        } else if ($comSFWNSFW == 'sfw') {
            $compostSfwNsfwOptionSelected = "sfw";
        } else if ($comSFWNSFW == 'nsfw') {
            $compostSfwNsfwOptionSelected = "nsfw";
        }

        $target_file = NULL;
        $uploadOk = 1;

        if (!empty($_FILES['compost_img']) && $_FILES['compost_img']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename(str_replace(' ', '_', $_FILES['compost_img']["name"]));
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

            $check = getimagesize($_FILES["compost_img"]["tmp_name"]);
            if ($check === false) {
                echo "File is not an image.";
                $uploadOk = 0;
            }

            if ($_FILES['compost_img']["size"] > 800000) {
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

            if ($uploadOk == 1) {
                if (move_uploaded_file($_FILES['compost_img']["tmp_name"], $target_file)) {
                    echo "The file was uploaded.";
                } else {
                    $uploadOk = 0;
                }
            }
        }

        if ((($target_file != NULL) && ($uploadOk == 1)) || $target_file == NULL) {

            $insert_post_query = "INSERT INTO `goblingizmos_community` (`user_id`, `compost_img`, `compost_category`, `compost_description`, `compost_sfw_nsfw`, `compost_likes`, `compost_creation_date`) VALUES (?, ?, ?, ?, ?, 0, CURRENT_TIMESTAMP)";

            $stmt = $mysqli->prepare($insert_post_query);
            $stmt->bind_param(
                "issss",
                $_SESSION['user_id'],
                $target_file,
                $compostCategoryOptionSelected,
                $_POST['compost_description'],
                $compostSfwNsfwOptionSelected
            );

            $stmt->execute();
            $stmt->close();

        }

    } else if ((isset($_POST['submit'])) && (empty($_POST['compost_category']) || empty($_POST['compost_description']))) {
        print "<p>There was an error</p>";
    }
}



$query_compost = "SELECT goblingizmos_community.user_id, compost_id, compost_img, compost_category, compost_description, compost_sfw_nsfw, compost_likes, compost_creation_date FROM `goblingizmos_community` INNER JOIN goblingizmos_users ON goblingizmos_community.user_id=goblingizmos_users.user_id ORDER BY compost_id DESC";



$resultCompost = $mysqli->query($query_compost);



















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

        <div class="communityGridContainer">

            <div id="comGridItem1">
                <aside>

                    <h4>Advertisements</h4>
                    <div class="adBoxes">
                        <p>Tired of the ads? Wish you could get rid of them?</p>
                        <p>Call xxx-xxx-xxxx</p>
                    </div>
                    <div class="adBoxes">
                        <p>Wear protection.</p>
                        <p>Call xxx-xxx-xxxx</p>
                    </div>
                    <div class="adBoxes">
                        <p>Find your soulmate!</p>
                        <p>Call xxx-xxx-xxxx</p>
                    </div>

                </aside>
            </div>



            <div id="comGridItem2" class="secondComGridPost">
                <!--This is where user makes post PHP-->
                <?php
                print "<form method=\"POST\" action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" enctype=\"multipart/form-data\">";
                ?>

                <div id="postItem1">

                </div>
                <div id="postItem2">
                    <textarea name="compost_description" rows="5" columns="30"></textarea>
                </div>



                <div id="postItem3">
                    <div class='addBoxPost'>
                        <img src="img/image.png" class="iconImg" alt="small picture box icon">
                        <label for="imagePost"></label>
                        <input type="file" id="imagePost" name='compost_img'>
                    </div>



                    <label for="category">Category</label>

                    <select name="compost_category" id="category">
                        <option value="autographs">Autographs</option>
                        <option value="books">Books</option>
                        <option value="caps">Bottle Caps</option>
                        <option value="cans">Cans</option>
                        <option value="charms">Charms</option>
                        <option value="coins">Coins</option>
                        <option value="figures">Figures</option>
                        <option value="jewelry">Jewelry</option>
                        <option value="magnets">Magnets</option>
                        <option value="minerals">Minerals</option>
                        <option value="perfume">Perfume</option>
                        <option value="plates">Plates</option>
                        <option value="cards">Playing Cards</option>
                        <option value="plushies">Plushies</option>
                        <option value="prints">Prints</option>
                        <option value="stamps">Stamps</option>
                        <option value="tickets">Tickets</option>
                        <option value="games">Video Games</option>
                        <option value="vinyls">Vinyls</option>
                        <option value="other">Other</option>


                    </select>

                    <label for="sfw">SFW</label>
                    <input type="radio" name="compost_sfw_nsfw" value="SFW">

                    <label for="nsfw">NSFW</label>
                    <input type="radio" name="compost_sfw_nsfw" value="NSFW">
                </div>

                <div id="postItem4">
                    <!--<img src="img/sendArrow.png" class="comIcons" alt="Post Bounty">-->
                    <button type="submit" class="goblinButtons" name="submit">Post</button>
                </div>

                </form>

            </div>



            <!--Community and friend tab

        why do we even have a friends tab

        Novatnik pressured us into making a social media feature and we folded very easily -carter
        -->

            <!-- <div id="comGridItem3">
                <ol class="comFriendsHeader">

                    <li>
                        <h2>Community</h2>
                    </li>

                </ol>
            </div>-->

            <!--Maybe they look the same but they're two different pages???
            or is this DOM?


            -->




            <div id="comGridItem4">


                <div class="specificCatItem5">













                    <?php

                    if ($resultCompost) {
                        while (($row2 = $resultCompost->fetch_array(MYSQLI_ASSOC))) {




                            print "<div class=\"boxesForEachPost\">";



                            print "<div class=\"gridItemForPostBox3\">" . "<p>" . $row['username'] . "</p>" . "</div>";
                            print "<div class=\"gridItemForPostBox4\">";

                            print "<div class=\"gridItemForPostBox5\">" . "<p>" . $row2['compost_category'] . "</p>" . "</div>";


                            print "<a href=\"/userProfileView.php?user_id=" . $row2['user_id'] . "\">";
                            print "<img src=\"" . $row['user_pfp'] . "\" alt=\"profile image of user\">";
                            print "</a>";



                            print "</div>";





                            print "<div class=\"gridItemForPostBox11\">" . "<p>" . $row2['compost_description'] . "</p>" . "</div>";
                            //this always exists
                    



                            if (!empty($row2['compost_img'])) {
                                print "<div class=\"gridItemForPostBox12\">" . "<img src=\"" . $row2['compost_img'] . "\">" . "</div>";
                                //doesn't always exist
                            }

                            if (!empty($row2['post_sfw_nsfw'])) {
                                print "<div class=\"gridItemForPostBox13\">" . "<p>" . $row2['compost_sfw_nsfw'] . "</p>" .
                                    "</div>";
                                //doesn't always exist
                            }


                            print "<div class=\"gridItemForPostBox14\">" . "<p>" . $row2['compost_creation_date'] . "</p>" . "</div>";

                            //always exists
                    

                            print "<div class=\"gridItemForPostBoxViewPost\">";
                            //print "<a href=\"../categories/postView.php?compost_id=" . $row2['compost_id'] . "\"\">View Post</a>";
                            print "</div>";

                            print "</div>";



                        }




                    }


                    ?>







                </div>







            </div>



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