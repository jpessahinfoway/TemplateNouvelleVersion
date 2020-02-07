import {Content} from "./Content";

class BackgroundContent extends Content{
    constructor(value,backgroundType,propertyValue=null){
        super(value)
        this.type = 'background';
        this.backgroundType = backgroundType
        this.property = this.buildProperty(backgroundType);
        this.propertyValue = propertyValue;
    }
    buildProperty(type){
        let propertyValue = null;
       switch(this.backgroundType){
           case 'image' :
               propertyValue = 'background-image';
               break;
           case'color' :
               propertyValue = 'background-color';
               break;
           default :
               propertyValue = null;
               break;
       }
       return propertyValue
    }
}
export {BackgroundContent}