<nav>
    <link rel="stylesheet" href="navbar.css">
    <div class="logo">Trivial</div>

    <div class="nav-links">
        <a href="../HomePage/homepage.php">Home</a>
        <a href="../overview/index.php">Quizzen</a>
        <a href="../Leaderboard/leaderboard.php" class="active">Leaderboard</a>

        <?php
        if (session_status() !== PHP_SESSION_ACTIVE) {
            session_start();    
        }

        if (isset($_SESSION['LoggedInQuizTaker'])) { ?>
            <a href="../LogInPage/logout.php">Log out</a>
        <?php } else { ?>
            <a href="../LogInPage/login.php">Log in</a>
        <?php } ?>
    </div>
</nav>