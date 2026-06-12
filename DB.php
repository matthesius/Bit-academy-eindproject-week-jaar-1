<?php

$host = 'aws-1-eu-central-2.pooler.supabase.com';
$port = '5432';
$dbname = 'postgres';
$user = 'postgres.vgayuiebdbpllyxlvrai';
$password = 'DemocracyRaffoxy';

if (!in_array('pgsql', PDO::getAvailableDrivers())) {
    echo "PDO PostgreSQL driver (pdo_pgsql) is niet geïnstalleerd of geactiveerd!\n";
    exit;
}

try {
    $dsn = "pgsql:host=$host;port=$port;dbname=$dbname;sslmode=require";
    $pdo = new PDO($dsn, $user, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Fout bij verbinden: " . $e->getMessage() . "\n";
    exit;
}
