const GetCompiledAPI = class GetCompiledAPI {
    constructor(id, name) {
        this.id = id;
        this.name = name;
    };

    async getAllQuizzes() {
        const allquiz = [];
        const quizresponse = await fetch("../API-Stuff/quizzesAPI.php?id=1");
        //const questionresponse = await fetch();
        //const answerresponse = await fetch();

        const quizdata = await quizresponse.json();
        //const questiondata = await questionresponse.json();
        //const answerdata = await answerresponse.json();
        return quizdata;
    }

    getQuizByFilter() {

    }

    getAllUsers() {

    }

    getTenUsers() {

    }

    getUserByFilter() {

    }
}

async function call() {
    const test = await new GetCompiledAPI().getAllQuizzes();
    console.log(test);
}

call();
//export default { GetCompiledAPI };