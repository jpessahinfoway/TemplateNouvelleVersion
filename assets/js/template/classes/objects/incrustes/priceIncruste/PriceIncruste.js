import {Incruste} from "../Incruste";
import {TextIncrusteContent} from "../../incrustesContents/textIncrusteConents/TextIncrusteContent";
import {PriceIncusteContent} from "../../incrustesContents/priceIncrustContents/centimePriceIncrusteContent/PriceIncrusteContent";
import {UnitePriceIncrusteContent} from "../../incrustesContents/priceIncrustContents/centimePriceIncrusteContent/UnitePriceIncrusteContent";
import {EuroPriceIncrusteContent} from "../../incrustesContents/priceIncrustContents/centimePriceIncrusteContent/EuroPriceIncrusteContent";
import {CentimePriceIncusteContent} from "../../incrustesContents/priceIncrustContents/centimePriceIncrusteContent/CentimePriceIncrusteContent";
import {IncrusteContent} from "../../incrustesContents/IncrusteContent";
import {SeparateurPriceIncrusteContent} from "../../incrustesContents/priceIncrustContents/centimePriceIncrusteContent/SeparateurPriceIncrusteContent";

class PriceIncruste extends Incruste{
    constructor(){
        super()
        this.type = 'price';
        this._incrusteElements = {}
    }

    addPrixElement() {
        return this._incrusteElements.prix = new PriceIncusteContent()
    }

    addEuroElement() {
        if( ! (typeof this.incrusteElements.prix === 'object' && this.incrusteElements.prix instanceof PriceIncusteContent))return console.log('need price incrust content')
        this.incrusteElements.prix.addSubContents(new EuroPriceIncrusteContent())
        return this.incrusteElements.prix.subContents.euro
    }

    addCentimeElement() {
        if( ! (typeof this.incrusteElements.prix === 'object' && this.incrusteElements.prix instanceof PriceIncusteContent))return console.log('need price incrust content')
        this.incrusteElements.prix.addSubContents(new CentimePriceIncusteContent())
        return this.incrusteElements.prix.subContents.centime
    }

    addSeparatorElement() {
        if( ! (typeof this.incrusteElements.prix === 'object' && this.incrusteElements.prix  instanceof PriceIncusteContent))return console.log('need price incrust content')
        this.incrusteElements.prix.addSubContents(new SeparateurPriceIncrusteContent())
        return this.incrusteElements.prix.subContents.separator
    }

    addUniteElement(){
        if( ! (typeof this.incrusteElements.prix === 'object' && this.incrusteElements.prix  instanceof PriceIncusteContent))return console.log('need price incrust content')
        this.incrusteElements.prix.addSubContents(new UnitePriceIncrusteContent())
        return this.incrusteElements.prix.subContents.unite
    }
}
export {PriceIncruste}