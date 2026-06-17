class GetCompiledAPI {
    constructor(id, name) {
        this.id = id;
        this.name = name;
    };

    async getAllQuizzes() {
        const res = await fetch(`wholequizAPI.php`); // needs proper endpoint //
        const data = res.json();
        return data;
    }

    async getQuizById() {
        const res = await fetch(`wholequizAPI.php?quiz_id=${this.id}`); // Works! //
        const data = res.json();
        return data;
    }

    async getQuizByName() {
        const res = await fetch(`wholequizAPI.php?title=${this.id}`); // Needs proper Endpoint //
        const data = res.json();
        return data;
    }

    async getAllUsers() {
        const res = await fetch(`usersAPI.php`); // Works! //
        const data = res.json();
        return data;
    }

    async getLeaderboardbyId() {
        const res = await fetch(`leaderboardAPI.PHP?quiz_id=${this.id}`);
        const data = res.json();
        return data;
    }

    async getUserByName() {
        const res = await fetch(`usersAPI.php?username=${this.name}`); // Works! //
        const data = res.json();
        return data;
    }
}

async function call() {
    const test = await new GetCompiledAPI(2, "arne").getQuizByName();
    console.log(test);
}

call();

export default GetCompiledAPI;