import {TemplateTool} from './parent/TemplateTool';
import {parse, stringify} from 'flatted/esm';
import {Zone} from "../Zone";

class ZoneDuplicatorTool extends TemplateTool{
    constructor(templateInterface){
        super(templateInterface);
        this.description='Dupliquer une zone'
        this.workSpace = $('.container-zone')
        this._targetZone = null;
    }


    get targetZone() {
        return this._targetZone;
    }

    set targetZone(targetZone) {
        if(typeof targetZone !== 'object' || ! ( targetZone instanceof Zone ) ) throw new Error('Invalid argument. Argument gived must be a Zone')
        this._targetZone = targetZone;
    }

    duplicateZone(zone){

        if(typeof zone !== 'object' || ! ( zone instanceof Zone ) ) throw new Error('Invalid argument. Argument gived must be a Zone')

        let newZonePos = { left: zone .position .left + 10 , top: zone .position .top } ;

        let newZone = new Zone()

        newZone .position =  newZonePos  ;
        newZone .size = { ...zone .size }  ;
        newZone .type = zone.type ;
        newZone.$location = this.interface.currentTemplate
        newZone.append()

    }

    onClickDisplayDuplicateComfirm(active){
        if(active){
            $('.zone').on('click.onClickDisplayDuplicateComfirm', (e) => {

                let zoneId = $(e.currentTarget).data('zone') ;
                this.targetZone = this. interface. currentTemplate. getZone(zoneId) ;

                this.duplicateZone(this.targetZone)

            })
        }else{
            $('.zone').off('click.onClickDisplayDuplicateComfirm')
        }

    }
    activeTool(active){
        super.activeTool(active)
            if(active){
                this.onClickDisplayDuplicateComfirm(true)
            }else{
                this.onClickDisplayDuplicateComfirm(false)
            }
    }

}

export {ZoneDuplicatorTool}