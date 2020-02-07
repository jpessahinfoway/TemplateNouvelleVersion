import {ZoneContainerMediaEditorSubTool} from "./parent/ZoneContainerMediaEditorSubTool";
import {MediaContent} from "../../../../zoneContents/MediaContent";
import {TextIncruste} from "../../../../objects/incrustes/textIncruste/TextIncruste";
import {MediaIncruste} from "../../../../objects/incrustes/mediaIncruste/MediaIncruste";
import {TextIncrusteContent} from "../../../../objects/incrustesContents/textIncrusteConents/TextIncrusteContent";
import {MediaIncrusteContent} from "../../../../objects/incrustesContents/mediaIncrustContents/MediaIncrusteContent";
import {VideoIncrusteContent} from "../../../../objects/incrustesContents/mediaIncrustContents/videoIncrusteContent/VideoIncrusteContent";
import {ImageIncrusteContent} from "../../../../objects/incrustesContents/mediaIncrustContents/imageIncrusteContent/ImageIncrusteContent";



class ZoneContainerMediaSelectorTool extends ZoneContainerMediaEditorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);

        this.title = 'Gestion des médias';

        this.$location.container = $('.modal.background-editor .right-container #list-medias');
        this.$location.comfirmButton = this.$location.container.find('button.comfirm')

        this.mediaSelected = null;

        //this.functionToExecuteOnSelectedZone = this.setMediaToSelectedZone;
    }

    activeTool(boolean){
        super.activeTool(boolean,this.onActivation,this.onDisactivation)
    }



    onClickOnSelectedMedia(active){
        if(active){
            console.log('activatedd')
            this.$location.container.on('click.onClickOnSelectedMedia','.incrustlist__element', (e)=> {
                let selectedMediaTarget = e.currentTarget;
                let selectedMediaIncruste = $(selectedMediaTarget).find('.incrust-model')
                let incrusteElementContent = {type : null, object:null, $:null};
                incrusteElementContent.$ = selectedMediaIncruste.find(`[data-incruste=${selectedMediaIncruste.data('type')}]`).eq(0);
                $('.incrustlist__element').removeClass('selected-element');
                $(selectedMediaTarget).addClass('selected-element');
                if(incrusteElementContent.$.length===0)return console.log("pas d'incruste trouvé dans le dom");


                incrusteElementContent.type = incrusteElementContent.$.data('type');

                if(incrusteElementContent.type === 'video')incrusteElementContent.object = new VideoIncrusteContent();
                else if (incrusteElementContent.type === 'image')incrusteElementContent.object= new ImageIncrusteContent();

                this.selectedIncrust = this.generateIncrust(selectedMediaIncruste.get(0), {incrust : { instance : new MediaIncruste(), required : ['id', 'type'] }, incrustElementContent: { instance : incrusteElementContent.object, required : ['type', 'id', 'incrustOrder', 'content'] }});
                console.log(this.selectedIncrust);

            })
        }else{
            this.$location.container.off('click.onClickOnSelectedMedia');
            $('.media').removeClass('selected-element');
            this.mediaSelected = null;
        }
    }

    onClickOnComfirmButton(active){
        if(active){
            this.$location.comfirmButton.on('click.onClickOnComfirmButton',()=>{
                this.parentTool.onComfirmSetZoneMedia(this.selectedIncrust)
            })
        }else{
            this.$location.comfirmButton.off('click.onClickOnComfirmButton')
        }
    }



    onDisactivation(){
        this.onClickOnSelectedMedia(false)
        this.onClickOnComfirmButton(false)
        this.mediaSelected=null
    }
    onActivation(){
        this.onClickOnSelectedMedia(true)
        this.onClickOnComfirmButton(true)
      /*  console.log(this)
        console.log(this.parentTool)
        this.parentTool.templateMiniature.resetMiniature().addZones(['media']);
        this.onClickOnSelectedMedia(true)*/
    }
}

export {ZoneContainerMediaSelectorTool}