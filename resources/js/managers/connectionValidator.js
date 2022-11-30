export default class ConnectionValidator {
    _isConnected = false;
    _counter = 0;
    _interval;
    _countFunc = () => {};
    _connectionAtemptFunc = () => {};
    _connectionEstablishedFunc = () => {};
    _preReconnectRequestFunc = () => {};
    _reconnectionEstablishedFunc = () => {};
    constructor(roomChannel, myKey, data = {}) {
        this.roomChannel = roomChannel;
        this.myKey = myKey;
        this.data = data;
    }
    setData(data) {
        this.data = data;
    }

    _getData() {
        return this.data;
    }

    setCountFunc(countFunc) {
        this._countFunc = countFunc;
    }
    setConnectionAtemptFunc(connectionAtemptFunc) {
        this._connectionAtemptFunc = connectionAtemptFunc;
    }
    setConnectionEstablishedFunc(connectionEstablishedFunc) {
        this._connectionEstablishedFunc = connectionEstablishedFunc;
    }
    setReconnectionEstablishedFunc(reconnectionEstablishedFunc) {
        this._reconnectionEstablishedFunc = reconnectionEstablishedFunc;
    }
    setPreReconnectRequestFunc(preReconnectRequestFunc) {
        this._preReconnectRequestFunc = preReconnectRequestFunc;
    }

    establishConnection() {
        this._interval = setInterval(() => {
            this._counter++;
            this._countFunc(this._counter);
            this.roomChannel.whisper("requestConnect", {
                key: this.myKey,
                ...this._getData(),
            });
            this._connectionAtemptFunc(this._counter);
        }, 5000);
        this._listeToRequestConnect();
        this._listeToRequestReconnect();
    }
    _listeToRequestConnect() {
        this.roomChannel.listenForWhisper("requestConnect", (e) => {
            if (!this._isConnected) {
                if (e.key !== this.myKey) {
                    this._isConnected = true;
                    this.roomChannel.whisper("requestConnect", {
                        key: this.myKey,
                        ...this._getData(),
                    });
                    clearInterval(this._interval);
                    this._connectionEstablishedFunc();
                }
            } else {
                this._preReconnectRequestFunc();
                this.roomChannel.whisper("reconnectRequest", {
                    key: this.myKey,
                    ...this._getData(),
                });
            }
        });
    }
    _listeToRequestReconnect() {
        this.roomChannel.listenForWhisper("reconnectRequest", (e) => {
            if (e.key !== this.myKey) {
                this._isConnected = true;
                clearInterval(this._interval);
                this._reconnectionEstablishedFunc(e);
            }
        });
    }
}
