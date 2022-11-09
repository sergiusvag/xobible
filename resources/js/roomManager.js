import Loader from "./Loader";

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

    hostName() {
        return this._hostNameLabel.textContent;
    }

    joinName() {
        return this._joinNameLabel.textContent;
    }

    locale() {
        return this._localeLabel.textContent;
    }

    roomNumber() {
        return this._roomNumberInput.value;
    }

    roomKey() {
        return this._roomKeyInput.value;
    }

    _activateInputs() {
        this._roomKeyInput.readOnly = true;
        this._roomNumberInput.readOnly = true;
    }

    _deactivateInputs() {
        this._roomKeyInput.readOnly = false;
        this._roomNumberInput.readOnly = false;
    }

    create(data) {
        this._createRoomBtn.classList.add("d-none");
        this._closeRoomBtn.classList.remove("d-none");
        this._joinRoomBtn.classList.add("control-btn-dis");
        this._exitRoomBtn.classList.add("d-none");
        this._backRoomBtn.classList.add("control-btn-dis");
        this._backRoomBtn.classList.add("v-hidden");
        this._inRoomControlls.classList.remove("v-hidden");
        this._hostNameLabel.classList.remove("v-hidden");
        this._hostNameLabel.textContent = data.host_name;
        this._roomNumberInput.value = data.room_number;
        this._roomKeyInput.value = data.room_key ? data.room_key : "";
        this._joinNameLabel.classList.add("v-hidden");
        this._startBtnHolder.classList.add("v-hidden");
        this._kickBtnHolder.classList.add("v-hidden");
        this._activateInputs();
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
        this._roomNumberInput.value = "";
        this._inRoomControlls.classList.add("v-hidden");
        this._deactivateInputs();
    }

    memberJoined(joinName) {
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

    memberJoining(data) {
        this._createRoomBtn.classList.add("control-btn-dis");
        this._closeRoomBtn.classList.add("d-none");
        this._joinRoomBtn.classList.add("d-none");
        this._exitRoomBtn.classList.remove("d-none");
        this._backRoomBtn.classList.add("control-btn-dis");
        this._backRoomBtn.classList.add("v-hidden");
        this._inRoomControlls.classList.remove("v-hidden");
        this._hostNameLabel.classList.remove("v-hidden");
        this._hostNameLabel.textContent = data.host_name;
        this._roomNumberInput.value = data.room_number;
        this._roomKeyInput.value = data.room_key;
        this._joinNameLabel.classList.remove("v-hidden");
        this._joinNameLabel.textContent = data.join_name;
        this._startBtnHolder.classList.add("v-hidden");
        this._kickBtnHolder.classList.add("v-hidden");
        this._activateInputs();
    }

    _setDisplayAsSuccess() {
        this._msgLabel.classList.add("room-label-success");
        this._msgLabel.classList.remove("room-label-error");
    }

    _setDisplayAsError() {
        this._msgLabel.classList.add("room-label-error");
        this._msgLabel.classList.remove("room-label-success");
    }

    _hideMsg() {
        this._msgLabel.classList.add("v-hidden");
        clearInterval(this._interval);
    }

    _displayMsg(msg) {
        this._msgLabel.textContent = msg;
        this._msgLabel.classList.remove("v-hidden");

        if (this._interval) {
            clearInterval(this._interval);
        }

        this._interval = setInterval(() => {
            this._hideMsg();
        }, 5000);
    }
    displaySuccessMsg(msg) {
        this._setDisplayAsSuccess();
        this._displayMsg(msg);
    }
    displayErrorMsg(msg) {
        this._setDisplayAsError();
        this._displayMsg(msg);
    }

    _getRoomEntry = () => {
        return {
            roomNum: this.roomNumber(),
            roomKey: this.roomKey(),
        };
    };

    _btnAddEvent = (btn, prePostFunc, link, thenFunc) => {
        this[btn].addEventListener("click", (e) => {
            Loader.On();
            prePostFunc();
            window.axios.post(link, this._getRoomEntry()).then(thenFunc);
        });
    };

    _btnAddEventBasic = (btn, prePostFunc, link) => {
        this._btnAddEvent(btn, prePostFunc, link, () => {});
    };

    createEvent = (prePostFunc, thenFunc) => {
        this._btnAddEvent(
            "_createRoomBtn",
            prePostFunc,
            `/create-room/${this.locale()}`,
            thenFunc
        );
    };

    joinEvent = (prePostFunc, thenFunc) => {
        this._btnAddEvent(
            "_joinRoomBtn",
            prePostFunc,
            `/join-room/${this.locale()}`,
            thenFunc
        );
    };

    kickEvent = (prePostFunc) => {
        this._btnAddEventBasic(
            "_kickBtnHolder",
            prePostFunc,
            `/kick-room/${this.locale()}`
        );
    };

    closeEvent = (prePostFunc) => {
        this._btnAddEventBasic(
            "_closeRoomBtn",
            prePostFunc,
            `/close-room/${this.locale()}`
        );
    };

    exitEvent = (prePostFunc) => {
        this._btnAddEventBasic(
            "_exitRoomBtn",
            prePostFunc,
            `/exit-room/${this.locale()}`
        );
    };

    startEvent = (prePostFunc) => {
        this._btnAddEventBasic(
            "_startBtnHolder",
            prePostFunc,
            `/start-room/${this.locale()}`
        );
    };
}

export default new RoomManager();
