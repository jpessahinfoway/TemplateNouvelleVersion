import {IncrusteContent} from "../../IncrusteContent";

class VideoIncrusteContent extends IncrusteContent{
    constructor(){
        super();
        this.type = 'video';

    }

    buildHTML(){
        console.log(this.type)
    }

}
export {VideoIncrusteContent}