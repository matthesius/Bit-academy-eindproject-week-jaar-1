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

function updateProgress(quiz, progress) {
    let answered = 0;

    for (let i = 0; i < quiz.questions.length; i++) {
        if (document.querySelector(`input[name="answers${i}"]:checked`)) {
            answered++;
        }
    }

    progress.value = answered;
}

function renderquestions(quiz) {
    const starttime = Temporal.Now.zonedDateTimeISO();
    for (const question of quiz.questions) {
        shuffleArray(question.options);
    }

    const maindiv = document.getElementById("questions");
    const quiztitle = document.createElement("h1");
    const progress = document.createElement("progress");
    const timer = document.createElement("p");

    quiztitle.textContent = quiz.title;

    progress.max = quiz.questions.length;
    progress.value = 0;
    progress.id = "quiz-progress";

    timer.id = "timer";
    timer.textContent = "Time: 00:00";

    const quizStart = Date.now();

    const interval = setInterval(() => {
        const elapsed = Math.floor((Date.now() - quizStart) / 1000);

        const minutes = String(Math.floor(elapsed / 60)).padStart(2, "0");
        const seconds = String(elapsed % 60).padStart(2, "0");

        timer.textContent = `Time: ${minutes}:${seconds}`;
    }, 1000);

    quiztitle.textContent = quiz.title;
    progress.max = quiz.questions.length;
    progress.value = 0;
    document.body.insertBefore(quiztitle, document.getElementById("questions"));
    document.body.insertBefore(progress, document.getElementById("questions"));
    document.body.insertBefore(timer, document.getElementById("questions"));

    for (let i = 0; i < quiz.questions.length; i++) {
        const questiondiv = document.createElement("div");
        const titlediv = document.createElement("div");
        const question = document.createElement("h3");
        const form = document.createElement("form");

        questiondiv.id = `questionid${i}`;
        question.textContent = quiz.questions[i].question_text;
        titlediv.className = "titlediv";
        titlediv.id = `titlediv${i}`;

        maindiv.appendChild(questiondiv);
        questiondiv.appendChild(titlediv);
        questiondiv.appendChild(form);
        titlediv.appendChild(question);
        const questionImageUrl = quiz.questions[i].question_image || quiz.questions[i].img || quiz.questions[i].image || quiz.questions[i].image_url || '';
        if (questionImageUrl) {
            const questionimage = document.createElement("img");
            questionimage.src = questionImageUrl;
            questionimage.alt = `Image for question ${i + 1}`;
            titlediv.appendChild(questionimage);
        }


        for (let j = 0; j < quiz.questions[i].options.length; j++) {
            const answerdiv = document.createElement("label");
            const select = document.createElement("input");
            const answer = document.createElement("label");

            answerdiv.setAttribute("class", "answerdiv");
            answerdiv.setAttribute("for", `option${i}-${j}`);
            answer.setAttribute("class", `option${i}-${j}`)
            select.type = "radio";
            select.name = `answers${i}`;
            select.id = `option${i}-${j}`;

            select.addEventListener("change", () => {
                updateProgress(quiz, progress);
            });

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
    submit.type = "button";
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

            clearInterval(interval);
            timer.textContent += " (Finished)";

            const totalSeconds = Math.floor((Date.now() - quizStart) / 1000);
            const minutes = String(Math.floor(totalSeconds / 60)).padStart(2, "0");
            const seconds = String(totalSeconds % 60).padStart(2, "0");

            const resultspage = document.getElementById("resultsmodal");
            document.getElementById("outermodal").style.display = "block";

            const count = document.createElement("p");
            const percentage = document.createElement("p");
            const time = document.createElement("p");
            const view = document.createElement("a");
            const homepage = document.createElement("a");
            const overview = document.createElement("a");
            const buttondiv = document.createElement("div");

            count.id = "count";
            percentage.id = "percentage";
            time.id = "time";
            view.id = "view";
            homepage.id = "homepage";
            overview.id = "overview";
            buttondiv.id = "buttondiv";

            count.textContent = `${points} / ${quiz.questions.length} Correct`;
            percentage.textContent = `${Math.round((points / quiz.questions.length) * 100)}%`;
            time.textContent = `Time: ${minutes}:${seconds}`;

            view.addEventListener("click", () => {
                document.getElementById("outermodal").style.display = "none";
                document.getElementById("submit").style.display = "none";
                document.getElementById("questions").style.marginBottom = "30px";
                for (let i = 0; i < quiz.questions.length; i++) {
                    for (let j = 0; j < quiz.questions[i].options.length; j++) {
                        if (quiz.questions[i].options[j].is_correct) {
                            document.querySelector(`label.option${i}-${j}`).style.color = "green";
                        } else {
                            document.querySelector(`label.option${i}-${j}`).style.color = "red";
                        }
                        document.getElementById(`option${i}-${j}`).setAttribute("disabled", "");
                    }
                }
            });
            view.textContent = "View your answers";
            homepage.href = "../HomePage/homepage.php";
            homepage.textContent = "To Homepage";
            overview.href = "../overview/index.php";
            overview.textContent = "To Overview";

            resultspage.appendChild(count);
            resultspage.appendChild(percentage);
            resultspage.appendChild(time);
            resultspage.appendChild(buttondiv);
            buttondiv.appendChild(view);
            buttondiv.appendChild(homepage);
            buttondiv.appendChild(overview);

            const table1 = constructTableOne(quiz, points, starttime);
            const table2 = constructTableTwo(quiz);

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
            const message = document.getElementById("errormessage");

            message.style.display = "block";

            setTimeout(() => {
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