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
            <div class="quizzes-header">
                <h2>Quizzes Completed</h2>
                <div class="filter-controls">
                    <select id="quizFilter" class="filter-select">
                        <option value="">All Quizzes</option>
                    </select>
                </div>
            </div>
            <div id="quizzesContainer" class="quizzes-list">
                <p class="loading">Loading quizzes...</p>
            </div>
        </section>

    </main>

    <script>
        let allAttempts = [];
        
        function formatDuration(seconds) {
            const hours = Math.floor(seconds / 3600);
            const minutes = Math.floor((seconds % 3600) / 60);
            const secs = seconds % 60;
            
            if (hours > 0) {
                return `${hours}h ${minutes}m ${secs}s`;
            } else if (minutes > 0) {
                return `${minutes}m ${secs}s`;
            } else {
                return `${secs}s`;
            }
        }

        function calculateTimeTaken(startedAt, finishedAt) {
            if (!finishedAt) return 'In Progress';
            const start = new Date(startedAt);
            const finish = new Date(finishedAt);
            const durationSeconds = Math.floor((finish - start) / 1000);
            return formatDuration(durationSeconds);
        }

        function renderQuizzes(filteredAttempts) {
            if (filteredAttempts.length === 0) {
                document.getElementById('quizzesContainer').innerHTML = '<p class="no-quizzes">No quizzes completed yet. <a href="../overview/index.php">Start taking quizzes!</a></p>';
                return;
            }
            
            let html = '';
            filteredAttempts.forEach((attempt, index) => {
                const totalQuestions = attempt.answers ? attempt.answers.length : 0;
                const correctAnswers = attempt.score || 0;
                const percentage = totalQuestions > 0 ? Math.round((correctAnswers / totalQuestions) * 100) : 0;
                const completedDate = new Date(attempt.finished_at).toLocaleDateString();
                const timeTaken = calculateTimeTaken(attempt.started_at, attempt.finished_at);
                
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
                            <div class="stat">
                                <span class="label">Time Taken</span>
                                <span class="value time-value">${timeTaken}</span>
                            </div>
                        </div>
                        <div class="progress-section">
                            <span class="progress-label">Score Progress</span>
                            <div class="progress-bar">
                                <div class="progress" style="width: ${percentage}%">
                                    <span class="progress-text">${percentage}%</span>
                                </div>
                            </div>
                        </div>
                    </div>
                `;
            });
            
            document.getElementById('quizzesContainer').innerHTML = html;
        }

        function populateFilterOptions() {
            const quizzes = [...new Set(allAttempts.map(a => a.quiz_title))];
            const select = document.getElementById('quizFilter');
            
            quizzes.forEach(quiz => {
                const option = document.createElement('option');
                option.value = quiz;
                option.textContent = escapeHtml(quiz);
                select.appendChild(option);
            });
            
            select.addEventListener('change', function() {
                const filtered = this.value 
                    ? allAttempts.filter(a => a.quiz_title === this.value)
                    : allAttempts;
                renderQuizzes(filtered);
            });
        }

        async function loadUserQuizzes() {
            try {
                const username = "<?= $user['username']; ?>";    
                const userId = "<?= $user['id']; ?>";
                
                const attemptsRes = await fetch('../API-Stuff/apithingie.php?action=getUserAttempts&user_id=' + userId);
                const attempts = await attemptsRes.json();
                
                if (attempts.error || attempts.length === 0) {
                    document.getElementById('quizzesContainer').innerHTML = '<p class="no-quizzes">No quizzes completed yet. <a href="../overview/index.php">Start taking quizzes!</a></p>';
                    return;
                }
                
                allAttempts = attempts;
                populateFilterOptions();
                renderQuizzes(allAttempts);
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
