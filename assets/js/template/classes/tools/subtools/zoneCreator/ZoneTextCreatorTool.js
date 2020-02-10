import {ZoneCreatorSubTool} from "./parent/ZoneCreatorSubTool.js";

class ZoneTextCreatorTool extends ZoneCreatorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.type='text';
    }
}

export {ZoneTextCreatorTool}