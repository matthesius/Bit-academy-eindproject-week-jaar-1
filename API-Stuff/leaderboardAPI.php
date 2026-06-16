<?php

require_once '../DB.php';

header("Content-Security-Policy: default-src 'self'; connect-src 'self' http://127.0.0.1:5500;");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

try {
    if (isset($_GET['quiz_id'])) {
        $stmt = $pdo->prepare("
            SELECT u.username, a.score, a.finished_at
            FROM attempts a
            JOIN users u ON u.id = a.user_id
            WHERE a.quiz_id = ? AND a.completed = TRUE
            ORDER BY a.score DESC
            LIMIT 10
        ");
        $stmt->execute([$_GET['quiz_id']]);
        $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($scores) {
            echo json_encode($scores);
        } else {
            echo json_encode(["error" => "Geen scores gevonden voor deze quiz"]);
        }
    } else {
        echo json_encode(["error" => "quiz_id is verplicht"]);
    }
} catch (PDOException $e) {
    echo json_encode(["error" => $e->getMessage()]);
}
