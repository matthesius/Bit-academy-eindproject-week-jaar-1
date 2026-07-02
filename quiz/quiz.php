<?php
    require '../LogInPage/login_check.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title id="websitetitle"></title>
    <script defer src="../navbar.js"></script>
    <script type="module" defer src="quiz.js"></script>
    <link rel="stylesheet" href="quiz.css">
    <link rel="stylesheet" href="../navbar.css">
    <link rel="icon" type="image/png" href="../favicon/web-app-manifest-192x192.png" />
    <!-- to do: verander deze slop favicon AUB -->
</head>
<body>
    <?php
    require '../navbar.php';
    ?>
    <h2 id="errormessage" style="display: none;">Please Answer All Questions</h2>
    <div id="outermodal">
        <div id="flexmodal">
            <div id="resultsmodal">
                <h2>Results:</h2>
            </div>
        </div>
    </div>
    <div id="questions"></div>
</body>
</html>
