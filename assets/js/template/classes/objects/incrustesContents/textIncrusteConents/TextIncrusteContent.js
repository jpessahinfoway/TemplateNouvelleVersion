import {IncrusteContent} from "../IncrusteContent";
import {PriceIncusteSubContent} from "../priceIncrustContents/PriceIncusteSubContent";

class TextIncrusteContent extends IncrusteContent{
    constructor(){
        super();
        this.type = 'text';
    }

    buildHTML(){

        if(typeof this.type !=='string' && this.type!=='prix') return console.log('mauvais type');
        if(typeof this.class !=='string' || this.class === '') return console.log('mauvaise classe');
        if(typeof parseInt(this.id) !=='number') return console.log('mauvais id');


        this.html = '<p ' +
            `class="${this.class}" ` +
            `data-type="${this.type}" ` +
            `data-id="${this.id}" ` +
            '>' +
            this.content;


        Object.values(this.subContents)
            .filter( subContent => subContent !== null && typeof subContent ==='object' && subContent instanceof PriceIncusteSubContent)
            .sort( (subContentA,subContentB) => subContentA - subContentB)
            .map( subContentFiltered => this.html += subContentFiltered.buildHTML())


        this.html += '</div>' ;
        return this.html

    }

}
export {TextIncrusteContent}