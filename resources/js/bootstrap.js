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

const locale = document.querySelector(".locale");
const createRoomBtn = document.querySelector(".btn-room-create");
const closeRoomBtn = document.querySelector(".btn-room-close");
const joinRoomBtn = document.querySelector(".btn-room-join");
const exitRoomBtn = document.querySelector(".btn-room-exit");
const backRoomBtn = document.querySelector(".btn-room-back");

const roomNumberInput = document.querySelector(".input-room-number");
const roomKeyInput = document.querySelector(".input-room-key");

const hostNameLabel = document.querySelector(".username-host");
const joinNameLabel = document.querySelector(".username-join");
const msgLabel = document.querySelector(".room-label-msg");
const startBtnHolder = document.querySelector(".holder-btn-room-start");
const kickBtnHolder = document.querySelector(".holder-btn-room-kick");
const inRoomControlls = document.querySelector(".in-room-controlls");

const activateRoomInputs = () => {
    roomKeyInput.readOnly = true;
    roomNumberInput.readOnly = true;
};
const deactivateRoomInputs = () => {
    roomKeyInput.readOnly = false;
    roomNumberInput.readOnly = false;
};

const myRoomCreated = (hostName, roomNum, roomKey) => {
    createRoomBtn.classList.add("d-none");
    closeRoomBtn.classList.remove("d-none");
    joinRoomBtn.classList.add("control-btn-dis");
    exitRoomBtn.classList.add("d-none");
    backRoomBtn.classList.add("control-btn-dis");
    backRoomBtn.classList.add("v-hidden");

    activateRoomInputs();

    inRoomControlls.classList.remove("v-hidden");
    hostNameLabel.classList.remove("v-hidden");
    hostNameLabel.textContent = hostName;
    roomNumberInput.value = roomNum;
    roomKeyInput.value = roomKey;
    joinNameLabel.classList.add("v-hidden");
    startBtnHolder.classList.add("v-hidden");
    kickBtnHolder.classList.add("v-hidden");
};

const roomClosed = () => {
    createRoomBtn.classList.remove("d-none");
    createRoomBtn.classList.remove("control-btn-dis");
    closeRoomBtn.classList.add("d-none");
    joinRoomBtn.classList.remove("control-btn-dis");
    joinRoomBtn.classList.remove("d-none");
    exitRoomBtn.classList.add("d-none");
    backRoomBtn.classList.remove("control-btn-dis");
    backRoomBtn.classList.remove("v-hidden");

    deactivateRoomInputs();

    roomNumberInput.value = "";

    inRoomControlls.classList.add("v-hidden");
};

const myRoomJoined = (joinName) => {
    joinNameLabel.classList.remove("v-hidden");
    joinNameLabel.textContent = joinName;
    startBtnHolder.classList.remove("v-hidden");
    kickBtnHolder.classList.remove("v-hidden");
};

const myRoomKicked = () => {
    joinNameLabel.classList.add("v-hidden");
    joinNameLabel.textContent = "";
    startBtnHolder.classList.add("v-hidden");
    kickBtnHolder.classList.add("v-hidden");
};

const otherRoomJoin = (hostName, joinName, roomNum, roomKey) => {
    createRoomBtn.classList.add("control-btn-dis");
    closeRoomBtn.classList.add("d-none");
    joinRoomBtn.classList.add("d-none");
    exitRoomBtn.classList.remove("d-none");
    backRoomBtn.classList.add("control-btn-dis");
    backRoomBtn.classList.add("v-hidden");

    activateRoomInputs();

    inRoomControlls.classList.remove("v-hidden");
    hostNameLabel.classList.remove("v-hidden");
    hostNameLabel.textContent = hostName;
    roomNumberInput.value = roomNum;
    roomKeyInput.value = roomKey;
    joinNameLabel.classList.remove("v-hidden");
    joinNameLabel.textContent = joinName;

    startBtnHolder.classList.add("v-hidden");
    kickBtnHolder.classList.add("v-hidden");
};

const setDisplayAsSuccess = () => {
    msgLabel.classList.add("room-label-success");
    msgLabel.classList.remove("room-label-error");
};

const setDisplayAsError = () => {
    msgLabel.classList.add("room-label-error");
    msgLabel.classList.remove("room-label-success");
};

let interval;

const displayMsg = (msg) => {
    msgLabel.textContent = msg;
    msgLabel.classList.remove("v-hidden");

    if (interval) {
        clearInterval(interval);
    }

    interval = setInterval(function () {
        msgLabel.classList.add("v-hidden");
        clearInterval(interval);
    }, 5000);
};
const displaySuccessMsg = (msg) => {
    setDisplayAsSuccess();
    displayMsg(msg);
};
const displayErrorMsg = (msg) => {
    setDisplayAsError();
    displayMsg(msg);
};

let privateRoomHost;
let privateRoomJoin;
let channelName;

