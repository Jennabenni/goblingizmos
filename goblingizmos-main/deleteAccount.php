<?php
session_start();
//include("/home/ad/je686804/public_html/dig3134c/assignment03/db-connect.php");



//DOCKER CONNECTION DO NOT TOUCH ARF ARF
require 'db_connectionGG.php';



if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true || !isset($_SESSION['user_id'])) {
    header("Location: signIn.php");
    exit();
}

$message = "";
$showConfirmation = false;
$userId = $_SESSION['user_id'];

// Get current user info for header profile picture
$query_user_info_on_pages = "SELECT * FROM `goblingizmos_users` WHERE user_id = ?";
$stmtHeader = $mysqli->prepare($query_user_info_on_pages);

if (!$stmtHeader) {
    die("Prepare failed: " . $mysqli->error);
}

$stmtHeader->bind_param("i", $userId);
$stmtHeader->execute();
$resultPFP = $stmtHeader->get_result();
$row = null;

if ($resultPFP && $resultPFP->num_rows > 0) {
    $row = $resultPFP->fetch_array(MYSQLI_ASSOC);
}

$stmtHeader->close();

// verify entered password against the logged-in user's stored password
if (isset($_POST['verify'])) {
    $enteredPassword = $_POST['password'] ?? "";

    $query = "SELECT password FROM goblingizmos_users WHERE user_id = ?";
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $rowPassword = $result->fetch_assoc();

        if (md5($enteredPassword) === $rowPassword['password']) {
            $showConfirmation = true;
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "User account could not be found.";
    }

    $stmt->close();
}

// Permanently delete account only after password verification and explicit confirmation
if (isset($_POST['confirm_delete'])) {
    $enteredPassword = $_POST['password'] ?? "";

    $query = "SELECT password FROM goblingizmos_users WHERE user_id = ?";
    $stmt = $mysqli->prepare($query);

    if (!$stmt) {
        die("Prepare failed: " . $mysqli->error);
    }

    $stmt->bind_param("i", $userId);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && $result->num_rows > 0) {
        $rowPassword = $result->fetch_assoc();

        if (md5($enteredPassword) === $rowPassword['password']) {
            $deleteQuery = "DELETE FROM goblingizmos_users WHERE user_id = ?";
            $deleteStmt = $mysqli->prepare($deleteQuery);

            if (!$deleteStmt) {
                die("Prepare failed: " . $mysqli->error);
            }

            $deleteStmt->bind_param("i", $userId);

            if ($deleteStmt->execute()) {
                session_unset();
                session_destroy();
                header("Location: index.php");
                exit();
            } else {
                $message = "Account could not be deleted.";
            }

            $deleteStmt->close();
        } else {
            $message = "Incorrect password.";
        }
    } else {
        $message = "User account could not be found.";
    }

    $stmt->close();
}

// If user clicks "No", return to settings
if (isset($_POST['cancel_delete'])) {
    header("Location: settings.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Goblin Gizmos - Delete Account</title>

    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/goblinScript.js"></script>
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
                    if ($row && !empty($row['user_pfp'])) {
                        print "<a href=\"userProfile.php\"><img src=\"" . htmlspecialchars($row['user_pfp']) . "\" class=\"userIconImageForSmaller\" alt=\"User's chosen profile picture\"></a>";
                    } else {
                        print "<a href=\"userProfile.php\"><img src=\"img/PFP.png\" class=\"userIconImageForSmaller\" alt=\"Profile\"></a>";
                    }
                    ?>
                </div>

            </div>
        </header>

        <div class="entireAreaUserProfile">
            <div class="smallerProfileBox" style="display:flex; flex-direction:column; align-items:center;">

                <h2 style="text-align:center; margin-bottom:10px;">Delete Account</h2>

                <?php if (!$showConfirmation) { ?>
                    <div style="width:100%; text-align:center;">
                        <p>Please enter your password to continue.</p>
                    </div>

                    <form method="POST">
                        <div class="evenSmallerProfileBox">
                            <input type="password" name="password" placeholder="Enter password" required>
                        </div>

                        <div style="display:flex; justify-content:center; margin-top:15px;">
                            <button type="submit" name="verify" class="goblinButtons">Verify</button>
                        </div>
                </div>
                </form>
            <?php } else { ?>
                <p style="text-align:center; margin-bottom:15px;">Are you sure you want to delete your account? This cannot
                    be undone.</p>

                <form method="POST">
                    <input type="hidden" name="password" value="<?php echo htmlspecialchars($_POST['password']); ?>">

                    <div style="display:flex; justify-content:center; gap:15px; margin-top:15px;">
                        <button type="submit" name="confirm_delete" class="deleteButton">Yes, Delete My Account</button>
                        <button type="submit" name="cancel_delete" class="goblinButtons">No, Go Back</button>
                    </div>
                </form>
            <?php } ?>

            <?php
            if (!empty($message)) {
                print "<p style=\"color:red;\">" . htmlspecialchars($message) . "</p>";
            }
            ?>

        </div>
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