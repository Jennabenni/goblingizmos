<?php
session_start();
//make sure to have the closer at the end of html


/*DO NOT DELETE THESE */

//include("../db-connect.php");
//include(__DIR__ . "/db-connect.php");
include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//WAIT THIS ONE WORKED??
//local

//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote



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

    /* FIX: make prepare failure obvious during debugging */
    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("i", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();

    /* FIX: only fetch if a row actually exists */
    if ($result && $result->num_rows > 0) {
        $row = $result->fetch_array(MYSQLI_ASSOC);
    }

    $stmt->close();



}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goblin Gizmos - Categories</title>

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

                    /* FIX: require logged_in to actually be true and row to exist */
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


        <!--Oh good lord 20 categories. yeah i noticed thanks this was fun to individually check-->

        <div class="categoryContainer">
            <!--Big DIV, for grid-->

            <div class="categoryGridItem">
                <a href="categories/autographsCategory.php">
                    <div>
                        <h3>Autographs</h3>
                        <img src="img/autographs.png" alt="a signature">
                    </div>
                </a>
            </div>


            <div class="categoryGridItem">
                <a href="categories/booksCategories.php">
                    <div>
                        <h3>Books</h3>
                        <img src="img/books.png" alt="books">
                    </div>
                </a>
            </div>

            <!--Do I need a bigger div to hold the categories?-->


            <div class="categoryGridItem">
                <a href="categories/bottleCapsCategories.php">
                    <div>
                        <h3>Bottle Caps</h3>
                        <img src="img/caps.png" alt="bottle caps">
                    </div>
                </a>
            </div>



            <div class="categoryGridItem">

                <a href="categories/cansCategories.php">
                    <div>
                        <h3>Cans</h3>
                        <img src="img/cans.png" alt="a wall of cans">
                    </div>
                </a>

            </div>


            <div class="categoryGridItem">

                <a href="categories/charmsCategories.php">
                    <div>
                        <h3>Charms</h3>
                        <img src="img/charms.png" alt="keychains shaped like game controllers">
                    </div>
                </a>


            </div>
            <div class="categoryGridItem">
                <a href="categories/coinsCategories.php">
                    <div>
                        <h3>Coins</h3>
                        <img src="img/coins.png" alt="coins">
                    </div>
                </a>
            </div>

            <div class="categoryGridItem">
                <a href="categories/figuresCategories.php">
                    <div>
                        <h3>Figures</h3>
                        <img src="img/figures.png" alt="a figurine of Goku">
                    </div>
                </a>

            </div>
            <div class="categoryGridItem">
                <a href="categories/jewelryCategories.php">
                    <div>
                        <h3>Jewelry</h3>
                        <img src="img/jewelry.png" alt="three rings in a pile">
                    </div>
                </a>
            </div>
            <div class="categoryGridItem">

                <a href="categories/magnetsCategories.php">
                    <div>
                        <h3>Magnets</h3>
                        <img src="img/magnets.png" alt="a collection of fun magnets">
                    </div>
                </a>
            </div>
            <div class="categoryGridItem">

                <a href="categories/mineralsCategories.php">
                    <div>
                        <h3>Minerals</h3>
                        <img src="img/minerals.png" alt="rocks in a row">
                    </div>
                </a>
            </div>
            <div class="categoryGridItem">

                <a href="categories/perfumeCategories.php">
                    <div>
                        <h3>Perfume</h3>
                        <img src="img/perfume.png" alt="perfume bottle">
                    </div>
                </a>

            </div>
            <div class="categoryGridItem">

                <a href="categories/platesCategories.php">
                    <div>
                        <h3>Plates</h3>
                        <img src="img/plates.png" alt="plates and dishes in a cupboard">
                    </div>
                </a>

            </div>
            <div class="categoryGridItem">

                <a href="categories/cardsCategories.php">
                    <div>
                        <h3>Playing Cards</h3>
                        <img src="img/playingCards.png" alt="playing cards">
                    </div>
                </a>
            </div>
            <div class="categoryGridItem">

                <a href="categories/plushiesCategories.php">
                    <div>
                        <h3>Plushies</h3>
                        <img src="img/plushies.png" alt="three cat plushies">
                    </div>
                </a>
            </div>
            <div class="categoryGridItem">


                <a href="categories/printsCategories.php">
                    <div>
                        <h3>Prints</h3>
                        <img src="img/prints.png" alt="photographs on a wall">
                    </div>
                </a>

            </div>
            <div class="categoryGridItem">

                <a href="categories/stampsCategories.php">
                    <div>
                        <h3>Stamps</h3>
                        <img src="img/stamps.png" alt="stamps">
                    </div>
                </a>
            </div>

            <div class="categoryGridItem">


                <a href="categories/ticketsCategories.php">
                    <div>
                        <h3>Tickets</h3>
                        <img src="img/tickets.png" alt="tickets">
                    </div>
                </a>

            </div>
            <div class="categoryGridItem">

                <a href="categories/videoGamesCategories.php">
                    <div>
                        <h3>Video Games</h3>
                        <img src="img/videoGames.png" alt="a gameboy">
                    </div>
                </a>

            </div>
            <div class="categoryGridItem">

                <a href="categories/vinylsCategories.php">
                    <div>
                        <h3>Vinyls</h3>
                        <img src="img/vinyls.png" alt="Vinyl records">
                    </div>
                </a>
            </div>
            <div class="categoryGridItem">


                <a href="categories/otherCategories.php">
                    <div>
                        <h3>Other</h3>
                        <img src="img/other.png" alt="small statue of a house">
                    </div>
                </a>
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
/* FIX: only close connection if it exists */
if (isset($mysqli) && $mysqli) {
    $mysqli->close();
}
?>