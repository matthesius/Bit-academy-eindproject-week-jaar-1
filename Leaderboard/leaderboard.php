<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Leaderboard - Trivial</title>
    <link rel="stylesheet" href="leaderboard.css">
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
            Filter by Quiz
        </label>

        <select id="quizFilter">
            <option>World Flags</option>
            <option>Physics for Babies</option>
            <option>French Beginner</option>
            <option>French Intermidiate</option>
            <option>World 1787</option>
        </select>

        <button class="filter-btn">
            Apply Filter
        </button>

    </div>

</section>

        <section class="top-three">

            <div class="podium second">
                <div class="avatar"></div>
                <h3>Fox</h3>
                <p>13 questions correct</p>
                <br>
                <p>*9 sec per question</p>
                <span></span>
            </div>

            <div class="podium first">
                <div class="avatar"></div>
                <h3>Raffoxygames</h3>
                <p>15 questions correct</p>
                <br>
                <p>*5 sec per question</p>
                <span></span>
            </div>

            <div class="podium third">
                <div class="avatar"></div>
                <h3>Ragames</h3>
                <p>13 questions correct</p>
                <br>
                <p>*11 sec per question</p>
                <span></span>
            </div>

        </section>

        <section class="leaderboard">

            <div class="leaderboard-header">
                <span>#</span>
                <span>Player</span>
                <span>Questions correct</span>
                <span>*Time per question</span>
            </div>

            <div class="player-row">
                <span>4</span>
                <span>Hugo</span>
                <span>8</span>
                <span>12</span>
            </div>

            <div class="player-row">
                <span>5</span>
                <span>Thomas</span>
                <span>7</span>
                <span>32</span>
            </div>

            <div class="player-row">
                <span>6</span>
                <span>Arne</span>
                <span>7</span>
                <span>67</span>
            </div>

            <div class="player-row">
                <span>7</span>
                <span>Jairo</span>
                <span>6</span>
                <span>68</span>
            </div>

            <div class="player-row">
                <span>8</span>
                <span>Bram</span>
                <span>6</span>
                <span>560</span>
            </div>

        </section>

    </main>

</body>
</html>