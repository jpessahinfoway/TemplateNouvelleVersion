import {TemplateSubTool} from "../parent/TemplateSubTool.js";
import {ZonePriorityManagerSubTool} from "./parent/ZonePriorityManagerSubTool.js";

class ZonePriorityBackgroundTool extends ZonePriorityManagerSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.description = 'Mettre en dernier plan';
        this.adaptableIconsInZone.element = '<span class="zone-priority-background-icon"><i class="fal fa-layer-group"></i></span>';
    }

    activeTool(boolean){
        super.activeTool(boolean,this.onActivation)
    }

    onActivation(){
        //this.appendIconInZones();
        let zonesOrderedByZindex = this.filterZonesByZindex(true);
        zonesOrderedByZindex.map((zone,index)=>{
            if(zone.identificator===this.currentZone.identificator){
                zone.setZIndex(0)
            }else{
                zone.setZIndex(zone.zIndex+1)
            };
        }
            );
    }
}
export {ZonePriorityBackgroundTool}