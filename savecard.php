<?php
session_start();
if (isset($_SESSION['user_id']) && $_SERVER['REQUEST_METHOD'] == 'POST') {
    $pdo = new PDO('mysql:host=localhost;dbname=tcg_site', 'root', '');
    $stmt = $pdo->prepare("INSERT INTO cards (user_id, poke_id, poke_name, types, height, weight) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([
        $_SESSION['user_id'],
        $_POST['id'],
        $_POST['name'], // registers the card with user id and info
        $_POST['types'],
        $_POST['height'],
        $_POST['weight'],
    ]);
}
?>