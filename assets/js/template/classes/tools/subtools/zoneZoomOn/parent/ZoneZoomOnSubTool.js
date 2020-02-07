import {TemplateSubTool} from "../../parent/TemplateSubTool.js";

class ZoneZoomOnSubTool extends TemplateSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.$eventLocation.click=$('body');
        this.toolOn=false;
        this.paused=false
        this.$applyZoneOn = $('.container-zone');
        this.currentScale  = this.interface.currentTemplate.getCurrentScale();
        this.zoomAction = false;
        this.$eventLocation.mousedown=this.$applyZoneOn;
        this.$eventLocation.mousemove=this.$applyZoneOn;
        this.$eventLocation.rightclick=this.$applyZoneOn;
        this.$eventLocation.mouseup=this.$applyZoneOn;
    }

    activeTool(boolean,onActivationFunction){
        super.activeToolDecorator(boolean,(mode)=>{
            if(mode==='on'){
                onActivationFunction.bind(this)()
            }else if(mode === 'off'){
                this.toolOn=false;
                this.paused=false;
                this.$eventLocation.rightclick.unbind(`contextmenu.${this.constructor.name}`);
                this.$eventLocation.click.unbind(`click.${this.constructor.name}`);
                this.$eventLocation.mousemove.unbind(`mousemove.${this.constructor.name}`);
            }
        })
    }


}
export {ZoneZoomOnSubTool}