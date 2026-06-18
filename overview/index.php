<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Overzicht</title>
    <link href="style.css" rel="stylesheet">
</head>
<body>

    <?php
        require '../navbar.php';
    ?>

    <header>
        <h1>Quiz Overzicht</h1>
        <p>Kies een quiz om te starten</p>
            <form id="searchbar">
                <input id="search" type="text" placeholder="Search Quizzes">
            </form>
    </header>

    <main id="quizContainer">
        <div class="loading">
            Quizzes loaden...
        </div>
    </main>
    <script defer type="module" src="script.js"></script>
</body>
</html>
