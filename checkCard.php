<?php
session_start();
$pdo = new PDO('mysql:host=localhost;dbname=tcg_site', 'root', '');
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $stmt = $pdo->prepare("SELECT last_pack_opened FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $last = $row['last_pack_opened'];
    $canOpen = !$last || (new DateTime($last))->diff(new DateTime())->h >= 24;
    echo json_encode(['canOpen' => $canOpen]);
}
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $stmt = $pdo->prepare("UPDATE users SET last_pack_opened = NOW() WHERE id = ?");
    $stmt->execute([$_SESSION['user_id']]);
    echo json_encode(['updated' => true]);
}
?>