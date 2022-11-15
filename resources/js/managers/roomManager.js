import Loader from "../helper/loader";

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

    _hostSmily = document.querySelector(".room-smily-host");
    _hostSmilyOptions = document.querySelector(".room-smily-options_host");
    _joinSmily = document.querySelector(".room-smily-join");
    _joinSmilyOptions = document.querySelector(".room-smily-options_join");

    constructor() {}
    _toggleSmileOptions(smilyOptions) {
        this[smilyOptions].classList.toggle("v-hidden");
        this[smilyOptions].classList.toggle("fade-in");
    }
    _initSmilies(smily, smilyOptions, name, clickFunc) {
        const optionsArr = [...this[smilyOptions].children];
        this[smily].addEventListener("click", (e) => {
            this._toggleSmileOptions(smilyOptions);
        });
        for (let i = 0; i < optionsArr.length; i++) {
            optionsArr[i].addEventListener("click", (e) => {
                this._toggleSmileOptions(smilyOptions);
                clickFunc({
                    name: this[name](),
                    message: e.target.dataset.roomMessage,
                });
            });
        }
    }

    hostChatEvent(clickFunc) {
        this._initSmilies(
            "_hostSmily",
            "_hostSmilyOptions",
            "_hostName",
            clickFunc
        );
    }
    joinChatEvent(clickFunc) {
        this._initSmilies(
            "_joinSmily",
            "_joinSmilyOptions",
            "_joinName",
            clickFunc
        );
    }
    _hostName() {
        return this._hostNameLabel.textContent;
    }

    _joinName() {
        return this._joinNameLabel.textContent;
    }
    locale() {
        return this._localeLabel.textContent;
    }
    roomNumber() {
        return this._roomNumberInput.value;
    }
    _roomKey() {
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
    _create(data) {
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
    _close() {
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
        this._joinSmily.classList.add("v-hidden");
        this._hostSmily.classList.add("v-hidden");
        this._hostSmilyOptions.classList.add("v-hidden");
        this._joinSmilyOptions.classList.add("v-hidden");
        this._hostSmilyOptions.classList.remove("fade-in");
        this._joinSmilyOptions.classList.remove("fade-in");
        this._deactivateInputs();
    }
    _memberJoinedDisplay(joinName) {
        this._joinNameLabel.classList.remove("v-hidden");
        this._joinNameLabel.textContent = joinName;
        this._startBtnHolder.classList.remove("v-hidden");
        this._kickBtnHolder.classList.remove("v-hidden");
        this._hostSmily.classList.remove("v-hidden");
    }
    _kicked() {
        this._joinNameLabel.classList.add("v-hidden");
        this._joinNameLabel.textContent = "";
        this._startBtnHolder.classList.add("v-hidden");
        this._kickBtnHolder.classList.add("v-hidden");
        this._hostSmily.classList.add("v-hidden");
    }
    _memberJoiningDisplay(data) {
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
        this._joinSmily.classList.remove("v-hidden");
        this._activateInputs();
    }
    _setDisplayAsSuccess() {
        this._msgLabel.classList.add("room-label-success");
        this._msgLabel.classList.add("fade-in");
        this._msgLabel.classList.remove("room-label-error");
    }
    _setDisplayAsError() {
        this._msgLabel.classList.add("room-label-error");
        this._msgLabel.classList.add("fade-in");
        this._msgLabel.classList.remove("room-label-success");
    }
    _hideMsg() {
        this._msgLabel.classList.add("v-hidden");
        this._msgLabel.classList.remove("fade-in");
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

    _displayProcess(displayFunc, data, displayMsg, message) {
        this[displayFunc](data);
        this[displayMsg](message);
    }
    hostCreate(data, message) {
        this._displayProcess("_create", data, "displaySuccessMsg", message);
    }
    memberJoined(joinName, message) {
        this._displayProcess(
            "_memberJoinedDisplay",
            joinName,
            "displaySuccessMsg",
            message
        );
    }
    memberJoining(data, message) {
        this._displayProcess(
            "_memberJoiningDisplay",
            data,
            "displaySuccessMsg",
            message
        );
    }
    memberExit(message) {
        this._displayProcess("_close", null, "displaySuccessMsg", message);
    }
    memberKicked(message) {
        this._displayProcess("_close", null, "displayErrorMsg", message);
    }
    hostKicked(message) {
        this._displayProcess("_kicked", null, "displaySuccessMsg", message);
    }
    hostMemberLeft(message) {
        this._displayProcess("_kicked", null, "displayErrorMsg", message);
    }

    _btnAddEvent = (btn, link, funcs) => {
        const functions = {
            prePostFunc: function () {},
            thenFunc: function () {},
            ...funcs,
        };
        this[btn].addEventListener("click", (e) => {
            Loader.On();
            functions.prePostFunc();
            window.axios
                .post(link, {
                    room_number: this.roomNumber(),
                    roomKey: this._roomKey(),
                })
                .then(functions.thenFunc);
        });
    };

    createEvent = (thenFunc) => {
        this._btnAddEvent("_createRoomBtn", `/create-room/${this.locale()}`, {
            thenFunc: thenFunc,
        });
    };

    joinEvent = (prePostFunc, thenFunc) => {
        this._btnAddEvent("_joinRoomBtn", `/join-room/${this.locale()}`, {
            prePostFunc,
            thenFunc,
        });
    };

    kickEvent = (prePostFunc) => {
        this._btnAddEvent("_kickBtnHolder", `/kick-room/${this.locale()}`, {
            prePostFunc,
        });
    };

    closeEvent = (prePostFunc) => {
        this._btnAddEvent("_closeRoomBtn", `/close-room/${this.locale()}`, {
            prePostFunc,
        });
    };

    exitEvent = (prePostFunc) => {
        this._btnAddEvent("_exitRoomBtn", `/exit-room/${this.locale()}`, {
            prePostFunc,
        });
    };

    startEvent = (prePostFunc) => {
        this._btnAddEvent("_startBtnHolder", `/start-room/${this.locale()}`, {
            prePostFunc,
        });
    };
}

export default new RoomManager();
