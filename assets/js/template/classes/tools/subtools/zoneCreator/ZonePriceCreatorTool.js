import {ZoneCreatorSubTool} from "./parent/ZoneCreatorSubTool.js";

class ZonePriceCreatorTool extends ZoneCreatorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.type='price';
    }

}

export {ZonePriceCreatorTool}