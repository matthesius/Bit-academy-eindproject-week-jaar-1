<?php

require_once '../DB.php';

header("Content-Security-Policy: default-src 'self'; connect-src 'self' http://127.0.0.1:5500;");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

try {
    if (isset($_GET['attempt_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM attempt_answers WHERE id = ?");
        $stmt->execute([$_GET['attempt_id']]);

        $attempt_answer = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($attempt_answer) {
            echo json_encode($attempt_answer);
        } else {
            echo json_encode([
                "error" => "Poging niet gevonden"
            ]);
        }
    } else {
        $stmt = $pdo->query("SELECT * FROM attempt_answers");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (PDOException $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
