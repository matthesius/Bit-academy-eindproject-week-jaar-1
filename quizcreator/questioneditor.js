const form = document.getElementById('quizForm');
const quizSelect = document.getElementById('quizSelect');
const quizIdInput = document.getElementById('quizId');
const titleInput = document.getElementById('quizTitle');
const descInput = document.getElementById('quizDescription');
const imageInput = document.getElementById('quizImage');
const questionList = document.getElementById('questionList');
const addQuestionButton = document.getElementById('addQuestion');
const formError = document.getElementById('formError');
const quizStatus = document.getElementById('quizStatus');

function createOptionItem(questionIndex, optionIndex, text = '', checked = false) {
    const optionWrapper = document.createElement('div');
    optionWrapper.className = 'option-item';
    optionWrapper.innerHTML = `
        <div class="option-row">
            <label>
                <input type="radio" name="correct-${questionIndex}" value="${optionIndex}" ${checked ? 'checked' : ''}>
                <span>Option ${optionIndex}</span>
            </label>
            <button type="button" class="remove-option">Remove</button>
        </div>
        <input type="text" name="optionText" value="${text}" placeholder="Answer text" required>
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
            <label for="question-${index}">Question ${index}</label>
            <button type="button" class="remove-question">Remove question</button>
        </div>
        <input type="text" id="question-${index}" name="questionText" placeholder="Type the question here" required>
        <div class="form-row">
            <label for="question-image-${index}">Question image URL (optional)</label>
            <input type="url" id="question-image-${index}" name="questionImage" placeholder="https://...jpg">
        </div>
        <div class="options-section">
            <div class="options-header">
                <span>Answers</span>
                <button type="button" class="add-option">Add option</button>
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
        labelText.textContent = `Option ${idx + 1}`;
    });
}

function updateQuestionLabels() {
    const items = questionList.querySelectorAll('.question-item');
    items.forEach((item, index) => {
        const label = item.querySelector('.question-header label');
        const questionInput = item.querySelector('input[name="questionText"]');
        const currentIndex = index + 1;
        item.dataset.questionIndex = currentIndex;
        label.textContent = `Question ${currentIndex}`;
        if (questionInput) {
            questionInput.id = `question-${currentIndex}`;
        }
        updateOptionLabels(currentIndex);
    });
}

function resetQuestionList() {
    questionList.innerHTML = '';
    questionList.appendChild(createQuestionItem(1));
}

function clearForm() {
    form.reset();
    quizIdInput.value = '';
    quizSelect.value = '';
    quizStatus.textContent = '';
    resetQuestionList();
    formError.textContent = '';
}

function fillForm(quiz) {
    quizIdInput.value = quiz.id;
    titleInput.value = quiz.title || '';
    descInput.value = quiz.description || '';
    imageInput.value = quiz.image_url || '';
    formError.textContent = '';
    quizStatus.textContent = `Editing “${quiz.title}”`;

    questionList.innerHTML = '';
    if (Array.isArray(quiz.questions) && quiz.questions.length > 0) {
        quiz.questions.forEach((question, index) => {
            const item = createQuestionItem(index + 1);
            const questionText = item.querySelector('input[name="questionText"]');
            const questionImage = item.querySelector('input[name="questionImage"]');
            questionText.value = question.question_text || question.text || '';
            questionImage.value = question.question_image || question.image_url || question.image || question.img || '';

            const optionsList = item.querySelector('.options-list');
            optionsList.innerHTML = '';
            const options = Array.isArray(question.options) ? question.options : [];

            options.forEach((option, optionIndex) => {
                const isCorrect = Boolean(option.is_correct);
                optionsList.appendChild(createOptionItem(index + 1, optionIndex + 1, option.option_text || '', isCorrect));
            });

            while (optionsList.querySelectorAll('.option-item').length < 4) {
                const nextIndex = optionsList.querySelectorAll('.option-item').length + 1;
                optionsList.appendChild(createOptionItem(index + 1, nextIndex, '', false));
            }

            questionList.appendChild(item);
        });
    } else {
        questionList.appendChild(createQuestionItem(1));
    }
}

addQuestionButton.addEventListener('click', () => {
    const nextIndex = questionList.querySelectorAll('.question-item').length + 1;
    questionList.appendChild(createQuestionItem(nextIndex));
    updateQuestionLabels();
});

quizSelect.addEventListener('change', async () => {
    const quizId = quizSelect.value;
    if (!quizId) {
        clearForm();
        return;
    }

    quizStatus.textContent = 'Loading quiz…';
    try {
        const response = await fetch(`../API-Stuff/apithingie.php?action=getQuiz&quiz_id=${encodeURIComponent(quizId)}`);
        const quiz = await response.json();
        if (quiz.error) {
            formError.textContent = quiz.error;
            quizStatus.textContent = 'Unable to load this quiz.';
            return;
        }
        fillForm(quiz);
    } catch (error) {
        formError.textContent = 'Could not load the selected quiz.';
        quizStatus.textContent = 'Unable to load this quiz.';
        console.error(error);
    }
});

async function loadUserQuizzes() {
    try {
        const response = await fetch('../API-Stuff/apithingie.php?action=getUserQuizzes');
        const quizzes = await response.json();
        if (!Array.isArray(quizzes)) {
            quizStatus.textContent = quizzes?.error || 'No quizzes found.';
            return;
        }

        quizSelect.innerHTML = '<option value="">Select a quiz to edit</option>';
        quizzes.forEach((quiz) => {
            const option = document.createElement('option');
            option.value = quiz.id;
            option.textContent = quiz.title;
            quizSelect.appendChild(option);
        });
    } catch (error) {
        console.error(error);
        quizStatus.textContent = 'Could not load your quizzes.';
    }
}

form.addEventListener('submit', async (event) => {
    event.preventDefault();
    formError.textContent = '';

    const quizId = quizIdInput.value.trim();
    const title = titleInput.value.trim();
    const description = descInput.value.trim();
    const imageUrl = imageInput.value.trim();
    const questionItems = questionList.querySelectorAll('.question-item');

    if (!quizId || !title || !description || !imageUrl) {
        formError.textContent = 'Select a quiz and fill in all required fields.';
        return;
    }

    if (questionItems.length === 0) {
        formError.textContent = 'Please add at least one question.';
        return;
    }

    const questions = Array.from(questionItems).map((item, index) => {
        const questionText = item.querySelector('input[name="questionText"]')?.value.trim() || '';
        const questionImage = item.querySelector('input[name="questionImage"]')?.value.trim() || '';
        const optionItems = item.querySelectorAll('.option-item');
        const options = Array.from(optionItems).map(optionItem => ({
            option_text: optionItem.querySelector('input[name="optionText"]').value.trim(),
            is_correct: optionItem.querySelector('input[type="radio"]').checked
        }));

        return {
            question_text: questionText,
            question_image: questionImage,
            position: index + 1,
            options
        };
    });

    for (const question of questions) {
        if (!question.question_text) {
            formError.textContent = 'Every question must include text.';
            return;
        }

        const filledOptions = question.options.filter(option => option.option_text !== '');
        const correctCount = filledOptions.filter(option => option.is_correct).length;

        if (filledOptions.length < 2) {
            formError.textContent = 'Each question needs at least two options.';
            return;
        }

        if (correctCount !== 1) {
            formError.textContent = 'Each question must have exactly one correct answer.';
            return;
        }

        question.options = filledOptions;
    }

    const quizData = {
        quiz_id: quizId,
        quizname: title,
        desc: description,
        quizthumbnail: imageUrl,
        questions
    };

    try {
        const response = await fetch('../API-Stuff/apiPostThingie.php?action=updateQuiz', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(quizData)
        });
        const result = await response.json();

        if (result.error) {
            formError.textContent = 'API error: ' + result.error;
            return;
        }

        formError.textContent = 'Quiz updated successfully.';
        quizStatus.textContent = 'Saved changes.';
        await loadUserQuizzes();
        quizSelect.value = quizId;
    } catch (error) {
        formError.textContent = 'Error updating quiz.';
        console.error(error);
    }
});

resetQuestionList();
loadUserQuizzes();
