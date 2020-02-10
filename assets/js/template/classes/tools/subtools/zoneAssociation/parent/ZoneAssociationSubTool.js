import {TemplateSubTool} from "../../parent/TemplateSubTool.js";


class ZoneAssociationSubTool extends TemplateSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.$location = {
            closeAssociationWindow : $('.modal.associate .close'),
            associationWindow : $('div.modal.associate#associate')
        }
    }


    activeTool(boolean,onActivationFunction){
        super.activeToolDecorator(boolean,(mode)=>{
            if(mode==='on'){
                this.currentZone = this.interface.toolBox.tools['ZoneInfoDisplayerTool'].instance.currentZone;
                this.currentZone.$location.addClass('associating');
                onActivationFunction.bind(this)();
            }else if(mode === 'off'){
                this.updateAssociatedChangesPreviewInInterfaceOnClickEvent(false);
            }
        })
    }

}

export {ZoneAssociationSubTool}