<?php

session_start();

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Trivial</title>
    <link rel="stylesheet" href="login.css">
    <link rel="icon" type="image/png" href="../favicon/web-app-manifest-192x192.png" />
    <!-- to do: verander deze slop favicon AUB -->
</head>

<body>

    <div class="login-container">
        <div class="login-card">
            <h1>Trivial</h1>
            <p>Log in to save your scores.</p>

            <form method="post" action="login_backend.php" id="loginForm">
                <div class="input-group">
                    <label>Username or email</label>
                    <input type="text" id="account" name="account" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" id="password" name="password" required>
                </div>

                <?php if (isset($_SESSION['loginError'])) { ?>
                    <span style="color : red"> <?= $_SESSION['loginError'] ?> </span> <br>
                <?php } ?>

                <a href="../LogInPage/SignUp.php">Don't have an account yet? Sign up here</a>
                <button type="submit">Log in</button>

            </form>
            <div class="links"></div>
        </div>
    </div>
</body>
</html>
