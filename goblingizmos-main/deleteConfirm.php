<?php
session_start();

/*DO NOT DELETE THESE */

//include("db-connect.php");
//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");


//DOCKER CONNECTION DO NOT TOUCH ARF ARF
require 'db_connectionGG.php';



ini_set('display_errors', 1);
error_reporting(E_ALL);


/* ---------------------------------------------------------------
 * ACCESS CONTROL
 * If the user is not logged in, send them to the sign-in page.
 * There is no reason a guest should be on this page at all.
 * --------------------------------------------------------------- */
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit();
}


/* ---------------------------------------------------------------
 * LOAD CURRENT USER'S INFO
 * We need the user's profile picture for the header, and we also
 * need their access_level to know if they are an admin.
 * --------------------------------------------------------------- */
$row = null;

$query_user_info = "SELECT * FROM goblingizmos_users WHERE user_id = ?";
$stmt = $mysqli->prepare($query_user_info);

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


/* ---------------------------------------------------------------
 * VALIDATE THE 'type' URL PARAMETER
 * 'type' tells us which table we are working with.
 *   - "community" → goblingizmos_community table
 *   - "post"      → goblingizmos_postsbounties table
 * If neither, kick the user out — something is wrong.
 * --------------------------------------------------------------- */
$allowedTypes = array("community", "post");
$postType = $_GET['type'] ?? $_POST['type'] ?? "";

if (!in_array($postType, $allowedTypes, true)) {
    header("Location: index.php");
    exit();
}


/* ---------------------------------------------------------------
 * VALIDATE THE 'post_id' URL PARAMETER
 * Cast to int immediately so there is no risk of SQL injection
 * even before we use prepared statements.
 * --------------------------------------------------------------- */
$postId = isset($_GET['post_id']) ? (int) $_GET['post_id'] : (isset($_POST['post_id']) ? (int) $_POST['post_id'] : 0);

if ($postId <= 0) {
    header("Location: index.php");
    exit();
}


/* ---------------------------------------------------------------
 * FETCH THE POST FROM THE CORRECT TABLE
 * We use a different query depending on the type.
 * Both queries also pull in the username so we can display the
 * post details on the confirmation page.
 * --------------------------------------------------------------- */
$targetPost = null;

if ($postType === "community") {

    /* Fetch a community post by its compost_id */
    $query_fetch = "
        SELECT
            goblingizmos_community.compost_id,
            goblingizmos_community.user_id,
            goblingizmos_community.compost_description,
            goblingizmos_community.compost_img,
            goblingizmos_community.compost_category,
            goblingizmos_users.username
        FROM goblingizmos_community
        INNER JOIN goblingizmos_users
            ON goblingizmos_community.user_id = goblingizmos_users.user_id
        WHERE goblingizmos_community.compost_id = ?
        LIMIT 1
    ";

} else {

    /* Fetch a marketplace post/bounty by its post_id */
    $query_fetch = "
        SELECT
            goblingizmos_postsbounties.post_id,
            goblingizmos_postsbounties.user_id,
            goblingizmos_postsbounties.post_description,
            goblingizmos_postsbounties.post_img,
            goblingizmos_postsbounties.post_category,
            goblingizmos_postsbounties.post_or_bounty,
            goblingizmos_users.username
        FROM goblingizmos_postsbounties
        INNER JOIN goblingizmos_users
            ON goblingizmos_postsbounties.user_id = goblingizmos_users.user_id
        WHERE goblingizmos_postsbounties.post_id = ?
        LIMIT 1
    ";

}

$stmt = $mysqli->prepare($query_fetch);

if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("i", $postId);
$stmt->execute();
$resultFetch = $stmt->get_result();

if ($resultFetch && $resultFetch->num_rows > 0) {
    $targetPost = $resultFetch->fetch_array(MYSQLI_ASSOC);
}

$stmt->close();


/* ---------------------------------------------------------------
 * OWNERSHIP CHECK
 * The post must exist AND the requester must be either:
 *   (a) the original author of the post, OR
 *   (b) a site admin
 * If neither condition is true, redirect away immediately.
 * --------------------------------------------------------------- */
if (!$targetPost) {
    header("Location: index.php");
    exit();
}

$isAuthor = ((int) $targetPost['user_id'] === (int) $_SESSION['user_id']);
$isAdmin = (isset($row['access_level']) && $row['access_level'] === 'admin');

if (!$isAuthor && !$isAdmin) {
    /* User is neither the author nor an admin — they should not be here */
    header("Location: index.php");
    exit();
}


/* ---------------------------------------------------------------
 * CATEGORY-TO-FILE MAP
 * Used after a successful marketplace post deletion to send the
 * user back to the correct category page.
 * --------------------------------------------------------------- */
$categoryFileMap = array(
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
    "other" => "categories/otherCategories.php",
);


/* ---------------------------------------------------------------
 * HANDLE THE CONFIRMED DELETION (POST request)
 * This block only runs when the user has clicked "Yes, Delete It"
 * on the confirmation form below.
 * --------------------------------------------------------------- */

/* Stores an error message if the DELETE query fails, so it can be
 * displayed inside the HTML page rather than printed before the doctype */
$deleteError = null;

