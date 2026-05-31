<?php   
try {
    $pdo = new PDO('mysql:host=localhost;dbname=tcg_site', 'root', '');
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        $email = trim($_POST['email']);
        $password = trim($_POST['password']);   
        if (!empty($email) && !empty($password)) {
            $hashedpassword = password_hash($password, PASSWORD_BCRYPT);
            $stmt = $pdo->prepare('INSERT INTO users (email, password) VALUES (?, ?)');
            $stmt->execute([$email, $hashedpassword]);
            header('Location: index.php'); // redir vers page normale 
        }
    }
} catch (PDOException $e) { echo 'Error: ' . $e->getMessage(); }
?>