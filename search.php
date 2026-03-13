<?php
session_start();
//make sure to have the closer at the end of html


/*
ini_set('display_errors', 1);
error_reporting(E_ALL);
*/

/*DO NOT DELETE THESE */

//include("../db-connect.php");
//include("/Applications/XAMPP/htdocs/dig3134c/db-connect.php");
//WAIT THIS ONE WORKED??
//local

include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote



//Will need to ask some stuff from Amy since I couldn't merge the same as usual





//pfp stuff

if (isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {

    $query_user_info_on_pages = "SELECT * FROM `goblingizmos_users` WHERE user_id = '" . $_SESSION['user_id'] . "'";


    //honestly all of this could be used

    $result = $mysqli->query($query_user_info_on_pages);



}



if (isset($_SESSION['logged_in'])) {



    $query_bounties = "SELECT post_id,
    goblingizmos_postsbounties.user_id,
    post_or_bounty,
    post_category,
    post_condition,
    post_boxCondition,
    post_price,
    post_location,
    post_description,
    post_img,
    post_sfw_nsfw,
    post_creation_date,
    goblingizmos_users.username,
    goblingizmos_users.user_pfp FROM `goblingizmos_postsbounties`
    INNER JOIN goblingizmos_users
    ON goblingizmos_postsbounties.user_id = goblingizmos_users.user_id
    ORDER BY post_id DESC";


    $resultBounties = $mysqli->query($query_bounties);







}

























// working search... perchance



$results = [];
$search_made = false;

// detects if a search was made
if (isset($_GET['search']) || isset($_GET['post_category'])) {
    $search_made = true;

    $search_query = isset($_GET['search']) ? trim($_GET['search']) : '';
    $category = isset($_GET['post_category']) ? trim($_GET['post_category']) : '';
    $sort_by = isset($_GET['sort']) ? trim($_GET['sort']) : 'newest';

    // base query, selects from database

    //Not sure what to make 'Title'
    // I realized we had no title section (oops) so I changed them all to post_description

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
                post_creation_date */




    $sql = "SELECT
    goblingizmos_postsbounties.post_id,
    goblingizmos_postsbounties.user_id,
    post_or_bounty,
    post_description,
    post_category,
    post_price,
    post_img,
    post_sfw_nsfw,
    post_creation_date,
    goblingizmos_users.username,
    goblingizmos_users.user_pfp
    FROM goblingizmos_postsbounties
    INNER JOIN goblingizmos_users
    ON goblingizmos_postsbounties.user_id = goblingizmos_users.user_id
    WHERE 1=1";
    // 1=1 is so the query doesn't kill itself. nothingburger code that's loadbearing so the AND clause works in sql

    // $mysqli is a placeholder and should be replaced with the actual db connection
    // wait i just realized we used the name $mysqli in the db connect file like 2 semesters ago so the $mysqli variable should work fine i think

    // searches for query in title OR desc of bounty
    if (!empty($search_query)) {
        // prevents sql attacks
        $safe_query = $mysqli->real_escape_string($search_query);
        // changed 'title' to post_description
        $sql .= " AND post_description LIKE '%$safe_query%'";
    }

    // category filter
    if (!empty($category)) {
        $safe_category = $mysqli->real_escape_string($category);
        // 'category' is whatever the category for the bounties will be called in the database
        $sql .= " AND post_category = '$safe_category'";
    }

    // sort by filter
    switch ($sort_by) {
        // date_posted, price, and title are placeholders for whatever they will be called in the database. in this instance, I'm assuming ordering it by the newest bounty will be the default
        case 'oldest':
            $sql .= " ORDER BY post_creation_date ASC";
            break;
        case 'price_high':
            $sql .= " ORDER BY post_price DESC";
            break;
        case 'price_low':
            $sql .= " ORDER BY post_price ASC";
            break;
        case 'a_z':
            $sql .= " ORDER BY post_description ASC";
            break;
        case 'z_a':
            $sql .= " ORDER BY post_description DESC";
            break;
        default:
            $sql .= " ORDER BY post_creation_date DESC";
    }

    // sends query to db and stores it
    $query = $mysqli->query($sql);
    while ($row = $query->fetch_assoc()) {
        $results[] = $row;
    }
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



        <div class="searchGridContainer">

            <div id="searchGridItem1">
                <aside>
                    <h4>Advertisements</h4>
                    <div class="adBoxes">
                        <p>Miss your wife? Get another!</p>
                        <p>Call xxx-xxx-xxxx</p>
                    </div>
                    <div class="adBoxes">
                        <p>Your foot fungus tells you a lot about your personality</p>
                        <p>Call xxx-xxx-xxxx</p>
                    </div>
                    <div class="adBoxes">
                        <p>Is life a pain? Join the club</p>
                        <p>Call xxx-xxx-xxxx</p>
                    </div>
                </aside>
            </div>


            <div id="searchGridItem2">
                <!--Search bar-->

                <!-- This is the form to make the search work I hope -->
                <form method="GET" action="search.php" id="searchForm">
                    <div class="searchBar">

                        <div>
                            <img src="img/magGlass.png" class="comIcons">
                        </div>

                        <!--Input-->
                        <div>
                            <label for="search" class="searchOnly"></label>
                            <!-- Reorganized this so it's easier to read and added protection against attacks -->
                            <input type="text" name="search" placeholder="Search Bounties..." class="searchBarItems"
                                id="borderForSearch"
                                value="<?php echo isset($_GET['search']) ? htmlspecialchars($_GET['search']) : ''; ?>"
                                autocomplete="off">
                        </div>



                        <div>
                            <!--Category drop down-->
                            <!-- + persistent filter -->
                            <select name="post_category" id="category" class="searchBarItems">

                                <option value="autographs" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'autographs') ? 'selected' : ''; ?>>Autographs</option>

                                <option value="books" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'books') ? 'selected' : ''; ?>>Books</option>

                                <option value="caps" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'caps') ? 'selected' : ''; ?>>Bottle Caps</option>

                                <option value="cans" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'cans') ? 'selected' : ''; ?>>Cans</option>

                                <option value="charms" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'charms') ? 'selected' : ''; ?>>Charms</option>

                                <option value="coins" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'coins') ? 'selected' : ''; ?>>Coins</option>

                                <option value="figures" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'figures') ? 'selected' : ''; ?>>Figures</option>

                                <option value="jewelry" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'jewelry') ? 'selected' : ''; ?>>Jewelry</option>

                                <option value="magnets" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'magnets') ? 'selected' : ''; ?>>Magnets</option>

                                <option value="minerals" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'minerals') ? 'selected' : ''; ?>>Minerals</option>

                                <option value="perfume" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'perfume') ? 'selected' : ''; ?>>Perfume</option>

                                <option value="plates" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'plates') ? 'selected' : ''; ?>>Plates</option>

                                <option value="cards" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'cards') ? 'selected' : ''; ?>>Playing Cards</option>

                                <option value="plushies" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'plushies') ? 'selected' : ''; ?>>Plushies</option>

                                <option value="prints" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'prints') ? 'selected' : ''; ?>>Prints</option>

                                <option value="stamps" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'stamps') ? 'selected' : ''; ?>>Stamps</option>

                                <option value="tickets" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'tickets') ? 'selected' : ''; ?>>Tickets</option>

                                <option value="games" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'games') ? 'selected' : ''; ?>>Video Games</option>

                                <option value="vinyls" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'vinyls') ? 'selected' : ''; ?>>Vinyls</option>

                                <option value="other" <?php echo (isset($_GET['post_category']) && $_GET['post_category'] === 'other') ? 'selected' : ''; ?>>Other</option>
                            </select>
                        </div>

                        <!-- Yay, more filters! I have no idea how to attach dropdowns to an image though.. maybe the filter button can be there for funsies to let people know there's a filter? Also persistent filter -->
                        <div>
                            <img src="img/filter.png" class="comIcons">
                            <select name="sort" id="sort" class="searchBarItems">

                                <option value="newest" <?php echo (!isset($_GET['sort']) || $_GET['sort'] === 'newest') ? 'selected' : ''; ?>>Newest</option>

                                <option value="oldest" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'oldest') ? 'selected' : ''; ?>>Oldest</option>

                                <option value="price_high" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price_high') ? 'selected' : ''; ?>>Price: High to Low</option>

                                <option value="price_low" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'price_low') ? 'selected' : ''; ?>>Price: Low to High</option>

                                <option value="a_z" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'a_z') ? 'selected' : ''; ?>>A-Z</option>

                                <option value="z_a" <?php echo (isset($_GET['sort']) && $_GET['sort'] === 'z_a') ? 'selected' : ''; ?>>Z-A</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>



            <div id="searchGridItem3"><a href="makePost.php">
                    <button type="button" class="goblinButtons">Create a Bounty</a></button>
            </div>
            <!--I think <button>'s cannot contain or be contained by <a>'s, no issue currently, works as intended, but if issues arise later check here, I'm not 100% sure myself-->

            <div id="searchGridItem4">
                <?php

                $displayRows = [];

                if ($search_made) {

                    $displayRows = $results;
                } else {

                    if (isset($resultBounties)) {
                        while ($row2 = $resultBounties->fetch_array(MYSQLI_ASSOC)) {

                            $displayRows[] = $row2;
                        }
                    }
                }

                if (isset($_SESSION['access_level'])) {

                    if (empty($displayRows) && $search_made) {
                        print "<p>No bounties found for your search.</p>";
                    }

                    foreach ($displayRows as $row2) {
                        if ($row2['post_or_bounty'] == 'bounty') {

                            print "<div class=\"boxesForEachPost\">";
                            print "<div class=\"gridItemForPostBox3\">" . "<p>" . $row2['username'] . "</p>" . "</div>";
                            print "<div class=\"gridItemForPostBox4\">";
                            if ($row2['user_pfp'] != NULL) {
                                print "<img src=\"" . $row2['user_pfp'] . "\" alt=\"profile image of user\">";
                            } else if ($row2['user_pfp'] == NULL) {
                                print "<img src=\"uploads/goblin.png\" alt=\"goblin image of user\">";

                            }
                            print "</a>";
                            print "</div>";

                            if (!empty($row2['post_price'])) {
                                print "<div class=\"gridItemForPostBox9\"><p>$" . $row2['post_price'] . "</p></div>";
                            }

                            print "<div class=\"gridItemForPostBox11\"><p>" . $row2['post_description'] . "</p></div>";
                            // this always exists
                
                            if (!empty($row2['post_img'])) {
                                print "<div class=\"gridItemForPostBox12\"><img src=\"" . $row2['post_img'] . "\"></div>";
                                // doesn't always exist
                            }

                            if (!empty($row2['post_sfw_nsfw'])) {
                                print "<div class=\"gridItemForPostBox13\"><p>" . $row2['post_sfw_nsfw'] . "</p></div>";
                                // doesn't always exist
                            }

                            print "<div class=\"gridItemForPostBox14\"><p>" . $row2['post_creation_date'] . "</p></div>";
                            // always exists
                
                            print "<div class=\"gridItemForPostBoxViewPost\">";
                            print "<a href=\"categories/postView.php?post_id=" . $row2['post_id'] . "\">View Post</a>";
                            print "</div>";

                            print "</div>";

                        }

                    }




                }
                ?>
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