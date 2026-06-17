<?php

require_once '../DB.php';

header("Content-Security-Policy: default-src 'self'; connect-src 'self' http://127.0.0.1:5500;");
header("Access-Control-Allow-Origin: *");
header('Content-Type: application/json');

class ApiThingie
{
    private PDO $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function handle()
    {
        $action = $_GET['action'] ?? null;

        switch ($action) {
            case 'getQuizzes':
                $this->getQuizzes();
                break;
            case 'getQuiz':
                $this->getQuiz();
                break;
            case 'getLeaderboard':
                $this->getLeaderboard();
                break;
            case 'getUsers':
                $this->getUsers();
                break;
            default:
                echo json_encode(["error" => "Unknown action"]);
        }
    }

    private function getQuizzes()
    {
        $stmt = $this->pdo->query("SELECT * FROM quizzes ORDER BY created_at DESC");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function getQuiz()
    {
        if (!isset($_GET['quiz_id'])) {
            echo json_encode(["error" => "quiz_id is required"]);
            return;
        }

        $quiz_id = $_GET['quiz_id'];

        $stmt = $this->pdo->prepare("SELECT * FROM quizzes WHERE id = ?");
        $stmt->execute([$quiz_id]);
        $quiz = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$quiz) {
            echo json_encode(["error" => "Quiz not found"]);
            return;
        }

        $stmt = $this->pdo->prepare("
            SELECT * FROM questions
            WHERE quiz_id = ?
            ORDER BY position ASC
        ");
        $stmt->execute([$quiz_id]);
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($questions as &$question) {
            $stmt = $this->pdo->prepare("SELECT * FROM options WHERE question_id = ?");
            $stmt->execute([$question['id']]);
            $question['options'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        $quiz['questions'] = $questions;
        echo json_encode($quiz);
    }

    private function getLeaderboard()
    {
        if (!isset($_GET['quiz_id'])) {
            echo json_encode(["error" => "quiz_id is required"]);
            return;
        }

        $stmt = $this->pdo->prepare("
            SELECT * FROM (
                SELECT DISTINCT ON (a.user_id) u.username, a.score, a.finished_at
                FROM attempts a
                JOIN users u ON u.id = a.user_id
                WHERE a.quiz_id = ? AND a.completed = TRUE
                ORDER BY a.user_id, a.score DESC
            ) ranked
            ORDER BY score DESC
            LIMIT 10
        ");
        $stmt->execute([$_GET['quiz_id']]);
        $scores = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($scores) {
            echo json_encode($scores);
        } else {
            echo json_encode(["error" => "No scores found for this quiz"]);
        }
    }

    private function getUsers()
    {
        if (isset($_GET['username'])) {
            $stmt = $this->pdo->prepare("SELECT * FROM users WHERE username = ?");
            $stmt->execute([$_GET['username']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user) {
                echo json_encode($user);
            } else {
                echo json_encode(["error" => "User not found"]);
            }
        } else {
            $stmt = $this->pdo->query("SELECT * FROM users");
            echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
        }
    }
}

$api = new ApiThingie($pdo);
$api->handle();

//All quizzes = apithingie.php?action=getQuizzes
//One full quiz = apithingie.php?action=getQuiz&quiz_id=1
//Leaderboard = apithingie.php?action=getLeaderboard&quiz_id=1
//All users = apithingie.php?action=getUsers
//One user = apithingie.php?action=getUsers&username=arne
