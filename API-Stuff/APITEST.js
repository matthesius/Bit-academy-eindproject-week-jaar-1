fetch("../API-Stuff/apithingie.php?action=getQuizAttempts&quiz_id=1")
.then((data) => (data.json()))
.then((data) => console.log(data));

fetch("../API-Stuff/apithingie.php?action=getUserAttempts&user_id=1")
.then((data) => (data.json()))
.then((data) => console.log(data));

