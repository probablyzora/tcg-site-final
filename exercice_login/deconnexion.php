<?php
session_start();
session_destroy(); // supprimer la session
header("Location: index.php"); // venir sur la page de connexion
exit();
?>