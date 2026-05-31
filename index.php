<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=tcg_site", "root", ""); // connexion a la bd
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "" . $e->getMessage();
}

$savedCards = [];
if (isset($_SESSION['user_id'])) {
    $request = $pdo->prepare("SELECT * FROM cards WHERE user_id = ?");
    $request->execute([$_SESSION['user_id']]);
    $savedCards = $request->fetchAll(PDO::FETCH_ASSOC); // verif de session et fetch des cartes
}
else header("Location: loginPage.php")
?>
<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>bad website</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css" />
    <link rel="stylesheet" href="tcg.css" />
</head>

<body>
    <!-- Theme change button-->
    <div class="themeButton" id="themeButton"></div>
    <!---->
    <!-- ## Top of page w/ Logo n dark/light mode-->
    <section class="topPage">
        <!-- <div class="logo" id="logo">a legally distinct <br>pocket monsters™<br>trading card game<br>website</div> -->
        <div class="logo" id="logo"><img src="assets/logo.png"></div>
    </section>
    <!-- ########################################-->

    <!-- ###### center w/ swiperjs and glightbox integration and text-->
    <section class="buttonSection">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div style="display:flex; gap:10px; justify-content:center; flex-wrap:wrap;">
                <!-- pas classe mais on peux mettre ca en dehors du css car j'aimerai bien rendre tout ca -->
                <!-- LOGIN -->
                <form action="login.php" method="POST" style="display:flex; gap:5px;">
                    <input type="email" name="email" placeholder="email" required>
                    <input type="password" name="password" placeholder="password" required>
                    <button type="submit" class="button">login</button>

                </form>
                <!-- ENREGISTRER -->
                <form action="register.php" method="POST" style="display:flex; gap:5px;">
                    <input type="email" name="email" placeholder="new email" required>
                    <input type="password" name="password" placeholder="new password" required>
                    <button type="submit" class="button">register</button>
                </form>
            </div>
        <?php else: ?>
            <div style="text-align:center; width:100%;">
                <p>logged in as: <?= ($_SESSION['email']) ?> // <a href="logout.php">logout</a></p>
            </div>
        <?php endif; ?>
    </section>
    <section class="buttonSection">
        <div class="button top pulse">NEW</div>
        <div class="button top">Collection</div>
        <div class="button top">My Account</div>
    </section>
    <section class="centerPage">
        <div class="swiper">
            <!-- Additional required wrapper -->
            <div class="swiper-wrapper">
                <!-- Different slides with text -->
                <div class="swiper-slide slide1">
                    <img src="assets/background.gif" onclick="window.open(this.src, '_blank')">Lorem ipsum dolor sit
                    amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna
                    aliqua.
                </div>
                <div class="swiper-slide slide2">
                    <img src="assets/background.gif" onclick="window.open(this.src, '_blank')"> Ut enim ad minim veniam,
                    quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure
                    dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur
                    sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.
                </div>
                <!-- If we need pagination -->
                <div class="swiper-pagination"></div>
    </section>
    <section class="buttonSection">
        <?php if (isset($_SESSION['user_id'])): ?> <!-- si il y a bien la session d'un utilisateur -->
            <input type="text" id="searchName" placeholder="enter pokemon name">
            <div class="button top" id="addButton">ADD TO DATABASE</div>
        <?php else: ?> <!-- sinon message par defaut -->
            <p>login to see your collection</p>
        <?php endif; ?> <!-- arreter le if car sections php différentes -->
    </section>
    <section class="centerPage" id="cardCollection"> <?php foreach ($savedCards as $card):
        $spriteUrl = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/" . $card['poke_id'] . ".png";
        ?>
            <div class="poke-card pulse">
                <a href="<?= $spriteUrl ?>" class="glightbox" data-gallery="gallery1">
                    <img src="<?= $spriteUrl ?>" alt="<?= ($card['poke_name']) ?>"
                        style="width: 100px; image-rendering: pixelated;">
                </a>
                <p>#<?= $card['poke_id'] ?>     <?= ($card['poke_name']) ?></p>
            </div>
        <?php endforeach; ?>
    </section>
    <section class="buttonSection">
        <div class="button bottom" onclick="document.location='iotprojects.php'">IOT Projects</div>
    </section>
    <section>
        <section class="topPage">
            <div class="newsletter" id="newsletter">a really bad<br>newsletter</div>
        </section>
        <br>
        <div>
            <form action="" method="get" enctype="application/x-www-form-urlencoded">
                <div>
                    <!-- email form -->
                    <input type="email" placeholder="john.google@google.com" name="email" id="email">
                    <label for="email">Enter your e-mail.</label>
                </div>
                <div>
                    <!-- checkbox -->
                    <input type="checkbox" name="tos" class="tosCheckbox" id="tosCheckbox">
                    <label for="checkbox">You confirm that you have read the Terms of Service. </label>
                </div>
                <div class="button" id="submitButton">Submit</div>
            </form>
        </div>
        <div class="notif" id="notif">You are now subscribed to the newsletter.</div>

    </section>
    <footer>
        made by probablyzora/szmyt oliwier
    </footer>
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script src="tcg.js"></script>
</body>

</html>