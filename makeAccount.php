<?php



session_start();


ini_set('display_errors', 1);
error_reporting(E_ALL);




//need this on everything, dont forget closing tag at bottom under html

//include("../db-connect.php");
//include(__DIR__ . "/db-connect.php");
include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//WAIT THIS ONE WORKED??
//local

//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote

//This will be Inserting into the DB

/*
 - First name
- last name
- Username
 - password
- Email
- Bio
- img


- Access level will always be user
- we are inserting into the db




First and last name:
/(\b[A-Z]*[a-z]*[ ]?[']?\b)*\/
be wary of second to last slash


username
^.{1,25}$(\b[\w]*[\d]*[ ]?[']*\b)*
/^.{1,25}$(\b[\w]*[\d]*[ ]?[']*\b)*\/gm



email


/[a-z@.]+\b(com)|/gm
has to be lowercase (I dont make the rules; Past jenna does)


password
/^.{1,50}$(\b[\w]*[\d]*[ ]?[']*\b)*\/gm

made it the same as username but added characters


and ill have one that checks for special characters
if anything has this, dont submit
/[#|$|%|^|&|*|(|)|<|>|~|`]/gm



    */



//I needed it to help with apostrophes.  These are modifications to my
//code

/* FIX: collect messages so they appear in one place */
$formMessage = "";

/* FIX: use stricter login check */
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    if (isset($_POST['submit'])) {

        // Only require: first_name, last_name, uname, password, user_email
        if (
            !empty($_POST['first_name']) &&
            !empty($_POST['last_name']) &&
            !empty($_POST['uname']) &&
            !empty($_POST['password']) &&
            !empty($_POST['user_email'])
        ) {

            // Trim whitespace
            $firstName = trim($_POST['first_name']);
            $lastName = trim($_POST['last_name']);
            $username = trim($_POST['uname']);
            $password = $_POST['password'];
            $email = trim($_POST['user_email']);
            $bio = isset($_POST['user_bio']) ? trim($_POST['user_bio']) : '';

            // Kill switch: reject if contains dangerous characters
            $regExKillSwitch = "/[#|$|%|^|&|*|(|)|<|>|~|`]/";

            if (
                preg_match($regExKillSwitch, $firstName) ||
                preg_match($regExKillSwitch, $lastName) ||
                preg_match($regExKillSwitch, $username) ||
                preg_match($regExKillSwitch, $password) ||
                preg_match($regExKillSwitch, $email) ||
                preg_match($regExKillSwitch, $bio)
            ) {
                $formMessage = "Invalid characters detected";
            } else {

                /* FIX: check for duplicate username or email before insert */
                $check_existing_query = "SELECT user_id FROM `goblingizmos_users` WHERE username = ? OR user_email = ?";
                $check_stmt = $mysqli->prepare($check_existing_query);

                if (!$check_stmt) {
                    die("Prepare failed: " . $mysqli->error);
                }

                $check_stmt->bind_param("ss", $username, $email);
                $check_stmt->execute();
                $check_result = $check_stmt->get_result();

                if ($check_result && $check_result->num_rows > 0) {
                    $formMessage = "That username or email is already in use.";
                } else {

                    // Handle file upload (optional)
                    $target_file = NULL;
                    $uploadOk = 1;

                    if (!empty($_FILES['user_pfp']) && $_FILES['user_pfp']['error'] === UPLOAD_ERR_OK) {
                        $target_dir = "uploads/";

                        /* FIX: create uploads folder if it does not exist */
                        if (!is_dir($target_dir)) {
                            mkdir($target_dir, 0777, true);
                        }

                        /* FIX: make uploaded filenames unique */
                        $clean_file_name = str_replace(' ', '_', basename($_FILES['user_pfp']["name"]));
                        $target_file = $target_dir . uniqid() . "_" . $clean_file_name;
                        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

                        // Check if file is an image
                        $check = getimagesize($_FILES["user_pfp"]["tmp_name"]);
                        if ($check === false) {
                            $formMessage = "File is not an image.";
                            $uploadOk = 0;
                        }

                        if ($_FILES['user_pfp']["size"] > 800000) {
                            $formMessage = "Your file is too large.";
                            $uploadOk = 0;
                        }

                        if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg") {
                            $formMessage = "Please choose an image that is either JPG, JPEG, or a PNG file.";
                            $uploadOk = 0;
                        }

                        if ($uploadOk == 1) {
                            if (!move_uploaded_file($_FILES['user_pfp']["tmp_name"], $target_file)) {
                                $formMessage = "File upload failed";
                                $uploadOk = 0;
                            }
                        }
                    }

                    // Insert user with prepared statement
                    if ((($target_file != NULL) && ($uploadOk == 1)) || $target_file == NULL) {

                        $hashedPassword = md5($password);
                        $userAccessLevel = "user";

                        $insert_user_info_query = "INSERT INTO `goblingizmos_users`
                        (`user_id`, `first_name`, `last_name`, `username`, `password`, `access_level`, `user_email`, `user_pfp`, `user_bio`)
                        VALUES (NULL, ?, ?, ?, ?, ?, ?, ?, ?)";

                        $stmt = $mysqli->prepare($insert_user_info_query);

                        if (!$stmt) {
                            die("Prepare failed: " . $mysqli->error);
                        }

                        $stmt->bind_param(
                            "ssssssss",
                            $firstName,
                            $lastName,
                            $username,
                            $hashedPassword,
                            $userAccessLevel,
                            $email,
                            $target_file,
                            $bio
                        );

                        if ($stmt->execute()) {
                            header("Location: signIn.php");
                            exit;
                        } else {
                            $formMessage = "Error creating account: " . $stmt->error;
                        }
                        $stmt->close();
                    }
                }

                $check_stmt->close();
            }
        } else {
            $formMessage = "Required fields: First Name, Last Name, Username, Password, and Email";
        }
    }


} else if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
    $formMessage = "You are currently logged in to an account.";
}




