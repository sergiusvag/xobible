import _ from "lodash";
window._ = _;

import "bootstrap";

/**
 * We'll load the axios HTTP library which allows us to easily issue requests
 * to our Laravel back-end. This library automatically handles sending the
 * CSRF token as a header based on the value of the "XSRF" token cookie.
 */

import axios from "axios";
window.axios = axios;

window.axios.defaults.headers.common["X-Requested-With"] = "XMLHttpRequest";

/**
 * Echo exposes an expressive API for subscribing to channels and listening
 * for events that are broadcast by Laravel. Echo and event broadcasting
 * allows your team to easily build robust real-time web applications.
 */

import Echo from "laravel-echo";

import Pusher from "pusher-js";
window.Pusher = Pusher;

window.Echo = new Echo({
    broadcaster: "pusher",
    key: import.meta.env.VITE_PUSHER_APP_KEY,
    cluster: import.meta.env.VITE_PUSHER_APP_CLUSTER,
    forceTLS: true,
    encryption: true,
});

import Loader from "./helper/loader";
import ColorPickerManager from "./managers/colorPickerManager";
import ConnectionValidator from "./managers/connectionValidator";

const room_number = document.querySelector(".room_number").textContent;
const btnStart = document.querySelector(".btn-start");
const locale = document.querySelector(".locale").textContent;
const isHost = document.querySelector(".isHost").textContent === "is_host";

let colorPickerManager;
let connectionValidator;
let roomChannel;
const startNotified = (data) => {
    window.Echo.leave(data.channel);
    window.location.href = `/online-game/${locale}?room_number=${room_number}`;
};
const connectionEstablishedFunc = () => {
    Loader.Off();
    if (!colorPickerManager) {
        colorPickerManager = new ColorPickerManager(true, isHost);
        colorPickerManager.additionalFunc = (pickedColorIndex) => {
            roomChannel.whisper("colorPicked", {
                pickedColorIndex,
            });
        };
        colorPickerManager.setReadyBtnFunc((isReady) => {
            roomChannel.whisper("readyBtnClicked", {
                isReady: isReady,
            });
        });
        colorPickerManager.setHostPickedFunc((e) => {
            roomChannel.whisper("hostPickedColor", {});
        });
    }
    connectionValidator.setPreReconnectRequestFunc(preReconnectFunc);
};
const preReconnectFunc = () => {
    const data = {
        isReady: colorPickerManager.getIsReady(),
        ...colorPickerManager.getPicks(),
    };
    connectionValidator.setData(data);
};
const reconnectionEstablishedFunc = (e) => {
    connectionEstablishedFunc();
    colorPickerManager.setPicks(e.playerOnePick, e.playerTwoPick, e.isReady);
};

const initConnectionValidator = () => {
    connectionValidator.setConnectionEstablishedFunc(connectionEstablishedFunc);
    connectionValidator.setReconnectionEstablishedFunc(
        reconnectionEstablishedFunc
    );
    connectionValidator.establishConnection();
};
const onLoad = () => {
    Loader.On();
    roomChannel = window.Echo.private(`room.${room_number}`);
    connectionValidator = new ConnectionValidator(roomChannel, isHost);
    initConnectionValidator();
    roomChannel.listenForWhisper("colorPicked", (e) => {
        colorPickerManager.changePickForOther(e.pickedColorIndex);
    });
    roomChannel.listenForWhisper("readyBtnClicked", (e) => {
        colorPickerManager.switchStartBtn(e.isReady);
    });
    roomChannel.listenForWhisper("hostPickedColor", (e) => {
        colorPickerManager.enableReadyBtn();
    });
    roomChannel.listen("ColorEventStart", startNotified);
};
onLoad();

btnStart.addEventListener("click", (e) => {
    Loader.On();
    window.axios.post(`/online-game-start/${locale}`, {
        room_number: room_number,
        ...colorPickerManager.getData(),
    });
});
