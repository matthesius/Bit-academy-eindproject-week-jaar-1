<?php

require '../DB.php';

session_start();

if (isset($_SESSION['loggedInUser'])) {
    header("Location: login.php");
    exit;
}

$naam = null;
$wachtwoord = null;

// var_dump($_POST);
// exit;

if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
    
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username AND password = :password");
    
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $password, PDO::PARAM_STR);
    
        $stmt->execute();
    
        $user = $stmt->fetch();
    
    if ($user !== false) {
        $_SESSION['loggedInUser'] = $user['id'];
        unset($_SESSION['error']);
        header("Location: HomePage/homepage.php");
        exit;
    } else {
        $_SESSION['error'] = "fout wachtwoord of gebruiker";
        header("Location: login.php?test");
    }
} else {
    $_SESSION['error'] = "vul de velden in";
    header("Location: login.php");
    exit;
}

?>
