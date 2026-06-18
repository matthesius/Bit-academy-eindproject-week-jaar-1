import GetCompiledAPI from "../API-Stuff/API-CALL.js";

async function getquizinfo() {
    const quizid = localStorage.getItem("selectedquiz");
    const quiz = await new GetCompiledAPI(quizid, undefined).getQuizById();
    console.log(quiz);
    renderquestions(quiz);
}

function shuffleArray(array) {
    for (let i = array.length - 1; i > 0; i--) {
        const j = Math.floor(Math.random() * (i + 1));

        [array[i], array[j]] = [array[j], array[i]];
    }
}

function renderquestions(quiz) {
    for (const question of quiz.questions) {
        shuffleArray(question.options);
    }

    const maindiv = document.getElementById("questions");

    const quiztitle = document.createElement("h1");
    quiztitle.textContent = quiz.title;
    document.body.prepend(quiztitle);

    for (let i = 0; i < quiz.questions.length; i++) {
        const questiondiv = document.createElement("div");
        const titlediv = document.createElement("div");
        const question = document.createElement("h3");
        const questionimage = document.createElement("img");
        const form = document.createElement("form");

        questiondiv.id = `questionid${i}`;
        question.textContent = quiz.questions[i].question_text;
        questionimage.setAttribute("src", quiz.questions[i].img);
        titlediv.class = "titlediv";
        titlediv.id = `titlediv${i}`;

        maindiv.appendChild(questiondiv);
        questiondiv.appendChild(titlediv);
        questiondiv.appendChild(form);
        titlediv.appendChild(question);
        titlediv.appendChild(questionimage);

        for (let j = 0; j < quiz.questions[i].options.length; j++) {
            const answerdiv = document.createElement("div");
            const select = document.createElement("input");
            const answer = document.createElement("label");

            select.type = "radio";
            select.name = `answers${i}`;
            select.id = `option${i}-${j}`;

            answer.textContent = quiz.questions[i].options[j].option_text;
            answer.for = `option${j}`;


            form.appendChild(answerdiv);
            answerdiv.appendChild(select);
            answerdiv.appendChild(answer);
        }
    }
    const submit = document.createElement("button");
    submit.id = "submit";
    submit.textContent = "Submit Answers";

    submit.addEventListener("click", () => {
        let points = 0;
        document.getElementById("resultsmodal").style.display = "block";
        for (let i = 0; i < quiz.questions.length; i++) {
            for (let j = 0; j < quiz.questions[i].options.length; j++) {
                if (document.getElementById(`option${i}-${j}`).checked == true && quiz.questions[i].options[j].is_correct == true) {
                    points++;
                }
            }
        }
        const resultspage = document.getElementById("resultsmodal");
        document.getElementById("outermodal").style.display = "block";

        const count = document.createElement("p");
        const percentage = document.createElement("p");
        const time = document.createElement("p");
        const homepage = document.createElement("a");
        const overview = document.createElement("a");
        const buttondiv = document.createElement("div");

        count.id = "count";
        percentage.id = "percentage";
        time.id = "time";
        homepage.id = "homepage";
        overview.id = "overview";
        buttondiv.id = "buttondiv";

        count.textContent = `${points} / ${quiz.questions.length} Correct`;
        percentage.textContent = `${(points / quiz.questions.length) * 100}%`;
        time.textContent = `Time: ${10}`;
        homepage.href = "../HomePage/homepage.php";
        homepage.textContent = "To Homepage";
        overview.href = "../overview/index.html";
        overview.textContent = "To Overview";

        
        
        resultspage.appendChild(count);
        resultspage.appendChild(percentage);
        resultspage.appendChild(time);
        resultspage.appendChild(buttondiv);
        buttondiv.appendChild(homepage);
        buttondiv.appendChild(overview);
    });
    document.body.appendChild(submit);

}

console.log(document.querySelectorAll("input"));

getquizinfo();