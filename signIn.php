<?php

/*Log in page, referenced assignment 5 from server side */

//http://localhost/goblingizmos/index.php
//http://localhost/goblingizmos/signIn.php
//this is what you copy and paste to open up the server
//does NOT need to be out of a comment


/* So I need to code for people logging in and creating a new account

I may change the sign up stuff to be
First name
Last name
Username
Email (do we need an email for anything??)
Password


Log in is just
assigned username (one that you pick) (and is visible)
pass


For the assignment 5, it had
- user ID (primary)
- username
- password
- first_name
- last_name
- access_level

now to have people make their own, they need to be able to push it to this table, similar to a post?
will need regex for this, safety!! aha

I can start with admins tho
MD5 is for hiding passwords


Regex ideas:

- only letters and numbers and specific special characters if we do emails (@)
- prevent user from putting code injections GET POST





*/





session_start();
//need this on everything, dont forget closing tag at bottom under html




//include("../db-connect.php");
//include(__DIR__ . "/db-connect.php");
//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//WAIT THIS ONE WORKED??
//local


include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote

//following my old code


ini_set('display_errors', 1);
error_reporting(E_ALL);




/* FIX: initialize row and login message */
$row = null;
$loginMessage = "";

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






if (
    isset($_POST['submit']) &&
    (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true)
) {
    /* FIX: query only the username being attempted instead of all users */
    $select_query = "SELECT * FROM `goblingizmos_users` WHERE username = ? LIMIT 1";

    $select_stmt = $mysqli->prepare($select_query);


    if (!$select_stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $submittedUsername = trim($_POST['uname'] ?? "");
    $submittedPassword = $_POST['password'] ?? "";

    $select_stmt->bind_param("s", $submittedUsername);
    $select_stmt->execute();
    $select_result = $select_stmt->get_result();

    if ($select_result && $select_result->num_rows > 0) {
        $userRow = $select_result->fetch_object();

        if (($submittedUsername == $userRow->username) && (md5($submittedPassword) == $userRow->password)) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_logged_in'] = $userRow->username;
            $_SESSION['user_id'] = $userRow->user_id;
            $_SESSION['access_level'] = $userRow->access_level;
            $_SESSION['first_name'] = $userRow->first_name;
            $_SESSION['last_name'] = $userRow->last_name;

            header("Location: index.php");
            exit();
        } else {
            $loginMessage = "Invalid username or password.";
        }
    } else {
        $loginMessage = "Invalid username or password.";
    }

    $select_stmt->close();

    //OMG I HAD .html... BROTHER


}



//ill be back




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

        <div class="signUpForms">
            <p>If you have been brought to this page after creating an account, please sign in below.</p>
        </div>

        <?php
        if (!empty($loginMessage)) {
            print "<div class=\"signUpForms\"><p>" . htmlspecialchars($loginMessage) . "</p></div>";
        }
        ?>

        <div class="signUpGap">

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="signUpForms">
                <div class="signInForm">
                    <div>
                        <h2>Sign In</h2>
                    </div>


                    <div>
                        <input type="text" id="uname" name="uname" placeholder="Username" class="inputBoxes">
                    </div>


                    <div>

                        <input type="password" id="password" name="password" placeholder="Password" class="inputBoxes">

                    </div>

                    <a href="signIn.php">Forgot Password?</a>

                    <div>
                        <input name="submit" type="submit" value="Login" id="submit" class="goblinButtons">
                    </div>


                </div>
            </form>












            <div>

                <div class="signUpForms">
                    <h2 class="goblinButtons"><a href="makeAccount.php">Sign Up</a></h2>
                </div>


            </div>
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