import {ZoneCreatorSubTool} from "./parent/ZoneCreatorSubTool.js";

class ZoneMediaCreatorTool extends ZoneCreatorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.type='media';
    }
}

export {ZoneMediaCreatorTool}