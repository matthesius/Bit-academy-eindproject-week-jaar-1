<?php

require '../DB.php';

session_start();

if (isset($_SESSION['LoggedInQuizTaker'])) {
    header("Location: ../HomePage/homepage.php");
    exit;
}

$naam = null;
$wachtwoord = null;

if (isset($_POST['account']) && isset($_POST['password'])) {
        $account = $_POST['account'];
        $password = $_POST['password'];
    
    
        $stmt = $pdo->prepare("SELECT password, id FROM users WHERE username = :username OR email = :email");
    
        $stmt->bindParam(':username', $account, PDO::PARAM_STR);
        $stmt->bindParam(':email', $account, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();

    if (password_verify("$password", $user['password'])) {
        $_SESSION['LoggedInQuizTaker'] = $user['id'];
        unset($_SESSION['loginError']);
        unset($_SESSION['SignUpError']);
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
