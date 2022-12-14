import ModalManager from "./modalManager";

class RulesModalManager extends ModalManager {
    _rulesTurnOnBtn = document.querySelector(".btn-rules-turn-on");
    _rulesContinueBtn = document.querySelector(".btn-rules-continue");
    _rules = document.querySelectorAll(".rules-wrap");
    _curRule = 0;
    constructor(
        animationContinue = {
            in: "animation-slide-in",
            out: "animation-slide-out",
        }
    ) {
        super(
            ".wrapper-modal_rules",
            ".rules-animation-wrap",
            ".btn-rules-close"
        );
        this.animationContinue = animationContinue;
        this._rulesTurnOnBtn.addEventListener("click", (e) => {
            this._resetRules();
            this.on();
        });

        this._rulesContinueBtn.addEventListener("click", (e) => {
            this._animation.classList.add(this.animationContinue.out);
            this._animation.onanimationiteration =
                this._rulesSlideOutEnd.bind(this);
        });

        this.continueBtn.addEventListener("click", this.off.bind(this));
    }

    _rulesSlideOutEnd(e) {
        this._animation.onanimationiteration = this._rulesSlideInEnd.bind(this);
        this._nextRule();
        this._animation.classList.remove(this.animationContinue.out);
        this._animation.classList.add(this.animationContinue.in);
    }
    _rulesSlideInEnd(e) {
        this._animation.onanimationiteration = () => {};
        this._animation.classList.remove(this.animationContinue.in);
    }
    _nextRule() {
        if (this._curRule + 1 === this._rules.length) {
            this._modal.classList.remove("active");
            this._resetRules();
        } else {
            this._rules[this._curRule].classList.remove("active");
            this._curRule++;
            this._rules[this._curRule].classList.add("active");
        }
    }
    _resetRules() {
        this._rules[this._curRule].classList.remove("active");
        this._curRule = 0;
        this._rules[0].classList.add("active");
    }
}

export default new RulesModalManager();
