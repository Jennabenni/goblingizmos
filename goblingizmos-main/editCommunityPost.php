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
    header("Location: signIn.php");
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
 * VALIDATE THE 'compost_id' URL PARAMETER
 * Cast to int immediately to prevent any injection risk before
 * prepared statements even come into play.
 * --------------------------------------------------------------- */
$compostId = isset($_GET['compost_id']) ? (int) $_GET['compost_id'] : 0;

if ($compostId <= 0) {
    header("Location: community.php");
    exit();
}


/* ---------------------------------------------------------------
 * FETCH THE EXISTING COMMUNITY POST
 * We need the current values to pre-fill the edit form, and we
 * also need user_id to verify the requester is the author.
 * --------------------------------------------------------------- */
$existingPost = null;

$query_fetch = "
    SELECT
        compost_id,
        user_id,
        compost_description,
        compost_category,
        compost_sfw_nsfw,
        compost_img
    FROM goblingizmos_community
    WHERE compost_id = ?
    LIMIT 1
";

$stmt = $mysqli->prepare($query_fetch);

if (!$stmt) {
    die("Prepare failed: " . $mysqli->error);
}

$stmt->bind_param("i", $compostId);
$stmt->execute();
$resultFetch = $stmt->get_result();

if ($resultFetch && $resultFetch->num_rows > 0) {
    $existingPost = $resultFetch->fetch_array(MYSQLI_ASSOC);
}

$stmt->close();


/* ---------------------------------------------------------------
 * OWNERSHIP CHECK
 * Only the original author may edit their own community post.
 * Admins can delete posts but cannot edit them — that is by design.
 * If the post does not exist or the user is not the author, redirect.
 * --------------------------------------------------------------- */
if (!$existingPost || (int) $existingPost['user_id'] !== (int) $_SESSION['user_id']) {
    header("Location: community.php");
    exit();
}


/* ---------------------------------------------------------------
 * WHITELIST FOR CATEGORY DROPDOWN
 * Same list used in community.php when creating a post.
 * Only these exact values will be accepted from the form.
 * --------------------------------------------------------------- */
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


/* ---------------------------------------------------------------
 * VARIABLES FOR FORM STATE AND ERROR DISPLAY
 * Initialised from the existing post so the form is pre-filled
 * on first load. If the update fails validation, these hold the
 * values the user typed so they don't lose their work.
 * --------------------------------------------------------------- */
$formDescription = $existingPost['compost_description'];
$formCategory    = $existingPost['compost_category'];
$formSfwNsfw     = $existingPost['compost_sfw_nsfw'];
$updateError     = null;
$updateSuccess   = false;


/* ---------------------------------------------------------------
 * HANDLE THE FORM SUBMISSION (POST request)
 * This block only runs when the user submits the edit form.
 * --------------------------------------------------------------- */
