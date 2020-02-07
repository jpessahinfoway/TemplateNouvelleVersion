import {TemplateTool} from './parent/TemplateTool'
import {ZoneBackgroundAssignerTool} from "./subtools/zoneContainerEditor/ZoneBackgroundAssignerTool";
import {TextZoneContentAssignerTool} from "./subtools/zoneContainerEditor/TextZoneContentAssignerTool";
import {TemplateMiniatorizerTool} from "./TemplateMiniatorizerTool";
import {Observer} from "../pattern/observer/Observer";
import {MediaZoneContentAssignerTool} from "./subtools/zoneContainerEditor/MediaZoneContentAssignerTool";
import {PriceZoneContentAssignerTool} from "./subtools/zoneContainerEditor/PriceZoneContentAssignerTool";


class ZoneContentAssignerTool extends TemplateTool{
    constructor(templateInterface){
        super(templateInterface);
        this.description = 'Definir les propriétés des contenus de la zone';
        this.$eventLocation=$('body');
        this.zonesSelected = [];
        this.subTools = {
            'TemplateMiniatorizerTool' : null,
            'ZoneBackgroundAssignerTool' : null,
            'TextZoneContentAssignerTool' : null,
            'MediaZoneContentAssignerTool' : null,
            'PriceZoneContentAssignerTool' : null
        };
        this.$location = {
            container: $('.modal.background-editor'),
            window : {
                closeIcon : {
                    $location : $('.modal.background-editor .close')
                },
            },
        };
        this.activatedZonesInMiniatureObserver = null;
        this.activeTool(true);
        // this.addSubTools(
        //     new TemplateMiniatorizerTool(this.interface,this.$location.container.find('.miniature')),
        //     new ZoneBackgroundAssignerTool(this.interface,this),
        //     new TextZoneContentAssignerTool(this.interface,this),
        //     new MediaZoneContentAssignerTool(this.interface,this),
        //     new PriceZoneContentAssignerTool(this.interface,this)
        //     );
       /* this.templateMiniature = null;

*/
        // this.addSubTools(template);
    }


    resetZonesSelected(){
        this.templateMiniature.resetZonesSelected();
        this.zonesSelected = []
    }


    setTitle(title=null){

    }

    onClickCloseZoneContainerWindow(active){
        if(active){
            this.$location.window.closeIcon.$location.on('click.onClickCloseZoneContainerWindow',()=>{
                this.$location.container.addClass('none')
                this.subTools['TemplateMiniatorizerTool'].miniature.resetZonesSelected();

                this.interface.toolBox.toolsList[this.name].activeTool(false)
            })
        }
    }





    activeTool(active){
        super.activeTool(active)
        this.onClickCloseZoneContainerWindow(active)
    }
}

export {ZoneContentAssignerTool}