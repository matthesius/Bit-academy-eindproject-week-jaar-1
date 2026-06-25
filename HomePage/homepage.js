console.log(quizzes);
const quizContainer = document.getElementById("featuredContainer");
const array = 
[quizzes.quiz_1, quizzes.quiz_2, quizzes.quiz_3, quizzes.quiz_4, quizzes.quiz_5];

async function renderPopQuizzes() {
    quizContainer.innerHTML = "";
    for (let i = 0; i < array.length; i++) {
        const res = await fetch(`../API-Stuff/apithingie.php?action=getQuiz&quiz_id=${array[i]}`);
        const quiz = await res.json();
        console.log(quiz);
        
        const card = document.createElement("div");
        card.className = "quiz-card";

        const img = document.createElement("img");
        img.className = "quiz-image";
        img.src = quiz.image_url;
        img.alt = quiz.title;

        const content = document.createElement("div");
        content.className = "quiz-content";

        const title = document.createElement("h2");
        title.textContent = quiz.title;

        const desc = document.createElement("p");
        desc.className = "quiz-description";
        desc.textContent = quiz.description ?? "Geen beschrijving";

        const stats = document.createElement("div");
        stats.className = "quiz-stats";
        stats.textContent =  `${quiz.questions.length} vragen` //`${quiz[i].questions.length} vragen`; //
        
        const button = document.createElement("a");
        button.href = "../quiz/quiz.php";
        button.textContent = "Start Quiz"
        button.className = "start-btn";

        button.addEventListener("click", () => {
            startQuiz(quiz.id);
        });
        
        content.appendChild(title);
        content.appendChild(desc);
        content.appendChild(stats);
        content.appendChild(button);

        card.appendChild(img);
        card.appendChild(content);

        quizContainer.appendChild(card);
    }
}

function startQuiz(id) {
    if (localStorage.getItem("selectedquiz")) {
        localStorage.removeItem("selectedquiz");
    }
    localStorage.setItem("selectedquiz", JSON.stringify(id));
}

renderPopQuizzes();