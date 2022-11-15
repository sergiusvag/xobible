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

const room_number = document.querySelector(".room_number").textContent;
const btnStart = document.querySelector(".btn-start");
const locale = document.querySelector(".locale").textContent;
const isHost = document.querySelector(".isHost").textContent === "is_host";

let colorPickerManager;

let roomChannel;
let isConnected = false;
let interval;
const init = () => {
    if (isConnected) {
        clearInterval(interval);
        Loader.Off();
        if (!colorPickerManager) {
            colorPickerManager = new ColorPickerManager(isHost);
            colorPickerManager.additionalFunc = (pickedColorIndex) => {
                roomChannel.whisper("colorPicked", {
                    pickedColorIndex,
                });
            };
        }
    }
};
const checkConnection = () => {
    interval = setInterval(() => {
        roomChannel.whisper("requestConnect", {
            isHost: isHost,
        });
    }, 5000);
};
const startNotified = (data) => {
    window.Echo.leave(data.channel);
    window.location.href = `/online-game/${locale}?room_number=${room_number}`;
};

const onLoad = () => {
    Loader.On();
    roomChannel = window.Echo.private(`room.${room_number}`);
    checkConnection();
    roomChannel.listenForWhisper("requestConnect", (request) => {
        if (isConnected) {
            init();
            roomChannel.whisper("requestReconnectRecieved", {
                isHost: isHost,
                ...colorPickerManager.getPicks(),
            });
        } else {
            isConnected = isHost !== request.isHost;
        }
    });
    roomChannel.listenForWhisper("requestReconnectRecieved", (request) => {
        isConnected = isHost !== request.isHost;
        init();
        colorPickerManager.setPicks(
            request.playerOnePick,
            request.playerTwoPick
        );
    });
    roomChannel.listenForWhisper("colorPicked", (e) => {
        colorPickerManager.changePickForOther(e.pickedColorIndex);
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
