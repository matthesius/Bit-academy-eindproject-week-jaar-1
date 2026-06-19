<?php

require '../DB.php';

$stmt = $pdo->prepare("SELECT COUNT(id) FROM quizzes");
$stmt->execute();

$quiz_info = $stmt->fetch();
$quiz['quiz_count'] = $quiz_info[0];

$stmt = $pdo->prepare("SELECT COUNT(id) FROM questions");
$stmt->execute();

$quiz_info = $stmt->fetch();
$quiz['question_count'] = $quiz_info[0];

?>