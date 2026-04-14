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
 * Guests have no business editing a post.
 * --------------------------------------------------------------- */
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
    header("Location:signIn.php");
    exit();
}


/* ---------------------------------------------------------------
 * LOAD CURRENT USER'S INFO
 * Used to display the profile picture in the header.
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
 * VALIDATE THE 'post_id' URL PARAMETER
 * Cast to int immediately to prevent any injection risk before
 * prepared statements even come into play.
 * --------------------------------------------------------------- */
$postId = isset($_GET['post_id']) ? (int) $_GET['post_id'] : 0;

if ($postId <= 0) {
    header("Location:categories.php");
    exit();
}


/* ---------------------------------------------------------------
 * FETCH THE EXISTING MARKETPLACE POST
 * We need the current values to pre-fill the edit form, and we
 * also need user_id to verify the requester is the author.
 * --------------------------------------------------------------- */
$existingPost = null;

$query_fetch = "
    SELECT
        post_id,
        user_id,
        post_or_bounty,
        post_category,
        post_condition,
        post_boxCondition,
        post_price,
        post_location,
        post_description,
        post_img,
        post_sfw_nsfw
    FROM goblingizmos_postsbounties
    WHERE post_id = ?
    LIMIT 1
";

$stmt = $mysqli->prepare($query_fetch);

if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("i", $postId);
$stmt->execute();
$resultFetch = $stmt->get_result();

if ($resultFetch && $resultFetch->num_rows > 0) {
    $existingPost = $resultFetch->fetch_array(MYSQLI_ASSOC);
}

$stmt->close();


/* ---------------------------------------------------------------
 * OWNERSHIP CHECK
 * Only the original author may edit their own post.
 * Admins can delete posts but cannot edit them — that is by design.
 * If the post does not exist or the user is not the author, redirect.
 * --------------------------------------------------------------- */
if (!$existingPost || (int) $existingPost['user_id'] !== (int) $_SESSION['user_id']) {
    header("Location:categories.php");
    exit();
}


/* ---------------------------------------------------------------
 * WHITELISTS
 * These mirror the whitelists in makePost.php exactly.
 * Only values present in these arrays will be accepted.
 * --------------------------------------------------------------- */

/* post or bounty */
$allowedPostBounty = array("post", "bounty");

/* category dropdown values */
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

/* condition radio values (key = submitted value, value = what goes into DB) */
$allowedConditions = array(
    "new" => "new",
    "likeNew" => "like new",
    "used" => "used",
    "damaged" => "damaged"
);

/* box condition radio values (key = submitted value, value = what goes into DB) */
$allowedBoxConditions = array(
    "boxnew" => "new",
    "boxlikeNew" => "like new",
    "boxused" => "used",
    "boxdamaged" => "damaged"
);

/* sfw/nsfw radio values */
$allowedContentLevels = array(
    "sfw" => "sfw",
    "nsfw" => "nsfw"
);


/* ---------------------------------------------------------------
 * REVERSE MAPS FOR PRE-FILLING RADIO BUTTONS
 * The DB stores the mapped display value ("like new"), but the
 * radio inputs use the submitted key ("likeNew"). On initial load
 * we need to translate from DB value back to radio key so the
 * correct radio is pre-checked.
 * --------------------------------------------------------------- */

/* DB value → radio key for post_condition */
$conditionReverseMap = array(
    "new" => "new",
    "like new" => "likeNew",
    "used" => "used",
    "damaged" => "damaged"
);

/* DB value → radio key for post_boxCondition */
$boxConditionReverseMap = array(
    "new" => "boxnew",
    "like new" => "boxlikeNew",
    "used" => "boxused",
    "damaged" => "boxdamaged"
);


/* ---------------------------------------------------------------
 * CATEGORY-TO-FILE MAP
 * Used to redirect the user back to the right category page after
 * a successful save. Matches the same map in makePost.php.
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
 * FORM STATE VARIABLES
 * Initialised from the existing post so the form is pre-filled
 * on first load.
 *
 * Condition and box condition need the radio KEY (e.g. "likeNew"),
 * not the DB value ("like new"), so we reverse-map them here.
 *
 * If the update fails validation these hold whatever the user
 * typed/selected so they don't lose their work on reshowing.
 * --------------------------------------------------------------- */
