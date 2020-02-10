import {PriceSubContent} from "../SubContent/PriceSubContent";

class EuroContent  extends PriceSubContent{
    constructor(value,className){
        super(value,className)
        this.subType = 'euro';
        this.id = null;
        this.order=1;
    }
}
export {EuroContent}