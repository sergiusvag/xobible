export default class ColorPickerManager {
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
    _playerOnePick = -1;
    _playerTwoPick = -1;

    getData() {
        return {
            host_color: this._colorClasses[this._playerOnePick],
            join_color: this._colorClasses[this._playerTwoPick],
        };
    }

    constructor(isHost) {
        this.isHost = isHost;
        this.additionalFunc = () => {};
        this.changePickForOther;
        if (isHost) {
            this._addOptionListener(
                this._xPickerOtions,
                this._playerOneTxt,
                "_playerOnePick",
                "_playerTwoPick"
            );
            this.changePickForOther = this._changePickForOtherFuncMaker(
                this._oPickerOtions,
                this._playerTwoTxt,
                "_playerTwoPick"
            );
        } else {
            this._addOptionListener(
                this._oPickerOtions,
                this._playerTwoTxt,
                "_playerTwoPick",
                "_playerOnePick"
            );
            this.changePickForOther = this._changePickForOtherFuncMaker(
                this._xPickerOtions,
                this._playerOneTxt,
                "_playerOnePick"
            );
        }
    }

    setPicks(one, two) {
        if (one !== -1) {
            this._playerOnePick = one;
            this._changePick(
                this._xPickerOtions,
                this._playerOneTxt,
                this._playerOnePick
            );
        }
        if (two !== -1) {
            this._playerTwoPick = two;
            this._changePick(
                this._oPickerOtions,
                this._playerTwoTxt,
                this._playerTwoPick
            );
        }
    }

    getPicks() {
        return {
            playerOnePick: this._playerOnePick,
            playerTwoPick: this._playerTwoPick,
        };
    }

    changeFunc(newFunc) {
        this.additionalFunc = newFunc;
    }

    _changePick(options, playerText, index) {
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

    _changePickForOtherFuncMaker(options, playerText, otherPlayerPick) {
        return function (index) {
            this._changePick(options, playerText, index);
            this[otherPlayerPick] = index;
        };
    }

    _addOptionListener(options, playerText, thisPlayerPick, otherPlayerPick) {
        for (let i = 0; i < options.length; i++) {
            options[i].addEventListener("click", (e) => {
                if (this[otherPlayerPick] != i) {
                    this._changePick(options, playerText, i);
                    this.additionalFunc(i);
                    this[thisPlayerPick] = i;
                }
            });
        }
    }
}
