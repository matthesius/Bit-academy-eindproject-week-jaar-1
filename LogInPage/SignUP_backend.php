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
    try {
        if (filter_var("$username", FILTER_VALIDATE_EMAIL)) {
            throw new Exception("email in gebruikersnaam veld" . PHP_EOL);
        }
        if ($password1 !== $password2) {                                    //checkt eerst de simpele eisen voor de sql query voor potentieel minder database lag
            throw new Exception("wachtwoorden zijn verschillend" . PHP_EOL);
        }

        $username = $_POST['username'];
        $email = $_POST['email'];
        $password1 = $_POST['password'];
        $password2 = $_POST['confirmPassword'];

        $stmt = $pdo->prepare("SELECT username, email FROM users WHERE username = :username OR email = :email");
        $stmt->bindParam(':username', $username, PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, PDO::PARAM_STR);
        $stmt->execute();
        $user_check = $stmt->fetchall();

        if (!empty($user_check)) {
            throw new Exception("gebruikersnaam of email is al in gebruik" . PHP_EOL);
        }
        unset($_SESSION['SignUpError']);        //verwijder alle errors
        unset($_SESSION['loginError']);
        unset($_SESSION['failed_logins']);
        unset($_SESSION['sleep_until']);
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
    $_SESSION['SignUpError'] = "vul al de velden in";
}

?>