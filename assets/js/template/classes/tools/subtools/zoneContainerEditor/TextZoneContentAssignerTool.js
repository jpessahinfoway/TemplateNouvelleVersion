import {ZoneContainerEditorSubTool} from "./parent/ZoneContainerEditorSubTool";
import {TextStylyzer} from "../../../TextStylyzer";
import {TextIncrusteStyle} from "../../../TextIncrusteStyle";;
import {TextZoneIncrusteSelectorTool} from "./zoneContainerTextEditor/TextZoneIncrusteSelectorTool";
import {TextZoneIncrusteCreatorTool} from "./zoneContainerTextEditor/TextZoneIncrusteCreatorTool";
import {Incruste} from "../../../objects/incrustes/Incruste";
import {Observer} from "../../../pattern/observer/Observer";


class TextZoneContentAssignerTool extends ZoneContainerEditorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);

        this.subTools = super.initSubTools(
            new TextZoneIncrusteSelectorTool(this.interface,this),
            new TextZoneIncrusteCreatorTool(this.interface,this)
        );
        this.zoneContainerEditorObserver = new Observer()
        this.initObserver()

        this.$location.styleLocation = $('');
        this.zonesHTMLToAppendToStyleChoiceContainer = '';
        this.stylyzer = null
       // this.getExistingStyles()
    }

    onComfirmAddIncrustToZone(text){
        if(typeof text !== 'object' && !(text instanceof Incruste))return ;

        this.parentTool.subTools['TemplateMiniatorizerTool'].miniature.zonesSelected.forEach(zoneSelected => {
            this.interface.currentTemplate.getZone(zoneSelected).setZoneContent(text)
        });
    }

    initObserver(){
        console.log(this.subTools)
        this.zoneContainerEditorObserver.observerFunction(observer => {
            switch(observer.data[0]){
                case 'zoneCreation' : {this.subTools['TextZoneIncrusteSelectorTool'].addIncrustToList(observer.data[1])};
                break;
            }
        })
    }

    activeTool(boolean){
        super.activeTool(boolean,this.onActivation,this.onDisactivation)
    }

    onActivation(){
        this.parentTool.templateMiniature.resetMiniature().addZones(['text']);
    }
    onDisactivation(){
        if(this.stylyzer !== null){
            this.stylyzer.activeStylyser(false)
        }
    }
}

export {TextZoneContentAssignerTool}