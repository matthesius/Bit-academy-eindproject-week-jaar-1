<nav>
    <div class="logo">Trivial</div>

    <div class="nav-links">
        <a href="../HomePage/homepage.php">Home</a>
        <a href="../overview/index.php">Quizzen</a>
        <a href="../Leaderboard/leaderboard.php">Leaderboard</a>
        <a href="../quizcreator/quizcreator.php">Quiz Creator🔨</a>


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