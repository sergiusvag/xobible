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
import ModalManager from "./managers/modalManager";
import ScoreManager from "./managers/scoreManager";
import NamesManager from "./managers/namesManager";
import ColorsManager from "./managers/colorsManager";
import BoardManager from "./managers/boardManager";
import QuestionManager from "./managers/questionManager";
import ConnectionValidator from "./managers/connectionValidator";

const locale = document.querySelector(".locale").textContent;
const room_number = document.querySelector(".room_number").textContent;

// const gameOverOnBtn = document.querySelector(".btn-over-turn-on");

// const overModalManager = new ModalManager(
//     ".wrapper-modal_over",
//     ".over-animation-wrap",
//     ".btn-over-continue"
// );
// gameOverOnBtn.addEventListener("click", (e) => {
//     overModalManager.on();
// });

let questionManager;
let namesManager;
let colorsManager;
let boardManager;
let roomChannel;
let myTurn;
let questions;
let isCorrect;
let currentPlayer;
let otherPlayer;
let connectionValidator;

const isMyTurn = () => {
    return myTurn;
};
const setManagers = (hostName, joinName, hostColor, joinColor) => {
    namesManager = new NamesManager(hostName, joinName);
    colorsManager = new ColorsManager(hostColor, joinColor);
    boardManager = new BoardManager(hostColor, joinColor);
    questionManager = new QuestionManager(
        hostColor,
        joinColor,
        __("Correct"),
        __("Wrong")
    );
};
const setScoreManager = (gameStatus) => {
    ScoreManager.setAll(
        {
            correct: gameStatus.host_current_score,
            bonus: gameStatus.host_current_bonus_score,
            wrong: gameStatus.host_current_wrong_score,
            total: gameStatus.host_current_total_score,
        },
        {
            correct: gameStatus.host_score,
            bonus: gameStatus.host_bonus_score,
            wrong: gameStatus.host_wrong_score,
            total: gameStatus.host_total_score,
        },
        {
            correct: gameStatus.join_current_score,
            bonus: gameStatus.join_current_bonus_score,
            wrong: gameStatus.join_current_wrong_score,
            total: gameStatus.join_current_total_score,
        },
        {
            correct: gameStatus.join_score,
            bonus: gameStatus.join_bonus_score,
            wrong: gameStatus.join_wrong_score,
            total: gameStatus.join_total_score,
        }
    );
};

const addScore = (isCorrect, player) => {
    const scoreToAddFunc = isCorrect
        ? `addCorrect${player}`
        : `addWrong${player}`;
    ScoreManager[scoreToAddFunc]();
};
const showQuestion = (index, currentPlayer, otherPlayer) => {
    const player = myTurn ? currentPlayer : otherPlayer;
    questionManager.start(questions[index], player, isMyTurn);
};

