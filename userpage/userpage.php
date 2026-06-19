<?php
    require 'userpage_backend.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile - Trivial</title>
    <link rel="stylesheet" href="../navbar.css">
    <link rel="stylesheet" href="userpage.css">
</head>
<body>

    <?php
        require '../navbar.php';
    ?>

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

        </section>

        <section class="quizzes-completed">
            <h2>Quizzes Completed</h2>
            <div id="quizzesContainer" class="quizzes-list">
                <p class="loading">Loading quizzes...</p>
            </div>
        </section>

    </main>

    <script>
        async function loadUserQuizzes() {
            try {
                const username = "<?= $user['username']; ?>";
                
                const usersRes = await fetch('../API-Stuff/apithingie.php?action=getUsers&username=' + encodeURIComponent(username));
                const userData = await usersRes.json();
                
                if (userData.error) {
                    document.getElementById('quizzesContainer').innerHTML = '<p class="error">Could not load user data</p>';
                    return;
                }
                
                const userId = userData.id;
                
                const attemptsRes = await fetch('../API-Stuff/apithingie.php?action=getUserAttempts&user_id=' + userId);
                const attempts = await attemptsRes.json();
                
                if (attempts.error || attempts.length === 0) {
                    document.getElementById('quizzesContainer').innerHTML = '<p class="no-quizzes">No quizzes completed yet. <a href="../overview/index.php">Start taking quizzes!</a></p>';
                    return;
                }
                
                let html = '';
                attempts.forEach((attempt, index) => {
                    const totalQuestions = attempt.answers ? attempt.answers.length : 0;
                    const correctAnswers = attempt.score || 0;
                    const percentage = totalQuestions > 0 ? Math.round((correctAnswers / totalQuestions) * 100) : 0;
                    const completedDate = new Date(attempt.finished_at).toLocaleDateString();
                    
                    html += `
                        <div class="quiz-card">
                            <div class="quiz-header">
                                <h3>${escapeHtml(attempt.quiz_title)}</h3>
                                <span class="date">${completedDate}</span>
                            </div>
                            <div class="quiz-stats">
                                <div class="stat">
                                    <span class="label">Score</span>
                                    <span class="value">${correctAnswers}/${totalQuestions}</span>
                                </div>
                                <div class="stat">
                                    <span class="label">Percentage</span>
                                    <span class="value">${percentage}%</span>
                                </div>
                                <div class="progress-bar">
                                    <div class="progress" style="width: ${percentage}%"></div>
                                </div>
                            </div>
                        </div>
                    `;
                });
                
                document.getElementById('quizzesContainer').innerHTML = html;
            } catch (error) {
                console.error('Failed to load quizzes:', error);
                document.getElementById('quizzesContainer').innerHTML = '<p class="error">Failed to load quizzes</p>';
            }
        }
        
        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }
        
        loadUserQuizzes();
    </script>
