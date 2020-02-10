import {PermanentTool} from "./parent/PermanentTool";
import {Observer} from "../pattern/observer/Observer";
class ZoneInfoDisplayerTool extends PermanentTool{
    constructor(templateInterface){
        super(templateInterface);
        this.$eventLocation.doubleclick = $('body')
        this.activeTool(true);
        this.positionReferent = {
            instance : templateInterface.currentTemplate,
            name  : templateInterface.currentTemplate.constructor.name
        },
        this.$location = {
            infosChamps : {
                name : $('.infos .input-group #name'),
                type : $('.infos .input-group #type'),
                pos  : {
                    x : $('.infos .input-group #posX'),
                    y : $('.infos .input-group #posY')
                },
                size : {
                    width : $('.infos .input-group #width'),
                    height : $('.infos .input-group #height')
                }
            }
        }
        this.zonePropertiesChangeObserver = null
        this.initZoneObserver();

        /*this.$location = {
            zones   : $('.zones'),
            infoDiv : $('.infos'),
        }
        this.infosZoneAttrs = {
            name     : null,
            position : null,
            size     : null
        }*/
    }

    initZoneObserver(){
        this.zonePropertiesChangeObserver = new Observer();
        this.zonePropertiesChangeObserver.observerFunction((observer)=>{
            let zoneInstance = observer.data[0];
            let infosToUpdate = observer.data[1];
            this.updateInfoZoneWithNewDatas(zoneInstance,infosToUpdate);
        })
    }

    updateInfoZoneWithNewDatas(objectLinkedToInfoZone,{name=null,pos={left:null,top:null},size={width:null,height:null},type=null}={}){
        if(name!==null)this.$location.infosChamps.name.val(name);
        if(type!==null){
            console.log(this.$location.infosChamps.type.find('option').each((optionIndex,option)=>{
                if($(option).val() === type && optionIndex>1){
                    $(option).clone().prependTo(this.$location.infosChamps.type)
                    $(option).remove()
                }
            }));
        }
        if(pos.left!==null)this.$location.infosChamps.pos.x.val(pos.left);
        if(pos.top!==null)this.$location.infosChamps.pos.y.val(pos.top);
        if(size.width!==null)this.$location.infosChamps.size.width.val(size.width);
        if(size.height!==null)this.$location.infosChamps.size.height.val(size.height);
    }

    activeTool(active){
        super.activeTool(active)


            if(active){
                this.$eventLocation.doubleclick.on(`dblclick.${this.constructor.name}`,'.zone',(e)=>{
                    let zoneId = $(e.currentTarget).data('zone')
                    this.currentZone = this.interface.currentTemplate.getZone(zoneId);
                    this.currentZone.zonePropertiesChangeObservable.addObserver(this.zonePropertiesChangeObserver);
                    this.updateInfoZoneWithNewDatas(this.currentZone,{name:this.currentZone.name,pos:{left:this.currentZone.position.left,top:this.currentZone.position.top},size:{width:this.currentZone.size.width,height:this.currentZone.size.height},type:this.currentZone.type} )
                    console.log(this.currentZone)
                    $('.infos').removeClass('none');
                })
               $('#wrapper-div').on(`click.${this.constructor.name}`,function(e){
                    console.log('test')
               })
            }
            else{

            }


    }




   /* updateInfosZoneContent({name = null, position = {left : null, top : null}, size = {width : null, height:null} } = {}){

        if(name !== null)this.$location.infoDiv.find('#name').val(name)

        if(position.left)this.$location.infoDiv.find('#x').val(position.left);
        if(position.top)this.$location.infoDiv.find('#y').val(position.top);

        if(size.width) this.$location.infoDiv.find('#width').val(size.width);
        if(size.height)this.$location.infoDiv.find('#height').val(size.height);
    }*/
  /*  activeTool(boolean){
        super.activeToolDecorator(boolean,(mode)=>{
            if(mode==='on'){
               
                $(this.$eventLocation).on('click', '.zone', e => {
                    let current = e.currentTarget
                    let zoneId=$(current).prop('id').match(/[0-9]*$/)[0];
                    this.lastTargetZone = this.template.zones[zoneId];
                   
                    this.updateInfosZoneContent({
                        name     : this.lastTargetZone.name,
                        position : this.lastTargetZone.position,
                        size     : this.lastTargetZone.size
                    })
                    // Supprime la classe 'opacity' sur l'ensemble des zones
                    $('.zone').removeClass('opacity')
                    // Ajoute la classe 'opacity' à la zone active
                    $(current).addClass('opacity')
                    // Affiche le container contenant les informations de la zone
                    $('.infos').fadeIn('slow')
                    // On rend disponible l'option 'Transformation manuelle'
                    $('#toolbar span.disabled, nav ul li.disabled').removeClass('disabled')
                })
            }
            if(mode=='off'){

            }
        })
    }*/

}

export {ZoneInfoDisplayerTool}