<?php  
session_start();
try{
    $pdo = new PDO("mysql:host=localhost;dbname=tcg_site","root","");
    if ($_SERVER["REQUEST_METHOD"] == "POST"){  
    $email= trim($_POST["email"]);
    $password= trim($_POST["password"]);
    $request = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $request-> execute([$email]);
    $user = $request->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])){
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["email"] = $user["email"];
    header("Location: index.php");
    exit();
    }
    }
}catch(PDOException $e){
    echo "Error : ".$e->getMessage();
}
?>