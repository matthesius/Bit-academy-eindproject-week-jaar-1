const quizContainer = document.getElementById("quizContainer");

loadQuizzes();

async function loadQuizzes() {
    try {
        const response = await fetch("./test.json");
        if (!response.ok) {
            throw new Error(`HTTP Error ${response.status}`);
        }
        const quizzes = await response.json();
        renderQuizzes(quizzes);
    } catch (error) {

        console.error(error);

        quizContainer.innerHTML = `
            <div class="error">
                Fout bij laden van quizzen<br>
                ${error.message}
            </div>
        `;
    }
}

function renderQuizzes(quizzes) {
    quizContainer.innerHTML = "";
    quizzes.forEach((quiz, index) => {
        const card = document.createElement("div");
        card.className = "quiz-card";
        card.innerHTML = `
            <img
                class="quiz-image"
                src="${quiz.quizthumbnnail}"
                alt="${quiz.quizname}"
            >

            <div class="quiz-content">

                <h2>${quiz.quizname}</h2>

                <p class="quiz-description">
                    ${quiz.desc ?? "Geen beschrijving"}
                </p>

                <div class="quiz-stats">
                    ${quiz.questions.length} vragen
                </div>

                <button
                    class="start-btn"
                    onclick="startQuiz(${index})"
                >
                    Start Quiz
                </button>
            </div>
        `;

        quizContainer.appendChild(card);
    });

    window.quizzes = quizzes;
}

function startQuiz(index) {

    const selectedQuiz = window.quizzes[index];

    localStorage.setItem(
        "selectedQuiz",
        JSON.stringify(selectedQuiz)
    );

    console.log(selectedQuiz);
    alert(`Quiz gestart: ${selectedQuiz.quizname}`);
}