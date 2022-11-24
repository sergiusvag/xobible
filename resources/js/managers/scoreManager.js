import { forEach } from "lodash";

class ScoreHolder {
    constructor(scoreCorrect, scoreBonus, scoreWrong, scoreTotal) {
        this.scoreCorrect = document.querySelectorAll(scoreCorrect);
        this.scoreBonus = document.querySelectorAll(scoreBonus);
        this.scoreWrong = document.querySelectorAll(scoreWrong);
        this.scoreTotal = document.querySelectorAll(scoreTotal);
    }

    _set(scoreDOM, score) {
        this[scoreDOM].forEach((e) => {
            e.textContent = score;
        });
    }
    _add(scoreDOM, score) {
        this[scoreDOM].forEach((e) => {
            e.textContent = e.textContent * 1 + score;
        });
    }

    _setCorrect(score) {
        this._set("scoreCorrect", score);
    }
    _setBonus(score) {
        this._set("scoreBonus", score);
    }
    _setWrong(score) {
        this._set("scoreWrong", score);
    }
    _setTotal(score) {
        this._set("scoreTotal", score);
    }
    _setAll(correct, bonus, wrong, total) {
        this._setCorrect(correct);
        this._setBonus(bonus);
        this._setWrong(wrong);
        this._setTotal(total);
    }
    _addCorrect(score = 1) {
        this._add("scoreCorrect", score);
        this._add("scoreTotal", score);
    }
    _addBonus(score) {
        this._add("scoreBonus", score);
        this._add("scoreTotal", score);
    }
    _addWrong(score = 1) {
        this._add("scoreWrong", score);
    }
}

class ScoreManager {
    _hostScoreHolderRound = new ScoreHolder(
        ".score-correct-round-host",
        ".score-bonus-round-host",
        ".score-wrong-round-host",
        ".score-total-round-host"
    );
    _hostScoreHolderGame = new ScoreHolder(
        ".score-correct-game-host",
        ".score-bonus-game-host",
        ".score-wrong-game-host",
        ".score-total-game-host"
    );
    _joinScoreHolderRound = new ScoreHolder(
        ".score-correct-round-join",
        ".score-bonus-round-join",
        ".score-wrong-round-join",
        ".score-total-round-join"
    );
    _joinScoreHolderGame = new ScoreHolder(
        ".score-correct-game-join",
        ".score-bonus-game-join",
        ".score-wrong-game-join",
        ".score-total-game-join"
    );
    constructor() {}

    setAll(roundHost, gameHost, roundJoin, gameJoin) {
        this._hostScoreHolderRound._setAll(
            roundHost.correct,
            roundHost.bonus,
            roundHost.wrong,
            roundHost.total
        );
        this._hostScoreHolderGame._setAll(
            gameHost.correct,
            gameHost.bonus,
            gameHost.wrong,
            gameHost.total
        );
        this._joinScoreHolderRound._setAll(
            roundJoin.correct,
            roundJoin.bonus,
            roundJoin.wrong,
            roundJoin.total
        );
        this._joinScoreHolderGame._setAll(
            gameJoin.correct,
            gameJoin.bonus,
            gameJoin.wrong,
            gameJoin.total
        );
    }

    resetCurrectRound() {
        this._hostScoreHolderRound._setAll(0, 0, 0, 0);
        this._joinScoreHolderRound._setAll(0, 0, 0, 0);
    }

    _add(player, type, score = 1) {
        this[`_${player}ScoreHolderRound`][`_add${type}`](score);
        this[`_${player}ScoreHolderGame`][`_add${type}`](score);
    }
    addCorrectHost() {
        this._add("host", "Correct");
    }
    addCorrectJoin() {
        this._add("join", "Correct");
    }
    addBonusHost(score) {
        this._add("host", "Bonus", score);
    }
    addBonusJoin(score) {
        this._add("join", "Bonus", score);
    }
    addWrongHost() {
        this._add("host", "Wrong");
    }
    addWrongJoin() {
        this._add("join", "Wrong");
    }
}

export default new ScoreManager();
