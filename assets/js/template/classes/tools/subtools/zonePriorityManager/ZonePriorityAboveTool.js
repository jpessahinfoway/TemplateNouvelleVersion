import {ZonePriorityManagerSubTool} from "./parent/ZonePriorityManagerSubTool.js";

class ZonePriorityAboveTool extends ZonePriorityManagerSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.$eventLocation.click=$('body');
        this.description = 'Avancer une zone';
        this.adaptableIconsInZone.element = '<span class="zone-priority-above-icon"><i class="fal fa-layer-plus"></i></span>';

    }

    activeTool(boolean){
        super.activeTool(boolean,this.onActivation)
    }

    onActivation(){
        let zonesOrderedByZindex = this.filterZonesByZindex();
        let zoneAboveThisOneBeforeChange;
        let currentZonePositionInTemplate = zonesOrderedByZindex.indexOf(this.currentZone);

        if(currentZonePositionInTemplate<zonesOrderedByZindex.length){

            console.log(zonesOrderedByZindex)

            zoneAboveThisOneBeforeChange = zonesOrderedByZindex[currentZonePositionInTemplate+1];

            if(typeof zoneAboveThisOneBeforeChange === 'undefined') return ;

            console.log(zoneAboveThisOneBeforeChange)
            this.currentZone.setZIndex(zoneAboveThisOneBeforeChange.zIndex);
            zoneAboveThisOneBeforeChange.setZIndex(zoneAboveThisOneBeforeChange.zIndex-1);
        }

    }

}
export {ZonePriorityAboveTool}