<?php

require '../DB.php';
require '../LogInPage/login_check.php';

$user;

$stmt = $pdo->prepare("SELECT id, username, email, created_at FROM users WHERE id = :id");
$stmt->bindParam(':id', $_SESSION['LoggedInQuizTaker'], PDO::PARAM_STR);
$stmt->execute();
$user = $stmt->fetch();

?>