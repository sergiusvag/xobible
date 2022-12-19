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

import Loader from "../helper/loader";
import { initLang, __ } from "../helper/translator";
import RulesModalManager from "./rulesModalManager";
import ScoreManager from "./scoreManager";
import NamesManager from "./namesManager";
import ColorsManager from "./colorsManager";
import BoardManager from "./boardManager";
import QuestionManager from "./questionManager";
import RoundManager from "./roundManager";
import ConnectionValidator from "./connectionValidator";
import AudioManager from "./audioManager";

export default class GameManager {
    locale = document.querySelector(".locale").textContent;
    room_number;
    max_round;
    current_round;
    domCounter;
    host_color;
    join_color;

    namesManager;
    colorsManager;
    boardManager;
    questionManager;
    roundManager;

    roomChannel;
    myTurn;
    questionStatus;
    questions;
    bonus;
    isCorrect;
    selectedIndex;
    thisTurnPlayer;
    currentPlayer;
    otherPlayer;
    connectionValidator;
    gameStatus;
    isHost;
    allRoundsQuestions;
    isOnline;
    isMyTurn = () => {};
    setManagersInnerFunc = () => {};
    optionClickedInnerFunc = () => {};
    questionAnsweredClickedInnerFunc = () => {};
    closeResultClickedInnerFunc = () => {};
    nextRoundClickedInnerFunc = () => {};
    newGameBtnInnerFunc = () => {};
    finishGameBtnInnerFunc = () => {};
    setRoundClicksInnerFunc = () => {};
    afterCloseInnerFunc = () => {};
    constructor(isOnline) {
        this.isOnline = isOnline;
        if (isOnline) {
            this.initOnlineInfo();
            this.initOnlineFunctions();
            this.onlineOnLoad();
        } else {
            this.initOfflineInfo();
            this.initOfflineFunctions();
            this.offlineOnLoad();
        }
    }

    initOfflineInfo() {
        this.host_color = document.querySelector(".host_color").textContent;
        this.join_color = document.querySelector(".join_color").textContent;
        this.currentPlayer = "Host";
        this.otherPlayer = "Join";
        this.isMyTurn = () => {
            return true;
        };
    }
    initOnlineInfo() {
        this.room_number = document.querySelector(".room_number").textContent;
        this.max_round = document.querySelector(".max_round").textContent;
        this.current_round =
            document.querySelector(".current_round").textContent;
        this.domCounter = document.querySelector(".connection-counter");
        this.isMyTurn = () => {
            return this.myTurn;
        };
    }
    initOnlineFunctions() {
        this.setManagersInnerFunc = () => {
            const hostColor = this.gameStatus.host_color;
            const joinColor = this.gameStatus.join_color;
            this.namesManager = new NamesManager(
                this.gameStatus.host_name,
                this.gameStatus.join_name
            );

            return { hostColor, joinColor };
        };
        this.optionClickedInnerFunc = (index) => {
            window.axios.post(`/online-game-option-selected/${this.locale}`, {
                room_number: this.room_number,
                index: index,
            });
        };
        this.questionAnsweredClickedInnerFunc = (getIndex) => {
            const index = getIndex();
            window.axios.post(`/online-game-question-answered/${this.locale}`, {
                room_number: this.room_number,
                is_correct: this.isCorrect,
                index: index,
            });
        };
        this.closeResultClickedInnerFunc = (isAllFull) => {
            window.axios
                .post(`/online-game-close-result/${this.locale}`, {
                    room_number: this.room_number,
                    is_correct: this.isCorrect,
                    index: this.selectedIndex,
                    bonus: this.bonus,
                    is_all_full: isAllFull,
                })
                .then(() => {
                    if (isAllFull) {
                        this.setInRound(this.currentPlayer);
                    }
                });
        };
        this.nextRoundClickedInnerFunc = () => {
            Loader.On();
            window.axios
                .post(`/online-game-next-round/${this.locale}`, {
                    room_number: this.room_number,
                })
                .then((resp) => {
                    this.prepareBoard(resp.data);
                    Loader.Off();
                });
        };
        this.newGameBtnInnerFunc = () => {
            this.roomChannel.whisper("newGamePreparing", {});
            window.axios
                .post(`/online-game-new-game/${this.locale}`, {
                    room_number: this.room_number,
                })
                .then(() => {
                    this.roomChannel.whisper("newGameReady", {});
                    window.location.href = `/online-game/${this.locale}?room_number=${this.room_number}`;
                });
        };
        this.finishGameBtnInnerFunc = () => {
            this.roomChannel.whisper("finishGamePreparing", {});
            window.axios
                .post(`/online-game-finish-game/${this.locale}`, {
                    room_number: this.room_number,
                })
                .then(() => {
                    this.roomChannel.whisper("finishGameReady", {});
                    window.location.href = `/welcome/${this.locale}`;
                });
        };
        this.setRoundClicksInnerFunc = () => {
            this.roundManager.setOverFunction(this.overFunction.bind(this));
            this.roundManager.setReadyBtnFunction(
                this.readyBtnFunction.bind(this)
            );
        };
    }
    initOfflineFunctions() {
        this.setManagersInnerFunc = () => {
            const hostColor = this.host_color;
            const joinColor = this.join_color;
            return { hostColor, joinColor };
        };
        this.closeResultClickedInnerFunc = (isAllFull) => {
            if (isAllFull) {
                this.setInRound(this.currentPlayer);
            }
        };
        this.nextRoundClickedInnerFunc = () => {
            this.prepareBoardOffline();
        };
        this.newGameBtnInnerFunc = () => {
            window.location.href = `/offline-game/${this.locale}?host_color=${this.host_color}&join_color=${this.join_color}`;
        };
        this.finishGameBtnInnerFunc = () => {
            window.location.href = `/welcome/${this.locale}`;
        };
        this.afterCloseInnerFunc = () => {
            this.switchPlayersOffline();
        };
    }
    async onLoad(beforeRequestFunc, requestLink, thenRequestFunc) {
        Loader.On();
        await initLang(this.locale);
        beforeRequestFunc();
        window.axios.get(requestLink).then(thenRequestFunc);
    }
    offlineOnLoad() {
        this.onLoad(
            () => {},
            `/offline-game-load/${this.locale}`,
            this.initOffline.bind(this)
        );
    }
    onlineOnLoad() {
        this.onLoad(
            () => {
                this.roomChannel = window.Echo.private(
                    `room.${this.room_number}`
                );
            },
            `/online-game-load/${this.locale}?room_number=${this.room_number}`,
            this.initOnline.bind(this)
        );
    }

