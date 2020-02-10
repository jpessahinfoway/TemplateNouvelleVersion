import {IncrusteContent} from "../IncrusteContent";
import {PriceIncusteSubContent} from "../priceIncrustContents/PriceIncusteSubContent";

class MediaIncrusteContent extends IncrusteContent{
    constructor(){
        super();
        this.type = 'media';

    }

    buildHTML(){
        console.log(this.type)
        if(typeof this.type !=='string' && this.type!=='media') return console.log('mauvais type');
        if(typeof this.className !=='string' || this.className === '') return console.log('mauvaise classe');
        if(typeof this.id !=='number') return console.log('mauvais id');
        this.html = '';
        this.html += '</div>' ;
        return this.html

    }

}
export {MediaIncrusteContent}