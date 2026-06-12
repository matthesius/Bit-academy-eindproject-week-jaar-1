
CREATE TABLE users (
    id          SERIAL PRIMARY KEY,
    username    VARCHAR(50)  NOT NULL UNIQUE,
    email       VARCHAR(255) NOT NULL UNIQUE,
    password    VARCHAR(255) NOT NULL,  -- sla op als bcrypt hash
    created_at  TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE quizzes (
    id          SERIAL PRIMARY KEY,
    title       VARCHAR(255) NOT NULL,
    description TEXT,
    created_at  TIMESTAMP NOT NULL DEFAULT NOW()
);

CREATE TABLE questions (
    id              SERIAL PRIMARY KEY,
    quiz_id         INT NOT NULL REFERENCES quizzes(id) ON DELETE CASCADE,
    question_text   TEXT NOT NULL,
    position        INT NOT NULL
);

CREATE TABLE options (
    id              SERIAL PRIMARY KEY,
    question_id     INT NOT NULL REFERENCES questions(id) ON DELETE CASCADE,
    option_text     VARCHAR(255) NOT NULL,
    is_correct      BOOLEAN NOT NULL DEFAULT FALSE
);

CREATE TABLE attempts (
    id          SERIAL PRIMARY KEY,
    user_id     INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    quiz_id     INT NOT NULL REFERENCES quizzes(id) ON DELETE CASCADE,
    score       INT NOT NULL DEFAULT 0,
    completed   BOOLEAN NOT NULL DEFAULT FALSE,
    started_at  TIMESTAMP NOT NULL DEFAULT NOW(),
    finished_at TIMESTAMP
);

CREATE TABLE attempt_answers (
    id           SERIAL PRIMARY KEY,
    attempt_id   INT NOT NULL REFERENCES attempts(id) ON DELETE CASCADE,
    question_id  INT NOT NULL REFERENCES questions(id) ON DELETE CASCADE,
    option_id    INT REFERENCES options(id) ON DELETE SET NULL,
    is_correct   BOOLEAN NOT NULL DEFAULT FALSE,
    UNIQUE (attempt_id, question_id)
);

CREATE INDEX idx_questions_quiz_id          ON questions(quiz_id);
CREATE INDEX idx_options_question_id         ON options(question_id);
CREATE INDEX idx_attempts_user_id            ON attempts(user_id);
CREATE INDEX idx_attempts_quiz_id            ON attempts(quiz_id);
CREATE INDEX idx_attempt_answers_attempt_id  ON attempt_answers(attempt_id);