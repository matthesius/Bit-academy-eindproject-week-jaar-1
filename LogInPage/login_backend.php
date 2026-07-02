<?php

require '../DB.php';

session_start();

if (isset($_SESSION['LoggedInQuizTaker'])) {
    header("Location: ../HomePage/homepage.php");
    exit;
}

if (isset($_SESSION['sleep_until'])) {      //blocks login attempts when timed out
    $current_time = explode(" ", microtime());
    $exploded_target_time = explode(" ", $_SESSION['sleep_until']);
    $time_until_retry = $exploded_target_time[1] - $current_time[1];
    if ($time_until_retry > 0) {
        $_SESSION['loginError'] = "you are being rate limited. try again in $time_until_retry seconds";
        header("Location: login.php");
        exit;
    } else {
        unset($_SESSION['sleep_until']);
    }
}

$naam = null;
$wachtwoord = null;

function login_throttling() //stopt login attempt spam
{
    $login_penalties = [30, 60, 180, 300, 600]; // 30s, 1m, 3m, 5m, 10m penalties based on login attemtps

    $current_time = microtime();
    $_SESSION['failed_logins'][] = $current_time;
    $current_time = explode(" ", $current_time);

    switch (count($_SESSION['failed_logins'])) {
        case 3:
            $target_time = "$current_time[0] " . $current_time[1] + $login_penalties[0];
            $_SESSION['sleep_until'] = $target_time;
            break;
        case 6:
            $target_time = "$current_time[0] " . $current_time[1] + $login_penalties[1];
            $_SESSION['sleep_until'] = $target_time;
            break;
        case 9:
            $target_time = "$current_time[0] " . $current_time[1] + $login_penalties[2];
            $_SESSION['sleep_until'] = $target_time;
            break;
        case 15:
            $target_time = "$current_time[0] " . $current_time[1] + $login_penalties[3];
            $_SESSION['sleep_until'] = $target_time;
            break;
        case count($_SESSION['failed_logins']) >= 20:
            $target_time = "$current_time[0] " . $current_time[1] + $login_penalties[4];
            $_SESSION['sleep_until'] = $target_time;
            break;
        default:
            unset($_SESSION['sleep_until']);
            $target_time = 0;
    }

    if ($target_time !== 0) {
        $current_time = explode(" ", microtime());
        $exploded_target_time = explode(" ", $target_time);
        $time_until_retry = $exploded_target_time[1] - $current_time[1];
        $_SESSION['loginError'] = "you are being rate limited. try again in $time_until_retry seconds";
    }
    header("Location: login.php");
    exit;
}

if (isset($_POST['account']) && isset($_POST['password'])) {
        $account = $_POST['account'];
        $password = $_POST['password'];
    
    
        $stmt = $pdo->prepare("SELECT password, id FROM users WHERE username = :username OR email = :email");
    
        $stmt->bindParam(':username', $account, PDO::PARAM_STR);
        $stmt->bindParam(':email', $account, PDO::PARAM_STR);
        $stmt->execute();
        $user = $stmt->fetch();                 //vult $user met password en id ($user['password'/'id']) als de email of gebruikers naam klopt

    if (password_verify("$password", $user['password'])) {
        $_SESSION['LoggedInQuizTaker'] = $user['id'];
        unset($_SESSION['loginError']);         //verwijder errors wanneer er successvol is ingelogd
        unset($_SESSION['SignUpError']);
        unset($_SESSION['failed_logins']);
        unset($_SESSION['sleep_until']);
        header("Location: ../HomePage/homepage.php");
        exit;
    } else {
        $_SESSION['loginError'] = "foute gebruikersnaam of wachtwoord";
        login_throttling();
        header("Location: login.php");
        exit;
    }
} else {                                            //als er missende velden zijn
    $_SESSION['loginError'] = "vul de velden in";
    header("Location: login.php");
    exit;
}

?>
