<?php
try {
    $pdo = new PDO('mysql:host=localhost;dbname=exercice_login', 'root','' );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = trim($_POST['email']);
        $password = trim($_POST['mot_de_passe']);

        if (!empty($email) && !empty($password)) { // si il y a bien mdp et email dans les post
            $hashedpassword = password_hash($password, PASSWORD_BCRYPT); // hacher le mdp
            $requete = $pdo->prepare('INSERT INTO utilisateurs (email, mot_de_passe) VALUES (:email, :password)'); // mettre dans la base de données l'email et mdp
            $requete->execute([
                'email' => $email,
                'password' => $hashedpassword
            ]);
            echo 'Inscription réussie';
        } else {
            echo 'Veuillez remplir tous les champs';
        }
    }
} catch (PDOException $e) {
    echo 'Erreur lors de la connexion a la base de donnees : ' . $e->getMessage();
    exit;
}
?>