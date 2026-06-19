<?php

require_once '../DB.php';

header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

class ApiPostThingie
{
    private PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function createQuiz()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(["error" => "POST required"]);
            return;
        }

        $input = json_decode(file_get_contents('php://input'), true);
        if (!is_array($input)) {
            echo json_encode(["error" => "Invalid JSON"]);
            return;
        }

        $title = trim($input['quizname'] ?? $input['title'] ?? '');
        $description = trim($input['desc'] ?? $input['description'] ?? '');
        $imageUrl = trim($input['quizthumbnail'] ?? $input['image_url'] ?? $input['quizThumbnail'] ?? '');

        if ($title === '' || $description === '' || $imageUrl === '') {
            echo json_encode(["error" => "title, desc and image_url are required"]);
            return;
        }

        $stmt = $this->pdo->prepare("INSERT INTO quizzes (title, description, image_url) VALUES (?, ?, ?) RETURNING id, title, description, image_url, created_at");
        $stmt->execute([$title, $description, $imageUrl]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        echo json_encode(["success" => true, "quiz" => $quiz]);
    }
}

$api = new ApiPostThingie($pdo);

$action = $_GET['action'] ?? '';
if ($action === 'createQuiz') {
    $api->createQuiz();
} else {
    echo json_encode(["error" => "Onbekende actie"]);
}

//make a quiz = ../Api-Stuff/apiPostThingie.php?action=createQuiz