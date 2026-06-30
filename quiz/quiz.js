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
    const starttime = Temporal.Now.zonedDateTimeISO();
    for (const question of quiz.questions) {
        shuffleArray(question.options);
    }

    const maindiv = document.getElementById("questions");
    const quiztitle = document.createElement("h1");
    const progress = document.createElement("progress");

    quiztitle.textContent = quiz.title;
    progress.max = "100";
    progress.value = "0";
    document.body.insertBefore(quiztitle, document.getElementById("questions"));
    document.body.insertBefore(progress, document.getElementById("questions"));

    for (let i = 0; i < quiz.questions.length; i++) {
        const questiondiv = document.createElement("div");
        const titlediv = document.createElement("div");
        const question = document.createElement("h3");
        const form = document.createElement("form");

        questiondiv.id = `questionid${i}`;
        question.textContent = quiz.questions[i].question_text;
        titlediv.class = "titlediv";
        titlediv.id = `titlediv${i}`;

        maindiv.appendChild(questiondiv);
        questiondiv.appendChild(titlediv);
        questiondiv.appendChild(form);
        titlediv.appendChild(question);
        if (quiz.questions[i].img) {
            const questionimage = document.createElement("img");
            questionimage.setAttribute("src", quiz.questions[i].img);
            titlediv.appendChild(questionimage);
        }


        for (let j = 0; j < quiz.questions[i].options.length; j++) {
            const answerdiv = document.createElement("label");
            const select = document.createElement("input");
            const answer = document.createElement("label");

            answerdiv.setAttribute("class", "answerdiv");
            answerdiv.setAttribute("for", `option${i}-${j}`);
            select.type = "radio";
            select.name = `answers${i}`;
            select.id = `option${i}-${j}`;

            answer.textContent = quiz.questions[i].options[j].option_text;
            answer.setAttribute("for", `option${i}-${j}`);

            form.appendChild(answerdiv);
            answerdiv.appendChild(select);
            answerdiv.appendChild(answer);
        }
    }

    const submit = document.createElement("button");
    submit.id = "submit";
    submit.textContent = "Submit Answers";
    submit.addEventListener("click", () => {
        let answers = 0;
        for (let i = 0; i < quiz.questions.length; i++) {
            for (let j = 0; j < quiz.questions[i].options.length; j++) {
                if (document.getElementById(`option${i}-${j}`).checked == true) {
                    answers++
                }
            }
        }

        if (answers == quiz.questions.length) {
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
            percentage.textContent = `${Math.round((points / quiz.questions.length) * 100)}%`;
            time.textContent = `Time: ${10}`;
            homepage.href = "../HomePage/homepage.php";
            homepage.textContent = "To Homepage";
            overview.href = "../overview/index.php";
            overview.textContent = "To Overview";

            resultspage.appendChild(count);
            resultspage.appendChild(percentage);
            resultspage.appendChild(time);
            resultspage.appendChild(buttondiv);
            buttondiv.appendChild(homepage);
            buttondiv.appendChild(overview);

            const table1 = constructTableOne(quiz, points, starttime);
            const table2 = constructTableTwo(quiz);
            console.log(JSON.stringify({ table1, table2 }));

            fetch('../API-Stuff/apiPostThingie.php?action=submitAttempt', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ table1, table2 })
            })
                .then(res => res.json())
                .then(data => {
                    console.log("Server response:", data);

                    if (data.error) {
                        alert(data.error);
                    }
                })
                .catch(err => {
                    console.error(err);
                })
        } else {
            const message = document.getElementById("errormessage")
            if (message.style.display = "block") {
                message.style.display = "none";
            }

            message.style.display = "block";

            const errortimeout = setTimeout(() => {
                message.style.display = "none";
            }, 3000);
        }
    });
    document.body.appendChild(submit);
}

function constructTableOne(quiz, score, startedat) {
    const table1 = {
        quiz_id: quiz.id,
        score: score,
        completed: true,
        started_at: startedat.toString().split('[')[0],  // verwijdert de timezone naam
        finished_at: Temporal.Now.zonedDateTimeISO().toString().split('[')[0]
    };
    return table1;
}

function constructTableTwo(quiz) {
    const table2 = [];

    for (let i = 0; i < quiz.questions.length; i++) {
        let questionid = quiz.questions[i].id;

        let optionid = undefined;
        for (let j = 0; j < quiz.questions[i].options.length; j++) {
            if (document.getElementById(`option${i}-${j}`).checked == true) {
                optionid = quiz.questions[i].options[j].id

            }
        }

        table2.push({ question_id: questionid, option_id: optionid });
    }
    return table2;
}

getquizinfo();