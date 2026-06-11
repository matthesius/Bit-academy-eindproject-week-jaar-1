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

        const errorDiv = document.createElement("div");
        errorDiv.className = "error";
        errorDiv.innerHTML = `
            Fout bij laden van quizzen<br>
            ${error.message}
        `;

        quizContainer.appendChild(errorDiv);
    }
}

function renderQuizzes(quizzes) {

    quizContainer.innerHTML = ""; // alleen container resetten

    quizzes.forEach((quiz, index) => {

        const card = document.createElement("div");
        card.className = "quiz-card";

        const img = document.createElement("img");
        img.className = "quiz-image";
        img.src = quiz.quizthumbnnail;
        img.alt = quiz.quizname;

        const content = document.createElement("div");
        content.className = "quiz-content";

        const title = document.createElement("h2");
        title.textContent = quiz.quizname;

        const desc = document.createElement("p");
        desc.className = "quiz-description";
        desc.textContent = quiz.desc ?? "Geen beschrijving";

        const stats = document.createElement("div");
        stats.className = "quiz-stats";
        stats.textContent = `${quiz.questions.length} vragen`;

        const button = document.createElement("button");
        button.className = "start-btn";
        button.textContent = "Start Quiz";

        button.addEventListener("click", () => {
            startQuiz(index);
        });

        content.appendChild(title);
        content.appendChild(desc);
        content.appendChild(stats);
        content.appendChild(button);

        card.appendChild(img);
        card.appendChild(content);

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