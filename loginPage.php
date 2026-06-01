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
    <!-- ## Top of page w/ Logo n dark/light mode-->
    <section class="topPage">
        <!-- <div class="logo" id="logo">a legally distinct <br>pocket monsters™<br>trading card game<br>website</div> -->
        <div class="logo" id="logo"><img src="assets/logo.png"></div>
    </section>
    <!-- ########################################-->
    <section class="topPage">
        <div class="newsletter" id="newsletter" style:"background-color:>sign in / register</div>
    </section>
    <!-- ###### center w/ swiperjs and glightbox integration and text-->
    <section class="loginSection">
        <?php if (!isset($_SESSION['user_id'])): ?>
            <div>
                <!-- pas classe mais on peux mettre ca en dehors du css car j'aimerai bien rendre tout ca -->
                <!-- LOGIN -->
                <form action="login.php" method="POST">
                    <input type="email" name="email" placeholder="email" required>
                    <input type="password" name="password" placeholder="password" required>
                    <button type="submit" class="button">login<br></button>
                </form>
            </div>
            <br>
            <div>
                <!-- ENREGISTRER -->
                <form action="register.php" method="POST">
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
    <script src="https://cdn.jsdelivr.net/gh/mcstudios/glightbox/dist/js/glightbox.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/swiper@12/swiper-bundle.min.js"></script>
    <script src="tcgLogin.js"></script>
</body>