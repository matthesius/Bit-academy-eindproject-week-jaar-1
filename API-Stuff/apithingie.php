<?php

require_once '../DB.php';

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

header("Content-Security-Policy: default-src 'self'; connect-src 'self' http://127.0.0.1:5500;");
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Methods: GET, POST, OPTIONS');
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit;
}

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
            case 'getUserQuizzes':
                $this->getUserQuizzes();
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
            case 'getQuizAttempts':
                $this->getQuizAttempts();
                break;
            case 'getUserAttempts':
                $this->getUserAttempts();
                break;
            default:
                echo json_encode(["error" => "Unknown action"]);
        }
    }

    private function getQuizzes()
    {
        $stmt = $this->pdo->query("
        SELECT q.*, COUNT(qu.id) AS question_count
        FROM quizzes q
        LEFT JOIN questions qu ON qu.quiz_id = q.id
        GROUP BY q.id
        ORDER BY q.created_at DESC
    ");
        echo json_encode($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    private function getCurrentUserId()
    {
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        $userId = $_SESSION['LoggedInQuizTaker'] ?? null;
        return $userId !== null ? (int) $userId : null;
    }

    private function getUserQuizzes()
    {
        $userId = $this->getCurrentUserId();
        if ($userId === null) {
            echo json_encode(["error" => "Please log in"]);
            return;
        }

        $this->pdo->exec("ALTER TABLE quizzes ADD COLUMN IF NOT EXISTS creator_id INT REFERENCES users(id) ON DELETE SET NULL;");

        $stmt = $this->pdo->prepare("
            SELECT q.*, COUNT(qu.id) AS question_count
            FROM quizzes q
            LEFT JOIN questions qu ON qu.quiz_id = q.id
            WHERE q.creator_id = ?
            GROUP BY q.id
            ORDER BY q.created_at DESC
        ");
        $stmt->execute([$userId]);
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
            $questionImage = trim($question['question_image'] ?? $question['image'] ?? $question['img'] ?? $question['image_url'] ?? '');
            $question['question_image'] = $questionImage;
            $question['img'] = $questionImage;
            $question['image'] = $questionImage;
            $question['image_url'] = $questionImage;
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
                SELECT DISTINCT ON (a.user_id)
                    u.username,
                    a.score,
                    a.started_at,
                    a.finished_at,
                    COUNT(q.id) OVER (PARTITION BY a.quiz_id) AS question_count,
                    EXTRACT(EPOCH FROM (a.finished_at - a.started_at)) / NULLIF(COUNT(q.id) OVER (PARTITION BY a.quiz_id), 0) AS seconds_per_question
                FROM attempts a
                JOIN users u ON u.id = a.user_id
                JOIN questions q ON q.quiz_id = a.quiz_id
                WHERE a.quiz_id = ? AND a.completed = TRUE
                ORDER BY a.user_id, a.score DESC, a.finished_at ASC
            ) ranked
            ORDER BY score DESC, seconds_per_question ASC
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

    private function getQuizAttempts()
    {
        if (!isset($_GET['quiz_id'])) {
            echo json_encode(["error" => "quiz_id is required"]);
            return;
        }

        $stmt = $this->pdo->prepare("
        SELECT a.*, u.username
        FROM attempts a
        JOIN users u ON u.id = a.user_id
        WHERE a.quiz_id = ?
        ORDER BY a.started_at DESC
    ");
        $stmt->execute([$_GET['quiz_id']]);
        $attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($attempts as &$attempt) {
            $stmt = $this->pdo->prepare("
            SELECT aa.*, q.question_text, o.option_text
            FROM attempt_answers aa
            JOIN questions q ON q.id = aa.question_id
            LEFT JOIN options o ON o.id = aa.option_id
            WHERE aa.attempt_id = ?
        ");
            $stmt->execute([$attempt['id']]);
            $attempt['answers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode($attempts);
    }

    private function getUserAttempts()
    {
        if (!isset($_GET['user_id'])) {
            echo json_encode(["error" => "user_id is required"]);
            return;
        }

        $stmt = $this->pdo->prepare("
        SELECT a.*, q.title AS quiz_title, u.username
        FROM attempts a
        JOIN quizzes q ON q.id = a.quiz_id
        JOIN users u ON u.id = a.user_id
        WHERE a.user_id = ?
        ORDER BY a.started_at DESC
    ");
        $stmt->execute([$_GET['user_id']]);
        $attempts = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($attempts as &$attempt) {
            $stmt = $this->pdo->prepare("
            SELECT aa.*, q.question_text, o.option_text
            FROM attempt_answers aa
            JOIN questions q ON q.id = aa.question_id
            LEFT JOIN options o ON o.id = aa.option_id
            WHERE aa.attempt_id = ?
        ");
            $stmt->execute([$attempt['id']]);
            $attempt['answers'] = $stmt->fetchAll(PDO::FETCH_ASSOC);
        }

        echo json_encode($attempts);
    }
}

$api = new ApiThingie($pdo);
$api->handle();

//All quizzes = ../API-Stuff/apithingie.php?action=getQuizzes
//One full quiz = ../API-Stuff/apithingie.php?action=getQuiz&quiz_id=1
//Leaderboard = ../API-Stuff/apithingie.php?action=getLeaderboard&quiz_id=1
//All users = ../API-Stuff/apithingie.php?action=getUsers
//One user = ../API-Stuff/apithingie.php?action=getUsers&username=arne
//Quiz attempts = ../API-Stuff/apithingie.php?action=getQuizAttempts&quiz_id=1
//User attempts = ../API-Stuff/apithingie.php?action=getUserAttempts&user_id=1