export default class ColorsManager {
    _coloredWrapsHost = document.querySelectorAll(".wrap-host");
    _coloredWrapsJoin = document.querySelectorAll(".wrap-join");
    _coloredWrapGameMainHost = document.querySelector(".wrap-host-game-main");
    _coloredWrapGameMainJoin = document.querySelector(".wrap-join-game-main");

    // transparent
    constructor(colorHost, colorJoin, currentPlayer, otherPlayer, isMyTurn) {
        this._colorClassHost = `color-${colorHost}`;
        this._colorClassJoin = `color-${colorJoin}`;
        this._setColors("_coloredWrapsHost", this._colorClassHost);
        this._setColors("_coloredWrapsJoin", this._colorClassJoin);

        const currentAction = isMyTurn ? "remove" : "add";
        const otherAction = isMyTurn ? "add" : "remove";
        this[`_coloredWrapGameMain${currentPlayer}`].classList[currentAction](
            "transparent"
        );
        this[`_coloredWrapGameMain${otherPlayer}`].classList[otherAction](
            "transparent"
        );
    }

    _setColors(playerWrap, colorClass) {
        this[playerWrap].forEach((e) => {
            e.classList.add(colorClass);
        });
    }

    toggleColors() {
        this._coloredWrapGameMainHost.classList.toggle("transparent");
        this._coloredWrapGameMainJoin.classList.toggle("transparent");
    }
}
