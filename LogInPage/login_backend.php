<?php

require '../DB.php';

session_start();

if (isset($_SESSION['LoggedInQuizTaker'])) {
    header("Location: ../HomePage/homepage.php");
    exit;
}

$naam = null;
$wachtwoord = null;

if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = $_POST['username'];
        $password = $_POST['password'];
    
    
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = :username");
    
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();

    if (password_verify("$password", $user['password'])) {
        $_SESSION['LoggedInQuizTaker'] = $user['id'];
        unset($_SESSION['loginError']);
        header("Location: ../HomePage/homepage.php");
        exit;
    } else {
        $_SESSION['loginError'] = "foute gebruikersnaam of wachtwoord";
        header("Location: login.php");
    }
} else {
    $_SESSION['loginError'] = "vul de velden in";
    header("Location: login.php");
    exit;
}

?>
