import {PriceIncusteSubContent} from "../PriceIncusteSubContent";
import {IncrusteContent} from "../../IncrusteContent";
import {UnitePriceIncrusteContent} from "./UnitePriceIncrusteContent";
import {EuroPriceIncrusteContent} from "./EuroPriceIncrusteContent";
import {SeparateurPriceIncrusteContent} from "./SeparateurPriceIncrusteContent";
import {CentimePriceIncusteContent} from "./CentimePriceIncrusteContent";

class PriceIncusteContent extends IncrusteContent{
    constructor(){
        super();
        this.type = 'prix';
        this.subContents.unite = null
        this.subContents.euro = null
        this.subContents.separator = null
        this.subContents.centime = null
    }

    buildHTML(){
        console.log(this)
        if(typeof this.type !=='string' && this.type!=='prix') return console.log('mauvais type');
        if(typeof this.class !=='string' || this.class === '') return console.log('mauvaise classe');
        if(typeof this.id !=='number') return console.log('mauvais id');

        this.html = '<div ' +
        `class="${this.class}" ` +
        `data-type="${this.type}" ` +
        `data-id="${this.id}" ` +
        '>';

        Object.values(this.subContents)
            .filter( subContent => subContent !== null && typeof subContent ==='object' && subContent instanceof PriceIncusteSubContent)
            .sort( (subContentA,subContentB) => subContentA - subContentB)
            .map( subContentFiltered => this.html += subContentFiltered.buildHTML())


        this.html += '</div>' ;
        return this.html

    }


}
export {PriceIncusteContent}