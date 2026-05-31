<?php   
session_start();
if (!isset($_SESSION["user_id"])){
    header("Location: index.php"); // si l'utilisateur n'est pas connecté, retourner vers la page de connexion
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    Page protegée<br>
    <a href="deconnexion.php">Déconnecter</a>
</body>
</html>