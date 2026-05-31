<?php
session_start();

try {
    $pdo = new PDO('mysql:host=localhost;dbname=exercice_login', 'root', '');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = trim($_POST['email']);
        $password = trim($_POST['mot_de_passe']);

        $request = $pdo->prepare('SELECT * FROM utilisateurs WHERE email = ?'); 
        $request->execute([$email]);
        $user = $request->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['mot_de_passe'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header('Location: accueil.php'); // aller dans l'accueil/ page protegé si le bon mot de passe utilisé, mettre l'id et email comme données dans la session
            exit();
        } else {
            echo 'Identifiants incorrects';
        }
    }
} catch (PDOException $e) {
    echo 'erreur'.$e->getMessage(); // erreur
}
?>