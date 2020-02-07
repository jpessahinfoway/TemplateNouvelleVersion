import {TemplateTool} from './parent/TemplateTool'
import {ZoneAssociatorSubTool} from "./subtools/zoneAssociation/ZoneAssociatorSubTool";

class ZoneAssociationTool extends TemplateTool{
    constructor(templateInterface){
        super(templateInterface);
        this.description = 'Associer à une zone';
        this.$eventLocation=$('body');
        this.addSubTools(new ZoneAssociatorSubTool(this.interface,this))
        this.$location = {
            closeAssociationWindow : $('.modal.associate .close'),
            associationWindow : $('div.modal.associate#associate')
        }
        // this.addSubTools(template);
    }


    activeTool(active){
        super.activeTool(active)
            if(active){
                this.onClickCloseAssociationWindow(true)
                Object.values(this.subTools).map(subtool=>{
                    console.log(subtool)
                    subtool.activeTool(true)
                });
                this.$location.associationWindow.removeClass('none');
            }else{
                Object.values(this.subTools).map(subtool=>{
                    console.log(subtool)
                    subtool.activeTool(false)
                });
                this.$location.associationWindow.addClass('none');
            }
    }

    onClickCloseAssociationWindow(active){
        if(active){
            this.$location.closeAssociationWindow.on('click.'+this.constructor.name, () => {
                this.$location.associationWindow.addClass('none')
                this.activeTool(false)
            })
        }else{
            this.$location.closeAssociationWindow.off('click.'+this.constructor.name)
        }
    }
}

export {ZoneAssociationTool}