if (isset($_POST['confirm_delete'])) {

    if ($postType === "community") {

        /* Delete the community post by compost_id */
        $query_delete = "DELETE FROM goblingizmos_community WHERE compost_id = ?";

    } else {

        /* Delete the marketplace post by post_id */
        $query_delete = "DELETE FROM goblingizmos_postsbounties WHERE post_id = ?";

    }

    $stmt = $mysqli->prepare($query_delete);

    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("i", $postId);

    if ($stmt->execute()) {

        /* -------------------------------------------------------
         * IMAGE CLEANUP
         * If the post had an uploaded image saved in the uploads/
         * folder, delete it from the server so we don't accumulate
         * orphaned files over time.
         * ------------------------------------------------------- */
        $imgField = ($postType === "community") ? "compost_img" : "post_img";

        if (!empty($targetPost[$imgField])) {
            $imagePath = $targetPost[$imgField];

            /* unlink() only runs if the file actually exists */
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
        }

        $stmt->close();

        /* -------------------------------------------------------
         * REDIRECT AFTER DELETION
         * Community posts go back to community.php.
         * Marketplace posts go back to their category page.
         * If the category somehow isn't in our map, fall back to
         * categories.php as a safe default.
         * ------------------------------------------------------- */
        if ($postType === "community") {
            header("Location: community.php");
        } else {
            $postCategory = $targetPost['post_category'] ?? "";
            $redirectTarget = $categoryFileMap[$postCategory] ?? "categories.php";
            header("Location: " . $redirectTarget);
        }

        exit();

    } else {

        /* Store the error to display inside the HTML page below —
         * printing before <!DOCTYPE html> would break the document structure */
        $deleteError = $stmt->error;
        $stmt->close();

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

    <title>Goblin Gizmos - Delete Post</title>
</head>

<body>

    <div class="page-wrap">

        <header>

            <div class="headerGrid">

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
                    /* Display the logged-in user's profile picture, or the default if none is set */
                    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true && isset($row)) {
                        if (!empty($row['user_pfp'])) {
                            print "<a href=\"userProfile.php\"><img src=\"" . htmlspecialchars($row['user_pfp']) . "\" class=\"userIconImageForSmaller\" alt=\"User's chosen profile picture\" onerror=\"this.src='img/PFP.png';\"></a>";
                        } else {
                            print "<a href=\"userProfile.php\"><img src=\"img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                        }
                    } else {
                        print "<a href=\"userProfile.php\"><img src=\"img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                    }
                    ?>

                </div>

            </div>
        </header>


        <!-- -------------------------------------------------------
             CONFIRMATION SECTION
             This is what the user sees before the deletion happens.
             We show them a brief summary of the post so they know
             exactly what they are about to delete.
             ------------------------------------------------------- -->
        <div class="signUpForms">

            <h2>Delete Post</h2>

            <?php
            /* If the DELETE query failed, show the error here inside the page */
            if ($deleteError !== null) {
                print "<p>Delete failed: " . htmlspecialchars($deleteError) . "</p>";
            }
            ?>

            <p>Are you sure you want to delete this post? <strong>This cannot be undone.</strong></p>

            <?php
            /* Show a short preview of the post the user is about to delete */
            if ($targetPost) {

                /* Display the post's description as a preview */
                $previewField = ($postType === "community") ? "compost_description" : "post_description";
                print "<div class=\"boxesForEachPost\">";
                print "<div class=\"gridItemForPostBox11\"><p>" . htmlspecialchars($targetPost[$previewField]) . "</p></div>";
                print "</div>";

            }
            ?>

            <!--
                The form passes the post_id and type as hidden fields so the
                server-side deletion block above knows what to delete when
                the user clicks confirm. This avoids putting sensitive values
                directly in the URL for a destructive action.
            -->
            <form method="POST" action="<?php print htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

                <input type="hidden" name="post_id" value="<?php print htmlspecialchars($postId); ?>">
                <input type="hidden" name="type" value="<?php print htmlspecialchars($postType); ?>">

                <!-- Confirm button uses the existing deleteButton style (red) -->
                <button type="submit" name="confirm_delete" class="deleteButton">Yes, Delete It</button>

            </form>

            <!-- Cancel sends the user back without doing anything -->
            <?php
            /* Build the cancel link so it returns to the right page */
            if ($postType === "community") {
                $cancelHref = "community.php";
            } else {
                $cancelCategory = $targetPost['post_category'] ?? "";
                $cancelHref = $categoryFileMap[$cancelCategory] ?? "categories.php";
            }
            ?>

            <a href="<?php print htmlspecialchars($cancelHref); ?>">
                <button type="button" class="goblinButtons">Cancel</button>
            </a>

        </div>


    </div>


    <footer>

        <img src="img/goblinLogo.png" id="bottomLogo" alt="a goblin face in a coin; the logo">

        <div>
            <div class="footerFlex">
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
                                <li><a href="https://x.com/"><img src="img/TwitterLogo.png" class="iconImg"
                                            alt="X logo"></a></li>
                                <li><a href="https://www.instagram.com/"><img src="img/instagram.png" class="iconImg"
                                            alt="Instagram logo"></a></li>
                                <li><a href="https://www.facebook.com/"><img src="img/facebook.png" class="iconImg"
                                            alt="Facebook logo"></a></li>
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