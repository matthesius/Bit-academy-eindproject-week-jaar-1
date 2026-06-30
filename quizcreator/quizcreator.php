<?php
require '../LogInPage/login_check.php';
?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Creator</title>
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
            <h1>Quiz Creator</h1>
            <p class="subtitle">Enter the quiz title, description, and image URL. The quiz data will be converted into a new quiz.</p>

            <form id="quizForm" class="quiz-form">
                <div class="button-row button-row--top">
                    <button type="submit">Create quiz</button>
                </div>

                <label for="quizTitle">Quiz title</label>
                <input type="text" id="quizTitle" name="quizTitle" placeholder="e.g. World Flags" required>

                <label for="quizDescription">Description</label>
                <textarea id="quizDescription" name="quizDescription" rows="4" placeholder="Describe the quiz" required></textarea>

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

    <script src="quizcreator.js"></script>
</body>

</html>