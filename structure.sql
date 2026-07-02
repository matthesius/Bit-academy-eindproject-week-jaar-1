-- =========================================
-- Quiz platform database schema
-- Gegenereerd op basis van ER-diagram
-- =========================================

-- USERS
CREATE TABLE users (
    id          SERIAL PRIMARY KEY,
    username    VARCHAR(255) NOT NULL UNIQUE,
    email       VARCHAR(255) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,
    created_at  TIMESTAMP NOT NULL DEFAULT NOW()
);

-- QUIZZES
CREATE TABLE quizzes (
    id          SERIAL PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    description TEXT,
    created_at  TIMESTAMP NOT NULL DEFAULT NOW(),
    image_url   VARCHAR(255) NOT NULL,
    creator_id  BIGINT REFERENCES users(id) ON DELETE SET NULL
);

-- QUESTIONS
CREATE TABLE questions (
    id              SERIAL PRIMARY KEY,
    quiz_id         INT4 NOT NULL REFERENCES quizzes(id) ON DELETE CASCADE,
    question_text   TEXT NOT NULL,
    position        INT4 NOT NULL,
    image_url       TEXT,
    question_image  VARCHAR(255)
);

-- OPTIONS
CREATE TABLE options (
    id           SERIAL PRIMARY KEY,
    question_id  INT4 NOT NULL REFERENCES questions(id) ON DELETE CASCADE,
    option_text  VARCHAR(255) NOT NULL,
    is_correct   BOOLEAN NOT NULL DEFAULT FALSE
);

-- ATTEMPTS
CREATE TABLE attempts (
    id           SERIAL PRIMARY KEY,
    user_id      INT4 NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    quiz_id      INT4 NOT NULL REFERENCES quizzes(id) ON DELETE CASCADE,
    score        INT4 NOT NULL DEFAULT 0,
    completed    BOOLEAN NOT NULL DEFAULT FALSE,
    started_at   TIMESTAMP NOT NULL DEFAULT NOW(),
    finished_at  TIMESTAMP
);

-- ATTEMPT_ANSWERS
CREATE TABLE attempt_answers (
    id           SERIAL PRIMARY KEY,
    attempt_id   INT4 NOT NULL REFERENCES attempts(id) ON DELETE CASCADE,
    question_id  INT4 NOT NULL REFERENCES questions(id) ON DELETE CASCADE,
    option_id    INT4 REFERENCES options(id) ON DELETE SET NULL,
    is_correct   BOOLEAN NOT NULL DEFAULT FALSE
);

-- =========================================
-- Indexen voor veelgebruikte foreign keys
-- =========================================
CREATE INDEX idx_quizzes_creator_id ON quizzes(creator_id);
CREATE INDEX idx_questions_quiz_id ON questions(quiz_id);
CREATE INDEX idx_options_question_id ON options(question_id);
CREATE INDEX idx_attempts_user_id ON attempts(user_id);
CREATE INDEX idx_attempts_quiz_id ON attempts(quiz_id);
CREATE INDEX idx_attempt_answers_attempt_id ON attempt_answers(attempt_id);
CREATE INDEX idx_attempt_answers_question_id ON attempt_answers(question_id);
CREATE INDEX idx_attempt_answers_option_id ON attempt_answers(option_id);