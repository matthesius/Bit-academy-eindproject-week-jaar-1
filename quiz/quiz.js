function getquizinfo() {
    const quiz = JSON.parse(localStorage.getItem("selectedquiz"));
    console.log(quiz);
    renderquestions(quiz)
}

function renderquestions(quiz) {
    const maindiv = document.getElementById("questions");
    for (let i = 0; i < quiz.question.length; i++) {
        const questiondiv = document.createElement("div");
        const titlediv = document.createElement("div");
        const question = document.createElement("h3");
        const questionimage = document.createElement("image");

        questiondiv.id = `questionid${i}`;
        titlediv.class = "titlediv";
        titlediv.id = `titlediv${i}`;

        for (let j = 0; j < quiz.questions[i].length; j++) {
            const form = document.createElement("form");
            const answerdiv = document.createElement("div");
            const select = document.createElement("input");
            const answer = document.createElement("label");

            select.type = "radio";
            select.name = `answers${i}`;
            select.id = `option${j}`;

            answer.textContent = quiz.questions[i].answers[j].answer;
            answer.for = `option${j}`;
        }
    }
}