if (isset($_POST['submit_edit'])) {

    /* Pull submitted values and trim whitespace from the description */
    $submittedDescription = trim($_POST['compost_description'] ?? "");
    $submittedCategory    = $_POST['compost_category'] ?? "";
    $submittedSfwNsfw     = $_POST['compost_sfw_nsfw'] ?? null;

    /* Update the form variables so the page re-fills correctly if
     * there is a validation error and the form needs to be reshown */
    $formDescription = $submittedDescription;
    $formCategory    = $submittedCategory;
    $formSfwNsfw     = $submittedSfwNsfw;

    /* -------------------------------------------------------
     * VALIDATE CATEGORY against the whitelist
     * If the submitted value is not in the allowed list, reject it.
     * ------------------------------------------------------- */
    if (!in_array($submittedCategory, $allowedCategories, true)) {
        $updateError = "Please select a valid category.";
    }

    /* -------------------------------------------------------
     * VALIDATE SFW/NSFW
     * Only "SFW", "NSFW", or nothing (null/empty) are allowed.
     * ------------------------------------------------------- */
    if ($updateError === null) {
        if (!empty($submittedSfwNsfw) && $submittedSfwNsfw !== "SFW" && $submittedSfwNsfw !== "NSFW") {
            $updateError = "Invalid rating selection.";
        }
    }

    /* -------------------------------------------------------
     * VALIDATE DESCRIPTION
     * The description is required — an empty post makes no sense.
     * ------------------------------------------------------- */
    if ($updateError === null && empty($submittedDescription)) {
        $updateError = "Post description cannot be empty.";
    }

    /* -------------------------------------------------------
     * HANDLE OPTIONAL IMAGE REPLACEMENT
     * If the user uploaded a new image, validate and save it.
     * If no new image was uploaded, keep the existing one.
     * This block is intentionally inside the $updateError === null
     * check so that a failed description/category validation never
     * causes us to move files on disk without updating the database.
     * ------------------------------------------------------- */
    $newImagePath = $existingPost['compost_img']; /* default: keep old image */
    $uploadOk     = 1;

    if ($updateError === null && !empty($_FILES['compost_img']) && $_FILES['compost_img']['error'] === UPLOAD_ERR_OK) {

        $target_dir = "uploads/";

        /* Create the uploads folder if it somehow doesn't exist */
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        /* Give the new file a unique name so it never collides with existing files */
        $clean_file_name = str_replace(' ', '_', basename($_FILES['compost_img']['name']));
        $candidate_path  = $target_dir . uniqid() . "_" . $clean_file_name;
        $imageFileType   = strtolower(pathinfo($candidate_path, PATHINFO_EXTENSION));

        /* Verify it is actually an image, not a renamed file */
        $check = getimagesize($_FILES['compost_img']['tmp_name']);
        if ($check === false) {
            $uploadOk    = 0;
            $updateError = "Uploaded file is not a valid image.";
        }

        /* Enforce the 800KB size limit */
        if ($uploadOk === 1 && $_FILES['compost_img']['size'] > 800000) {
            $uploadOk    = 0;
            $updateError = "Image file is too large. Maximum size is 800KB.";
        }

        /* Only allow JPG, JPEG, and PNG */
        if ($uploadOk === 1 && $imageFileType !== "jpg" && $imageFileType !== "jpeg" && $imageFileType !== "png") {
            $uploadOk    = 0;
            $updateError = "Only JPG, JPEG, and PNG images are allowed.";
        }

        if ($uploadOk === 1) {
            if (move_uploaded_file($_FILES['compost_img']['tmp_name'], $candidate_path)) {

                /* New image saved — delete the old one so we don't leave orphaned files */
                if (!empty($existingPost['compost_img']) && file_exists($existingPost['compost_img'])) {
                    unlink($existingPost['compost_img']);
                }

                $newImagePath = $candidate_path;

            } else {
                $uploadOk    = 0;
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
            UPDATE goblingizmos_community
            SET
                compost_description = ?,
                compost_category    = ?,
                compost_sfw_nsfw    = ?,
                compost_img         = ?
            WHERE compost_id = ?
              AND user_id    = ?
        ";

        $stmt = $mysqli->prepare($query_update);

        if (!$stmt) {
            die("Prepare failed: " . $mysqli->error);
        }

        /* ssssii = string, string, string, string, int, int */
        $stmt->bind_param(
            "ssssii",
            $submittedDescription,
            $submittedCategory,
            $submittedSfwNsfw,
            $newImagePath,
            $compostId,
            $_SESSION['user_id']
        );

        if ($stmt->execute()) {
            $updateSuccess = true;
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

    <title>Goblin Gizmos - Edit Community Post</title>
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


        <div class="signUpForms">

            <h2>Edit Community Post</h2>

            <?php
            /* -------------------------------------------------------
             * SUCCESS / ERROR FEEDBACK
             * Show the outcome of the last save attempt above the form.
             * On success the form stays visible so the user can keep
             * editing if they want to make further changes.
             * ------------------------------------------------------- */
            if ($updateSuccess) {
                print "<p>Post updated successfully!</p>";
            }

            if ($updateError !== null) {
                print "<p>" . htmlspecialchars($updateError) . "</p>";
            }
            ?>

            <!--
                enctype="multipart/form-data" is required any time a form
                includes a file upload input, otherwise the image never arrives.
                The compost_id is passed as a GET parameter in the action URL
                so this page always knows which post to update.
            -->
            <form method="POST"
                  action="<?php print htmlspecialchars($_SERVER["PHP_SELF"]) . "?compost_id=" . htmlspecialchars($compostId); ?>"
                  enctype="multipart/form-data">

                <!-- Description textarea — pre-filled with the current post text -->
                <div>
                    <label for="compost_description">Description</label>
                    <textarea
                        name="compost_description"
                        id="compost_description"
                        rows="3"
                        cols="30"
                    ><?php print htmlspecialchars($formDescription); ?></textarea>
                </div>

                <!-- Category dropdown — the selected option matches the saved category -->
                <div>
                    <label for="compost_category">Category</label>
                    <select name="compost_category" id="compost_category">

                        <?php
                        /* Build each <option> and mark the saved category as selected */
                        $categoryLabels = array(
                            "autographs" => "Autographs",
                            "books"      => "Books",
                            "caps"       => "Bottle Caps",
                            "cans"       => "Cans",
                            "charms"     => "Charms",
                            "coins"      => "Coins",
                            "figures"    => "Figures",
                            "jewelry"    => "Jewelry",
                            "magnets"    => "Magnets",
                            "minerals"   => "Minerals",
                            "perfume"    => "Perfume",
                            "plates"     => "Plates",
                            "cards"      => "Playing Cards",
                            "plushies"   => "Plushies",
                            "prints"     => "Prints",
                            "stamps"     => "Stamps",
                            "tickets"    => "Tickets",
                            "games"      => "Video Games",
                            "vinyls"     => "Vinyls",
                            "other"      => "Other",
                        );

                        foreach ($categoryLabels as $value => $label) {
                            /* Add selected="selected" on the option that matches the saved category */
                            $selected = ($formCategory === $value) ? " selected=\"selected\"" : "";
                            print "<option value=\"" . htmlspecialchars($value) . "\"" . $selected . ">" . htmlspecialchars($label) . "</option>";
                        }
                        ?>

                    </select>
                </div>

                <!-- SFW / NSFW radio buttons — the saved value is pre-checked -->
                <div>
                    <label for="sfw">SFW</label>
                    <input
                        type="radio"
                        name="compost_sfw_nsfw"
                        id="sfw"
                        value="SFW"
                        <?php if ($formSfwNsfw === "SFW") print "checked"; ?>
                    >

                    <label for="nsfw">NSFW</label>
                    <input
                        type="radio"
                        name="compost_sfw_nsfw"
                        id="nsfw"
                        value="NSFW"
                        <?php if ($formSfwNsfw === "NSFW") print "checked"; ?>
                    >
                </div>

                <!-- Image upload — optional replacement for the existing image -->
                <div>
                    <?php
                    /* Show the current image if one exists so the user knows what is saved */
                    if (!empty($existingPost['compost_img'])) {
                        print "<p>Current image:</p>";
                        print "<img src=\"" . htmlspecialchars($existingPost['compost_img']) . "\" alt=\"current post image\" style=\"max-width:200px;\">";
                        print "<p>Upload a new image below to replace it, or leave blank to keep the current one.</p>";
                    }
                    ?>
                    <div class="addBoxPost">
                        <img src="img/image.png" class="iconImg" alt="small picture box icon"
                             onclick="document.getElementById('imagePostEdit').click()">
                        <label for="imagePostEdit"></label>
                        <input type="file" id="imagePostEdit" name="compost_img">
                    </div>
                </div>

                <!-- Submit uses the standard site button style -->
                <button type="submit" name="submit_edit" class="goblinButtons">Save Changes</button>

            </form>

            <!-- Cancel returns to the community feed without saving anything -->
            <a href="community.php">
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
                                <li><a href="https://x.com/"><img src="img/TwitterLogo.png" class="iconImg" alt="X logo"></a></li>
                                <li><a href="https://www.instagram.com/"><img src="img/instagram.png" class="iconImg" alt="Instagram logo"></a></li>
                                <li><a href="https://www.facebook.com/"><img src="img/facebook.png" class="iconImg" alt="Facebook logo"></a></li>
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
