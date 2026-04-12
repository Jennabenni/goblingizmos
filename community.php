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


/*WHY DO THESE ALL HAVE BUTTONS FUCK */


ini_set('display_errors', 1);
error_reporting(E_ALL);






/* FIX: initialize row so it exists even if no query runs */
$row = null;

if (
    isset($_SESSION['logged_in']) &&
    $_SESSION['logged_in'] === true &&
    isset($_SESSION['user_id'])
) {


    /* FIX: use prepared statement instead of direct SQL concatenation */
    $query_user_info_on_pages = "SELECT * FROM `goblingizmos_users` WHERE user_id = ?";


    //honestly all of this could be used

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



$comSFWNSFW = NULL;
$compostCategory = "";
$compostLikes = 0;

/* FIX: initialize variables so they always exist */
$compostCategoryOptionSelected = NULL;
$compostSfwNsfwOptionSelected = NULL;
$compostDescription = "";

/* FIX: use a whitelist array instead of a long chain of if/else statements */
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






if (isset($_POST['submit'])) {

    if (
        isset($_SESSION['logged_in']) &&
        $_SESSION['logged_in'] === true &&
        isset($_POST['submit'])
    ) {

        $compostCategory = $_POST['compost_category'] ?? "";
        $compostDescription = trim($_POST['compost_description'] ?? "");

        /* FIX: validate category using whitelist */
        if (in_array($compostCategory, $allowedCategories, true)) {
            $compostCategoryOptionSelected = $compostCategory;
        }

        $comSFWNSFW = $_POST['compost_sfw_nsfw'] ?? NULL;

        if (!isset($_POST['compost_sfw_nsfw'])) {
            $compostSfwNsfwOptionSelected = NULL;
        } else if ($comSFWNSFW == 'SFW') {
            $compostSfwNsfwOptionSelected = "SFW";
        } else if ($comSFWNSFW == 'NSFW') {
            $compostSfwNsfwOptionSelected = "NSFW";
        }

        $target_file = NULL;
        $uploadOk = 1;

        if (!empty($_FILES['compost_img']) && $_FILES['compost_img']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";

            /* FIX: create uploads folder if it does not exist */
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }

            /* FIX: give uploads unique filenames so duplicate names do not collide */
            $clean_file_name = str_replace(' ', '_', basename($_FILES['compost_img']["name"]));
            $target_file = $target_dir . uniqid() . "_" . $clean_file_name;
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

        /* FIX: require a valid category and non-empty description before insert */
        if (
            !empty($compostCategoryOptionSelected) &&
            !empty($compostDescription) &&
            ((($target_file != NULL) && ($uploadOk == 1)) || $target_file == NULL)
        ) {

            $insert_post_query = "INSERT INTO `goblingizmos_community` (`user_id`, `compost_img`, `compost_category`, `compost_description`, `compost_sfw_nsfw`, `compost_likes`, `compost_creation_date`) VALUES (?, ?, ?, ?, ?, 0, CURRENT_TIMESTAMP)";

            $stmt = $mysqli->prepare($insert_post_query);

            if (!$stmt) {
                die("Prepare failed: " . $mysqli->error);
            }

            $stmt->bind_param(
                "issss",
                $_SESSION['user_id'],
                $target_file,
                $compostCategoryOptionSelected,
                $compostDescription,
                $compostSfwNsfwOptionSelected
            );

            /* FIX: show helpful insert error while debugging */
            if (!$stmt->execute()) {
                print "<p>Post insert failed: " . htmlspecialchars($stmt->error) . "</p>";
            }

            $stmt->close();

        } else {
            print "<p>There was an error</p>";
        }

    } else if ((isset($_POST['submit'])) && (empty($_POST['compost_category']) || empty($_POST['compost_description']))) {
        print "<p>There was an error</p>";
    }
}



/* FIX: select poster username and profile picture from joined users table */
$query_compost = "SELECT goblingizmos_community.user_id, goblingizmos_community.compost_id, goblingizmos_community.compost_img, goblingizmos_community.compost_category, goblingizmos_community.compost_description, goblingizmos_community.compost_sfw_nsfw, goblingizmos_community.compost_likes, goblingizmos_community.compost_creation_date, goblingizmos_users.username, goblingizmos_users.user_pfp FROM `goblingizmos_community` INNER JOIN goblingizmos_users ON goblingizmos_community.user_id = goblingizmos_users.user_id ORDER BY goblingizmos_community.compost_id DESC";



$resultCompost = $mysqli->query($query_compost);

/* FIX: avoid hard crash if local table is missing */
if (!$resultCompost) {
    $resultCompost = null;
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
                <?php if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) { ?>

                    <form method="POST" action="<?php print htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                        enctype="multipart/form-data">

                        <div class="postTopRow">
                            <div id="postItem1">

                            </div>
                            <div id="postItem2">
                                <textarea name="compost_description" placeholder="Type your post..." rows="3"
                                    cols="30"></textarea>
                            </div>
                        </div>


                        <div class="postBottomRow">
                            <div id="postItem3">
                                <div class='addBoxPost'>
                                    <img src="img/image.png" class="iconImg" alt="small picture box icon"
                                        onclick="document.getElementById('imagePost').click()">
                                    <label for="imagePost"></label>
                                    <input type="file" id="imagePost" name='compost_img'>
                                </div>


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

                <?php } else { ?>

                    <p>Please sign in to create a post.</p>

                <?php } ?>

            </div>



            <div id="communityFeed">


                <div class="specificCatItem5">



                    <?php

                    /* FIX: display posts based on query success, not on login state */
                    if ($resultCompost && $resultCompost->num_rows > 0) {
                        //I don't have time to delve into this
                    
                        while (($row2 = $resultCompost->fetch_array(MYSQLI_ASSOC))) {


                            print "<div class=\"boxesForEachPost\">";



                            print "<div class=\"gridItemForPostBox3\">" . "<p>" . htmlspecialchars($row2['username']) . "</p>" . "</div>";
                            print "<div class=\"gridItemForPostBox4\">";

                            print "<div class=\"gridItemForPostBox5\">" . "<p>" . htmlspecialchars($row2['compost_category']) . "</p>" . "</div>";


                            print "<a href=\"userProfileView.php?user_id=" . urlencode($row2['user_id']) . "\">";
                            if (!empty($row2['user_pfp'])) {
                                print "<img src=\"" . htmlspecialchars($row2['user_pfp']) . "\" alt=\"profile image of user\" onerror=\"this.src='img/PFP.png';\">";
                            } else {
                                print "<img src=\"img/PFP.png\" alt=\"profile image of user\">";
                            }
                            print "</a>";



                            print "</div>";





                            print "<div class=\"gridItemForPostBox11\">" . "<p>" . htmlspecialchars($row2['compost_description']) . "</p>" . "</div>";
                            //this always exists
                    



                            if (!empty($row2['compost_img'])) {
                                print "<div class=\"gridItemForPostBox12\">" . "<img src=\"" . htmlspecialchars($row2['compost_img']) . "\" alt=\"community post image\">" . "</div>";
                                //doesn't always exist
                            }

                            if (!empty($row2['compost_sfw_nsfw'])) {
                                print "<div class=\"gridItemForPostBox13\">" . "<p>" . htmlspecialchars($row2['compost_sfw_nsfw']) . "</p>" .
                                    "</div>";
                                //doesn't always exist
                            }


                            print "<div class=\"gridItemForPostBox14\">" . "<p>" . htmlspecialchars($row2['compost_creation_date']) . "</p>" . "</div>";

                            //always exists
                    

                            print "<div class=\"gridItemForPostBoxViewPost\">";
                            //print "<a href=\"../categories/postView.php?compost_id=" . $row2['compost_id'] . "\"\">View Post</a>";
                            print "</div>";

                            /* -- EDIT BUTTON --
                             * Only the author of this post sees the Edit button.
                             * $row2['user_id'] is the post author's ID.
                             * $_SESSION['user_id'] is the currently logged-in user's ID. */
                            if (
                                isset($_SESSION['logged_in']) &&
                                $_SESSION['logged_in'] === true &&
                                (int) $row2['user_id'] === (int) $_SESSION['user_id']
                            ) {
                                print "<div class=\"gridItemForPostBoxViewPost\">";
                                print "<a href=\"editCommunityPost.php?compost_id=" . urlencode($row2['compost_id']) . "\">";
                                print "<button type=\"button\" class=\"goblinButtons\">Edit</button>";
                                print "</a>";
                                print "</div>";
                            }

                            /* -- DELETE BUTTON --
                             * The author OR any admin can delete a community post.
                             * $row holds the logged-in user's info (loaded before this loop),
                             * so $row['access_level'] is safe to check here. */
                            if (
                                isset($_SESSION['logged_in']) &&
                                $_SESSION['logged_in'] === true &&
                                (
                                    (int) $row2['user_id'] === (int) $_SESSION['user_id'] ||
                                    (isset($row['access_level']) && $row['access_level'] === 'admin')
                                )
                            ) {
                                print "<div class=\"gridItemForPostBoxViewPost\">";
                                print "<a href=\"deleteConfirm.php?post_id=" . urlencode($row2['compost_id']) . "&type=community\">";
                                print "<button type=\"button\" class=\"deleteButton\">Delete</button>";
                                print "</a>";
                                print "</div>";
                            }








                            //THE COMMENTS (Viewing)
                            print "<div><a href=\"communityComments.php?compost_id=" . htmlspecialchars($row2['compost_id']) . "\"\">See Comments</a>></div>";

                            print "</div>";



                        }


                    } else {
                        print "<p>No community posts yet.</p>";
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
if (isset($mysqli) && $mysqli) {
    $mysqli->close();
}
?>