const joinNotified = (data) => {
    myRoomJoined(data.join_name);
    displaySuccessMsg(`${data.join_name} ${data.message}`);

    privateRoomHost.listenForWhisper("ExitNotification", (e) => {
        myRoomKicked();
        displayErrorMsg(`${e.message} ${e.join_name}`);
    });
};

const closeNotified = (data) => {
    roomClosed();
    displaySuccessMsg(data.message);
    window.Echo.leave(channelName);
};

const kickNotified = (data) => {
    roomClosed();
    displayErrorMsg(data.message);
    window.Echo.leave(channelName);
};

const startNotified = () => {
    window.location.href = `/game-online/${locale.textContent}`;
};

const onLoad = () => {
    window.axios.get(`/check-room/${locale.textContent}`).then((resp) => {
        if (resp.data.in_room) {
            channelName = `room.${resp.data.room_number}`;
            if (resp.data.is_host) {
                myRoomCreated(
                    resp.data.host_name,
                    resp.data.room_number,
                    resp.data.room_key
                );
                displaySuccessMsg(resp.data.message);

                privateRoomHost = window.Echo.private(channelName);
                privateRoomHost.listenForWhisper(
                    "JoinNotification",
                    joinNotified
                );

                if (resp.data.join_name) {
                    joinNotified(resp.data);
                }
            } else {
                privateRoomJoin = window.Echo.private(channelName);
                otherRoomJoin(
                    resp.data.host_name,
                    resp.data.join_name,
                    resp.data.room_number,
                    resp.data.room_key
                );

                privateRoomJoin.listenForWhisper(
                    "CloseNotification",
                    closeNotified
                );
                privateRoomJoin.listenForWhisper(
                    "KickNotification",
                    kickNotified
                );
                privateRoomJoin.listenForWhisper(
                    "StartNotification",
                    startNotified
                );

                displaySuccessMsg(resp.data.message);
            }
        }
    });
};

onLoad();

createRoomBtn.addEventListener("click", (e) => {
    window.axios
        .post(`/create-room/${locale.textContent}`, {
            roomKey: roomKeyInput.value,
        })
        .then((resp) => {
            myRoomCreated(
                resp.data.host_name,
                resp.data.room_number,
                resp.data.room_key
            );
            displaySuccessMsg(resp.data.message);

            privateRoomHost = window.Echo.private(
                `room.${resp.data.room_number}`
            );
            privateRoomHost.listenForWhisper("JoinNotification", joinNotified);
        });
});

joinRoomBtn.addEventListener("click", (e) => {
    channelName = `room.${roomNumberInput.value}`;
    privateRoomJoin = window.Echo.private(`room.${roomNumberInput.value}`);

    window.axios
        .post(`/join-room/${locale.textContent}`, {
            roomNum: roomNumberInput.value,
            roomKey: roomKeyInput.value,
        })
        .then((resp) => {
            if (resp.data.joinSuccess) {
                otherRoomJoin(
                    resp.data.host_name,
                    resp.data.join_name,
                    resp.data.room_number,
                    resp.data.room_key
                );

                privateRoomJoin.whisper("JoinNotification", {
                    join_name: resp.data.join_name,
                    message: resp.data.message_for_host,
                });
                privateRoomJoin.listenForWhisper(
                    "CloseNotification",
                    closeNotified
                );
                privateRoomJoin.listenForWhisper(
                    "KickNotification",
                    kickNotified
                );
                privateRoomJoin.listenForWhisper(
                    "StartNotification",
                    startNotified
                );

                displaySuccessMsg(resp.data.message);
            } else {
                displayErrorMsg(resp.data.message);
                window.Echo.leave(channelName);
            }
        });
});

closeRoomBtn.addEventListener("click", (e) => {
    window.axios
        .post(`/close-room/${locale.textContent}`, {
            roomNum: roomNumberInput.value,
        })
        .then((resp) => {
            privateRoomHost.whisper("CloseNotification", {
                message: resp.data.message,
            });

            roomClosed();
            displaySuccessMsg(resp.data.message);
        });
});

kickBtnHolder.addEventListener("click", (e) => {
    window.axios
        .post(`/kick-room/${locale.textContent}`, {
            roomNum: roomNumberInput.value,
        })
        .then((resp) => {
            myRoomKicked();
            displaySuccessMsg(`${resp.data.message} ${resp.data.join_name}`);
            privateRoomHost.whisper("KickNotification", {
                message: resp.data.message_for_join,
            });
        });
});

exitRoomBtn.addEventListener("click", (e) => {
    window.axios
        .post(`/exit-room/${locale.textContent}`, {
            roomNum: roomNumberInput.value,
        })
        .then((resp) => {
            console.log(resp.data);
            privateRoomJoin.whisper("ExitNotification", {
                join_name: resp.data.join_name,
                message: resp.data.message_for_host,
            });

            roomClosed();
            displaySuccessMsg(resp.data.message);
        });
});

startBtnHolder.addEventListener("click", (e) => {
    privateRoomHost.whisper("StartNotification", {});
    window.location.href = `/game-online/${locale.textContent}`;
});
