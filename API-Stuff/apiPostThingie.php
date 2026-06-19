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

        $questions = $input['questions'] ?? [];
        if (!is_array($questions) || count($questions) === 0) {
            echo json_encode(["error" => "At least one question is required"]);
            return;
        }

        try {
            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("INSERT INTO quizzes (title, description, image_url) VALUES (?, ?, ?) RETURNING id, title, description, image_url, created_at");
            $stmt->execute([$title, $description, $imageUrl]);
            $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

            $stmtQuestion = $this->pdo->prepare("INSERT INTO questions (quiz_id, question_text, position) VALUES (?, ?, ?) RETURNING id, question_text, position");
            $stmtOption = $this->pdo->prepare("INSERT INTO options (question_id, option_text, is_correct) VALUES (?, ?, ?) RETURNING id, option_text, is_correct");
            $createdQuestions = [];

            foreach ($questions as $question) {
                $questionText = trim($question['question_text'] ?? $question['text'] ?? '');
                $position = isset($question['position']) ? (int)$question['position'] : 0;
                $options = $question['options'] ?? [];

                if ($questionText === '' || $position <= 0) {
                    throw new Exception('Each question needs question_text and a positive position.');
                }

                if (!is_array($options) || count($options) < 2) {
                    throw new Exception('Each question needs at least two options.');
                }

                $filledOptions = array_filter($options, fn($opt) => trim($opt['option_text'] ?? '') !== '');
                $correctCount = count(array_filter($filledOptions, fn($opt) => !empty($opt['is_correct'])));

                if ($correctCount !== 1) {
                    throw new Exception('Each question must have exactly one correct option.');
                }

                $stmtQuestion->execute([$quiz['id'], $questionText, $position]);
                $createdQuestion = $stmtQuestion->fetch(PDO::FETCH_ASSOC);

                $createdOptions = [];
                foreach ($filledOptions as $option) {
                    $optionText = trim($option['option_text'] ?? '');
                    $isCorrect = !empty($option['is_correct']);
                    $stmtOption->execute([$createdQuestion['id'], $optionText, $isCorrect ? 'true' : 'false']);
                    $createdOptions[] = $stmtOption->fetch(PDO::FETCH_ASSOC);
                }

                $createdQuestion['options'] = $createdOptions;
                $createdQuestions[] = $createdQuestion;
            }

            $this->pdo->commit();
            $quiz['questions'] = $createdQuestions;
            echo json_encode(["success" => true, "quiz" => $quiz]);
        } catch (Exception $e) {
            $this->pdo->rollBack();
            echo json_encode(["error" => $e->getMessage()]);
        }
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