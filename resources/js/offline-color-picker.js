import _ from "lodash";
window._ = _;

import "bootstrap";

import Loader from "./helper/loader";
import ColorPickerManager from "./managers/colorPickerManager";

const btnStart = document.querySelector(".btn-start");
const locale = document.querySelector(".locale").textContent;

let colorPickerManager;
const onLoad = () => {
    colorPickerManager = new ColorPickerManager(false);
};
onLoad();

btnStart.addEventListener("click", (e) => {
    Loader.On();
    const colors = colorPickerManager.getData();
    window.location.href = `/offline-game/${locale}?host_color=${colors.host_color}&join_color=${colors.join_color}`;
});
