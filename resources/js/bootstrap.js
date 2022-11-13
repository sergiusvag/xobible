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

import Loader from "./helper/loader";
import { initLang, __ } from "./helper/translator";
import RoomManager from "./roomManager";

let roomChannel;
const joinNotified = (data) => {
    RoomManager.memberJoined(data.join_name, __(data.message));
    roomChannel.listen("HostRoomEventExit", (e) => {
        RoomManager.hostMemberLeft(`${__(e.message)} ${e.join_name}`);
    });
    roomChannel.listenForWhisper("smily", (e) => {
        RoomManager.displaySuccessMsg(`${e.name} : ${__(e.message)}`);
    });
};

const closeNotified = (data) => {
    RoomManager.memberExit(__(data.message));
    window.Echo.leave(data.channel);
};

const kickNotified = (data) => {
    RoomManager.memberKicked(__(data.message));
    window.Echo.leave(data.channel);
};

const startNotified = (data) => {
    window.Echo.leave(data.channel);
    window.location.href = `/online-game/${RoomManager.locale()}`;
};

const memberListenChannels = () => {
    roomChannel.listen("RoomEventClose", closeNotified);
    roomChannel.listen("MemberRoomEventKicked", kickNotified);
    roomChannel.listen("RoomEventStart", startNotified);
    roomChannel.listenForWhisper("smily", (e) => {
        RoomManager.displaySuccessMsg(`${e.name} : ${__(e.message)}`);
    });
};

const memberJoinAndDisplay = (data) => {
    RoomManager.memberJoining(data, __(data.message));
    memberListenChannels();
};

const hostJoinChannels = async (channel) => {
    roomChannel = await window.Echo.private(channel);
    roomChannel.listen("HostRoomEventJoin", (e) => {
        e.message = `${e.join_name} ${__(e.message)}`;
        joinNotified(e);
    });
};

const hostCreateAndDisplay = (data) => {
    RoomManager.hostCreate(data, __(data.message));
    hostJoinChannels(data.channel);
};

const memberJoinRoomChannel = async (channel) => {
    roomChannel = await window.Echo.private(channel);
    roomChannel.listen("MemberRoomEventJoin", (e) => {
        memberJoinAndDisplay(e);
        Loader.Off();
    });
    roomChannel.listenForWhisper("smily", (e) => {
        RoomManager.displaySuccessMsg(`${e.name} : ${__(e.message)}`);
    });
};

const channelListenClose = () => {
    roomChannel.listen("RoomEventClose", (e) => {
        closeNotified(e);
        Loader.Off();
    });
};

const channelListenKick = () => {
    roomChannel.listen("HostRoomEventKicked", (e) => {
        RoomManager.hostKicked(`${__(e.message)} ${e.join_name}`);
        Loader.Off();
    });
};

const channelListenExit = () => {
    roomChannel.listen("MemberRoomEventExit", (e) => {
        closeNotified(e);
        Loader.Off();
    });
};

const channelListenStart = () => {
    roomChannel.listen("RoomEventStart", startNotified);
};

const channelListenChat = (data) => {
    roomChannel.whisper("smily", {
        message: data.message,
        name: data.name,
    });
};
const initEvents = () => {
    RoomManager.createEvent((resp) => {
        hostCreateAndDisplay(resp.data);
        Loader.Off();
    });
    RoomManager.joinEvent(
        () => {
            memberJoinRoomChannel(`room.${RoomManager.roomNumber()}`);
        },
        (resp) => {
            if (!resp.data.joinSuccess) {
                RoomManager.displayErrorMsg(__(resp.data.message));
                window.Echo.leave(resp.data.channel);
                Loader.Off();
            }
        }
    );
    RoomManager.kickEvent(channelListenKick);
    RoomManager.closeEvent(channelListenClose);
    RoomManager.exitEvent(channelListenExit);
    RoomManager.startEvent(channelListenStart);
    RoomManager.hostChatEvent(channelListenChat);
    RoomManager.joinChatEvent(channelListenChat);
};
const checkIfInRoom = () => {
    window.axios
        .get(`/check-room/${RoomManager.locale()}`)
        .then(async (resp) => {
            roomChannel = await window.Echo.private(resp.data.channel);
            if (resp.data.status === "in_game") {
                window.location.href = `/online-game/${RoomManager.locale()}`;
            } else if (resp.data.status === "in_room") {
                if (resp.data.is_host) {
                    hostCreateAndDisplay(resp.data);

                    if (resp.data.join_name) {
                        joinNotified(resp.data);
                    }
                } else {
                    memberJoinAndDisplay(resp.data);
                }
            }
            Loader.Off();
        });
};
const onLoad = async () => {
    Loader.On();

    initEvents();
    await initLang(RoomManager.locale());
    checkIfInRoom();
};

onLoad();