$formPostBounty = $existingPost['post_or_bounty'] ?? "";
$formCategory = $existingPost['post_category'] ?? "";
$formCondition = isset($existingPost['post_condition']) ? ($conditionReverseMap[$existingPost['post_condition']] ?? "") : "";
$formBoxCondition = isset($existingPost['post_boxCondition']) ? ($boxConditionReverseMap[$existingPost['post_boxCondition']] ?? "") : "";
$formPrice = $existingPost['post_price'] > 0 ? $existingPost['post_price'] : "";
$formLocation = $existingPost['post_location'] ?? "";
$formDescription = $existingPost['post_description'] ?? "";
$formSfwNsfw = $existingPost['post_sfw_nsfw'] ?? "";

$updateError = null;
$updateSuccess = false;


/* ---------------------------------------------------------------
 * HANDLE THE FORM SUBMISSION (POST request)
 * This block only runs when the user submits the edit form.
 * --------------------------------------------------------------- */
if (isset($_POST['submit_edit'])) {

    /* Pull every submitted field; trim strings where appropriate */
    $submittedPostBounty = $_POST['post_or_bounty'] ?? "";
    $submittedCategory = $_POST['post_category'] ?? "";
    $submittedCondition = $_POST['post_condition'] ?? null;
    $submittedBoxCondition = $_POST['post_boxCondition'] ?? null;
    $submittedPrice = trim($_POST['post_price'] ?? "");
    $submittedLocation = trim($_POST['post_location'] ?? "");
    $submittedDescription = trim($_POST['post_description'] ?? "");
    $submittedSfwNsfw = $_POST['post_sfw_nsfw'] ?? null;

    /* Update form state variables so the form re-fills correctly
     * if validation fails and the page needs to be reshown */
    $formPostBounty = $submittedPostBounty;
    $formCategory = $submittedCategory;
    $formCondition = $submittedCondition;
    $formBoxCondition = $submittedBoxCondition;
    $formPrice = $submittedPrice;
    $formLocation = $submittedLocation;
    $formDescription = $submittedDescription;
    $formSfwNsfw = $submittedSfwNsfw;

    /* -------------------------------------------------------
     * VALIDATE post_or_bounty
     * Must be exactly "post" or "bounty".
     * ------------------------------------------------------- */
    if (!in_array($submittedPostBounty, $allowedPostBounty, true)) {
        $updateError = "Please select Post or Bounty.";
    }

    /* -------------------------------------------------------
     * VALIDATE category
     * Must be one of the 20 allowed category values.
     * ------------------------------------------------------- */
    if ($updateError === null && !in_array($submittedCategory, $allowedCategories, true)) {
        $updateError = "Please select a valid category.";
    }

    /* -------------------------------------------------------
     * VALIDATE description
     * Required field — cannot be empty.
     * ------------------------------------------------------- */
    if ($updateError === null && empty($submittedDescription)) {
        $updateError = "Post description cannot be empty.";
    }

    /* -------------------------------------------------------
     * VALIDATE AND MAP optional fields
     * condition, box condition, and sfw/nsfw are optional.
     * If submitted, they must be in the whitelist.
     * If valid, map the submitted key to the DB value.
     * ------------------------------------------------------- */
    $mappedCondition = null;
    $mappedBoxCondition = null;
    $mappedSfwNsfw = null;

    if ($updateError === null) {

        /* Condition is optional — only validate if one was selected */
        if (!empty($submittedCondition)) {
            if (isset($allowedConditions[$submittedCondition])) {
                $mappedCondition = $allowedConditions[$submittedCondition];
            } else {
                $updateError = "Invalid item condition selected.";
            }
        }

        /* Box condition is optional — only validate if one was selected */
        if ($updateError === null && !empty($submittedBoxCondition)) {
            if (isset($allowedBoxConditions[$submittedBoxCondition])) {
                $mappedBoxCondition = $allowedBoxConditions[$submittedBoxCondition];
            } else {
                $updateError = "Invalid box condition selected.";
            }
        }

        /* SFW/NSFW is optional — only validate if one was selected */
        if ($updateError === null && !empty($submittedSfwNsfw)) {
            if (isset($allowedContentLevels[$submittedSfwNsfw])) {
                $mappedSfwNsfw = $allowedContentLevels[$submittedSfwNsfw];
            } else {
                $updateError = "Invalid content level selected.";
            }
        }
    }

    /* -------------------------------------------------------
     * VALIDATE AND CAST price
     * Optional integer field. Cast to int; treat blank as null
     * so the DB column stores NULL rather than 0 when not set.
     * ------------------------------------------------------- */
    $mappedPrice = null;

    if ($updateError === null && $submittedPrice !== "") {
        $mappedPrice = (int) $submittedPrice;
    }

    /* -------------------------------------------------------
     * HANDLE OPTIONAL IMAGE REPLACEMENT
     * Only runs if all validation above has passed, so a
     * failed description/category check never touches files
     * on disk without a matching DB update.
     * ------------------------------------------------------- */
    $newImagePath = $existingPost['post_img']; /* default: keep the existing image */
    $uploadOk = 1;

    if ($updateError === null && !empty($_FILES['post_img']) && $_FILES['post_img']['error'] === UPLOAD_ERR_OK) {

        $target_dir = "uploads/";

        /* Create the uploads folder if it somehow doesn't exist */
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        /* Give the new file a unique name so it never collides with existing files */
        $clean_file_name = str_replace(' ', '_', basename($_FILES['post_img']['name']));
        $candidate_path = $target_dir . uniqid() . "_" . $clean_file_name;
        $imageFileType = strtolower(pathinfo($candidate_path, PATHINFO_EXTENSION));

        /* Verify it is actually an image, not a renamed file */
        $check = getimagesize($_FILES['post_img']['tmp_name']);
        if ($check === false) {
            $uploadOk = 0;
            $updateError = "Uploaded file is not a valid image.";
        }

        /* Enforce the 800KB size limit */
        if ($uploadOk === 1 && $_FILES['post_img']['size'] > 800000) {
            $uploadOk = 0;
            $updateError = "Image file is too large. Maximum size is 800KB.";
        }

        /* Only allow JPG, JPEG, and PNG */
        if ($uploadOk === 1 && $imageFileType !== "jpg" && $imageFileType !== "jpeg" && $imageFileType !== "png") {
            $uploadOk = 0;
            $updateError = "Only JPG, JPEG, and PNG images are allowed.";
        }

        if ($uploadOk === 1) {
            if (move_uploaded_file($_FILES['post_img']['tmp_name'], $candidate_path)) {

                /* New image saved — delete the old one so we don't leave orphaned files */
                if (!empty($existingPost['post_img']) && file_exists($existingPost['post_img'])) {
                    unlink($existingPost['post_img']);
                }

                $newImagePath = $candidate_path;

            } else {
                $uploadOk = 0;
                $updateError = "Image could not be saved. Please try again.";
            }
        }
    }


    /* -------------------------------------------------------
     * RUN THE UPDATE QUERY
     * Only runs if all validation above passed with no errors.
     * We also re-check ownership in the WHERE clause as a
     * second layer of protection against tampered requests.
     * ------------------------------------------------------- */
    if ($updateError === null && $uploadOk === 1) {

        $query_update = "
            UPDATE goblingizmos_postsbounties
            SET
                post_or_bounty   = ?,
                post_category    = ?,
                post_condition   = ?,
                post_boxCondition = ?,
                post_price       = ?,
                post_location    = ?,
                post_description = ?,
                post_img         = ?,
                post_sfw_nsfw    = ?
            WHERE post_id = ?
              AND user_id = ?
        ";

        $stmt = $mysqli->prepare($query_update);

        if (!$stmt) {
            die("Prepare failed: " . $mysqli->error);
        }

        /* ssssissssii
         * s - post_or_bounty
         * s - post_category
         * s - post_condition    (nullable)
         * s - post_boxCondition (nullable)
         * i - post_price        (nullable int)
         * s - post_location     (nullable)
         * s - post_description
         * s - post_img          (nullable)
         * s - post_sfw_nsfw     (nullable)
         * i - post_id
         * i - user_id
         */
        $stmt->bind_param(
            "ssssissssii",
            $submittedPostBounty,
            $submittedCategory,
            $mappedCondition,
            $mappedBoxCondition,
            $mappedPrice,
            $submittedLocation,
            $submittedDescription,
            $newImagePath,
            $mappedSfwNsfw,
            $postId,
            $_SESSION['user_id']
        );

        if ($stmt->execute()) {

            $updateSuccess = true;

            /* ---------------------------------------------------
             * REDIRECT AFTER SUCCESSFUL SAVE
             * Bounties always go to search.php (all bounties lead
             * to search — matching makePost.php behaviour).
             * Posts go to their category page.
             * --------------------------------------------------- */
            if ($submittedPostBounty === "bounty") {
                header("Location:search.php");
                exit();
            } else {
                $redirectTarget = $categoryFileMap[$submittedCategory] ?? "categories.php";
                header("Location:" . $redirectTarget);
                exit();
            }

        } else {
            $updateError = "Update failed: " . $stmt->error;
        }

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

    <title>Goblin Gizmos - Edit Post</title>
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


        <div class="FAQ">

            <h2>Edit Post</h2>

            <?php
            /* -------------------------------------------------------
             * SUCCESS / ERROR FEEDBACK
             * Errors display above the form so the user sees them
             * immediately without having to scroll.
             * ------------------------------------------------------- */
            if ($updateError !== null) {
                print "<p>" . htmlspecialchars($updateError) . "</p>";
            }
            ?>

            <!--
                enctype="multipart/form-data" is required any time a form
                includes a file upload input, otherwise the image never arrives.
                The post_id is kept in the action URL as a GET parameter so the
                page always knows which post it is editing, even on POST.
            -->
            <form method="POST"
                action="<?php print htmlspecialchars($_SERVER["PHP_SELF"]) . "?post_id=" . htmlspecialchars($postId); ?>"
                enctype="multipart/form-data">

                <!-- POST OR BOUNTY -->
                <h3>Is this a Category Post or a Bounty?</h3>
                <p>Are you showing off (post) or looking for this item (bounty)?</p>

                <?php
                /* Pre-check whichever radio button matches the saved post_or_bounty value */
                $postChecked = ($formPostBounty === "post") ? "checked" : "";
                $bountyChecked = ($formPostBounty === "bounty") ? "checked" : "";
                ?>

                <input type="radio" id="postChecked" name="post_or_bounty" value="post" <?php print $postChecked; ?>>
                <label for="postChecked">Post</label>

                <input type="radio" id="bountyChecked" name="post_or_bounty" value="bounty" <?php print $bountyChecked; ?>>
                <label for="bountyChecked">Bounty</label>


                <!-- CATEGORY -->
                <h3>Category</h3>

                <label for="post_category"></label>
                <select name="post_category" id="post_category" class="searchBarItems">

                    <?php
                    /* Build each <option> and mark the saved category as selected */
                    $categoryLabels = array(
                        "autographs" => "Autographs",
                        "books" => "Books",
                        "caps" => "Bottle Caps",
                        "cans" => "Cans",
                        "charms" => "Charms",
                        "coins" => "Coins",
                        "figures" => "Figures",
                        "jewelry" => "Jewelry",
                        "magnets" => "Magnets",
                        "minerals" => "Minerals",
                        "perfume" => "Perfume",
                        "plates" => "Plates",
                        "cards" => "Playing Cards",
                        "plushies" => "Plushies",
                        "prints" => "Prints",
                        "stamps" => "Stamps",
                        "tickets" => "Tickets",
                        "games" => "Video Games",
                        "vinyls" => "Vinyls",
                        "other" => "Other",
                    );

                    foreach ($categoryLabels as $value => $label) {
                        $selected = ($formCategory === $value) ? " selected=\"selected\"" : "";
                        print "<option value=\"" . htmlspecialchars($value) . "\"" . $selected . ">" . htmlspecialchars($label) . "</option>";
                    }
                    ?>

                </select>


                <!-- ITEM CONDITION (optional) -->
                <h3>Item Condition</h3>

                <?php
                /* Pre-check whichever condition radio matches the saved value.
                 * $formCondition holds the radio KEY ("likeNew"), not the DB value ("like new"). */
                ?>

                <input type="radio" id="post_condition_new" name="post_condition" value="new" <?php if ($formCondition === "new")
                    print "checked"; ?>>
                <label for="post_condition_new">New</label>

                <input type="radio" id="post_condition_likeNew" name="post_condition" value="likeNew" <?php if ($formCondition === "likeNew")
                    print "checked"; ?>>
                <label for="post_condition_likeNew">Like New</label>

                <input type="radio" id="post_condition_used" name="post_condition" value="used" <?php if ($formCondition === "used")
                    print "checked"; ?>>
                <label for="post_condition_used">Used</label>

                <input type="radio" id="post_condition_damaged" name="post_condition" value="damaged" <?php if ($formCondition === "damaged")
                    print "checked"; ?>>
                <label for="post_condition_damaged">Damaged</label>


                <!-- BOX CONDITION (optional) -->
                <h3>Box Condition</h3>

                <?php
                /* Pre-check whichever box condition radio matches the saved value.
                 * $formBoxCondition holds the radio KEY ("boxlikeNew"). */
                ?>

                <input type="radio" id="post_boxCondition_new" name="post_boxCondition" value="boxnew" <?php if ($formBoxCondition === "boxnew")
                    print "checked"; ?>>
                <label for="post_boxCondition_new">New</label>

                <input type="radio" id="post_boxCondition_likeNew" name="post_boxCondition" value="boxlikeNew" <?php if ($formBoxCondition === "boxlikeNew")
                    print "checked"; ?>>
                <label for="post_boxCondition_likeNew">Like New</label>

                <input type="radio" id="post_boxCondition_used" name="post_boxCondition" value="boxused" <?php if ($formBoxCondition === "boxused")
                    print "checked"; ?>>
                <label for="post_boxCondition_used">Used</label>

                <input type="radio" id="post_boxCondition_damaged" name="post_boxCondition" value="boxdamaged" <?php if ($formBoxCondition === "boxdamaged")
                    print "checked"; ?>>
                <label for="post_boxCondition_damaged">Damaged</label>


                <!-- PRICE (optional) -->
                <h3>Price/Currency (if one)</h3>

                <label for="post_price"></label>
                <!-- Value is pre-filled with the saved price; blank if no price was set -->
                <input type="text" id="post_price" name="post_price" placeholder="Enter Price"
                    value="<?php print htmlspecialchars($formPrice); ?>">


                <!-- LOCATION (optional) -->
                <h3>Location (Optional)</h3>

                <input type="text" id="post_location" name="post_location" placeholder="Enter Location"
                    value="<?php print htmlspecialchars($formLocation); ?>">


                <!-- DESCRIPTION (required) -->
                <h3>Description</h3>

                <!-- The textarea content must sit between the tags with no extra whitespace
                     or the pre-filled text will have leading/trailing spaces -->
                <textarea placeholder="Input Text" class="textAreaSize" rows="7" cols="50" id="post_description"
                    name="post_description"><?php print htmlspecialchars($formDescription); ?></textarea>


                <!-- IMAGE (optional replacement) -->
                <h3>Image</h3>

                <?php
                /* Show the current image so the user knows what is already saved */
                if (!empty($existingPost['post_img'])) {
                    print "<p>Current image:</p>";
                    print "<img src=\"" . htmlspecialchars($existingPost['post_img']) . "\" alt=\"current post image\" style=\"max-width:200px;\">";
                    print "<p>Upload a new image below to replace it, or leave blank to keep the current one.</p>";
                }
                ?>

                <div class="addBoxPost">
                    <img src="img/image.png" class="iconImg" alt="small picture box icon"
                        onclick="document.getElementById('imagePostEdit').click()">
                    <label for="imagePostEdit"></label>
                    <input type="file" id="imagePostEdit" name="post_img">
                </div>


                <!-- CONTENT LEVEL (optional) -->
                <h3>Content Level</h3>
                <p>Can this be shown to a child? (SFW = Safe for work)</p>
                <p>If not, mark the post as 'NSFW' (NSFW = Not safe for work)</p>

                <input type="radio" id="post_sfw_nsfw_sfw" name="post_sfw_nsfw" value="sfw" <?php if ($formSfwNsfw === "sfw")
                    print "checked"; ?>>
                <label for="post_sfw_nsfw_sfw">SFW</label>

                <input type="radio" id="post_sfw_nsfw_nsfw" name="post_sfw_nsfw" value="nsfw" <?php if ($formSfwNsfw === "nsfw")
                    print "checked"; ?>>
                <label for="post_sfw_nsfw_nsfw">NSFW</label>


                <!-- Submit uses the standard site button style -->
                <div>
                    <button type="submit" name="submit_edit" class="goblinButtons" id="holdingSpace">Save
                        Changes</button>
                </div>

            </form>

            <!-- Cancel returns to the category page without saving anything -->
            <?php
            /* Build the cancel link so it returns to the right page for this post's category */
            $cancelHref = $categoryFileMap[$existingPost['post_category']] ?? "categories.php";
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