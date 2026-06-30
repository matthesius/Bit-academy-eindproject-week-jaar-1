const quizContainer = document.getElementById("featuredContainer");
const array = [{
    "id":quizzes.quiz_1_id,
    "attempts":quizzes.quiz_1_attempts
},{
    "id":quizzes.quiz_2_id,
    "attempts":quizzes.quiz_2_attempts
},{
    "id":quizzes.quiz_3_id,
    "attempts":quizzes.quiz_3_attempts
},{
    "id":quizzes.quiz_4_id,
    "attempts":quizzes.quiz_4_attempts
},{
    "id":quizzes.quiz_5_id,
    "attempts":quizzes.quiz_5_attempts
}];
console.log(array);


async function renderPopQuizzes() {
    quizContainer.innerHTML = "";
    for (let i = 0; i < 5; i++) {
        const res = await fetch(`../API-Stuff/apithingie.php?action=getQuiz&quiz_id=${array[i].id}`);
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

        const questions = document.createElement("div");
        questions.className = "quiz-stats";
        questions.textContent =  `${quiz.questions.length} vragen`;
        
        const attempts = document.createElement("p");
        attempts.className = "quiz-stats";
        attempts.textContent = `${array[i].attempts} Global Attempts`;

        const button = document.createElement("a");
        button.href = "../quiz/quiz.php";
        button.textContent = "Start Quiz";
        button.className = "start-btn";

        button.addEventListener("click", () => {
            startQuiz(quiz.id);
        });
        
        content.appendChild(title);
        content.appendChild(desc);
        content.appendChild(questions);
        content.appendChild(attempts)
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