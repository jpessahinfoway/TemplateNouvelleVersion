import {TemplateTool} from "./parent/TemplateTool";
import {ZoneZoomIncreaserTool} from "./subtools/zoneZoomOn/ZoneZoomIncreaserTool";

class ZoneZoomOnTool extends TemplateTool{
    constructor(templateInterface){
        super(templateInterface)
        this.$eventLocation=$('body');
        console.log(this.interface)
        this.subTools = {
            'ZoneZoomIncreaserTool' : null
        }
    }


    activeTool(active){
        super.activeTool(active)
    }
}

export {ZoneZoomOnTool}