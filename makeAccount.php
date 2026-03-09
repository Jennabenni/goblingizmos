<?php



session_start();


ini_set('display_errors', 1);
error_reporting(E_ALL);




//need this on everything, dont forget closing tag at bottom under html

//include("../db-connect.php");
include("/Applications/XAMPP/htdocs/dig3134c/db-connect.php");
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




if (isset($_POST['submit'])) {


    $regExName = "/(\b[A-Z]*[a-z]*[ ]?[']?\b)*/";
    $regExUsername = "/^.{1,25}$(\b[\w]*[\d]*[ ]?[']*\b)*/";
    $regExPass = "/^.{1,50}$(\b[\w]*[\d]*[ ]?[']*\b)*/";
    $regExEmail = "/[a-z@.]+\b(com)|/";

    $regExKillSwitch = "/[#|$|%|^|&|*|(|)|<|>|~|`]/";
    //if anything has these characters ^^ KILL

    $userAccessLevel = "user";


    //I also need to sanitize
    /*

var emailRegEx = /[a-z@.]+\b(com)|[a-z@.]+\b(edu)/gm
//EUREKA
var emailRegExValidate = userEmailValue.match(emailRegEx);

    */

    $validateFirstName = preg_match($regExName, $_POST['first_name']);

    $validateLastName = preg_match($regExName, $_POST['last_name']);

    $validateUsername = preg_match($regExUsername, $_POST['uname']);

    $validatePassword = preg_match($regExPass, $_POST['password']);

    $validateEmail = preg_match($regExEmail, $_POST['user_email']);

    //equals 1 if pattern was in string
    //these should




    /*If any of these equal one, that's a no no */

    $killFirstName = preg_match($regExKillSwitch, $_POST['first_name']);
    $killLastName = preg_match($regExKillSwitch, $_POST['last_name']);
    $killUsername = preg_match($regExKillSwitch, $_POST['uname']);
    $killPass = preg_match($regExKillSwitch, $_POST['password']);
    $killEmail = preg_match($regExKillSwitch, $_POST['user_email']);
    $killBio = preg_match($regExKillSwitch, $_POST['user_bio']);




    if (
        (isset($_POST['first_name']) && (isset($_POST['last_name']) && (isset($_POST['uname']) && (isset($_POST['password'])) && (isset($_POST['user_email']))))) && (!empty($_POST['first_name'])) &&

        (!empty($_POST['last_name'])) && (!empty($_POST['uname'])) && !empty($_POST['password']) && (!empty($_POST['user_email']))

        && ($validateFirstName == 1) && ($validateLastName == 1) && ($validatePassword == 1) && ($validateEmail == 1) && ($validateUsername == 1) && (($killEmail || $killFirstName || $killLastName || $killPass || $killUsername || $killBio) != 1) && (($regExName || $regExEmail || $regExPass || $regExUsername) != 0)



        //So I dont think my regexes do anything.
        //BUT! The kill switch works
        //that's the most crucial thing

    ) {


        //We post it
        //print "<p>Success</p>";

        $target_file = NULL;
        if (!empty($_FILES['user_pfp']) && $_FILES['user_pfp']['error'] === UPLOAD_ERR_OK) {
            $target_dir = "uploads/";
            $target_file = $target_dir . basename(str_replace(' ', '_', $_FILES['user_pfp']["name"]));
            $uploadOk = 1;
            $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));



            //checks if file is an image or if its fake (whatever that means)
            if (!empty($_FILES['user_pfp'])) {
                $check = getimagesize($_FILES["user_pfp"]["tmp_name"]);
                if ($check !== false) {
                    echo "File is an image - " . $check["mime"] . ".";
                    $uploadOk = 1;
                } else {
                    echo "File is not an image.";
                    $uploadOk = 0;
                }
            }
            if ($_FILES['user_pfp']["size"] > 800000) {
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
                if (move_uploaded_file($_FILES['user_pfp']["tmp_name"], $target_file)) {
                    echo "The file was uploaded.";

                }
            }


        }




        if ((($target_file != NULL) && ($uploadOk == 1)) || $target_file == NULL) {


            $insert_user_info_query = "INSERT INTO `goblingizmos_users` (`user_id`, `first_name`, `last_name`, `username`, `password`, `access_level`, `user_email`, `user_pfp`, `user_bio`) VALUES (NULL, '" . $_POST['first_name'] . "', '" . $_POST['last_name'] . "', '" . $_POST['uname'] . "', '" . md5($_POST['password']) . "', '" . $userAccessLevel . "', '" . $_POST['user_email'] . "', " . ($target_file !== NULL ? "'" . $target_file . "'" : "NULL") . ", '" . $_POST['user_bio'] . "')";

            //need to sanitize bio to allow for apostrophes
            //I'm assuming everything else is that way too

            $mysqli->query($insert_user_info_query);



            header("Location: signIn.php");




        } else if ($uploadOk == 0) {
            //nothin.  It gives the error message
        }
    } else {

        if (($killEmail || $killFirstName || $killLastName || $killPass || $killUsername) == 1) {

            print "<p>No special characters allowed.  Please refrain from using the following symbols: '#$%^&*()[]<>;:/\'</p>";

        }

        if ($regExName == 0) {
            print "<p>Please insert your first and last name starting with a capital letter</p>";
        }

        if ($regExPass == 0) {
            print "<p>Please keep password under 50 characters, and refrain from special characters</p>";
        }

        if ($regExEmail == 0) {
            print "<p>Please enter your email in the format: 'email@email.com'</p>";
        }

        if ($regExUsername == 0) {
            print "<p>Please make sure your username is under 25 characters and refrains from using special characters.</p>";
        }

        print "<p>Your first name, last name, username, password, and email cannot be blank.  Please check that  your username is less that 25 characters, the password is less than 50 characters, and no special characters are used.</p>";

    }





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
                    ?>

                </div>

            </div>
        </header>

        <div>

            <form method="POST" action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" enctype="multipart/form-data"
                class="FAQ">


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

                        <input type="text" id="password" name="password" placeholder="Password" class="inputBoxes">

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
$mysqli->close();
?>