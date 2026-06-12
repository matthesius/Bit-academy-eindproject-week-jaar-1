<?php

require_once '../DB.php';

header("Content-Security-Policy: default-src 'self'; connect-src 'self' http://127.0.0.1:5500;");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

try {
    if (isset($_GET['quiz_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
        $stmt->execute([$_GET['quiz_id']]);

        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($quiz) {
            echo json_encode($quiz);
        } else {
            echo json_encode([
                "error" => "Quiz niet gevonden"
            ]);
        }
    } else {
        $stmt = $pdo->query("SELECT * FROM quizzes");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (PDOException $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
