import _ from "lodash";
window._ = _;

import "bootstrap";

import Loader from "./helper/loader";
import ColorPickerManager from "./managers/colorPickerManager";

const btnStart = document.querySelector(".btn-start");
const locale = document.querySelector(".locale").textContent;

let colorPickerManager;
const onLoad = () => {
    Loader.On();
    colorPickerManager = new ColorPickerManager(false);
};
onLoad();
Promise.all(
    Array.from(document.images)
        .filter((img) => !img.complete)
        .map(
            (img) =>
                new Promise((resolve) => {
                    img.onload = img.onerror = resolve;
                })
        )
).then(() => {
    Loader.Off();
});
btnStart.addEventListener("click", (e) => {
    colorPickerManager.play();
    const interval = setInterval(() => {
        Loader.On();
        const colors = colorPickerManager.getData();
        clearInterval(interval);
        window.location.href = `/offline-game/${locale}?host_color=${colors.host_color}&join_color=${colors.join_color}`;
    }, 500);
});
