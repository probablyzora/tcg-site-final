<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
</head>

<body>

    <form method="POST" action="register.php">
        <label for="email">Email</label>
        <input type="email" name="email" required>

        <label for="password">Mot de passe</label>
        <input type="password" name="mot_de_passe" required>
        <button type="submit"> S'inscrire</button>
    </form>
    <div>
        <a href="index.php">Se connecter</a>
    </div>
</body>

</html>