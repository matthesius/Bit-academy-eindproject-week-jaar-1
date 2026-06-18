const quizSelect = document.getElementById('quizFilter');
const loadBtn = document.getElementById('loadLeaderboardBtn');
const leaderboardRows = document.getElementById('leaderboardRows');
const leaderboardEmpty = document.getElementById('leaderboardEmpty');
const podiumFirst = document.getElementById('podiumFirst');
const podiumSecond = document.getElementById('podiumSecond');
const podiumThird = document.getElementById('podiumThird');

async function fetchQuizzes() {
    try {
        const res = await fetch('../API-Stuff/apithingie.php?action=getQuizzes');
        const quizzes = await res.json();
        populateQuizSelect(quizzes);
    } catch (error) {
        console.error('Failed to load quizzes:', error);
        quizSelect.innerHTML = '<option value="">Unable to load quizzes</option>';
    }
}

function populateQuizSelect(quizzes) {
    if (!Array.isArray(quizzes) || quizzes.length === 0) {
        quizSelect.innerHTML = '<option value="">No quizzes available</option>';
        return;
    }

    quizSelect.innerHTML = '<option value="">Select a quiz</option>';
    quizzes.forEach((quiz) => {
        const option = document.createElement('option');
        option.value = quiz.id;
        option.textContent = quiz.title;
        quizSelect.appendChild(option);
    });
}

async function fetchLeaderboard(quizId) {
    try {
        const res = await fetch(`../API-Stuff/apithingie.php?action=getLeaderboard&quiz_id=${quizId}`);
        const data = await res.json();
        return data;
    } catch (error) {
        console.error('Failed to load leaderboard:', error);
        return null;
    }
}

function renderLeaderboard(rows) {
    leaderboardRows.innerHTML = '';

    if (!Array.isArray(rows) || rows.length === 0 || rows[0].error) {
        leaderboardEmpty.style.display = 'block';
        leaderboardEmpty.textContent = rows && rows.error ? rows.error : 'No leaderboard data found.';
        podiumFirst.style.display = 'none';
        podiumSecond.style.display = 'none';
        podiumThird.style.display = 'none';
        return;
    }

    leaderboardEmpty.style.display = 'none';
    podiumFirst.style.display = '';
    podiumSecond.style.display = '';
    podiumThird.style.display = '';

    const topThree = rows.slice(0, 3);
    updatePodium(topThree);

    rows.forEach((row, index) => {
        const playerRow = document.createElement('div');
        playerRow.className = 'player-row';
        playerRow.innerHTML = `
            <span>${index + 1}</span>
            <span>${escapeHtml(row.username)}</span>
            <span>${escapeHtml(row.score)}</span>
            <span>${formatSeconds(row.seconds_per_question)}</span>
        `;
        leaderboardRows.appendChild(playerRow);
    });
}

function updatePodium(topThree) {
    const defaultContent = () => ({ username: 'Waiting...', score: '-', seconds_per_question: '-' });
    const first = topThree[0] || defaultContent();
    const second = topThree[1] || defaultContent();
    const third = topThree[2] || defaultContent();

    podiumFirst.querySelector('h3').textContent = first.username;
    podiumFirst.querySelector('p').textContent = `${first.score} questions correct`;
    podiumFirst.querySelector('span').textContent = `*${formatSeconds(first.seconds_per_question)} Seconds`;

    podiumSecond.querySelector('h3').textContent = second.username;
    podiumSecond.querySelector('p').textContent = `${second.score} questions correct`;
    podiumSecond.querySelector('span').textContent = `*${formatSeconds(second.seconds_per_question)} Seconds`;

    podiumThird.querySelector('h3').textContent = third.username;
    podiumThird.querySelector('p').textContent = `${third.score} questions correct`;
    podiumThird.querySelector('span').textContent = `*${formatSeconds(third.seconds_per_question)} Seconds`;
}

function formatSeconds(value) {
    if (value === null || value === undefined || value === '-') {
        return '-';
    }
    return Number(value).toFixed(2);
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

loadBtn.addEventListener('click', async () => {
    const quizId = quizSelect.value;
    if (!quizId) {
        leaderboardEmpty.style.display = 'block';
        leaderboardEmpty.textContent = 'Please select a quiz first.';
        return;
    }

    const leaderboard = await fetchLeaderboard(quizId);
    renderLeaderboard(leaderboard);
});

fetchQuizzes();