const setInQuestion = (index, currentPlayer, otherPlayer) => {
    boardManager.setSelectedTile(index);
    showQuestion(index, currentPlayer, otherPlayer);
};
const setInResult = (index, isCorrect, currentPlayer, otherPlayer) => {
    const player = myTurn ? currentPlayer : otherPlayer;
    boardManager.setSelectedTile(index);
    questionManager.setData(questions[index], player, isMyTurn);
    questionManager.setQuestionAnswered(isCorrect);
};
const setChannelListeners = () => {
    roomChannel.listen("GameTileSelected", (e) => {
        showQuestion(e.index, currentPlayer, otherPlayer);
    });
    roomChannel.listen("GameOptionSelected", (e) => {
        questionManager.switchSelected(e.index);
    });
    roomChannel.listen("GameQuestionAnswered", (e) => {
        questionManager.questionAnswered(e.is_correct);
    });
    roomChannel.listen("GameCloseResult", (e) => {
        gameClosedFunc(e.is_correct, e.index, e.bonus, otherPlayer);
        if (e.is_all_full) {
            initFinishRound(currentPlayer);
        }
    });
};
const optionClicked = (index) => {
    questionManager.enableAnswerButton();
    questionManager.switchSelected(index);
    window.axios.post(`/online-game-option-selected/${locale}`, {
        room_number: room_number,
        index: index,
    });
};
const questionAnsweredClicked = (getIsCorrect, getIndex) => {
    isCorrect = getIsCorrect();
    const index = getIndex();
    questionManager.questionAnswered(isCorrect);
    window.axios.post(`/online-game-question-answered/${locale}`, {
        room_number: room_number,
        is_correct: isCorrect,
        index: index,
    });
};
const closeResultClicked = () => {
    const index = boardManager.getSelectedTile();
    let isAllFull = false;
    let bonus = 0;
    if (isCorrect) {
        bonus = boardManager.calculateBonus(currentPlayer, index);
        isAllFull = boardManager.isAllFull();
    }
    gameClosedFunc(isCorrect, index, bonus, currentPlayer);
    window.axios
        .post(`/online-game-close-result/${locale}`, {
            room_number: room_number,
            is_correct: isCorrect,
            index: index,
            bonus: bonus,
            is_all_full: isAllFull,
        })
        .then(() => {
            if (isAllFull) {
                initFinishRound(currentPlayer);
            }
        });
};
const initFinishRound = (player) => {
    console.log(player);
};
const gameClosedFunc = (isCorrect, index, bonus, player) => {
    questionManager.closeResult();
    addScore(isCorrect, player);
    if (isCorrect) {
        boardManager[`selected${player}`](index);
        ScoreManager[`addBonus${player}`](bonus);
    }
    boardManager.toggleFreeTiles();
    myTurn = !myTurn;
};
const setQuestionClicks = () => {
    questionManager.setOptionClickedFunction(optionClicked);
    questionManager.setQuestionAnsweredFunction(questionAnsweredClicked);
    questionManager.setCloseResultFunction(closeResultClicked);
};
const connectionCountFunc = (counter) => {
    const domCounter = document.querySelector(".connection-counter");
    domCounter.textContent = counter;
};
const connectionAtemptFunc = (counter) => {
    const backBtn = document.querySelector(".btn-room-back-loader");
    let isBackOn = false;
    if (!isBackOn && counter >= 2) {
        backBtn.classList.add("show-loader");
        backBtn.onanimationend = () => {
            backBtn.style.opacity = 1;
        };
        isBackOn = true;
    }
};
const connectionEstablishedFunc = () => {
    Loader.Off();
};
const initConnectionValidator = () => {
    connectionValidator.setCountFunc(connectionCountFunc);
    connectionValidator.setConnectionAtemptFunc(connectionAtemptFunc);
    connectionValidator.setConnectionEstablishedFunc(connectionEstablishedFunc);
    connectionValidator.setReconnectionEstablishedFunc(
        connectionEstablishedFunc
    );
    connectionValidator.establishConnection();
};
const onLoad = async () => {
    Loader.On();
    roomChannel = window.Echo.private(`room.${room_number}`);
    await initLang(locale);
    window.axios
        .get(`/online-game-load/${locale}?room_number=${room_number}`)
        .then((resp) => {
            console.log(resp.data);
            const gameStatus = resp.data.game_status;
            currentPlayer = resp.data["i_am_upper"];
            otherPlayer = currentPlayer === "Host" ? "Join" : "Host";
            isCorrect = gameStatus.result === "is_correct";
            myTurn = resp.data["i_am"] === gameStatus.current_player;

            setManagers(
                gameStatus.host_name,
                gameStatus.join_name,
                gameStatus.host_color,
                gameStatus.join_color
            );
            if (gameStatus.status !== "in_over") {
                let questionStatus = resp.data.question_status;
                questions = resp.data.questions;

                setScoreManager(gameStatus);
                boardManager.setBoard(questionStatus, isMyTurn);
                boardManager.setTileFunction((clickedTile) => {
                    window.axios.post(`/online-game-tile-selected/${locale}`, {
                        room_number: room_number,
                        index: clickedTile.dataset.questionIndex,
                    });
                });

                setQuestionClicks();
                setChannelListeners();

                const index = questionStatus.selected_field.charAt(0) * 1;
                switch (gameStatus.status) {
                    case "in_round":
                        break;
                    case "in_question":
                        setInQuestion(index, currentPlayer, otherPlayer);
                        break;
                    case "in_result":
                        setInResult(
                            index,
                            isCorrect,
                            currentPlayer,
                            otherPlayer
                        );
                        break;
                }
            }
            connectionValidator = new ConnectionValidator(
                roomChannel,
                currentPlayer
            );
            initConnectionValidator();
        });
};

onLoad();
