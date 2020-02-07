import {Content} from "./Content";

class MediaContent extends Content{
    constructor(value,url){
        console.log(value)
        super(value)
        this.type = 'media';
        this.url = url;
    }

    buildHTML(){
        return `<img src="${this.url}">`
    }
}
export {MediaContent}