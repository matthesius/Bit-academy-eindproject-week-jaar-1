class GetCompiledAPI {
    constructor(id, name) {
        this.id = id;
        this.name = name;
    };

    async getAllQuizzes() {
        const res = await fetch(`../API-Stuff/apithingie.php?action=getQuizzes`); // Works! //
        const data = res.json();
        return data;
    }

    async getQuizById() {
        const res = await fetch(`../API-Stuff/apithingie.php?action=getQuiz&quiz_id=${this.id}`); // Works! //
        const data = res.json();
        return data;
    }

    async getQuizByName() {
        const res = await fetch(`../API-Stuff/wholequizAPI.php?title=${this.name}`); // Works! //
        const data = res.json();
        return data;
    }

    async getAllUsers() {
        const res = await fetch(`../API-Stuff/usersAPI.php`); // Works! //
        const data = res.json();
        return data;
    }

    async getLeaderboardbyId() {
        const res = await fetch(`../API-Stuff/leaderboardAPI.PHP?quiz_id=${this.id}`); // ? //
        const data = res.json();
        return data;
    }

    async getUserByName() {
        const res = await fetch(`../API-Stuff/usersAPI.php?username=${this.name}`); // Works! //
        const data = res.json();
        return data;
    }
}

async function call() {
    const test = await new GetCompiledAPI(undefined, "Programmeren").getAllQuizzes();
    console.log(test);
}

//call();

export default GetCompiledAPI;