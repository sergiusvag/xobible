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
    _colorsForSending = ["red", "green", "blue", "pink", "orange"];
    _xPickerOtions = document.querySelector(".color-picker-wrap_one").children;
    _oPickerOtions = document.querySelector(".color-picker-wrap_two").children;
    _playerOneTxt = document.querySelector(".player-one");
    _playerTwoTxt = document.querySelector(".player-two");
    _picker = document.querySelector(".picker");
    _playerOneBtn = document.querySelector(".btn-start");
    _playerOnePick = -1;
    _playerTwoPick = -1;
    _isReady = false;
    _readyBtnFunc = () => {};
    _hostPickedFunc = () => {};
    additionalFunc = () => {};
    constructor(isOnline, isHost = true) {
        this._isOnline = isOnline;
        if (this._isOnline) {
            this.initOnlineSettings(isHost);
        } else {
            this.initOfflineSettings();
        }
    }
    initOfflineSettings() {
        this.setPlayerOnePicker();
        this.setPlayerTwoPicker();
    }

    initOnlineSettings(isHost) {
        this.isHost = isHost;
        this._playerTwoBtn = document.querySelector(".btn-ready");
        this.changePickForOther;
        if (isHost) {
            this.setPlayerOnePicker();
            this.changePickForOther = this._changePickForOtherFuncMaker(
                this._oPickerOtions,
                this._playerTwoTxt,
                "_playerTwoPick"
            );
            this._playerTwoBtn.classList.add("d-hide");
        } else {
            this.setPlayerTwoPicker();
            this.changePickForOther = this._changePickForOtherFuncMaker(
                this._xPickerOtions,
                this._playerOneTxt,
                "_playerOnePick"
            );

            this._playerOneBtn.classList.add("d-hide");
            this._playerTwoBtn.addEventListener("click", (e) => {
                this._isReady = !this._isReady;
                this._playerTwoBtn.classList.toggle("btn-ready-clicked");
                this._readyBtnFunc(this._isReady);
            });
        }
    }
    setPlayerOnePicker() {
        this._addOptionListener(
            this._xPickerOtions,
            this._playerOneTxt,
            "_playerOnePick",
            "_playerTwoPick"
        );
    }
    setPlayerTwoPicker() {
        this._addOptionListener(
            this._oPickerOtions,
            this._playerTwoTxt,
            "_playerTwoPick",
            "_playerOnePick"
        );
    }

    setReadyBtnFunc(readyBtnFunc) {
        this._readyBtnFunc = readyBtnFunc;
    }
    setHostPickedFunc(hostPickedFunc) {
        this._hostPickedFunc = hostPickedFunc;
    }
    getIsReady() {
        return this._isReady;
    }
    switchStartBtn(isReady) {
        this._isReady = isReady;
        const addOrRemove = isReady ? "remove" : "add";
        this._playerOneBtn.classList[addOrRemove]("control-btn-dis");
    }

    getData() {
        return {
            host_color: this._colorsForSending[this._playerOnePick],
            join_color: this._colorsForSending[this._playerTwoPick],
        };
    }

    setPicks(one, two, isReady) {
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
        if (one !== -1 && two !== -1) {
            if (this.isHost && isReady) {
                this._playerOneBtn.classList.remove("control-btn-dis");
            } else if (!this.isHost) {
                this._playerTwoBtn.classList.remove("control-btn-dis");
                this._isReady = isReady;
                if (isReady) {
                    this._playerTwoBtn.classList.add("btn-ready-clicked");
                }
            }
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

    enableBtnByPick(btnName) {
        if (this._playerOnePick !== -1 && this._playerTwoPick !== -1) {
            this[btnName].classList.remove("control-btn-dis");
        }
    }
    enableReadyBtn() {
        this.enableBtnByPick("_playerTwoBtn");
    }
    _addOptionListener(options, playerText, thisPlayerPick, otherPlayerPick) {
        for (let i = 0; i < options.length; i++) {
            options[i].addEventListener("click", (e) => {
                if (this[otherPlayerPick] !== i) {
                    this._changePick(options, playerText, i);
                    this.additionalFunc(i);
                    this[thisPlayerPick] = i;
                    if (this._isOnline) {
                        if (!this.isHost) {
                            this.enableReadyBtn();
                        } else {
                            this._hostPickedFunc();
                        }
                    } else {
                        this.enableBtnByPick("_playerOneBtn");
                    }
                }
            });
        }
    }
}
