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
import NamesManager from "./managers/namesManager";
import ColorsManager from "./managers/colorsManager";
import BoardManager from "./managers/boardManager";
import QuestionManager from "./managers/questionManager";
import RoundManager from "./managers/roundManager";
import ConnectionValidator from "./managers/connectionValidator";

const locale = document.querySelector(".locale").textContent;
const room_number = document.querySelector(".room_number").textContent;
const max_round = document.querySelector(".max_round").textContent;
const current_round = document.querySelector(".current_round").textContent;
const domCounter = document.querySelector(".connection-counter");

let questionManager;
let namesManager;
let colorsManager;
let boardManager;
let roundManager;
let roomChannel;
let myTurn;
let questionStatus;
let questions;
let isCorrect;
let currentPlayer;
let otherPlayer;
let connectionValidator;
let gameStatus;
let isHost;

const isMyTurn = () => {
    return myTurn;
};
const setManagers = (
    hostName,
    joinName,
    hostColor,
    joinColor,
    currentPlayer,
    otherPlayer,
    isMyTurn
) => {
    namesManager = new NamesManager(hostName, joinName);
    colorsManager = new ColorsManager(
        hostColor,
        joinColor,
        currentPlayer,
        otherPlayer,
        isMyTurn
    );
    boardManager = new BoardManager(hostColor, joinColor);
    questionManager = new QuestionManager(
        hostColor,
        joinColor,
        __("Correct"),
        __("Wrong")
    );
    roundManager = new RoundManager(currentPlayer);
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

const showQuestion = (index, currentPlayer, otherPlayer) => {
    const player = myTurn ? currentPlayer : otherPlayer;
    questionManager.start(questions[index], player, isMyTurn);
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
            setInRound(currentPlayer);
        }
    });
    roomChannel.listen("GameOver", (e) => {
        roundManager.showOver();
    });
    roomChannel.listen("GameNextRoundClicked", (e) => {
        Loader.On();
        roundManager.nextRound();
        window.axios
            .post(`/online-game-next-round-join/${locale}`, {
                room_number: room_number,
            })
            .then((resp) => {
                prepareBoard(resp.data);
                Loader.Off();
            });
    });

    roomChannel.listenForWhisper("readyBtnClicked", (e) => {
        roundManager.switchStartBtn(e.isReady);
    });
    roomChannel.listenForWhisper("newGamePreparing", () => {
        Loader.On();
    });
    roomChannel.listenForWhisper("newGameReady", () => {
        window.location.href = `/online-game/${locale}?room_number=${room_number}`;
    });
    roomChannel.listenForWhisper("finishGamePreparing", () => {
        Loader.On();
    });
    roomChannel.listenForWhisper("finishGameReady", () => {
        window.location.href = `/welcome/${locale}`;
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
    }
    gameClosedFunc(isCorrect, index, bonus, currentPlayer);
    isAllFull = boardManager.isAllFull();
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
                setInRound(currentPlayer);
            }
        });
};
const overFunction = () => {
    window.axios.post(`/online-game-over/${locale}`, {
        room_number: room_number,
    });
};

const nextRoundClickedFunction = () => {
    roundManager.nextRound();
    Loader.On();
    window.axios
        .post(`/online-game-next-round/${locale}`, {
            room_number: room_number,
        })
        .then((resp) => {
            prepareBoard(resp.data);
            Loader.Off();
        });
};
const readyBtnFunction = (isReady) => {
    roomChannel.whisper("readyBtnClicked", {
        isReady: isReady,
    });
};
const newGameBtnFunction = () => {
    Loader.On();
    roomChannel.whisper("newGamePreparing", {});
    window.axios
        .post(`/online-game-new-game/${locale}`, {
            room_number: room_number,
        })
        .then(() => {
            roomChannel.whisper("newGameReady", {});
            window.location.href = `/online-game/${locale}?room_number=${room_number}`;
        });
};

const finishGameBtnFunction = () => {
    Loader.On();
    roomChannel.whisper("finishGamePreparing", {});
    window.axios
        .post(`/online-game-finish-game/${locale}`, {
            room_number: room_number,
        })
        .then(() => {
            roomChannel.whisper("finishGameReady", {});
            window.location.href = `/welcome/${locale}`;
        });
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
const setInRound = () => {
    roundManager.showRoundEnd(current_round, current_round === max_round);
};
const setInOver = () => {
    roundManager.showOver();
};
const gameClosedFunc = (isCorrect, index, bonus, player) => {
    questionManager.closeResult();
    ScoreManager.addScore(isCorrect, player);
    if (isCorrect) {
        boardManager[`selected${player}`](index);
        ScoreManager[`addBonus${player}`](bonus);
    }
    boardManager.toggleFreeTiles();
    myTurn = !myTurn;
    colorsManager.toggleColors();
};
const setQuestionClicks = () => {
    questionManager.setOptionClickedFunction(optionClicked);
    questionManager.setQuestionAnsweredFunction(questionAnsweredClicked);
    questionManager.setCloseResultFunction(closeResultClicked);
};
const setRoundClicks = () => {
    roundManager.setNextRoundFunction(nextRoundClickedFunction);
    roundManager.setOverFunction(overFunction);

    roundManager.setReadyBtnFunction(readyBtnFunction);
    roundManager.setNewGameBtnFunction(newGameBtnFunction);
    roundManager.setFinishGameBtnFunction(finishGameBtnFunction);
};
const connectionCountFunc = (counter) => {
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
    domCounter.textContent = "";
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

const prepareBoard = (data) => {
    gameStatus = data.game_status;
    questionStatus = data.question_status;
    questions = data.questions;

    setScoreManager(gameStatus);
    boardManager.resetBoard();
    boardManager.setBoard(questionStatus, isMyTurn);
};
const onLoad = async () => {
    Loader.On();
    roomChannel = window.Echo.private(`room.${room_number}`);
    await initLang(locale);
    window.axios
        .get(`/online-game-load/${locale}?room_number=${room_number}`)
        .then((resp) => {
            gameStatus = resp.data.game_status;
            questionStatus = resp.data.question_status;
            questions = resp.data.questions;
            isHost = resp.data["i_am_upper"] === "Host";
            otherPlayer = isHost ? "Join" : "Host";
            isCorrect = gameStatus.result === "is_correct";
            myTurn = resp.data["i_am"] === gameStatus.current_player;
            currentPlayer = resp.data["i_am_upper"];

            setManagers(
                gameStatus.host_name,
                gameStatus.join_name,
                gameStatus.host_color,
                gameStatus.join_color,
                currentPlayer,
                otherPlayer,
                myTurn
            );

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
            setRoundClicks();

            const index = questionStatus.selected_field.charAt(0) * 1;
            switch (gameStatus.status) {
                case "in_round":
                    setInRound();
                    break;
                case "in_question":
                    setInQuestion(index, currentPlayer, otherPlayer);
                    break;
                case "in_result":
                    setInResult(index, isCorrect, currentPlayer, otherPlayer);
                    break;
                case "in_over":
                    setInOver();
                    break;
            }
            connectionValidator = new ConnectionValidator(
                roomChannel,
                currentPlayer
            );
            initConnectionValidator();
        });
};

onLoad();
