<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Trivial</title>
    <link rel="stylesheet" href="homepage.css">
    <link rel="icon" type="image/png" href="../favicon/web-app-manifest-192x192.png" />
    <!-- to do: verander deze kut slop favicon AUB -->
</head>

<body>

    <?php
        require '../navbar.php';
    ?>

    <section class="hero">
        <h1>Test your knowledge</h1>
        <p>Play all sorts of quizes about numerous topics.</p>
        <a href="../overview/index.php" class="hero-btn">Start Now!</a>
    </section>

    <section class="stats">
        <div class="stat-card">
            <h2 id="quizCount">69</h2>
            <p>Quizzes</p>
        </div>

        <div class="stat-card">
            <h2 id="questionCount">1787</h2>
            <p>Questions</p>
        </div>
    </section>

    <section class="featured">
        <h2>Popular Quizzes</h2>
        <div id="featuredContainer" class="featured-grid"></div>
    </section>

    <section class="benefits">
        <h2>Why Trivial?</h2>
        <div class="benefits-grid">
            <div class="benefit">
                <h3>Learn New Stuff</h3>
                <p>Discover interesting facts and knowledge.</p>
            </div>
            <div class="benefit">
                <h3>Challenge yourself</h3>
                <p>Improve your score with every try.</p>
            </div>
            <div class="benefit">
                <h3>Fast and Simple</h3>
                <p>Play without hassle.</p>
            </div>
        </div>
    </section>

    <section class="cta">
        <h2>Ready for a challenge?</h2>
        <a href="/Bit-academy-project-week-1/overview/index.php" class="hero-btn">See all of our Quizzes </a>
    </section>

    <footer>
        © 2026 Trivial
    </footer>
</body>

</html>