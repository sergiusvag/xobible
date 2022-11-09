const Loader = document.querySelector(".wrapper-modal_loader");

Loader["On"] = () => {
    Loader.classList.add("active");
};
Loader["Off"] = () => {
    Loader.classList.remove("active");
};

export default Loader;
