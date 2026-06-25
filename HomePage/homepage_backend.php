<?php

require '../DB.php';

//quiz and question count

$stmt = $pdo->prepare("SELECT COUNT(id) FROM quizzes");
$stmt->execute();

$quiz_info = $stmt->fetch();
$quiz['quiz_count'] = $quiz_info['count'];

$stmt = $pdo->prepare("SELECT COUNT(id) FROM questions");
$stmt->execute();

$quiz_info = $stmt->fetch();
$quiz['question_count'] = $quiz_info['count'];

//popular quizzes

$stmt = $pdo->prepare("SELECT quiz_id, COUNT(*) AS attempts FROM attempts GROUP BY quiz_id ORDER BY attempts DESC LIMIT 5");
$stmt->execute();

$popular_quizzes = $stmt->fetchall();

$index = 0;
foreach ($popular_quizzes as $popular_quiz) {
    $index++;
    $quiz['top_quizzes']["quiz_" . $index . "_id"] = $popular_quiz['quiz_id'];
    $quiz['top_quizzes']["quiz_" . $index . "_attempts"] = $popular_quiz['attempts'];
}

?>