# Trivial Quiz Website Manual

Welcome to Trivial, a quiz platform where users can browse quizzes, take them, create their own, and compete on a leaderboard.

## Getting started


### Setup
The website is accesible through the repository and you can access it by using a platform like apache (through xampp) to make the PHP code work.
you will need to make sure postgresql is enabled too to allow the connection to the online database.

## Main pages

### The navbar buttons
The homepage is the main entry point of the site. It introduces the platform and gives users quick access to the quiz overview.

The quiz overview page lists all available quizzes.
When a quiz is started, the quiz page loads the selected quiz and presents the questions one by one.
- The user can answer each question
- A timer runs during the quiz
- Progress is shown while answering
- The quiz ends with a score summary and links back to the homepage or overview as well as an overview of your responses

Users can log in to access personalized features.
- Enter a username and password
- If the credentials are valid, the user is sent to the homepage
- logins are throttled to prevent bruteforcing

New users can create an account.

### Quiz Creator
You can create your own quizzes.

To create a quiz:
1. Enter a quiz title
2. Enter a description
3. Add an image URL
4. Add one or more questions
5. (add an image to your question)
6. Add answer options for each question
7. Mark exactly one answer as correct
8. Submit the quiz

The quiz is sent to the backend API and stored in the database.

### Edit and delete your quizzes
Users can also edit or remove their own quizzes.

- Choose one of your quizzes from the dropdown
- Update the title, description, image, or questions
- Save the changes
- Or delete the quiz entirely

## Leaderboard

The leaderboard shows the best results for each quiz.

- Open [Leaderboard/leaderboard.php](Leaderboard/leaderboard.php)
- Select a quiz from the dropdown
- The page displays the top players and their score information

Ranking is based on:
- higher score first
- lower time per question for ties

## User profile and progress

The user page shows account and quiz related activity.


## API endpoints

- Get all quizzes
- Get one quiz with questions and options
- Get leaderboard data
- Create a quiz
- Submit quiz attempts

## Project structure

- [HomePage](HomePage) – homepage files
- [overview](overview) – quiz overview page
- [quiz](quiz) – quiz-taking interface
- [quizcreator](quizcreator) – create, edit, and delete quizzes
- [Leaderboard](Leaderboard) – leaderboard page
- [LogInPage](LogInPage) – login and signup flow
- [userpage](userpage) – user profile page
- [API-Stuff](API-Stuff) – backend API scripts
- [DB.php](DB.php) – database connection
- [structure.sql](structure.sql) – database schema

## Side Notes

- Only logged-in users can create or edit quizzes
- The leaderboard and quiz results are tied to the database-backed quiz attempts
