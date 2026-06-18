<nav>
    <link rel="stylesheet" href="navbar.css">
    <div class="logo">Trivial</div>

    <div class="nav-links">
        <a href="../HomePage/homepage.php">Home</a>
        <a href="../overview/index.php">Quizzen</a>
        <a href="../Leaderboard/leaderboard.php" class="active">Leaderboard</a>

        <?php

        if (isset($_SESSION['LoggedInQuizTaker'])) { ?>
            <a href="../LogInPage/logout.php">log out</a>
        <?php } else { ?>
            <a href="../LogInPage/login.php">log in</a>
        <?php }          
        ?>
    </div>
</nav>