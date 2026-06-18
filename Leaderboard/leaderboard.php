<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Trivial</title>
    <link rel="stylesheet" href="leaderboard.css">
    <link rel="icon" type="image/png" href="../favicon/web-app-manifest-192x192.png" />
    <!-- to do: verander deze kut slop favicon AUB -->
</head>
<body>

    <?php
        require '../navbar.php';
    ?>

    <header class="hero">
        <h1>Global Leaderboard</h1>
        <p>See who dominates the trivial rankings.</p>
    </header>

    <main>

        <section class="filters">
            <div class="filter-box">
                <label for="quizFilter">
                    Select Quiz
                </label>

                <select id="quizFilter">
                    <option value="">Loading quizzes...</option>
                </select>

                <button class="filter-btn" id="loadLeaderboardBtn">
                    Load Leaderboard
                </button>
            </div>
        </section>

        <section class="top-three" id="topThreeSection">
            <div class="podium second" id="podiumSecond">
                <div class="avatar">2</div>
                <h3>Waiting...</h3>
                <p></p>
                <span></span>
            </div>

            <div class="podium first" id="podiumFirst">
                <div class="avatar">1</div>
                <h3>Waiting...</h3>
                <p></p>
                <span></span>
            </div>

            <div class="podium third" id="podiumThird">
                <div class="avatar">3</div>
                <h3>Waiting...</h3>
                <p></p>
                <span></span>
            </div>
        </section>

        <section class="leaderboard">
            <div class="leaderboard-header">
                <span>#</span>
                <span>Player</span>
                <span>Questions correct</span>
                <span>Time per question</span>
            </div>

            <div id="leaderboardRows"></div>
        </section>

        <div class="leaderboard-empty" id="leaderboardEmpty">Choose a quiz and load the leaderboard.</div>

    </main>

    <script src="leaderboard.js" defer></script>
</body>
</html>