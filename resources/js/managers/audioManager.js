import { auto } from "@popperjs/core";

class AudioManager {
    _allAudio = document.querySelectorAll(".game_audio");
    colorSelect = document.querySelector(".audio_color-select");
    questionSelect = document.querySelector(".audio_question-select");
    colorError = document.querySelector(".audio_color-error");
    buttonClick = document.querySelector(".audio_button-click");
    resultWrong = document.querySelector(".audio_result-wrong");
    transition = document.querySelector(".audio_transition");
    transitionOut = document.querySelector(".audio_transition-out");
    selectSelected = document.querySelector(".audio_select-selected");
    resultCorrect = document.querySelector(".audio_result-correct");
    message = document.querySelector(".audio_message");

    _buttons = document.querySelectorAll(".btn");
    _tileButtons = document.querySelectorAll(".tile-field");
    constructor() {
        this._initButtons();
    }
    initTileSelection() {
        this._tileButtons.forEach((tileBtn) => {
            tileBtn.addEventListener("click", () => {
                this.play("questionSelect");
            });
        });
    }
    _initButtons() {
        this._buttons.forEach((btn) => {
            btn.addEventListener("click", () => {
                if (
                    !btn.classList.contains("btn-dis") &&
                    !btn.classList.contains("control-btn-dis")
                ) {
                    if (btn.classList.contains("btn-locale")) {
                        this.play("transition");
                    } else {
                        this.play("buttonClick");
                    }
                }
            });
        });
    }

    play(sound) {
        this[sound].currentTime = 0;
        this[sound].play();
    }
}

export default new AudioManager();
