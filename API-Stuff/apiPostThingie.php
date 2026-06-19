<?php

require_once '../DB.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

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

    public function submitAttempt()
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

        $attempt = $input['table1'] ?? null;
        $answers = $input['table2'] ?? null;

        if (!$attempt || !is_array($answers) || count($answers) === 0) {
            echo json_encode(["error" => "table1 en table2 zijn verplicht"]);
            return;
        }

        $quizId     = $attempt['quiz_id']     ?? null;
        $score      = $attempt['score']       ?? null;
        $completed  = $attempt['completed']   ?? false;
        $startedAt  = $attempt['started_at']  ?? null;
        $finishedAt = $attempt['finished_at'] ?? null;

        if ($quizId === null || $score === null || $startedAt === null || $finishedAt === null) {
            echo json_encode(["error" => "quiz_id, score, started_at en finished_at zijn verplicht"]);
            return;
        }

        try {
            if (!isset($_SESSION['LoggedInQuizTaker'])) {
                echo json_encode(["error" => "Niet ingelogd"]);
                return;
            }

            $userId = $_SESSION['LoggedInQuizTaker'];

            $this->pdo->beginTransaction();

            $stmt = $this->pdo->prepare("
            INSERT INTO attempts (user_id, quiz_id, score, completed, started_at, finished_at)
            VALUES (?, ?, ?, ?, ?, ?)
            RETURNING id, user_id, quiz_id, score, completed, started_at, finished_at
        ");
            $stmt->execute([
                $userId,
                $quizId,
                $score,
                $completed ? 'true' : 'false',
                $startedAt,
                $finishedAt
            ]);
            $createdAttempt = $stmt->fetch(PDO::FETCH_ASSOC);
            $attemptId = $createdAttempt['id'];

            $stmtAnswer = $this->pdo->prepare("
            INSERT INTO attempt_answers (attempt_id, question_id, option_id)
            VALUES (?, ?, ?)
        ");

            foreach ($answers as $answer) {
                $questionId = $answer['question_id'] ?? null;
                $optionId   = $answer['option_id']   ?? null;

                if ($questionId === null || $optionId === null) {
                    throw new Exception('Elk antwoord heeft een question_id en option_id nodig.');
                }

                $stmtAnswer->execute([$attemptId, $questionId, $optionId]);
            }

            $this->pdo->commit();
            echo json_encode(["success" => true, "attempt" => $createdAttempt]);
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
} elseif ($action === 'submitAttempt') {
    $api->submitAttempt();
} else {
    echo json_encode(["error" => "Onbekende actie"]);
}

//make a quiz = ../API-Stuff/apiPostThingie.php?action=createQuiz
//submit an attempt = ../API-Stuff/apiPostThingie.php?action=submitAttempt