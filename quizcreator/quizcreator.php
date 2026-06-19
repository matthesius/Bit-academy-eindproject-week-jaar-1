<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Creator</title>
    <link rel="stylesheet" href="quizcreator.css">
</head>
<body>
    <main class="creator-page">
        <section class="creator-card">
            <h1>Quiz Creator</h1>
            <p class="subtitle">Vul de titel, beschrijving en afbeelding in. De gegevens worden omgezet naar een quiz.</p>

            <form id="quizForm" class="quiz-form">
                <label for="quizTitle">Quiz titel</label>
                <input type="text" id="quizTitle" name="quizTitle" placeholder="Bijv. World Flags" required>

                <label for="quizDescription">Beschrijving</label>
                <textarea id="quizDescription" name="quizDescription" rows="4" placeholder="Beschrijf de quiz" required></textarea>

                <label for="quizImage">Afbeelding URL</label>
                <input type="url" id="quizImage" name="quizImage" placeholder="https://...jpg" required>

                <div class="questions-section">
                    <div class="questions-header">
                        <h2>Vragen</h2>
                        <button type="button" id="addQuestion" class="secondary-button">Voeg vraag toe</button>
                    </div>
                    <div id="questionList">
                        <div class="question-item">
                            <label for="question-1">Vraag 1</label>
                            <textarea id="question-1" name="questionText" rows="3" placeholder="Typ hier de vraag" required></textarea>
                        </div>
                    </div>
                </div>

                <div class="button-row">
                    <button type="submit">Maak quiz</button>
                </div>

                <p id="formError" class="form-error" aria-live="polite"></p>
            </form>
        </section>
    </main>

    <script src="quizcreator.js"></script>
</body>
</html>
