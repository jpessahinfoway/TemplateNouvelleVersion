import {TemplateTool} from './parent/TemplateTool'
import {ZoneContainerBackgroundEditorTool} from "./subtools/zoneContainerEditor/ZoneContainerBackgroundEditorTool";
import {ZoneContainerTextEditorTool} from "./subtools/zoneContainerEditor/ZoneContainerTextEditorTool";
import {TemplateMiniatorizerTool} from "./TemplateMiniatorizerTool";
import {Observer} from "../pattern/observer/Observer";
import {ZoneContainerMediaEditorTool} from "./subtools/zoneContainerEditor/ZoneContainerMediaEditorTool";
import {ZoneContainerPriceEditorTool} from "./subtools/zoneContainerEditor/ZoneContainerPriceEditorTool";


class ZoneContainerEditorTool extends TemplateTool{
    constructor(templateInterface){
        super(templateInterface);
        this.description = 'Definir les propriétés des contenus de la zone';
        this.$eventLocation=$('body');
        this.zonesSelected = [];
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
        this.addSubTools(
            new TemplateMiniatorizerTool(this.interface,this.$location.container.find('.miniature')),
            new ZoneContainerBackgroundEditorTool(this.interface,this),
            new ZoneContainerTextEditorTool(this.interface,this),
            new ZoneContainerMediaEditorTool(this.interface,this),
            new ZoneContainerPriceEditorTool(this.interface,this)
            );
        this.subTools['TemplateMiniatorizerTool'].activeTool(true)
        this.buildZoneContainerMiniature();
       /* this.templateMiniature = null;

*/
        // this.addSubTools(template);
    }


    resetZonesSelected(){
        this.templateMiniature.resetZonesSelected();
        this.zonesSelected = []
    }


    buildZoneContainerMiniature(){

        console.log(this.subTools)
        this.templateMiniature = this.subTools['TemplateMiniatorizerTool'].createMiniature();
        this.templateMiniature.append();
        console.log(this.templateMiniature)

    }



    setTitle(title=null){

    }

    onClickCloseZoneContainerWindow(active){
        if(active){
            this.$location.window.closeIcon.$location.on('click.onClickCloseZoneContainerWindow',()=>{
                this.$location.container.addClass('none')
                this.subTools['TemplateMiniatorizerTool'].miniature.resetZonesSelected();
                this.interface.toolBox.activeToolInToolBox(this.name,false)
            })
        }
    }





    activeTool(active){
        super.activeTool(active)
        this.onClickCloseZoneContainerWindow(active)
    }
}

export {ZoneContainerEditorTool}