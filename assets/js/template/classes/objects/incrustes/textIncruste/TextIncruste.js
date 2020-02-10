import {Incruste} from "../Incruste";
import {TextIncrusteContent} from "../../incrustesContents/textIncrusteConents/TextIncrusteContent";

class TextIncruste extends Incruste{
    constructor(){
        super()
        this.type = 'text';
        this._incrusteElements = {}
    }

}
export {TextIncruste}