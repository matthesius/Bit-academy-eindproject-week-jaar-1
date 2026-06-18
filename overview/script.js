import GetCompiledAPI from "../API-Stuff/API-CALL.js";

loadQuizzes();
const quizContainer = document.getElementById("quizContainer");

document.getElementById("search").addEventListener("input", () => {
    filter();
})

async function filter() {
    const quizzes = await new GetCompiledAPI().getAllQuizzes();
    const searchitem = document.getElementById("search").value.toLowerCase();
    const filter = quizzes.filter(quizzes => quizzes.title.toLowerCase().includes(searchitem) == true);
    console.log(filter);
    loadQuizzes(filter);
}

async function loadQuizzes(filter) {
    try {
        let quizzes = undefined;
        if (!filter) {
            quizzes = await new GetCompiledAPI().getAllQuizzes();
        } else {
            quizzes = filter;
        }

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
    console.log(quiz);

    for (let i = 0; i < quiz.length; i++) {

        const card = document.createElement("div");
        card.className = "quiz-card";

        const img = document.createElement("img");
        img.className = "quiz-image";
        img.src = quiz[i].image_url;
        img.alt = quiz[i].title;

        const content = document.createElement("div");
        content.className = "quiz-content";

        const title = document.createElement("h2");
        title.textContent = quiz[i].title;

        const desc = document.createElement("p");
        desc.className = "quiz-description";
        desc.textContent = quiz[i].description ?? "Geen beschrijving";

        const stats = document.createElement("div");
        stats.className = "quiz-stats";
        stats.textContent =  `${quiz[i].id} vragen` //`${quiz[i].questions.length} vragen`; //
        
        const button = document.createElement("a");
        button.href = "../quiz/quiz.php";
        button.textContent = "Start Quiz"
        button.className = "start-btn";

        button.addEventListener("click", () => {
            startQuiz(quiz[i].id);
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

function startQuiz(id) {
    if (localStorage.getItem("selectedquiz")) {
        localStorage.removeItem("selectedquiz");
    }
    localStorage.setItem("selectedquiz", JSON.stringify(id));
}