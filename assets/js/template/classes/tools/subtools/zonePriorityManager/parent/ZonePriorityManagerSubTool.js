import {TemplateSubTool} from "../../parent/TemplateSubTool.js";

class ZonePriorityManagerSubTool extends TemplateSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.$eventLocation.click=$('body');
    }


    activePriorityManagerTool(active){
        active ? Object.values(this.interface.currentTemplate.getZones()).map(zone => zone.$location.addClass('priorityManagerMode')) : $('.priorityManagerMode').removeClass('priorityManagerMode')
      //  active ? this.interface.currentTemplate.getZones()
    }
    activeTool(boolean,onActivationFunction){
        super.activeToolDecorator(boolean,(mode)=>{
            console.log(mode)
            console.log(this.constructor.name)
            if(mode==='on'){
                this.appendIconInZones()
                this.activePriorityManagerTool(true)
                this.$eventLocation.click.on('click.'+this.constructor.name,'.icon-action-div',(e)=>{
                    let idZone = $(e.currentTarget).parent('.zone').data('zone');
                    this.currentZone = this.interface.currentTemplate.getZone(idZone)

                    onActivationFunction.bind(this)();
                    this.updateZoneZindex();
                })
            }else if(mode === 'off'){
                this.activePriorityManagerTool(false)
                this.$eventLocation.click.off('click.'+this.constructor.name)

            }
        })
    }

    filterZonesByZindex(croissant=true){
       return Object.keys(this.interface.currentTemplate.getZones())
           .sort(   (a,b)=>{
              return croissant ? this.interface.currentTemplate.getZone(a).zIndex - this.interface.currentTemplate.getZone(b).zIndex : this.interface.currentTemplate.getZone(b).zIndex - this.interface.currentTemplate.getZone(a).zIndex}).map( val=>{    return this.interface.currentTemplate.getZone(val)  })
    }

    updateZoneZindex(){
        this.filterZonesByZindex().map((zone,index)=>{if(zone.zIndex!==index)zone.setZIndex(index)})
        console.log(this.filterZonesByZindex())
    }

}
export {ZonePriorityManagerSubTool}