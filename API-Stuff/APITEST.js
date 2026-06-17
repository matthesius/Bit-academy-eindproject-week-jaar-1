fetch("leaderboardAPI.php")
.then((data) => (data.json()))
.then((data) => console.log(data));

