import {PriceSubContent} from "../SubContent/PriceSubContent";

class CentimeContent  extends PriceSubContent{
    constructor(value,className){
        super(value,className)
        this.subType = 'centime';
        this.id = null;
        this.order=3;
    }
}
export {CentimeContent}