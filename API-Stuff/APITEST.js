fetch("http://php/Projects/Bit-academy-project-week-1/API-Stuff/usersAPI.php")
.then((data) => (data.json()))
.then((data) => console.log(data));