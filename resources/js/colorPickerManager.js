import { colors } from "laravel-mix/src/Log";

class ColorPickerManager {
    _colorPicked = "color-wrap-clicked";
    _colorsAddon = ["-red", "-green", "-blue", "-pink", "-orange"];
    _colorClasses = [
        "color-red",
        "color-green",
        "color-blue",
        "color-pink",
        "color-orange",
    ];
    _xPickerOtions = document.querySelector(".color-picker-wrap_one").children;
    _oPickerOtions = document.querySelector(".color-picker-wrap_two").children;
    _playerOneTxt = document.querySelector(".player-one");
    _playerTwoTxt = document.querySelector(".player-two");
    _picker = document.querySelector(".picker");

    constructor() {
        this.additionalFunc = () => {};
        this._addOptionListener(this._xPickerOtions, this._playerOneTxt);
        this._addOptionListener(this._oPickerOtions, this._playerTwoTxt);
    }

    changeFunc(newFunc) {
        this.additionalFunc = newFunc;
    }

    _changePick(options, index, playerText) {
        for (let i = 0; i < options.length; i++) {
            options[i].classList.remove(
                `${this._colorPicked}${this._colorsAddon[i]}`
            );
        }
        options[index].classList.add(
            `${this._colorPicked}${this._colorsAddon[index]}`
        );
        playerText.classList.remove(
            "color-red",
            "color-green",
            "color-blue",
            "color-pink",
            "color-orange"
        );
        playerText.classList.add(this._colorClasses[index]);
    }

    _addOptionListener(options, playerText) {
        for (let i = 0; i < options.length; i++) {
            options[i].addEventListener("click", (e) => {
                // if (secPlayerPickSet.index != i) {
                this._changePick(options, i, playerText);
                this.additionalFunc();
                // playerPickSet.changeIndex(i);
                // }
            });
        }
    }
}
export default new ColorPickerManager();
