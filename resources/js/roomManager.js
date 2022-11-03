class RoomManager {
    _localeLabel = document.querySelector(".locale");
    _createRoomBtn = document.querySelector(".btn-room-create");
    _closeRoomBtn = document.querySelector(".btn-room-close");
    _joinRoomBtn = document.querySelector(".btn-room-join");
    _exitRoomBtn = document.querySelector(".btn-room-exit");
    _backRoomBtn = document.querySelector(".btn-room-back");
    _roomNumberInput = document.querySelector(".input-room-number");
    _roomKeyInput = document.querySelector(".input-room-key");
    _hostNameLabel = document.querySelector(".username-host");
    _joinNameLabel = document.querySelector(".username-join");
    _msgLabel = document.querySelector(".room-label-msg");
    _startBtnHolder = document.querySelector(".holder-btn-room-start");
    _kickBtnHolder = document.querySelector(".holder-btn-room-kick");
    _inRoomControlls = document.querySelector(".in-room-controlls");
    _interval = undefined;

    constructor() {}

    locale() {
        return this._localeLabel.textContent;
    }

    roomNumber() {
        return this._roomNumberInput.value;
    }

    roomKey() {
        return this._roomKeyInput.value;
    }

    activateInputs() {
        this._roomKeyInput.readOnly = true;
        this._roomNumberInput.readOnly = true;
    }

    deactivateInputs() {
        this._roomKeyInput.readOnly = false;
        this._roomNumberInput.readOnly = false;
    }

    create(hostName, roomNum, roomKey) {
        this._createRoomBtn.classList.add("d-none");
        this._closeRoomBtn.classList.remove("d-none");
        this._joinRoomBtn.classList.add("control-btn-dis");
        this._exitRoomBtn.classList.add("d-none");
        this._backRoomBtn.classList.add("control-btn-dis");
        this._backRoomBtn.classList.add("v-hidden");

        this.activateInputs();

        this._inRoomControlls.classList.remove("v-hidden");
        this._hostNameLabel.classList.remove("v-hidden");
        this._hostNameLabel.textContent = hostName;
        this._roomNumberInput.value = roomNum;
        this._roomKeyInput.value = roomKey;
        this._joinNameLabel.classList.add("v-hidden");
        this._startBtnHolder.classList.add("v-hidden");
        this._kickBtnHolder.classList.add("v-hidden");
    }

    close() {
        this._createRoomBtn.classList.remove("d-none");
        this._createRoomBtn.classList.remove("control-btn-dis");
        this._closeRoomBtn.classList.add("d-none");
        this._joinRoomBtn.classList.remove("control-btn-dis");
        this._joinRoomBtn.classList.remove("d-none");
        this._exitRoomBtn.classList.add("d-none");
        this._backRoomBtn.classList.remove("control-btn-dis");
        this._backRoomBtn.classList.remove("v-hidden");

        this.deactivateInputs();

        this._roomNumberInput.value = "";

        this._inRoomControlls.classList.add("v-hidden");
    }

    joined(joinName) {
        this._joinNameLabel.classList.remove("v-hidden");
        this._joinNameLabel.textContent = joinName;
        this._startBtnHolder.classList.remove("v-hidden");
        this._kickBtnHolder.classList.remove("v-hidden");
    }

    kicked() {
        this._joinNameLabel.classList.add("v-hidden");
        this._joinNameLabel.textContent = "";
        this._startBtnHolder.classList.add("v-hidden");
        this._kickBtnHolder.classList.add("v-hidden");
    }

    meJoining(hostName, joinName, roomNum, roomKey) {
        this._createRoomBtn.classList.add("control-btn-dis");
        this._closeRoomBtn.classList.add("d-none");
        this._joinRoomBtn.classList.add("d-none");
        this._exitRoomBtn.classList.remove("d-none");
        this._backRoomBtn.classList.add("control-btn-dis");
        this._backRoomBtn.classList.add("v-hidden");

        this.activateInputs();

        this._inRoomControlls.classList.remove("v-hidden");
        this._hostNameLabel.classList.remove("v-hidden");
        this._hostNameLabel.textContent = hostName;
        this._roomNumberInput.value = roomNum;
        this._roomKeyInput.value = roomKey;
        this._joinNameLabel.classList.remove("v-hidden");
        this._joinNameLabel.textContent = joinName;

        this._startBtnHolder.classList.add("v-hidden");
        this._kickBtnHolder.classList.add("v-hidden");
    }

    setDisplayAsSuccess() {
        this._msgLabel.classList.add("room-label-success");
        this._msgLabel.classList.remove("room-label-error");
    }

    setDisplayAsError() {
        this._msgLabel.classList.add("room-label-error");
        this._msgLabel.classList.remove("room-label-success");
    }

    hideMsg() {
        this._msgLabel.classList.add("v-hidden");
        clearInterval(this._interval);
    }

    displayMsg(msg) {
        this._msgLabel.textContent = msg;
        this._msgLabel.classList.remove("v-hidden");

        if (this._interval) {
            clearInterval(this._interval);
        }

        this._interval = setInterval(() => {
            this.hideMsg();
        }, 5000);
    }
    displaySuccessMsg(msg) {
        this.setDisplayAsSuccess();
        this.displayMsg(msg);
    }
    displayErrorMsg(msg) {
        this.setDisplayAsError();
        this.displayMsg(msg);
    }
}

export default new RoomManager();
