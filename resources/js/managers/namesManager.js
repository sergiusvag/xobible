export default class NamesManager {
    _nameHost = document.querySelectorAll(".name-host");
    _nameJoin = document.querySelectorAll(".name-join");
    constructor(nameHost, nameJoin) {
        this._setName("_nameHost", nameHost);
        this._setName("_nameJoin", nameJoin);
    }

    _setName(player, name) {
        this[player].forEach((e) => {
            e.textContent = name;
        });
    }
}
