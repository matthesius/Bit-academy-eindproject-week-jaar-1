<?php

session_start();

?>

<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up - Trivial</title>
    <link rel="stylesheet" href="SignUp.css">
    <link rel="icon" type="image/png" href="../favicon/web-app-manifest-192x192.png" />
    <!-- to do: verander deze slop favicon AUB -->
</head>

<script>
document.getElementById("loginForm").addEventListener("submit", function(e) {

    const password = document.getElementById("password").value;
    const confirmPassword = document.getElementById("confirmPassword").value;

    if (password !== confirmPassword) {
        e.preventDefault();
        alert("Passwords do not match.");
        return false;
    }
});
</script>

<body>

    <div class="login-container">
        <div class="login-card">
            <h1>Sign up</h1>
            <p>Create your trivial account!.</p>

            <form method="post" action="SignUp_backend.php" id="loginForm">
                <div class="input-group">
                    <label>Username</label>
                    <input type="text" id="username" name="username" placeholder="Name" required>
                </div>
                <div class="input-group">
                    <label>E-mail</label>
                    <input type="email" id="email" name="email"placeholder="name@email.com" required>
                </div>

                <div class="input-group">
                    <label>Password</label>
                    <input type="password" id="password" name="password" placeholder="don't make your password too simple!" required>
                </div>
                <div class="input-group">
                                
                <label>Confirm Password</label>
                <input
                    type="password"
                    id="confirmPassword"
                    name="confirmPassword"
                    placeholder="Repeat your password"
                    required
                ></div>

                <?php if (isset($_SESSION['SignUpError'])) { ?>
                    <span style="color : red"> <?= $_SESSION['SignUpError'] ?> </span> <br>
                <?php } ?>

                <a href="../LogInPage/login.php">Already have an account? Sign in instead here</a>
                <button  class="signb" type="submit">Sign up</button>

            </form>
            <div class="links"></div>
        </div>
    </div>
</body>
</html>
