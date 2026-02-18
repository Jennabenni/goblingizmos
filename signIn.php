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
include("/Applications/XAMPP/htdocs/dig3134c/db-connect.php");
//WAIT THIS ONE WORKED??
//local

//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");
//remote

//following my old code

if (isset($_POST['submit']) && (!isset($_SESSION['logged_in']))) {
    $select_query = "SELECT * FROM goblingizmos_users";



    $select_result = $mysqli->query($select_query);
    /*if ($mysqli->error) {
        print "BAD BAD WRONG WOMP WOMP.  Message: " . $mysqli->error;

        This is always an error hence why it's a comment
    }*/

    while ($row = $select_result->fetch_object()) {
        if ((($_POST['uname']) == ($row->username)) && (md5($_POST['password']) == ($row->password))) {
            $_SESSION['logged_in'] = true;
            $_SESSION['user_logged_in'] = $row->username;
            $_SESSION['user_id'] = $row->user_id;
            $_SESSION['access_level'] = $row->access_level;
            $_SESSION['first_name'] = $row->first_name;
            $_SESSION['last_name'] = $row->last_name;
        } else {
            //You messed with the wrong house fool
        }
    }
    if (isset($_SESSION['logged_in'])) {
        header("Location: index.php");
    }

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

                    <a href="userProfile.php"> <img src="img/PFP.png" alt="Profile Picture"></a>
                    <!--PLACEHOLDER!! REPLACE LATER:  USER ICON-->
                </div>

            </div>
        </header>




        <div class="signUpGap">

            <form method="POST" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']); ?>" class="signUpForms">
                <div class="signInForm">
                    <div>
                        <h2>Sign In</h2>
                    </div>

                    <!--
            <div>
                <label for="uname">Username</label>
            </div>
            -->

                    <div>
                        <input type="text" id="uname" name="uname" placeholder="Username/Email" class="inputBoxes">
                    </div>

                    <!--
            <div>
                <label for="password">Password</label>
            </div>

            -->


                    <div>

                        <input type="text" id="password" name="password" placeholder="Password" class="inputBoxes">

                    </div>

                    <a href="signIn.php">Forgot Password?</a>

                    <div>
                        <input name="submit" type="submit" value="Login" id="submit" class="goblinButtons">
                    </div>


                </div>
            </form>













            <form action="signIn.php" class="signUpForms">
                <div class="signInForm">
                    <div>
                        <h2>Sign Up</h2>
                    </div>

                    <!--
            <div>

                <label for=" email">Email</label>
            </div>
            -->

                    <div>
                        <input type="text" id="email" name="email" placeholder="Email" class="inputBoxes">
                    </div>

                    <!--
            <div>
                <label for="password">Password</label>
            </div>
            -->
                    <div>
                        <input type="text" id="password" name="password" placeholder="Password" class="inputBoxes">
                    </div>

                    <!-- <div>
                        <input type="submit" value="Create Account" class="goblinButtons">
                    </div>-->
                    <!--This one would push it-->

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