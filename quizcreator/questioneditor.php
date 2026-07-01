<?php
require '../LogInPage/login_check.php';
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Question Editor</title>
    <script defer src="../navbar.js"></script>
    <link rel="stylesheet" href="quizcreator.css">
    <link rel="stylesheet" href="../navbar.css">
</head>

<body>
    <?php
    require '../navbar.php';
    ?>

    <main class="creator-page">
        <section class="creator-card">
            <h1>Edit your questions</h1>
            <p class="subtitle">Choose one of your quizzes, update the quiz details, and replace its questions.</p>

            <form id="quizForm" class="quiz-form">
                <div class="button-row button-row--top">
                    <a href="quizcreator.php" class="secondary-link">Create a new quiz</a>
                    <button type="button" id="deleteQuizButton" class="remove-question">Delete quiz</button>
                    <button type="submit">Save changes</button>
                </div>

                <div class="editor-controls">
                    <label for="quizSelect">Your quizzes</label>
                    <select id="quizSelect" name="quizSelect" required>
                        <option value="">Loading your quizzes…</option>
                    </select>
                </div>

                <p id="quizStatus" class="quiz-status" aria-live="polite"></p>
                <input type="hidden" id="quizId" name="quizId">

                <label for="quizTitle">Quiz title</label>
                <input type="text" id="quizTitle" name="quizTitle" placeholder="e.g. World Flags" maxlength="25" required>

                <label for="quizDescription">Description</label>
                <input type="text" id="quizDescription" name="quizDescription" placeholder="Describe the quiz" maxlength="100" required>

                <label for="quizImage">Quiz image URL</label>
                <input type="url" id="quizImage" name="quizImage" placeholder="https://...jpg" required>

                <div class="questions-section">
                    <div class="questions-header">
                        <h2>Questions</h2>
                        <button type="button" id="addQuestion" class="secondary-button">Add question</button>
                    </div>
                    <div id="questionList"></div>
                </div>

                <p id="formError" class="form-error" aria-live="polite"></p>
            </form>
        </section>
    </main>

    <script src="questioneditor.js"></script>
</body>

</html>