    initOffline(resp) {
        this.allRoundsQuestions = resp.data.questions;
        this.current_round = 1;
        this.questions = this.allRoundsQuestions[this.current_round];
        this.max_round = resp.data.maxRound;
        this.myTurn = true;
        this.setManagers(true);

        Loader.Off();
        ScoreManager.initAll();
        this.boardManager.setTileFunction((clickedTile) => {
            this.questionManager.start(
                this.questions[clickedTile.dataset.questionIndex],
                this.currentPlayer,
                this.isMyTurn.bind(this)
            );
        });

        this.setQuestionClicks();
        this.setRoundClicks();
        AudioManager.initTileSelection();
    }

    initOnline(resp) {
        this.gameStatus = resp.data.game_status;
        this.questionStatus = resp.data.question_status;
        this.questions = resp.data.questions;
        this.isHost = resp.data["i_am_upper"] === "Host";
        this.otherPlayer = this.isHost ? "Join" : "Host";
        this.isCorrect = this.gameStatus.result === "is_correct";
        this.myTurn = resp.data["i_am"] === this.gameStatus.current_player;
        this.currentPlayer = resp.data["i_am_upper"];
        const index = this.questionStatus.selected_field.charAt(0) * 1;

        this.setManagers();
        this.setScoreManager();
        this.boardManager.setBoard(
            this.questionStatus,
            this.isMyTurn.bind(this)
        );
        this.boardManager.setTileFunction((clickedTile) => {
            window.axios.post(`/online-game-tile-selected/${this.locale}`, {
                room_number: this.room_number,
                index: clickedTile.dataset.questionIndex,
            });
        });

        this.setQuestionClicks();
        this.setChannelListeners();
        this.setRoundClicks();

        switch (this.gameStatus.status) {
            case "in_round":
                this.setInRound();
                break;
            case "in_question":
                this.setInQuestion(index);
                break;
            case "in_result":
                this.setInResult(index, this.isCorrect);
                break;
            case "in_over":
                this.setInOver();
                break;
        }
        this.connectionValidator = new ConnectionValidator(
            this.roomChannel,
            this.currentPlayer
        );
        this.initConnectionValidator();
    }

    setManagers() {
        let { hostColor, joinColor } = this.setManagersInnerFunc();
        this.colorsManager = new ColorsManager(
            hostColor,
            joinColor,
            this.currentPlayer,
            this.otherPlayer,
            this.myTurn
        );
        this.boardManager = new BoardManager(hostColor, joinColor);
        this.questionManager = new QuestionManager(
            hostColor,
            joinColor,
            __("Correct"),
            __("Wrong")
        );
        this.roundManager = new RoundManager(this.currentPlayer, this.isOnline);
    }

