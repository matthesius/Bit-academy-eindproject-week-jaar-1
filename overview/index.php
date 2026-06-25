<!DOCTYPE html>
<html lang="nl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz Overzicht</title>
    <script defer src="../navbar.js"></script>
    <link rel="stylesheet" href="../navbar.css">
    <link href="style.css" rel="stylesheet">
    <link rel="icon" type="image/png" href="../favicon/web-app-manifest-192x192.png" />
    <!-- to do: verander deze kut slop favicon AUB -->
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
