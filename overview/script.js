const quizContainer = document.getElementById("quizContainer");

loadQuizzes();

async function loadQuizzes() {
    try {
        const response = await fetch("../test.json");

        if (!response.ok) {
            throw new Error(`HTTP Error ${response.status}`);
        }

        const quizzes = await response.json();
        console.log(quizzes);
        renderQuizzes(quizzes);

    } catch (error) {

        console.error(error);

        const errorDiv = document.createElement("div");
        errorDiv.className = "error";
        errorDiv.innerHTML = `Fout bij laden van quizzen<br> ${error.message}`;

        quizContainer.appendChild(errorDiv);
    }
}

function renderQuizzes(quiz) {

    quizContainer.innerHTML = "";

    for (let i = 0; i < quiz.length; i++) {

        const card = document.createElement("div");
        card.className = "quiz-card";

        const img = document.createElement("img");
        img.className = "quiz-image";
        img.src = quiz[i].quizthumbnnail;
        img.alt = quiz[i].quizname;

        const content = document.createElement("div");
        content.className = "quiz-content";

        const title = document.createElement("h2");
        title.textContent = quiz[i].quizname;

        const desc = document.createElement("p");
        desc.className = "quiz-description";
        desc.textContent = quiz[i].desc ?? "Geen beschrijving";

        const stats = document.createElement("div");
        stats.className = "quiz-stats";
        stats.textContent = `${quiz[i].questions.length} vragen`;
        
        const button = document.createElement("a");
        button.href = "../quiz/quiz.php";
        button.textContent = "Start Quiz"
        button.className = "start-btn";

        button.addEventListener("click", () => {
            startQuiz(quiz, i);
        });
        
        content.appendChild(title);
        content.appendChild(desc);
        content.appendChild(stats);
        content.appendChild(button);

        card.appendChild(img);
        card.appendChild(content);

        quizContainer.appendChild(card);
    };
}

function startQuiz(quiz, index) {
    if (localStorage.getItem("selectedquiz")) {
        localStorage.removeItem("selectedquiz");
    }
    localStorage.setItem("selectedquiz", JSON.stringify(quiz[index]));
}