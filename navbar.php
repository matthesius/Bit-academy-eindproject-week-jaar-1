<nav>
    <div class="logo">Trivial</div>
    <button id="responsivemenu">. . .</button>
    <div class="nav-links">
        <a href="../HomePage/homepage.php">Home</a>
        <a href="../overview/index.php">Quizzes</a>
        <a href="../Leaderboard/leaderboard.php">Leaderboard</a>
        <a href="../quizcreator/quizcreator.php">Quiz Creator</a>
        <a href="../quizcreator/questioneditor.php">Edit questions</a>

        <?php
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION['LoggedInQuizTaker'])) { ?>
            <a href="../LogInPage/logout.php">Log out</a>
            <a href="../userpage/userpage.php">User</a>
        <?php } else { ?>
            <a href="../LogInPage/login.php">Log in</a>
        <?php } ?>
    </div>
</nav>

<div id="navmodal">
    <div id="nav-links" class="modal">
        <a href="../HomePage/homepage.php">Home</a>
        <a href="../overview/index.php">Quizzes</a>
        <a href="../Leaderboard/leaderboard.php">Leaderboard</a>
        <a href="../quizcreator/quizcreator.php">Quiz Creator</a>
        <a href="../quizcreator/questioneditor.php">Edit questions</a>

        <?php
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();
        }

        if (isset($_SESSION['LoggedInQuizTaker'])) { ?>
            <a href="../LogInPage/logout.php">Log out</a>
            <a href="../userpage/userpage.php">User</a>
        <?php } else { ?>
            <a href="../LogInPage/login.php">Log in</a>
        <?php } ?>
        <button id="closenavmodal">X</button>
    </div>
</div>