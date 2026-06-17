function getquizinfo() {
    const quiz = JSON.parse(localStorage.getItem("selectedquiz"));
    document.getElementById("websitetitle").textContent = quiz.quizname;
    console.log(quiz);
    renderquestions(quiz)
}

function renderquestions(quiz) {
    const maindiv = document.getElementById("questions");
    
    const quiztitle = document.createElement("h1");
    quiztitle.textContent = quiz.quizname;
    document.body.prepend(quiztitle);

    for (let i = 0; i < quiz.questions.length; i++) {
        const questiondiv = document.createElement("div");
        const titlediv = document.createElement("div");
        const question = document.createElement("h3");
        const questionimage = document.createElement("img");
        const form = document.createElement("form");

        questiondiv.id = `questionid${i}`;
        question.textContent = quiz.questions[i].questiontitle;
        questionimage.setAttribute("src", quiz.questions[i].img);
        titlediv.class = "titlediv";
        titlediv.id = `titlediv${i}`;

        maindiv.appendChild(questiondiv);
        questiondiv.appendChild(titlediv);
        questiondiv.appendChild(form);
        titlediv.appendChild(question);
        titlediv.appendChild(questionimage);
        
        for (let j = 0; j < quiz.questions[i].answers.length; j++) {
            const answerdiv = document.createElement("div");
            const select = document.createElement("input");
            const answer = document.createElement("label");

            select.type = "radio";
            select.name = `answers${i}`;
            select.id = `option${i}-${j}`;

            answer.textContent = quiz.questions[i].answers[j].answer;
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
            for (let j = 0; j < quiz.questions[i].answers.length; j++) {
                if (document.getElementById(`option${i}-${j}`).checked == true && quiz.questions[i].answers[j].iscorrect == true) {
                    points++;
                }
            }
        }
        console.log(points);
    });

    document.body.appendChild(submit);
}

getquizinfo();