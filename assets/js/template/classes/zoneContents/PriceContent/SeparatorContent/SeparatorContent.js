import {PriceSubContent} from "../SubContent/PriceSubContent";

class SeparatorContent  extends PriceSubContent{
    constructor(value,className){
        super(value,className)
        this.subType = 'separator';
        this.id = null;
        this.order=2;
    }
}
export { SeparatorContent }