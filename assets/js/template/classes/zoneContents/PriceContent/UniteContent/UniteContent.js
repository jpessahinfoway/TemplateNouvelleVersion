import {PriceSubContent} from "../SubContent/PriceSubContent";

class UniteContent  extends PriceSubContent{
    constructor(value,className){
        super(value,className)
        this.subType = 'unite';
        this.id = null;
        this.order=0;
    }
}
export {UniteContent}