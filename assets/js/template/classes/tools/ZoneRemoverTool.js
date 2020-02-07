import {TemplateTool} from './parent/TemplateTool'
import {ChoiceDiv} from "../utilities/ChoiceDiv";
import {Zone} from "../Zone";
class ZoneRemoverTool extends TemplateTool{
    constructor(templateInterface){
        super(templateInterface);
        this.description = 'Supprimer une zone';
        this._targetZone = null ;
    }


   set targetZone(zone){
       if(typeof zone !== 'object' || ! ( zone instanceof Zone ) )throw new Error('Invalid argument ! need to be a zone')
       this._targetZone = zone
   }

   get targetZone(){
       return this._targetZone
   }

    buildConfirmationDiv(){

        this.interface.choiceDiv.label = 'Supression de zones' ;
        this.interface.choiceDiv.onYes = ()=>{
            this.interface.currentTemplate.removeZone(this.targetZone)
            this.interface.choiceDiv.hide()

        }
        this.interface.choiceDiv.onNo = ()=> this.interface.choiceDiv.hide()
        this.interface.choiceDiv.activeChoiceDiv(true)

    }

    onClickOnZoneDeleteZone(active){
        if(active){
            $('.zone').on('click.onClickOnZoneDeleteZone',(e)=>{

                let zoneId = $(e.currentTarget).data('zone');

                this.targetZone = this.interface.currentTemplate.getZone(zoneId) ;
                this.interface.choiceDiv.content = `<p> Voulez vous vraiment supprimer la zone "${this.targetZone.identificator}" ? </p>` ;
                this.interface.choiceDiv.show()

            })
        }else{
            $('.zone').off('click.onClickOnZoneDeleteZone')
        }

    }

    activeTool(active){
        super.activeTool(active)
            if(active){
                this.buildConfirmationDiv()
                this.onClickOnZoneDeleteZone(true)
               // this.initOnZoneHoverFocusIcon();
            }else{
                this.interface.choiceDiv.activeChoiceDiv(false)
                this.onClickOnZoneDeleteZone(false)
            }

    }
}

export {ZoneRemoverTool}