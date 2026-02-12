<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="css/style.css">
    <script src="js/goblinScript.js"></script>

    <title>Goblin Gizmos - Community</title>
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

        <div class="communityGridContainer">

            <div id="comGridItem1">
                <aside>

                    <h4>Advertisements</h4>
                    <div class="adBoxes">
                        <p>Tired of the ads? Wish you could get rid of them?</p>
                        <p>Call xxx-xxx-xxxx</p>
                    </div>
                    <div class="adBoxes">
                        <p>Wear protection.</p>
                        <p>Call xxx-xxx-xxxx</p>
                    </div>
                    <div class="adBoxes">
                        <p>Find your soulmate!</p>
                        <p>Call xxx-xxx-xxxx</p>
                    </div>

                </aside>
            </div>



            <div id="comGridItem2" class="secondComGridPost">
                <!--This is where user makes post PHP-->

                <div id="postItem1">
                    <img src="img/PFP.png" id="userProfilePicture" alt="profile picture">
                </div>
                <div id="postItem2">
                    <textarea name="information" rows="5" columns="30"></textarea>
                </div>
                <div id="postItem3">
                    <img src="img/image.png" class="comIcons" alt="upload image">
                    <!--This is the upload icon-->

                    <label for="category">Category</label>

                    <select name="category" id="category">
                        <option value="autographs">Autographs</option>
                        <option value="books">Books</option>
                        <option value="caps">Bottle Caps</option>
                        <option value="cans">Cans</option>
                        <option value="charms">Charms</option>
                        <option value="coins">Coins</option>
                        <option value="figures">Figures</option>
                        <option value="jewelry">Jewelry</option>
                        <option value="magnets">Magnets</option>
                        <option value="minerals">Minerals</option>
                        <option value="perfume">Perfume</option>
                        <option value="plates">Plates</option>
                        <option value="cards">Playing Cards</option>
                        <option value="plushies">Plushies</option>
                        <option value="prints">Prints</option>
                        <option value="stamps">Stamps</option>
                        <option value="tickets">Tickets</option>
                        <option value="games">Video Games</option>
                        <option value="vinyls">Vinyls</option>
                        <option value="other">Other</option>


                    </select>

                    <label for="sfw">SFW</label>
                    <input type="radio" name="sfwToggle" value="SFW">

                    <label for="nsfw">NSFW</label>
                    <input type="radio" name="sfwToggle" value="NSFW">
                </div>

                <div id="postItem4">
                    <img src="img/sendArrow.png" class="comIcons" alt="Post Bounty">
                </div>

            </div>



            <!--Community and friend tab

        why do we even have a friends tab

        Novatnik pressured us into making a social media feature and we folded very easily -carter
        -->

            <div id="comGridItem3">
                <ol class="comFriendsHeader">

                    <li>
                        <h2>Community</h2>
                    </li>

                    <li>
                        <h2>Friends</h2>
                    </li>

                </ol>
            </div>

            <!--Maybe they look the same but they're two different pages???
            or is this DOM?


            -->




            <div id="comGridItem4">
                <!--Post section-->
                <!--This is PHP-->
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