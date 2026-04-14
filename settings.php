<?php
session_start();
//make sure to have the closer at the end of html


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






ini_set('display_errors', 1);
error_reporting(E_ALL);


if (isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {


    $query_user_info_on_pages = "SELECT * FROM `goblingizmos_users` WHERE user_id = '" . $_SESSION['user_id'] . "'";


    //honestly all of this could be used

    $resultPFP = $mysqli->query($query_user_info_on_pages);



}

/*
$pfpMessage = "";

// Check if a profile picture URL was submitted and user is logged in
if (isset($_POST['pfp_url']) && isset($_SESSION['logged_in']) && isset($_SESSION['user_id'])) {

    $pfp_url = trim($_POST['pfp_url']);

    // Only update if URL is not empty
    if (!empty($pfp_url)) {

        $update_query = "UPDATE goblingizmos_users SET user_pfp = ? WHERE user_id = ?";
        $stmt = $mysqli->prepare($update_query);

        if (!$stmt) {
            die("Prepare failed: " . $mysqli->error);
        }

        // Bind values and execute
        $stmt->bind_param("si", $pfp_url, $_SESSION['user_id']);
        $stmt->execute();
        $stmt->close();

        $pfpMessage = "Profile picture updated successfully.";
    }
}*/


$pfpMessage = "";

if (
    isset($_FILES['profile_image']) &&
    $_FILES['profile_image']['error'] == UPLOAD_ERR_OK &&
    isset($_SESSION['user_id'])
) {
    $uploadDir = 'uploads/';
    $fileName = basename($_FILES['profile_image']['name']);
    $targetFile = $uploadDir . $fileName;


    $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
    if (!in_array($_FILES['profile_image']['type'], $allowedTypes)) {
        $pfpMessage = "Only JPG, JPEG, and PNG files are allowed.";
    } else {
        if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
            $update_query = "UPDATE goblingizmos_users SET user_pfp = ? WHERE user_id = ?";
            $stmt = $mysqli->prepare($update_query);
            if (!$stmt) {
                die("Prepare failed: " . $mysqli->error);
            }
            $stmt->bind_param("si", $targetFile, $_SESSION['user_id']);
            $stmt->execute();
            $stmt->close();
            $pfpMessage = "Profile picture updated successfully.";
        } else {
            $pfpMessage = "Error uploading file.";
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

    <!-- === PROFILE PICTURE UPLOAD SCRIPT === -->
    <!-- This JavaScript runs in the browser and handles sending the image to Supabase Storage -->
    <!-- <script>
        // Wait for the full page to load before trying to find elements on it
        document.addEventListener('DOMContentLoaded', function () {

            // Find the file input by its ID so we can listen for when the user picks a file
            var fileInput = document.getElementById('pfp-file-input');

            // If the file input doesn't exist on this page (user not logged in), stop here
            if (!fileInput) return;

            // Run this function every time the user picks a new file
            fileInput.addEventListener('change', async function () {

                // Get the actual file object the user selected (files[0] = first file)
                var file = fileInput.files[0];

                // If no file was selected, do nothing
                if (!file) return;

                // Your Supabase project URL (the base address for all Supabase requests)
                var supabaseUrl = 'https://iuqxnrjgatfhjclkobrk.supabase.co';

                // Your Supabase publishable anon API key — paste your real key here to replace this placeholder
                var supabaseKey = 'sb_publishable_GY3do36iVl-UMv1i5Nupyg_1HH0uJO7';

                // The name of the storage bucket you set up inside Supabase
                var bucketName = 'profile-pictures';

                // Create a unique file name using the current timestamp (milliseconds since 1970)
                // This avoids overwriting files when two users upload images with the same filename
                var fileName = Date.now() + '-' + file.name;

                // Build the full upload URL: Supabase project + storage path + bucket + file name
                var uploadUrl = supabaseUrl + '/storage/v1/object/' + bucketName + '/' + fileName;

                // Show "Uploading..." so the user knows something is happening
                document.getElementById('pfp-upload-status').textContent = 'Uploading...';

                // Send the image file to Supabase Storage using a PUT request
                // PUT means "place this file at this location"
                var response = await fetch(uploadUrl, {
                    method: 'PUT',
                    headers: {
                        'Authorization': 'Bearer ' + supabaseKey, // Proves we have permission to upload
                        'Content-Type': file.type,                 // Tells Supabase the file type (e.g. image/jpeg)
                    },
                    body: file  // The actual image data being uploaded
                });

                // Check whether the upload succeeded (HTTP 200 = OK)
                if (response.ok) {

                    // Build the public URL — this is the permanent link to the uploaded image
                    var publicUrl = supabaseUrl + '/storage/v1/object/public/' + bucketName + '/' + fileName;

                    // Put the public URL into the hidden form field so PHP can read it when the form is submitted
                    document.getElementById('pfp-url-hidden').value = publicUrl;

                    // Show a small preview of the image so the user can see what they uploaded
                    var preview = document.getElementById('pfp-preview');
                    preview.src = publicUrl;
                    preview.style.display = 'block';

                    // Tell the user the upload worked and they can now save
                    document.getElementById('pfp-upload-status').textContent = 'Image ready! Click "Save Profile Picture" to save.';

                } else {
                    // The upload failed — let the user know so they can try again
                    document.getElementById('pfp-upload-status').textContent = 'Upload failed. Please try again.';
                }
            });
        });
    </script>-->
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
                        $row = $resultPFP->fetch_array(MYSQLI_ASSOC);

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




        <?php
        if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {


            print "<div class=\"entireAreaUserProfile\">";
            print "<div class=\"smallerProfileBox\" style=\"display:flex; flex-direction:column; align-items:center;\">";

            print "<h2 style=\"margin-bottom:10px;\">Settings</h2>";
            print "<p style=\"margin-bottom:25px;\">Manage your account settings below.</p>";

            print "<h3 style=\"margin-bottom:15px;\">Profile Picture</h3>";

            print "<form method=\"POST\" enctype=\"multipart/form-data\" action=\"" . htmlspecialchars($_SERVER["PHP_SELF"]) . "\" style=\"display:flex; flex-direction:column; align-items:center; gap:12px;\">";

            if (!empty($pfpMessage)) {
                print "<p>" . htmlspecialchars($pfpMessage) . "</p>";
            }

            if (isset($row['user_pfp']) && !empty($row['user_pfp'])) {
                print "<img src=\"" . htmlspecialchars($row['user_pfp']) . "\" alt=\"Current profile picture\" style=\"max-width:150px; max-height:150px; border-radius:50%;\">";
            }

            print "<label for=\"pfp-file-input\">Choose an image:</label>";
            /* print "<input type=\"file\" id=\"pfp-file-input\" accept=\"image/*\">";

             print "<img id=\"pfp-preview\" src=\"\" alt=\"Preview of your new profile picture\" style=\"display:none; max-width:150px; max-height:150px; border-radius:50%;\">";

             print "<p id=\"pfp-upload-status\" style=\"font-style:italic;\"></p>";

             print "<input type=\"hidden\" id=\"pfp-url-hidden\" name=\"pfp_url\" value=\"\">";

             print "<button type=\"submit\" class=\"goblinButtons\">Save Profile Picture</button>";*/

            //this is copilot stuff bc pfp upload isn't working
            print "<input type=\"file\" name=\"profile_image\" accept=\"image/*\">";
            print "<button type=\"submit\" class=\"goblinButtons\">Save Profile Picture</button>";

            print "</form>";

            print "<div style=\"margin-top:25px;\">";
            print "<form action=\"deleteAccount.php\" method=\"GET\">";
            print "<button type=\"submit\" class=\"deleteButton\">Delete Account</button>";
            print "</form>";
            print "</div>";

            print "</div>";
            print "</div>";

            print "</div>";


        } else {
            print "<div class=\"signUpForms\">";
            print "<p>Please log in to access settings.</p>";
            print "<a href=\"signIn.php\">Sign In</a>";
            print "</div>";
        }
        ?>



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

                                    <li><a href="https://www.instagram.com/"> <img src="img/instagram.png"
                                                class="iconImg" alt="Instagram logo"></a>
                                    </li>

                                    <li> <a href="https://www.facebook.com/"> <img src="img/facebook.png"
                                                class="iconImg" alt="Facebook logo"></a>
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