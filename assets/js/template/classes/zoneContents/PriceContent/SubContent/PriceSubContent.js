import {SubContent} from "../../SubContent";

class PriceSubContent extends SubContent{
    constructor(value=null, className=null){
        super(value);
        console.log(value)
        this.type = 'price';
        this._className = className;
        this.order = undefined;
    }


    get className() {
        return this._className;
    }

    set className(value) {
        this._className = value;
    }
}
export {PriceSubContent}