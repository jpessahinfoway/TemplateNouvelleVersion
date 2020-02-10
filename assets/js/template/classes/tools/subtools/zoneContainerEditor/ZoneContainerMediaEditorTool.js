import {ZoneContainerEditorSubTool} from "./parent/ZoneContainerEditorSubTool";
import {BackgroundContent} from "../../../zoneContents/BackgroundContent";
import {MediaContent} from "../../../zoneContents/MediaContent";
import {ZoneContainerMediaSelectorTool} from "./zoneContainerMediaEditor/ZoneContainerMediaSelectorTool";
import {MediaIncruste} from "../../../objects/incrustes/mediaIncruste/MediaIncruste";



class ZoneContainerMediaEditorTool extends ZoneContainerEditorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.subTools = super.initSubTools(
            new ZoneContainerMediaSelectorTool(this.interface,this)
            );

        //this.functionToExecuteOnSelectedZone = this.setMediaToSelectedZone;
    }


    activeTool(boolean){
        super.activeTool(boolean,this.onActivation,this.onDisactivation)
    }

    onClickOnSelectedBackground(active){
        if(active){
            this.$location.window.containers.top.containers.right.container.$location.on('click.onClickOnSelectedBackground','.media', (e)=> {
                $('.selected-background').removeClass('selected-background');
                $(e.currentTarget).addClass('selected-background');
                this.backgroundSelected = $(e.currentTarget).children('img').attr('src').split('/').pop();
            })
        }
    }

    setBackgroundToSelectedZone(zone){
        let background = this.backgroundSelected === null ? null : new BackgroundContent(this.backgroundSelected);
        if(background !== null)zone.setZoneBackground(background)

    }

    onComfirmSetZoneMedia(media){
        if(typeof media !== 'object' && !(media instanceof MediaIncruste))return ;

            this.parentTool.subTools['TemplateMiniatorizerTool'].miniature.zonesSelected.forEach(zoneSelected => {
               this.interface.currentTemplate.getZone(zoneSelected).setZoneContent(media)
            })
    }

    setMediaToSelectedZone(zone){
        let media = this.mediaSelected === null ? null : new MediaContent(this.mediaSelected);
        console.log(media)
        if(media !== null)zone.setZoneContent(media)
    }



    onDisactivation(){

    }
    onActivation(){
        this.parentTool.templateMiniature.resetMiniature().addZones(['media']);
    }
}

export {ZoneContainerMediaEditorTool}