<?php

require_once '../DB.php';

header("Content-Security-Policy: default-src 'self'; connect-src 'self' http://127.0.0.1:5500;");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

try {
    if (isset($_GET['username'])) {
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->execute([$_GET['username']]);

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            echo json_encode($user);
        } else {
            echo json_encode([
                "error" => "Gebruiker niet gevonden"
            ]);
        }
    } else {
        $stmt = $pdo->query("SELECT * FROM users");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }
} catch (PDOException $e) {
    echo json_encode([
        "error" => $e->getMessage()
    ]);
}
