<?php

require_once '../DB.php';

header("Content-Security-Policy: default-src 'self'; connect-src 'self' http://127.0.0.1:5500;");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

try {
    if (isset($_GET['question_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM questions WHERE id = ?");
        $stmt->execute([$_GET['question_id']]);

        $question = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($question) {
            echo json_encode($question);
        } else {
            echo json_encode([
                "error" => "Vraag niet gevonden"
            ]);
        }
    } else {
        $stmt = $pdo->query("SELECT * FROM questions");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (PDOException $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
