<?php

if (!isset($_SESSION['LoggedInQuizTaker'])) {
    header("Location: ../LogInPage/login.php");
    exit;
}

?>