    setScoreManager() {
        ScoreManager.setAll(
            {
                correct: this.gameStatus.host_current_score,
                bonus: this.gameStatus.host_current_bonus_score,
                wrong: this.gameStatus.host_current_wrong_score,
                total: this.gameStatus.host_current_total_score,
            },
            {
                correct: this.gameStatus.host_score,
                bonus: this.gameStatus.host_bonus_score,
                wrong: this.gameStatus.host_wrong_score,
                total: this.gameStatus.host_total_score,
            },
            {
                correct: this.gameStatus.join_current_score,
                bonus: this.gameStatus.join_current_bonus_score,
                wrong: this.gameStatus.join_current_wrong_score,
                total: this.gameStatus.join_current_total_score,
            },
            {
                correct: this.gameStatus.join_score,
                bonus: this.gameStatus.join_bonus_score,
                wrong: this.gameStatus.join_wrong_score,
                total: this.gameStatus.join_total_score,
            }
        );
    }

    showQuestion(index) {
        const player = this.myTurn
            ? this.getCurrentPlayer()
            : this.getOtherPlayer();
        this.questionManager.start(
            this.questions[index],
            player,
            this.isMyTurn.bind(this)
        );
    }

    setChannelListeners() {
        this.roomChannel.listen("GameTileSelected", (e) => {
            this.showQuestion(e.index);
            AudioManager.play("questionSelect");
        });
        this.roomChannel.listen("GameOptionSelected", (e) => {
            this.questionManager.switchSelected(e.index);
            AudioManager.play("transition");
        });
        this.roomChannel.listen("GameQuestionAnswered", (e) => {
            this.questionManager.questionAnswered(e.is_correct);
            if (e.is_correct) {
                AudioManager.play("resultCorrect");
            } else {
                AudioManager.play("resultWrong");
            }
        });
        this.roomChannel.listen("GameCloseResult", (e) => {
            this.bonus = e.bonus;
            this.isCorrect = e.is_correct;
            this.selectedIndex = e.index;
            this.thisTurnPlayer = this.otherPlayer;
            this.gameClosedFunc();
            if (e.is_all_full) {
                this.setInRound(this.currentPlayer);
            }
        });
        this.roomChannel.listen("GameOver", (e) => {
            this.roundManager.showOver();
        });
        this.roomChannel.listen("GameNextRoundClicked", (e) => {
            Loader.On();
            this.roundManager.nextRound();
            window.axios
                .post(`/online-game-next-round-join/${this.locale}`, {
                    room_number: this.room_number,
                })
                .then((resp) => {
                    this.prepareBoard(resp.data);
                    Loader.Off();
                });
        });
        this.roomChannel.listenForWhisper("readyBtnClicked", (e) => {
            this.roundManager.switchStartBtn(e.isReady);
        });
        this.roomChannel.listenForWhisper("newGamePreparing", () => {
            Loader.On();
        });
        this.roomChannel.listenForWhisper("newGameReady", () => {
            window.location.href = `/online-game/${this.locale}?room_number=${this.room_number}`;
        });
        this.roomChannel.listenForWhisper("finishGamePreparing", () => {
            Loader.On();
        });
        this.roomChannel.listenForWhisper("finishGameReady", () => {
            window.location.href = `/welcome/${this.locale}`;
        });
    }
    optionClicked(index) {
        if (this.questionManager.isSelected(index)) {
            AudioManager.play("selectSelected");
        } else {
            AudioManager.play("transition");
            this.questionManager.enableAnswerButton();
            this.questionManager.switchSelected(index);
            this.optionClickedInnerFunc(index);
        }
    }
    questionAnsweredClicked(getIsCorrect, getIndex) {
        this.isCorrect = getIsCorrect();
        if (this.isCorrect) {
            AudioManager.play("resultCorrect");
        } else {
            AudioManager.play("resultWrong");
        }
        this.questionManager.questionAnswered(this.isCorrect);
        this.questionAnsweredClickedInnerFunc(getIndex);
    }
    closeResultClicked() {
        let filledTiles = this.boardManager.countFilledTiles();
        let isAllFull = false;
        this.selectedIndex = this.boardManager.getSelectedTile();
        this.bonus = 0;
        if (this.isCorrect) {
            this.bonus = this.boardManager.calculateBonus(
                this.currentPlayer,
                this.selectedIndex
            );
            if (this.bonus) {
                console.log("YES A BONUS FINALLY!!!");
            }
            filledTiles++;
        }
        isAllFull = filledTiles === 9;
        this.thisTurnPlayer = this.currentPlayer;
        this.gameClosedFunc();
        this.closeResultClickedInnerFunc(isAllFull);
    }
    overFunction() {
        window.axios.post(`/online-game-over/${this.locale}`, {
            room_number: this.room_number,
        });
    }
    nextRoundClickedFunction() {
        this.roundManager.nextRound();
        this.nextRoundClickedInnerFunc();
    }
    readyBtnFunction(isReady) {
        this.roomChannel.whisper("readyBtnClicked", {
            isReady: isReady,
        });
    }
    newGameBtnFunction() {
        Loader.On();
        this.newGameBtnInnerFunc();
    }
    finishGameBtnFunction() {
        Loader.On();
        this.finishGameBtnInnerFunc();
    }
    setInQuestion(index) {
        this.boardManager.setSelectedTile(index);
        this.showQuestion(index);
    }
    setInResult(index, isCorrect) {
        const player = this.myTurn ? this.currentPlayer : this.otherPlayer;
        this.boardManager.setSelectedTile(index);
        this.questionManager.setData(
            this.questions[index],
            this.gameStatus.selected_option,
            player,
            this.isMyTurn.bind(this)
        );
        this.questionManager.setQuestionAnswered(isCorrect);
    }
    setInRound() {
        this.roundManager.showRoundEnd(
            this.current_round,
            this.current_round === this.max_round
        );
    }
    setInOver() {
        this.roundManager.showOver();
    }
    get_isCorrect() {
        return this.isCorrect;
    }
    get_index() {
        return this.selectedIndex;
    }
    get_bonus() {
        return this.bonus;
    }
    get_thisTurnPlayer() {
        return this.thisTurnPlayer;
    }
    getCurrentPlayer() {
        return this.currentPlayer;
    }
    getOtherPlayer() {
        return this.otherPlayer;
    }
    switchPlayersOffline() {
        const tempPlayer = this.currentPlayer;
        this.currentPlayer = this.otherPlayer;
        this.otherPlayer = tempPlayer;
    }
    afterCloseFunc() {
        const curIsCorrect = this.get_isCorrect();
        const curIndex = this.get_index();
        const curBonus = this.get_bonus();
        const curThisTurnPlayer = this.get_thisTurnPlayer();
        ScoreManager.addScore(curIsCorrect, curThisTurnPlayer);
        if (curIsCorrect) {
            this.boardManager[`selected${curThisTurnPlayer}`](curIndex);
            ScoreManager[`addBonus${curThisTurnPlayer}`](curBonus);
        }
        this.boardManager.toggleFreeTiles();
        this.myTurn = !this.myTurn;
        this.colorsManager.toggleColors();
        this.afterCloseInnerFunc();
    }
    gameClosedFunc() {
        this.questionManager.closeResult();
    }

