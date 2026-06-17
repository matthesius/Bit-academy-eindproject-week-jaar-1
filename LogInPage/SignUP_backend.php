<?php

session_start();

if (isset($_SESSION['LoggedInQuizTaker'])) {
    header("Location: ../HomePage/homepage.php");
    exit;
}

require '../DB.php';

$naam;
$email;
$password1;
$password2;

if (isset($_POST['username']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['confirmPassword'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password1 = $_POST['password'];
    $password2 = $_POST['confirmPassword'];

    $stmt = $pdo->prepare("SELECT username, email FROM users WHERE username = :username OR email = :email");
    $stmt->bindParam(':username', $username, PDO::PARAM_STR);
    $stmt->bindParam(':email', $email, PDO::PARAM_STR);
    $stmt->execute();
    $user_check = $stmt->fetchall();

//    var_dump($user_check);
//    exit;

    try {
        if (!isset($user_check)) {
            throw new Exception("gebruikersnaam of email is al in gebruik" . PHP_EOL);
        }
        if ($password1 !== $password2) {
            throw new Exception("wachtwoorden zijn verschillend" . PHP_EOL);
        }
        unset($_SESSION['SignUpError']);
        $hashed_password = password_hash("$password1", PASSWORD_DEFAULT);

        $stmt = $pdo->prepare("INSERT INTO users (username, password, email) VALUES (:username, :password, :email);");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':password', $hashed_password, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();

        $get_id = $pdo->lastInsertId();
        $id = intval($get_id);
        $_SESSION['LoggedInQuizTaker'] = $id;
        header("Location: ../HomePage/homepage.php");
    } catch (Exception $error) {
        $error_message = "error: " . $error -> getMessage();
        $_SESSION['SignUpError'] = $error_message;
        header("Location: SignUp.php");
        exit;
    }
} else {
    $_SESSION['SignUpError'] = "wat de skibidi";
}

?>