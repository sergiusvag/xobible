class questionCategoryManager {
    _options = document.querySelectorAll(".question-category");
    selected = 0;

    constructor() {
        this._init();
    }

    _init() {
        this._options.forEach((option) => {
            option.addEventListener("click", (e) => {
                option.classList.remove("selected");
                this.selected++;
                if (this.selected === this._options.length) {
                    this.selected = 0;
                }
                this._options[this.selected].classList.add("selected");
            });
        });
    }

    selectedName() {
        return this._options[this.selected].dataset.categoryName;
    }
    selectedId() {
        return this._options[this.selected].dataset.categoryId;
    }
}

export default new questionCategoryManager();
