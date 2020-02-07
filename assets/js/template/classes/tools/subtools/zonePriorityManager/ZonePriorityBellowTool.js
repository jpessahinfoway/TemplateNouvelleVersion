import {ZonePriorityManagerSubTool} from "./parent/ZonePriorityManagerSubTool.js";

class ZonePriorityBellowTool extends ZonePriorityManagerSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.$eventLocation.click=$('body');
        this.description = 'Reculer une zone';
        this.adaptableIconsInZone.element = '<span class="zone-priority-above-icon"><i class="fal fa-layer-minus"></i></span>';
    }

    activeTool(boolean){
        super.activeTool(boolean,this.onActivation)
    }

    onActivation(){
        let zonesOrderedByZindex = this.filterZonesByZindex();
        let zoneBellowThisOneBeforeChange;
        let currentZonePositionInTemplate = zonesOrderedByZindex.indexOf(this.currentZone);

        if(currentZonePositionInTemplate>0){

            zoneBellowThisOneBeforeChange = zonesOrderedByZindex[currentZonePositionInTemplate-1];
            this.currentZone.setZIndex(zoneBellowThisOneBeforeChange.zIndex);
            zoneBellowThisOneBeforeChange.setZIndex(zoneBellowThisOneBeforeChange.zIndex+1);
        }

    }

}
export {ZonePriorityBellowTool}