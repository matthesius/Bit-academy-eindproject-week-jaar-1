<?php

require_once '../DB.php';

header("Content-Security-Policy: default-src 'self'; connect-src 'self' http://127.0.0.1:5500;");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

try {
    if (isset($_GET['quiz_id'])) {
        $quiz_id = $_GET['quiz_id'];

        $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
        $stmt->execute([$quiz_id]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$quiz) {
            echo json_encode(["error" => "Quiz niet gevonden"]);
            exit;
        }

        $stmt = $pdo->prepare("
            SELECT * FROM questions
            WHERE quiz_id = ?
            ORDER BY position ASC
        ");
        $stmt->execute([$quiz_id]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($questions as &$question) {
            $stmt = $pdo->prepare("
                SELECT * FROM options
                WHERE question_id = ?
            ");
            $stmt->execute([$question['id']]);
            $question['options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $quiz['questions'] = $questions;
        echo json_encode($quiz);
    } elseif (isset($_GET['title'])) {                  //tijdelijke slop voor hugo
        $title = $_GET['title'];

        $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE title = ?");
        $stmt->execute([$title]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$quiz) {
            echo json_encode(["error" => "Quiz niet gevonden"]);
            exit;
        }

        $stmt = $pdo->prepare("
            SELECT * FROM questions
            WHERE title = ?
            ORDER BY position ASC
        ");
        $stmt->execute([$title]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($questions as &$question) {
            $stmt = $pdo->prepare("
                SELECT * FROM options
                WHERE question_id = ?
            ");
            $stmt->execute([$question['id']]);
            $question['options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $quiz['questions'] = $questions;
        echo json_encode($quiz);
    } else {                                        // einde van hugo slop
        echo json_encode(["error" => "quiz_id is verplicht"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
