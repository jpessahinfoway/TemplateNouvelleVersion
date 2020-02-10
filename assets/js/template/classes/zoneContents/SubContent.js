import {Content} from "./Content";

class SubContent extends Content{
    constructor(value){
        super(value)
        this.subType = null;
    }
}
export {SubContent}