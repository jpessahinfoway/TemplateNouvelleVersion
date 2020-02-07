import {IncrusteContent} from "../IncrusteContent";

class PriceIncusteSubContent extends IncrusteContent{
    constructor(){
        super();
        this.type = 'prix';
    }

    buildHTML(){
        this.html = '<span' +
        ` class="${this.class}"` +
        ` data-type="${this.type}"` +
        ` data-id="${this.type}"`+
        '>' +
        `${this.content}` +
        '</span>';

        return this.html
    }


}
export {PriceIncusteSubContent}