<?php

require_once '../DB.php';

$users = [];

try {
    $stmt = $pdo->query("SELECT * FROM users");
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (PDOException $e) {
    echo "Fout bij uitvoeren van query: " . $e->getMessage() . "\n";
}

header('Content-Type: application/json');
echo json_encode($users);