export default class ColorsManager {
    _coloredWrapsHost = document.querySelectorAll(".wrap-host");
    _coloredWrapsJoin = document.querySelectorAll(".wrap-join");

    constructor(colorHost, colorJoin) {
        this._colorClassHost = `color-${colorHost}`;
        this._colorClassJoin = `color-${colorJoin}`;
        this._setColors("_coloredWrapsHost", this._colorClassHost);
        this._setColors("_coloredWrapsJoin", this._colorClassJoin);
    }

    _setColors(playerWrap, colorClass) {
        this[playerWrap].forEach((e) => {
            e.classList.add(colorClass);
        });
    }
}
