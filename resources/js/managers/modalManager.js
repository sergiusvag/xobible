export default class ModalManager {
    _modal;
    _animation;
    _afterCloseFunction = () => {};
    constructor(
        modalName,
        animationWrapName,
        btnName,
        animations = {
            in: "animator-fader-in",
            out: "animator-fader-out",
        }
    ) {
        this._modal = document.querySelector(modalName);
        this._animation = document.querySelector(animationWrapName);
        this.continueBtn = document.querySelector(btnName);
        this.animations = animations;
    }
    on() {
        this._modal.classList.add("active");
        this._animation.classList.add(this.animations.in);
        this._animation.onanimationiteration = this._slideInEnd.bind(this);
    }
    off() {
        this._animation.classList.add(this.animations.out);
        this._animation.onanimationiteration = this._slideOutEnd.bind(this);
    }
    _slideInEnd() {
        this._animation.classList.remove(this.animations.in);
        this._animation.onanimationiteration = () => {};
    }
    _slideOutEnd() {
        this._modal.classList.remove("active");
        this._animation.classList.remove(this.animations.out);
        this._animation.onanimationiteration = () => {};
        this._afterCloseFunction();
    }
    setAfterOffFunction(afterCloseFunction) {
        this._afterCloseFunction = afterCloseFunction;
    }
}
