import ModalManager from "./modalManager";

export default class QuestionManager {
    _questionModalManager = new ModalManager(
        ".wrapper-modal_question",
        ".question-animation-wrap",
        ".btn-question-confirm"
    );

    _resultModalManager = new ModalManager(
        ".wrapper-modal_result",
        ".result-animation-wrap",
        ".btn-result-continue"
    );
    _resultHeader = document.querySelector(".modal-header-result");
    _resultWrap = document.querySelector(".modal-wrap-result");
    _resultLocation = document.querySelector(".result-correct-location");
    _resultSelectedAnswer = document.querySelector(".selected-answer-result");

    _questionWrap = document.querySelector(".question-wrap");
    _questionNumber = document.querySelector(".question-number");
    _question = document.querySelectorAll(".question");
    _options = document.querySelectorAll(".option");
    _selected = -1;
    _questionData;
    _currentPlayer;
    _questionAnsweredFunction = () => {};
    _optionClickedFunction = () => {};
    _closeResultFunction = () => {};
    constructor(colorHost, colorJoin, correctText, wrongText) {
        this._colorClassHost = `color-${colorHost}`;
        this._colorClassJoin = `color-${colorJoin}`;
        this._correctText = correctText;
        this._wrongText = wrongText;
        this._setQuestionModalManager();

        this.defaultQuestion = {
            question: this._question[0].textContent,
            answer: this._resultSelectedAnswer.textContent,
            option_1: this._options[0].textContent,
            option_2: this._options[0].textContent,
            option_3: this._options[0].textContent,
            option_4: this._options[0].textContent,
            location: this._resultLocation.textContent,
        };
    }
    _selectedIndex() {
        return this._selected;
    }
    _isAnswerCorrect() {
        return (
            this._options[this._selected].textContent ===
            this._questionData.answer
        );
    }
    _setResultWindow(isCorrect) {
        const classToAdd = isCorrect ? "color-correct" : "color-wrong";
        const classToRemove = isCorrect ? "color-wrong" : "color-correct";
        const headerText = isCorrect ? this._correctText : this._wrongText;
        const locationAction = isCorrect ? "add" : "remove";
        const locationText = isCorrect ? this._questionData.location : "";
        this._resultHeader.classList.add(classToAdd);
        this._resultWrap.classList.add(classToAdd);
        this._resultHeader.classList.remove(classToRemove);
        this._resultWrap.classList.remove(classToRemove);
        this._resultHeader.textContent = headerText;
        this._resultLocation.classList[locationAction]("active");
        this._resultLocation.textContent = locationText;
        this._resultSelectedAnswer.textContent =
            this._options[this._selected].textContent;
    }
    _setQuestionModalManager() {
        this._questionModalManager.continueBtn.classList.add("control-btn-dis");
        this._options.forEach((e, i) => {
            e.addEventListener("click", (e) => {
                this._optionClickedFunction(i);
            });
        });
    }
    closeResult() {
        this._resultModalManager.off();
    }
    enableAnswerButton() {
        this._questionModalManager.continueBtn.classList.remove(
            "control-btn-dis"
        );
    }
    questionAnswered(isCorrect) {
        this.setQuestionAnswered(isCorrect);
        this._questionModalManager.off();
    }
    setQuestionAnswered(isCorrect) {
        this._setResultWindow(isCorrect);
        this._resultModalManager.on();
    }
    setOptionClickedFunction(optionClickedFunction) {
        this._optionClickedFunction = optionClickedFunction;
    }
    setQuestionAnsweredFunction(questionAnsweredFunction) {
        this._questionAnsweredFunction = questionAnsweredFunction;
        this._questionModalManager.continueBtn.addEventListener(
            "click",
            this._questionAnsweredFunction.bind(
                this,
                this._isAnswerCorrect.bind(this),
                this._selectedIndex.bind(this)
            )
        );
    }
    setCloseResultFunction(closeResultFunction) {
        this._closeResultFunction = closeResultFunction;
        this._resultModalManager.continueBtn.addEventListener(
            "click",
            this._closeResultFunction.bind(this)
        );
    }
    setAfterCloseFunction(afterCloseFunction) {
        this._resultModalManager.setAfterOffFunction(afterCloseFunction);
    }
    switchSelected(index) {
        this._options.forEach((e) => {
            e.classList.remove("active");
        });
        this._options[index].classList.add("active");
        this._selected = index;
    }
    _setQuestion(questionData, isMyTurn) {
        const addOrRemove = isMyTurn() ? "remove" : "add";
        this._resultModalManager.continueBtn.classList[addOrRemove](
            "control-btn-dis"
        );
        this._questionModalManager.continueBtn.classList.add("control-btn-dis");
        // question data still need handling
        this._questionData = questionData.confirmed
            ? questionData
            : this.defaultQuestion;
        this._questionNumber.textContent = this._questionData.id;
        this._question.forEach((e, i) => {
            this._question[i].textContent = this._questionData.question;
        });
        this._options.forEach((e, i) => {
            this._options[i].classList.remove("active");
            this._options[i].textContent =
                this._questionData[`option_${i + 1}`];
            this._options[i].classList[addOrRemove]("control-dis");
        });
    }
    _setBorderHost() {
        this._questionWrap.classList.remove("border-right");
    }
    _setBorderJoin() {
        this._questionWrap.classList.add("border-right");
    }
    _setColor(player) {
        this._questionWrap.classList.remove(this._colorClassHost);
        this._questionWrap.classList.remove(this._colorClassJoin);
        this._questionWrap.classList.add(this[`_colorClass${player}`]);
        this[`_setBorder${player}`]();
    }
    start(questionData, player, isMyTurn) {
        this.setData(questionData, player, isMyTurn);
        this._questionModalManager.on();
    }
    setData(questionData, player, isMyTurn) {
        this._selected = 1;
        this._currentPlayer = player;
        this._setQuestion(questionData, isMyTurn);
        this._setColor(player);
    }
}
