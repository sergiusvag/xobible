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
const joinRoomBtn = document.querySelector(".btn-room-join");

import Loader from "./Loader";
import RoomManager from "./roomManager";

let roomChannel;

const joinNotified = (data) => {
    RoomManager.memberJoined(data.join_name);
    RoomManager.displaySuccessMsg(data.message);

    roomChannel.listen("HostRoomEventExit", (e) => {
        RoomManager.kicked();
        RoomManager.displayErrorMsg(e.message);
    });
};

const closeNotified = (data) => {
    RoomManager.close();
    RoomManager.displaySuccessMsg(data.message);
    window.Echo.leave(data.channel);
};

const kickNotified = (data) => {
    RoomManager.close();
    RoomManager.displayErrorMsg(data.message);
    window.Echo.leave(data.channel);
};

const startNotified = (data) => {
    window.Echo.leave(data.channel);
    window.location.href = `/online-game/${RoomManager.locale()}`;
};

const memberDisplayOnJoin = (data) => {
    RoomManager.memberJoining(data);
    RoomManager.displaySuccessMsg(data.message);
};

const memberListenChannels = () => {
    roomChannel.listen("RoomEventClose", closeNotified);
    roomChannel.listen("MemberRoomEventKicked", kickNotified);
    roomChannel.listen("RoomEventStart", startNotified);
};

const memberJoinAndDisplay = (data) => {
    memberDisplayOnJoin(data);
    memberListenChannels();
};

const hostDisplayOnCreate = (data) => {
    RoomManager.create(data);
    RoomManager.displaySuccessMsg(data.message);
};

const hostJoinChannels = (channel) => {
    roomChannel = window.Echo.private(channel);
    roomChannel.listen("HostRoomEventJoin", joinNotified);
};

const hostCreateAndDisplay = (data) => {
    hostDisplayOnCreate(data);
    hostJoinChannels(data.channel);
};

const memberJoinRoomChannel = (channel) => {
    roomChannel = window.Echo.private(channel);
    roomChannel.listen("MemberRoomEventJoin", (e) => {
        memberJoinAndDisplay(e);
        Loader.Off();
    });
};

const channelListen = (eventName, eventFunc) => {
    roomChannel.listen(eventName, eventFunc);
};

const channelListenClose = () => {
    channelListen("RoomEventClose", (e) => {
        closeNotified(e);
        Loader.Off();
    });
};

const channelListenKick = () => {
    channelListen("HostRoomEventKicked", (e) => {
        RoomManager.kicked();
        RoomManager.displaySuccessMsg(e.message);
        Loader.Off();
    });
};

const channelListenExit = () => {
    channelListen("MemberRoomEventExit", (e) => {
        closeNotified(e);
        Loader.Off();
    });
};

const channelListenStart = () => {
    channelListen("RoomEventStart", startNotified);
};

const initEvents = () => {
    RoomManager.createEvent(
        () => {},
        (resp) => {
            hostCreateAndDisplay(resp.data);
            Loader.Off();
        }
    );
    RoomManager.joinEvent(
        () => {
            memberJoinRoomChannel(`room.${RoomManager.roomNumber()}`);
        },
        (resp) => {
            if (!resp.data.joinSuccess) {
                RoomManager.displayErrorMsg(resp.data.message);
                window.Echo.leave(resp.data.channel);
                Loader.Off();
            }
        }
    );
    RoomManager.kickEvent(channelListenKick);
    RoomManager.closeEvent(channelListenClose);
    RoomManager.exitEvent(channelListenExit);
    RoomManager.startEvent(channelListenStart);
};
const onLoad = () => {
    Loader.On();
    window.axios.get(`/check-room/${RoomManager.locale()}`).then((resp) => {
        if (resp.data.status === "in_game") {
            window.location.href = `/online-game/${RoomManager.locale()}`;
        }
        if (resp.data.status === "in_room") {
            if (resp.data.is_host) {
                hostCreateAndDisplay(resp.data);

                if (resp.data.join_name) {
                    joinNotified(resp.data);
                }
            } else {
                roomChannel = window.Echo.private(resp.data.channel);
                memberJoinAndDisplay(resp.data);
            }
        }
        Loader.Off();
    });

    initEvents();
};

onLoad();
