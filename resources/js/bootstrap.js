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

const createRoomBtn = document.querySelector(".btn-room-create");
const closeRoomBtn = document.querySelector(".btn-room-close");
const joinRoomBtn = document.querySelector(".btn-room-join");
const exitRoomBtn = document.querySelector(".btn-room-exit");
const startBtnHolder = document.querySelector(".holder-btn-room-start");
const kickBtnHolder = document.querySelector(".holder-btn-room-kick");

import RoomManager from "./roomManager";

let privateRoomHost;
let privateRoomJoin;
let channelName;

const joinNotified = (data) => {
    RoomManager.joined(data.join_name);
    RoomManager.displaySuccessMsg(`${data.join_name} ${data.message}`);

    privateRoomHost.listenForWhisper("ExitNotification", (e) => {
        RoomManager.kicked();
        RoomManager.displayErrorMsg(`${e.message} ${e.join_name}`);
    });
};

const closeNotified = (data) => {
    RoomManager.close();
    RoomManager.displaySuccessMsg(data.message);
    window.Echo.leave(channelName);
};

const kickNotified = (data) => {
    RoomManager.close();
    RoomManager.displayErrorMsg(data.message);
    window.Echo.leave(channelName);
};

const startNotified = () => {
    window.location.href = `/game-online/${RoomManager.locale()}`;
};

const onLoad = () => {
    window.axios.get(`/check-room/${RoomManager.locale()}`).then((resp) => {
        if (resp.data.in_room) {
            channelName = `room.${resp.data.room_number}`;
            if (resp.data.is_host) {
                RoomManager.create(
                    resp.data.host_name,
                    resp.data.room_number,
                    resp.data.room_key
                );
                RoomManager.displaySuccessMsg(resp.data.message);

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
                RoomManager.meJoining(
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

                RoomManager.displaySuccessMsg(resp.data.message);
            }
        }
    });
};

onLoad();

createRoomBtn.addEventListener("click", (e) => {
    window.axios
        .post(`/create-room/${RoomManager.locale()}`, {
            roomKey: RoomManager.roomKey(),
        })
        .then((resp) => {
            RoomManager.create(
                resp.data.host_name,
                resp.data.room_number,
                resp.data.room_key
            );
            RoomManager.displaySuccessMsg(resp.data.message);

            privateRoomHost = window.Echo.private(
                `room.${resp.data.room_number}`
            );
            privateRoomHost.listenForWhisper("JoinNotification", joinNotified);
        });
});

joinRoomBtn.addEventListener("click", (e) => {
    channelName = `room.${RoomManager.roomNumber()}`;
    privateRoomJoin = window.Echo.private(`room.${RoomManager.roomNumber()}`);

    window.axios
        .post(`/join-room/${RoomManager.locale()}`, {
            roomNum: RoomManager.roomNumber(),
            roomKey: RoomManager.roomKey(),
        })
        .then((resp) => {
            if (resp.data.joinSuccess) {
                RoomManager.meJoining(
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

                RoomManager.displaySuccessMsg(resp.data.message);
            } else {
                RoomManager.displayErrorMsg(resp.data.message);
                window.Echo.leave(channelName);
            }
        });
});

closeRoomBtn.addEventListener("click", (e) => {
    window.axios
        .post(`/close-room/${RoomManager.locale()}`, {
            roomNum: RoomManager.roomNumber(),
        })
        .then((resp) => {
            privateRoomHost.whisper("CloseNotification", {
                message: resp.data.message,
            });

            RoomManager.close();
            RoomManager.displaySuccessMsg(resp.data.message);
        });
});

kickBtnHolder.addEventListener("click", (e) => {
    window.axios
        .post(`/kick-room/${RoomManager.locale()}`, {
            roomNum: RoomManager.roomNumber(),
        })
        .then((resp) => {
            RoomManager.kicked();
            RoomManager.displaySuccessMsg(
                `${resp.data.message} ${resp.data.join_name}`
            );
            privateRoomHost.whisper("KickNotification", {
                message: resp.data.message_for_join,
            });
        });
});

exitRoomBtn.addEventListener("click", (e) => {
    window.axios
        .post(`/exit-room/${RoomManager.locale()}`, {
            roomNum: RoomManager.roomNumber(),
        })
        .then((resp) => {
            privateRoomJoin.whisper("ExitNotification", {
                join_name: resp.data.join_name,
                message: resp.data.message_for_host,
            });

            RoomManager.close();
            RoomManager.displaySuccessMsg(resp.data.message);
        });
});

startBtnHolder.addEventListener("click", (e) => {
    privateRoomHost.whisper("StartNotification", {});
    window.location.href = `/game-online/${RoomManager.locale()}`;
});
