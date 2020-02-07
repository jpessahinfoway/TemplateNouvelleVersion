class Content{
    constructor(value=null){
        this._type = null;
        this._incruste = null;
        this._id = null;
        this._value = value;
    }


    get value() {
        return this._value;
    }

    set value(value) {
        this._value = value;
    }

    get type() {
        return this._type;
    }

    set type(value) {
        this._type = value;
    }

    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }
}
export {Content}