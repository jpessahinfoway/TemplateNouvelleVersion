import {Incruste} from "../Incruste";
import {MediaIncrusteContent} from "../../incrustesContents/mediaIncrustContents/MediaIncrusteContent";

class MediaIncruste extends Incruste{
    constructor(){
        super()
        this.type = 'media';
        this._incrusteElements = {}
    }

    updateIncrust(){
        Object.values(this.incrusteElements).forEach(incrustElement => Object.keys(incrustElement).map(incrustElementProperties => {
            console.log(incrustElementProperties);
        }))

    }

}
export {MediaIncruste}