?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goblin Gizmos - Sign In</title>

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
                    /*
                    if (isset($_SESSION['logged_in'])) {
                        $row = $result->fetch_array(MYSQLI_ASSOC);

                        if ($row['user_pfp'] != '') {
                            print "<a href=\"userProfile.php\"><img src=\"" . $row['user_pfp'] . "\"
                            class=\"userIconImageForSmaller\" alt=\"User's chosen profile picture\"></a>";
                        } else if ($row['user_pfp'] == '') {
                            print "<a href=\"userProfile.php\"> <img src=\"img/PFP.png\" class=\"userIconImageForSmaller\"
                            alt=\"Profile\"></a>";
                        }
                    }


                    if (!isset($_SESSION['logged_in'])) {
                        print "<a href=\"userProfile.php\"> <img src=\"img/PFP.png\" class=\"userIconImageForSmaller\"
                            alt=\"Profile\"></a>";
                    }*/

                    print "<a href=\"userProfile.php\"> <img src=\"img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                    ?>

                </div>

            </div>
        </header>

        <div>

            <?php
            if (!empty($formMessage)) {
                print "<p>" . htmlspecialchars($formMessage) . "</p>";
            }
            ?>

            <form method="POST" action="<?php print htmlspecialchars($_SERVER["PHP_SELF"]); ?>"
                enctype="multipart/form-data" class="FAQ">


                <div class="signInForm">

                    <h3>Please fill out the following information</h3>

                    <!--MAKING AN ACCOUNT

            - First name
            - last name
            - Username
            - password
            - Email
            - Bio
            - img


            - Access level will always be user
            - we are inserting into the db

            -->

                    <div>
                        <input type="text" id="first_name" name="first_name" placeholder="First Name"
                            class="inputBoxes">
                    </div>


                    <div>
                        <input type="text" id="last_name" name="last_name" placeholder="Last Name" class="inputBoxes">
                    </div>


                    <div>
                        <input type="text" id="uname" name="uname" placeholder="Username" class="inputBoxes">
                    </div>

                    <div>

                        <input type="password" id="password" name="password" placeholder="Password" class="inputBoxes">

                    </div>


                    <div>

                        <input type="text" id="user_email" name="user_email" placeholder="Email" class="inputBoxes">

                    </div>


                    <div>

                        <textarea placeholder='Bio' class='textAreaSize' rows='7' cols='30' id='user_bio'
                            name='user_bio'></textarea>
                    </div>


                    <div class='addBoxPost'>
                        <img src="img/image.png" class="iconImg" alt="small picture box icon">
                        <label for="imagePost"></label>
                        <input type="file" id="user_pfp" name="user_pfp">
                    </div>




                    <div>
                        <input name="submit" type="submit" value="Sign Up" id="submit" class="goblinButtons">
                    </div>

                </div>

            </form>


        </div>





    </div> <!--when you do php, keep this div after it, it's for the footer staying sticky-->



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