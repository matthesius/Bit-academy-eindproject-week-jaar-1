<?php

require_once '../DB.php';

header("Content-Security-Policy: default-src 'self'; connect-src 'self' http://127.0.0.1:5500;");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

try {
    if (isset($_GET['option_id'])) {
        $stmt = $pdo->prepare("SELECT * FROM options WHERE id = ?");
        $stmt->execute([$_GET['option_id']]);

        $option = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($option) {
            echo json_encode($option);
        } else {
            echo json_encode([
                "error" => "Optie niet gevonden"
            ]);
        }
    } else {
        $stmt = $pdo->query("SELECT * FROM options");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (PDOException $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
