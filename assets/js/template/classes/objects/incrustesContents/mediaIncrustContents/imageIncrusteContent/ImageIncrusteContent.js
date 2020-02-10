import {IncrusteContent} from "../../IncrusteContent";

class ImageIncrusteContent extends IncrusteContent{
    constructor(){
        super();
        this.type = 'image';


    }

    buildHTML(){
        console.log(this.constructor.name.split('IncrusteContent')[0].toLowerCase());
        if(typeof this.type !=='string' && this.type!==this.constructor.name.split('IncrusteContent')[0].toLowerCase()) return console.log('mauvais type');
        if(typeof this.id !=='number') return console.log('mauvais id');
        console.log(this.html)
        this.html = '';
        this.html += `<img src="/build/medias/${this.content}">`;
        return this.html
    }

}
export {ImageIncrusteContent}