<?php

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();    
}

if (!isset($_SESSION['LoggedInQuizTaker'])) {
    header("Location: ../LogInPage/login.php");
    exit;
}

?>