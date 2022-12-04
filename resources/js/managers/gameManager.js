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

class GameManager {
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
    constructor(isOnline) {
        if (isOnline) {
            this.initOnlineDomInfo();
            this.onlineOnLoad();
        } else {
            this.initOfflineDomInfo();
            this.offlineOnLoad();
        }
    }
    initOfflineDomInfo() {
        this.host_color = document.querySelector(".host_color").textContent;
        this.join_color = document.querySelector(".join_color").textContent;
    }
    initOnlineDomInfo() {
        this.room_number = document.querySelector(".room_number").textContent;
        this.max_round = document.querySelector(".max_round").textContent;
        this.current_round =
            document.querySelector(".current_round").textContent;
        this.domCounter = document.querySelector(".connection-counter");
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
            this.initOffline
        );
    }
    onlineOnLoad() {
        this.onLoad(
            () => {
                this.roomChannel = window.Echo.private(
                    `room.${this.room_number}`
                );
            },
            `/online-game-load/${locale}?room_number=${this.room_number}`,
            this.initOnline
        );
    }

    initOffline() {}

    initOnline(resp) {
        this.gameStatus = resp.data.game_status;
        this.questionStatus = resp.data.question_status;
        this.uestions = resp.data.questions;
        this.isHost = resp.data["i_am_upper"] === "Host";
        this.otherPlayer = this.isHost ? "Join" : "Host";
        this.isCorrect = this.gameStatus.result === "is_correct";
        this.myTurn = resp.data["i_am"] === this.gameStatus.current_player;
        this.currentPlayer = resp.data["i_am_upper"];

        this.setManagers();
        this.setScoreManager();
        this.boardManager.setBoard(this.questionStatus, this.isMyTurn);
        this.boardManager.setTileFunction((clickedTile) => {
            window.axios.post(`/online-game-tile-selected/${this.locale}`, {
                room_number: this.room_number,
                index: clickedTile.dataset.questionIndex,
            });
        });

        this.setQuestionClicks();
        this.setChannelListeners();
        this.setRoundClicks();

        const index = this.questionStatus.selected_field.charAt(0) * 1;
        switch (this.gameStatus.status) {
            case "in_round":
                this.setInRound();
                break;
            case "in_question":
                this.setInQuestion(index, this.currentPlayer, this.otherPlayer);
                break;
            case "in_result":
                this.setInResult(
                    index,
                    this.isCorrect,
                    this.currentPlayer,
                    this.otherPlayer
                );
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
        this.namesManager = new NamesManager(
            this.gameStatus.host_name,
            this.gameStatus.join_name
        );
        this.colorsManager = new ColorsManager(
            this.gameStatus.host_color,
            this.gameStatus.join_color,
            this.currentPlayer,
            this.otherPlayer,
            this.myTurn
        );
        this.boardManager = new BoardManager(
            this.gameStatus.host_color,
            this.gameStatus.join_color
        );
        this.questionManager = new QuestionManager(
            this.gameStatus.host_color,
            this.gameStatus.join_color,
            __("Correct"),
            __("Wrong")
        );
        this.roundManager = new RoundManager(this.currentPlayer);
    }

    setScoreManager = () => {
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
    };

    isMyTurn() {
        return this.myTurn;
    }
}
