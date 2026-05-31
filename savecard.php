<?php
session_start();
if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = new PDO('mysql:host=localhost;dbname=tcg_site', 'root', '');
    $stmt = $pdo->prepare("INSERT INTO cards (user_id, poke_id, poke_name) VALUES (?, ?, ?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['id'],
        $_POST['name'] // ajouter id et nom de cartes appartenant a user id
    ]);
}
?>