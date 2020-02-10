import {ZonePriorityManagerSubTool} from "./parent/ZonePriorityManagerSubTool.js";

class ZonePriorityForegroundTool extends ZonePriorityManagerSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.$eventLocation.click=$('body');
        this.description = 'Mettre au premier plan';
        this.adaptableIconsInZone.element = '<span class="zone-priority-foreground-icon"><i class="fal fa-bring-front"></i></span>';
      //  this.setIcon('fad fa-bring-forward')
    }

    activeTool(boolean){
        super.activeTool(boolean,this.onActivation)
    }

    onActivation(){
        let zonesOrderedByZindex = this.filterZonesByZindex(false);
        this.currentZone.setZIndex(zonesOrderedByZindex[0].zIndex+1);
    }

}
export {ZonePriorityForegroundTool}