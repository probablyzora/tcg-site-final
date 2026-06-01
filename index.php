<?php
session_start();

try {
    $pdo = new PDO("mysql:host=localhost;dbname=tcg_site", "root", ""); // connection to the database
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "" . $e->getMessage();
}

$savedCards = [];
if (isset($_SESSION['user_id'])) {
    $request = $pdo->prepare("SELECT * FROM cards WHERE user_id = ?");
    $request->execute([$_SESSION['user_id']]);
    $savedCards = $request->fetchAll(PDO::FETCH_ASSOC); // session verification and fetching cards
} else
    header("Location: loginPage.php")
        ?>

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
                <!-- putting style in the div itself isn't great but i don't know and don't have enough time to fix this -->
                <!-- LOGIN -->
                <form action="login.php" method="POST" style="display:flex; gap:5px;">
                    <input type="email" name="email" placeholder="email" required>
                    <input type="password" name="password" placeholder="password" required>
                    <button type="submit" class="button">login</button>

                </form>
                <!-- REGISTER -->
                <form action="register.php" method="POST" style="display:flex; gap:5px;">
                    <input type="email" name="email" placeholder="new email" required>
                    <input type="password" name="password" placeholder="new password" required>
                    <button type="submit" class="button">register</button>
                </form>
        <!-- no longer useful because it is handled on its own page-->
            </div>
        <?php else: ?>
            <div style="text-align:center; width:100%;">
                <p>logged in as: <?= ($_SESSION['email']) ?> // <a href="logout.php">logout</a></p>
            </div>
        <?php endif; ?>
    </section>
    <section class="buttonSection">
        <div class="button top pulse">NEW</div>
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
    <section class="topPage">
        <div class="newsletter" id="newsletter">cards</div>
    </section>
    <section class="buttonSection">
        <?php if (isset($_SESSION['user_id'])): ?> <!-- show sectiom if only logged in,
            no longer useful since login is done at the page and this page is innaccessible if not logged in -->
            <input type="text" id="searchName" placeholder="enter pokemon name">
            <div class="button top" id="addButton">ADD TO DATABASE</div>
            <div class="button top" id="boosterPack">DAILY PACK</div>
        <?php else: ?> <!-- no longer useful -->
            <p>login to see your collection</p>
        <?php endif; ?> <!-- ending this part of php code -->
    </section>

    <div id="cardModal" class="cardModal"> <!-- i literally no longer know why it doesn't work when it comes to css -->
        <div style="background:orange; border-radius:20px; padding:30px; text-align:center; position:relative; min-width:220px;">
            <button id="closeModal" 
                style="position:absolute; top:10px; right:14px; background:none; border:none; cursor:pointer;">X</button>
            <!-- otherwise x doesn't show in the right place -->
                <img id="modalSprite" src="" style="width:120px; image-rendering:pixelated;">
            <h3 id="modalName"></h3>
            <p id="modalId"></p>
            <div id="modalTypes"></div>
            <p id="modalHeight"></p>
            <p id="modalWeight"></p>
        </div>
    </div>
   <section class="centerPage" id="cardCollection">
<?php foreach ($savedCards as $card):
    $spriteUrl = "https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/" . $card['poke_id'] . ".png"; 
    $primaryType = !empty($card['types']) ? explode(',', $card['types'])[0] : 'normal'; // explode -> string -> array, 
    // if types of the card is not empty ( exists ) , turn the strings of the types into arrays, then take first type. defaults to nomal
?>
    <div class="poke-card type-<?= $primaryType ?>" 
         data-id="<?= $card['poke_id'] ?>"
         data-name="<?= $card['poke_name'] ?>"
         data-sprite="<?= $spriteUrl ?>"
         data-types="<?= !empty($card['types']) ? $card['types'] : '' ?>"
         data-height="<?= $card['height'] ?? 0 ?>" 
         data-weight="<?= $card['weight'] ?? 0 ?>"> <!-- if no weight, put 0 -->
        <span class="star">☆</span> <!-- favorite system doens't work :/ -->
        <img src="<?= $spriteUrl ?>" alt="<?= $card['poke_name'] ?>" style="width:100px; image-rendering:pixelated;">
        <p>#<?= $card['poke_id'] ?> <?= $card['poke_name'] ?></p>
        <?php foreach (explode(',', !empty($card['types']) ? $card['types'] : '') as $t): ?>
            <span class="type-badge type-<?= $t ?>"><?= $t ?></span> <!-- type badge type-fire > fire < xx > -->
        <?php endforeach; ?>
    </div>
<?php endforeach; ?>
</section>
    <section class="buttonSection">
        <div class="button bottom" onclick="document.location='iotprojects.php'">IOT Projects</div>
    </section>
    <section style="style="diplay: flex; align-items:center; justify-content:center;>
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