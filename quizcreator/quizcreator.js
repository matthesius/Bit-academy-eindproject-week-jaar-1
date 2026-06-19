const form = document.getElementById('quizForm');
const titleInput = document.getElementById('quizTitle');
const descInput = document.getElementById('quizDescription');
const imageInput = document.getElementById('quizImage');
const formError = document.getElementById('formError');

form.addEventListener('submit', event => {
    event.preventDefault();
    formError.textContent = '';

    const title = titleInput.value.trim();
    const description = descInput.value.trim();
    const imageUrl = imageInput.value.trim();

    if (!title || !description || !imageUrl) {
        formError.textContent = 'Vul eerst alle velden in.';
        return;
    }

    const quizData = {
        quizname: title,
        desc: description,
        quizthumbnail: imageUrl,
        questions: []
    };

    fetch('../API-Stuff/apiPostThingie.php?action=createQuiz', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(quizData)
    })
    .then(response => response.json())
    .then(result => {
        if (result.error) {
            formError.textContent = 'API-fout: ' + result.error;
            return;
        }

        formError.textContent = 'Quiz succesvol verzonden naar API.';
        form.reset();
    })
    .catch(error => {
        formError.textContent = 'Fout bij verzenden naar API.';
        console.error(error);
    });
});
// End of submit handler
