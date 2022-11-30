export default class BoardManager {
    _tileField = document.querySelectorAll(".tile-field");
    _colorClassBgHost;
    _colorClassBgJoin;
    _symbolHost = "/img/x.png";
    _symbolJoin = "/img/o.png";
    _symbolDefault = "/img/blank.jpg";
    _selectedTile;
    _addOrRemove;
    _logicalTile = [0, 0, 0, 0, 0, 0, 0, 0, 0];
    _filledTiles = 0;

    constructor(colorHost, colorJoin) {
        this._colorClassBgHost = `bg-${colorHost}`;
        this._colorClassBgJoin = `bg-${colorJoin}`;
    }

    resetBoard() {
        this._tileField.forEach((e, i) => {
            this._tileField[i].classList.remove("control-dis");
            this._tileField[i].classList.remove(this._colorClassBgHost);
            this._tileField[i].classList.remove(this._colorClassBgJoin);
            this._tileField[i].children[0].src = this._symbolDefault;
        });
        this._logicalTile = [0, 0, 0, 0, 0, 0, 0, 0, 0];
        this._filledTiles = 0;
    }

    _setLogicalTile(index, player) {
        const setTo = player === "Host" ? 1 : 2;
        this._logicalTile[index] = setTo;
        this._filledTiles++;
    }

    isAllFull() {
        return this._filledTiles === 9;
    }

    _setTile(index, player) {
        this._setLogicalTile(index, player);
        this._tileField[index].classList.add(this[`_colorClassBg${player}`]);
        this._tileField[index].classList.add("control-dis");
        this._tileField[index].children[0].src = this[`_symbol${player}`];
        this._tileField[index].dataset.isFree = "not-free";
    }
    _returnBonus(index_a, index_b, check) {
        return this._logicalTile[index_a] === check &&
            this._logicalTile[index_b] === check
            ? 1
            : 0;
    }
    calculateBonus(otherPlayer, index) {
        const check = otherPlayer === "Host" ? 1 : 2;
        let bonus = 0;
        switch (index) {
            case 0:
                bonus +=
                    this._returnBonus(1, 2, check) +
                    this._returnBonus(4, 8, check) +
                    this._returnBonus(3, 6, check);
                break;
            case 1:
                bonus +=
                    this._returnBonus(0, 2, check) +
                    this._returnBonus(4, 7, check);
                break;
            case 2:
                bonus +=
                    this._returnBonus(0, 1, check) +
                    this._returnBonus(4, 6, check) +
                    this._returnBonus(5, 8, check);
                break;
            case 3:
                bonus +=
                    this._returnBonus(0, 6, check) +
                    this._returnBonus(4, 5, check);
                break;
            case 4:
                bonus +=
                    this._returnBonus(0, 8, check) +
                    this._returnBonus(1, 7, check) +
                    this._returnBonus(2, 6, check) +
                    this._returnBonus(3, 5, check);
                break;
            case 5:
                bonus +=
                    this._returnBonus(2, 8, check) +
                    this._returnBonus(3, 4, check);
                break;
            case 6:
                bonus +=
                    this._returnBonus(0, 3, check) +
                    this._returnBonus(2, 4, check) +
                    this._returnBonus(7, 8, check);
                break;
            case 7:
                bonus +=
                    this._returnBonus(1, 4, check) +
                    this._returnBonus(6, 8, check);
                break;
            case 8:
                bonus +=
                    this._returnBonus(0, 4, check) +
                    this._returnBonus(2, 5, check) +
                    this._returnBonus(6, 7, check);
                break;
        }

        return bonus;
    }
    selectedHost(index) {
        this._setTile(index, "Host");
    }
    selectedJoin(index) {
        this._setTile(index, "Join");
    }
    setSelectedTile(index) {
        this._selectedTile = index;
    }
    getSelectedTile() {
        return this._selectedTile;
    }
    setTileFunction(tileFunction) {
        this._tileField.forEach((e, i) => {
            e.addEventListener("click", (e) => {
                this._selectedTile = i;
                tileFunction(this._tileField[i]);
            });
        });
    }

    enableFreeTiles() {
        this._tileField.forEach((e, i) => {
            if (this._tileField[i].dataset.isFree === "free") {
                this._tileField[i].classList.remove("control-dis");
            }
        });
    }
    disableFreeTiles() {
        this._tileField.forEach((e, i) => {
            if (this._tileField[i].dataset.isFree === "free") {
                this._tileField[i].classList.add("control-dis");
            }
        });
    }
    toggleFreeTiles() {
        this._tileField.forEach((e, i) => {
            if (this._tileField[i].dataset.isFree === "free") {
                this._tileField[i].classList[this._addOrRemove]("control-dis");
            }
        });
        this._addOrRemove = this._addOrRemove === "remove" ? "add" : "remove";
    }

    setBoard(questionStatus, isMyTurn) {
        this._tileField.forEach((e, i) => {
            switch (questionStatus[`${i}_field_question_status`]) {
                case "none":
                    if (!isMyTurn()) {
                        this._tileField[i].classList.add("control-dis");
                        this._addOrRemove = "remove";
                    } else {
                        this._addOrRemove = "add";
                    }
                    this._tileField[i].dataset.isFree = "free";
                    this._tileField[i].dataset.questionId =
                        questionStatus[`${i}_field_question_id`];
                    break;
                case "host_answered":
                    this.selectedHost(i);
                    break;
                case "join_answered":
                    this.selectedJoin(i);
                    break;
            }
        });
    }
}
