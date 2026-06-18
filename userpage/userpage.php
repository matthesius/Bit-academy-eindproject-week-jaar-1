<?php
    require 'userpage_backend.php';
    require '../navbar.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Trivial</title>
    <link rel="stylesheet" href="userpage.css">
    <link rel="icon" type="image/png" href="../favicon/web-app-manifest-192x192.png" />
    <!-- to do: verander deze kut slop favicon AUB -->
</head>
<body>

    <header class="hero">
        <h1>My Profile</h1>
        <p>Welcome back, <?= $user['username'];?>!</p>
    </header>

    <main class="user-dashboard">
        
        <section class="profile-card">
            <div class="avatar-large">
                <?php echo strtoupper(substr($user['username'], 0, 1)); ?>
            </div>
            <div class="profile-info">
                <h2><?php echo htmlspecialchars($user['username']); ?></h2>
                <?php if ($user && isset($user['email'])) : ?>
                    <p class="email"><?php echo htmlspecialchars($user['email']); ?></p>
                <?php endif; ?>
                <?php if ($user && isset($user['created_at'])) : ?>
                    <p class="join-date">Joined: <?php echo date('F j, Y', strtotime($user['created_at'])); ?></p>
                <?php endif; ?>
            </div>
        </section>

        <section class="stats-overview">
            <div class="stat-card">
                <h3><?php echo count($quizScores); ?></h3>
                <p>Quizzes Completed</p>
            </div>
            <?php if (!empty($quizScores)) : ?>
                <div class="stat-card">
                    <h3><?php 
                        $avgScore = array_sum(array_column($quizScores, 'percentage')) / count($quizScores);
                        echo round($avgScore, 1) . '%';
                    ?></h3>
                    <p>Average Score</p>
                </div>
            <?php endif; ?>
        </section>

        <section class="quiz-scores">
            <h2>Quiz Scores</h2>
            <?php if (!empty($quizScores)) : ?>
                <div class="scores-list">
                    <?php foreach ($quizScores as $score) : ?>
                        <div class="score-item">
                            <div class="quiz-name"><?php echo htmlspecialchars($score['quiz_title']); ?></div>
                            <div class="score-details">
                                <span class="score"><?php echo $score['score']; ?>/<?php echo $score['total_questions']; ?></span>
                                <span class="percentage"><?php echo $score['percentage']; ?>%</span>
                                <span class="date"><?php echo date('M d, Y', strtotime($score['completed_at'])); ?></span>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php else : ?>
                <p class="no-scores">You haven't completed any quizzes yet. <a href="../overview/index.php">Start a quiz!</a></p>
            <?php endif; ?>
        </section>

    </main>

</body>
</html>
