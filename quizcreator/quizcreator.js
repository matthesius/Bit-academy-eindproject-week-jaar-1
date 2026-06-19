const form = document.getElementById('quizForm');
const titleInput = document.getElementById('quizTitle');
const descInput = document.getElementById('quizDescription');
const imageInput = document.getElementById('quizImage');
const questionList = document.getElementById('questionList');
const addQuestionButton = document.getElementById('addQuestion');
const formError = document.getElementById('formError');

function createOptionItem(questionIndex, optionIndex, text = '', checked = false) {
    const optionWrapper = document.createElement('div');
    optionWrapper.className = 'option-item';
    optionWrapper.innerHTML = `
        <div class="option-row">
            <label>
                <input type="radio" name="correct-${questionIndex}" value="${optionIndex}" ${checked ? 'checked' : ''}>
                <span>Optie ${optionIndex}</span>
            </label>
            <button type="button" class="remove-option">Verwijder</button>
        </div>
        <input type="text" name="optionText" value="${text}" placeholder="Antwoordtekst" required>
    `;

    optionWrapper.querySelector('.remove-option').addEventListener('click', () => {
        const optionItems = optionWrapper.parentElement.querySelectorAll('.option-item');
        if (optionItems.length > 2) {
            optionWrapper.remove();
            updateOptionLabels(questionIndex);
        }
    });

    return optionWrapper;
}

function createQuestionItem(index) {
    const wrapper = document.createElement('div');
    wrapper.className = 'question-item';
    wrapper.dataset.questionIndex = index;
    wrapper.innerHTML = `
        <div class="question-header">
            <label for="question-${index}">Vraag ${index}</label>
            <button type="button" class="remove-question">Verwijder vraag</button>
        </div>
        <textarea id="question-${index}" name="questionText" rows="3" placeholder="Typ hier de vraag" required></textarea>
        <div class="options-section">
            <div class="options-header">
                <span>Antwoorden</span>
                <button type="button" class="add-option">Voeg optie toe</button>
            </div>
            <div class="options-list"></div>
        </div>
    `;

    const optionsList = wrapper.querySelector('.options-list');
    const addOptionButton = wrapper.querySelector('.add-option');

    for (let i = 1; i <= 4; i += 1) {
        optionsList.appendChild(createOptionItem(index, i, '', i === 1));
    }

    addOptionButton.addEventListener('click', () => {
        const optionCount = optionsList.querySelectorAll('.option-item').length;
        optionsList.appendChild(createOptionItem(index, optionCount + 1, '', false));
        updateOptionLabels(index);
    });

    wrapper.querySelector('.remove-question').addEventListener('click', () => {
        wrapper.remove();
        updateQuestionLabels();
    });

    return wrapper;
}

function updateOptionLabels(questionIndex) {
    const questionItem = questionList.querySelector(`.question-item[data-question-index="${questionIndex}"]`);
    if (!questionItem) return;
    const optionItems = questionItem.querySelectorAll('.option-item');

    optionItems.forEach((option, idx) => {
        const radio = option.querySelector('input[type="radio"]');
        const labelText = option.querySelector('.option-row span');
        radio.value = idx + 1;
        radio.name = `correct-${questionIndex}`;
        labelText.textContent = `Optie ${idx + 1}`;
    });
}

function updateQuestionLabels() {
    const items = questionList.querySelectorAll('.question-item');
    items.forEach((item, index) => {
        const label = item.querySelector('.question-header label');
        const textarea = item.querySelector('textarea[name="questionText"]');
        const currentIndex = index + 1;
        item.dataset.questionIndex = currentIndex;
        label.textContent = `Vraag ${currentIndex}`;
        textarea.id = `question-${currentIndex}`;
        updateOptionLabels(currentIndex);
    });
}

addQuestionButton.addEventListener('click', () => {
    const nextIndex = questionList.querySelectorAll('.question-item').length + 1;
    questionList.appendChild(createQuestionItem(nextIndex));
    updateQuestionLabels();
});

function resetQuestionList() {
    questionList.innerHTML = '';
    questionList.appendChild(createQuestionItem(1));
}

resetQuestionList();

form.addEventListener('submit', event => {
    event.preventDefault();
    formError.textContent = '';

    const title = titleInput.value.trim();
    const description = descInput.value.trim();
    const imageUrl = imageInput.value.trim();
    const questionItems = questionList.querySelectorAll('.question-item');

    if (!title || !description || !imageUrl) {
        formError.textContent = 'Vul eerst alle velden in.';
        return;
    }

    if (questionItems.length === 0) {
        formError.textContent = 'Voeg minimaal één vraag toe.';
        return;
    }

    const questions = Array.from(questionItems).map((item, index) => {
        const questionText = item.querySelector('textarea[name="questionText"]').value.trim();
        const optionItems = item.querySelectorAll('.option-item');
        const options = Array.from(optionItems).map(optionItem => ({
            option_text: optionItem.querySelector('input[name="optionText"]').value.trim(),
            is_correct: optionItem.querySelector('input[type="radio"]').checked
        }));

        return {
            question_text: questionText,
            position: index + 1,
            options
        };
    });

    for (const question of questions) {
        if (!question.question_text) {
            formError.textContent = 'Alle vragen moeten tekst hebben.';
            return;
        }

        const filledOptions = question.options.filter(option => option.option_text !== '');
        const correctCount = filledOptions.filter(option => option.is_correct).length;

        if (filledOptions.length < 2) {
            formError.textContent = 'Elke vraag heeft minimaal twee opties nodig.';
            return;
        }

        if (correctCount !== 1) {
            formError.textContent = 'Elke vraag moet precies één correct antwoord hebben.';
            return;
        }

        question.options = filledOptions;
    }

    const quizData = {
        quizname: title,
        desc: description,
        quizthumbnail: imageUrl,
        questions
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
        resetQuestionList();
    })
    .catch(error => {
        formError.textContent = 'Fout bij verzenden naar API.';
        console.error(error);
    });
});
