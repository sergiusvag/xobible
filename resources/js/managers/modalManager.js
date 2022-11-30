export default class ModalManager {
    _modal;
    _animation;
    _additionalSlideOutFunc = () => {};
    constructor(modalName, animationWrapName, btnName) {
        this._modal = document.querySelector(modalName);
        this._animation = document.querySelector(animationWrapName);
        this.continueBtn = document.querySelector(btnName);
    }
    on() {
        this._modal.classList.add("active");
        this._animation.classList.add("animation-slide-in");
        this._animation.onanimationiteration = this._slideInEnd.bind(this);
    }
    off() {
        this._animation.classList.add("animation-slide-out");
        this._animation.onanimationiteration = this._slideOutEnd.bind(this);
    }
    _slideInEnd() {
        this._animation.classList.remove("animation-slide-in");
        this._animation.onanimationiteration = () => {};
    }
    _slideOutEnd() {
        this._animation.classList.remove("animation-slide-out");
        this._animation.onanimationiteration = () => {};
        this._modal.classList.remove("active");
        this._additionalSlideOutFunc();
    }
}
