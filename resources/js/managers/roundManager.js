import ModalManager from "./modalManager";

export default class RoundManager {
    _roundModalManager = new ModalManager(
        ".wrapper-modal_round",
        ".round-animation-wrap",
        ".btn-round-continue"
    );
    _roundFinishBtn = document.querySelector(".btn-round-finish");
    _overModalManager = new ModalManager(
        ".wrapper-modal_over",
        ".over-animation-wrap",
        ".btn-over-continue"
    );
    _overFinishBtn = document.querySelector(".btn-over-finish");
    _overNewGameBtn = document.querySelector(".btn-over-new-game");
    _overReadyBtn = document.querySelector(".btn-over-ready");

    _roundNumber = document.querySelector(".round-number");
    _isFinal = false;
    _isReady = false;
    _overFunction = () => {};
    _nextRoundFunc = () => {};
    _readyBtnFunc = () => {};
    _newGameBtnFunc = () => {};
    _finishGameBtnFunc = () => {};
    constructor(currentPlayer) {
        this[`init${currentPlayer}`]();

        this._overFinishBtn.addEventListener("click", () => {
            this._finishGameBtnFunc();
        });
    }
    initHost() {
        this._roundModalManager.continueBtn.classList.remove("control-btn-dis");
        this._roundFinishBtn.classList.remove("control-btn-dis");
        this._roundModalManager.continueBtn.addEventListener("click", (e) => {
            if (this._isFinal) {
                this._showOver();
            } else {
                this._nextRoundFunc();
            }
        });
        this._roundFinishBtn.addEventListener("click", (e) => {
            this._showOver();
        });

        this._overReadyBtn.classList.add("d-hide");
        this._overNewGameBtn.classList.add("control-btn-dis");
        this._overNewGameBtn.addEventListener("click", (e) => {
            this._newGameBtnFunc();
        });
    }
    initJoin() {
        this._roundModalManager.continueBtn.classList.add("control-btn-dis");
        this._roundFinishBtn.classList.add("control-btn-dis");
        this._overNewGameBtn.classList.add("d-hide");
        this._overReadyBtn.addEventListener("click", (e) => {
            this._isReady = !this._isReady;
            this._overReadyBtn.classList.toggle("btn-ready-clicked");
            this._readyBtnFunc(this._isReady);
        });
    }
    setOverFunction(overFunction) {
        this._overFunction = overFunction;
    }
    setNextRoundFunction(nextRoundFunc) {
        this._nextRoundFunc = nextRoundFunc;
    }

    setReadyBtnFunction(readyBtnFunc) {
        this._readyBtnFunc = readyBtnFunc;
    }
    setNewGameBtnFunction(newGameBtnFunc) {
        this._newGameBtnFunc = newGameBtnFunc;
    }
    setFinishGameBtnFunction(finishGameBtnFunc) {
        this._finishGameBtnFunc = finishGameBtnFunc;
    }

    _showOver() {
        this._overFunction();
        this.showOver();
    }
    showOver() {
        this._roundModalManager.off();
        this._overModalManager.on();
    }
    nextRound() {
        this._roundModalManager.off();
    }
    showRoundEnd(roundNumber, isFinal = false) {
        this._roundNumber.textContent = roundNumber;
        this._roundModalManager.on();
        this._isFinal = isFinal;
    }

    switchStartBtn(isReady) {
        this._isReady = isReady;
        const addOrRemove = isReady ? "remove" : "add";
        this._overNewGameBtn.classList[addOrRemove]("control-btn-dis");
    }
}
