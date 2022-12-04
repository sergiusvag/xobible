import _ from "lodash";
window._ = _;

import "bootstrap";

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encryption: true,
});

import Loader from "./helper/loader";
import { initLang, __ } from "./helper/translator";
import RulesModalManager from "./managers/rulesModalManager";
import ScoreManager from "./managers/scoreManager";
import ColorsManager from "./managers/colorsManager";
import BoardManager from "./managers/boardManager";
import QuestionManager from "./managers/questionManager";
import RoundManager from "./managers/roundManager";

const locale = document.querySelector(".locale").textContent;
const host_color = document.querySelector(".host_color").textContent;
const join_color = document.querySelector(".join_color").textContent;
let current_round;
let questionManager;
let colorsManager;
let boardManager;
let roundManager;
let myTurn;
let questions;
let bonus;
let isCorrect;
let selectedIndex;
let thisTurnPlayer;
let currentPlayer = "Host";
let otherPlayer = "Join";
let allRoundsQuestions;
let max_round;

const isMyTurn = () => {
    return true;
};
const setManagers = (isMyTurn) => {
    colorsManager = new ColorsManager(
        host_color,
        join_color,
        "Host",
        "Join",
        isMyTurn
    );
    boardManager = new BoardManager(host_color, join_color);
    questionManager = new QuestionManager(
        host_color,
        join_color,
        __("Correct"),
        __("Wrong")
    );
    roundManager = new RoundManager("Host", false);
};
const setScoreManager = () => {
    ScoreManager.setAll(
        {
            correct: 0,
            bonus: 0,
            wrong: 0,
            total: 0,
        },
        {
            correct: 0,
            bonus: 0,
            wrong: 0,
            total: 0,
        },
        {
            correct: 0,
            bonus: 0,
            wrong: 0,
            total: 0,
        },
        {
            correct: 0,
            bonus: 0,
            wrong: 0,
            total: 0,
        }
    );
};

const showQuestion = (index, currentPlayer, otherPlayer) => {
    questionManager.start(questions[index], currentPlayer, isMyTurn);
};
const optionClicked = (index) => {
    questionManager.enableAnswerButton();
    questionManager.switchSelected(index);
};

const questionAnsweredClicked = (getIsCorrect, getIndex) => {
    isCorrect = getIsCorrect();
    questionManager.questionAnswered(isCorrect);
};
const closeResultClicked = () => {
    let filledTiles = boardManager.countFilledTiles();
    let isAllFull = false;
    selectedIndex = boardManager.getSelectedTile();
    bonus = 0;
    if (isCorrect) {
        bonus = boardManager.calculateBonus(currentPlayer, selectedIndex);
        filledTiles++;
    }
    isAllFull = filledTiles === 9;
    thisTurnPlayer = currentPlayer;
    gameClosedFunc();
    if (isAllFull) {
        setInRound(currentPlayer);
    }
};

const nextRoundClickedFunction = () => {
    roundManager.nextRound();
    Loader.On();
    prepareBoard();
    Loader.Off();
};

const newGameBtnFunction = () => {
    Loader.On();
    window.location.href = `/offline-game/${locale}?host_color=${host_color}&join_color=${join_color}`;
};

const finishGameBtnFunction = () => {
    Loader.On();
    window.location.href = `/welcome/${locale}`;
};

const setInRound = () => {
    roundManager.showRoundEnd(current_round, current_round === max_round);
};

const get_isCorrect = () => {
    return isCorrect;
};
const get_index = () => {
    return selectedIndex;
};
const get_bonus = () => {
    return bonus;
};
const get_thisTurnPlayer = () => {
    return thisTurnPlayer;
};

const afterCloseFunc = () => {
    const curIsCorrect = get_isCorrect();
    const curIndex = get_index();
    const curBonus = get_bonus();
    const curThisTurnPlayer = get_thisTurnPlayer();
    const tempPlayer = currentPlayer;

    ScoreManager.addScore(curIsCorrect, curThisTurnPlayer);
    if (curIsCorrect) {
        boardManager[`selected${curThisTurnPlayer}`](curIndex);
        ScoreManager[`addBonus${curThisTurnPlayer}`](curBonus);
    }
    boardManager.toggleFreeTiles();
    myTurn = !myTurn;
    colorsManager.toggleColors();
    currentPlayer = otherPlayer;
    otherPlayer = tempPlayer;
};
const gameClosedFunc = () => {
    questionManager.closeResult();
};

const setQuestionClicks = () => {
    questionManager.setOptionClickedFunction(optionClicked);
    questionManager.setQuestionAnsweredFunction(questionAnsweredClicked);
    questionManager.setCloseResultFunction(closeResultClicked);
    questionManager.setAfterCloseFunction(afterCloseFunc);
};
const setRoundClicks = () => {
    roundManager.setNextRoundFunction(nextRoundClickedFunction);
    roundManager.setNewGameBtnFunction(newGameBtnFunction);
    roundManager.setFinishGameBtnFunction(finishGameBtnFunction);
};

const prepareBoard = () => {
    current_round++;
    questions = allRoundsQuestions[current_round];
    ScoreManager.resetCurrectRound();
    boardManager.resetBoard();
};

const getCurrentPlayer = () => {
    return currentPlayer;
};
const getOtherPlayer = () => {
    return otherPlayer;
};
const onLoad = async () => {
    Loader.On();
    await initLang(locale);
    window.axios.get(`/offline-game-load/${locale}`).then((resp) => {
        allRoundsQuestions = resp.data.questions;
        current_round = 1;
        questions = allRoundsQuestions[current_round];
        max_round = resp.data.maxRound;
        myTurn = true;
        setManagers(true);

        Loader.Off();
        setScoreManager();
        boardManager.setTileFunction((clickedTile) => {
            const index = clickedTile.dataset.questionIndex;
            const currentPlayer = getCurrentPlayer();
            const otherPlayer = getOtherPlayer();
            showQuestion(index, currentPlayer, otherPlayer);
        });

        setQuestionClicks();
        setRoundClicks();
    });
};

onLoad();