    setQuestionClicks() {
        this.questionManager.setOptionClickedFunction(
            this.optionClicked.bind(this)
        );
        this.questionManager.setQuestionAnsweredFunction(
            this.questionAnsweredClicked.bind(this)
        );
        this.questionManager.setCloseResultFunction(
            this.closeResultClicked.bind(this)
        );
        this.questionManager.setAfterCloseFunction(
            this.afterCloseFunc.bind(this)
        );
    }
    setRoundClicks() {
        this.roundManager.setNextRoundFunction(
            this.nextRoundClickedFunction.bind(this)
        );
        this.roundManager.setNewGameBtnFunction(
            this.newGameBtnFunction.bind(this)
        );
        this.roundManager.setFinishGameBtnFunction(
            this.finishGameBtnFunction.bind(this)
        );
        this.setRoundClicksInnerFunc();
    }
    connectionCountFunc(counter) {
        this.domCounter.textContent = counter;
    }
    connectionAtemptFunc(counter) {
        const backBtn = document.querySelector(".btn-room-back-loader");
        let isBackOn = false;
        if (!isBackOn && counter >= 2) {
            backBtn.classList.add("show-loader");
            backBtn.onanimationend = () => {
                backBtn.style.opacity = 1;
            };
            isBackOn = true;
        }
    }
    connectionEstablishedFunc() {
        this.domCounter.textContent = "";
        Loader.Off();
    }
    initConnectionValidator = () => {
        this.connectionValidator.setCountFunc(
            this.connectionCountFunc.bind(this)
        );
        this.connectionValidator.setConnectionAtemptFunc(
            this.connectionAtemptFunc.bind(this)
        );
        this.connectionValidator.setConnectionEstablishedFunc(
            this.connectionEstablishedFunc.bind(this)
        );
        this.connectionValidator.setReconnectionEstablishedFunc(
            this.connectionEstablishedFunc.bind(this)
        );
        this.connectionValidator.establishConnection();
    };

    prepareBoard(data) {
        this.gameStatus = data.game_status;
        this.questionStatus = data.question_status;
        this.questions = data.questions;

        this.setScoreManager(this.gameStatus);
        this.boardManager.resetBoard();
        this.boardManager.setBoard(
            this.questionStatus,
            this.isMyTurn.bind(this)
        );
    }

    prepareBoardOffline() {
        this.current_round++;
        this.questions = this.allRoundsQuestions[this.current_round];
        ScoreManager.resetCurrectRound();
        this.boardManager.resetBoard();
    }
}
