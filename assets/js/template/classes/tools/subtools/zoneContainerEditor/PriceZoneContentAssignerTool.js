import {ZoneContainerEditorSubTool} from "./parent/ZoneContainerEditorSubTool";
import {TextStylyzer} from "../../../TextStylyzer";
import {TextIncrusteStyle} from "../../../TextIncrusteStyle";
import {PriceZoneSelectorTool} from "./zoneContainerPriceEditor/PriceZoneSelectorTool";
import {PriceZoneContentAssignerRuptureIncrusteCreatorTool} from "./zoneContainerPriceEditor/PriceZoneContentAssignerRuptureIncrusteCreatorTool";
import {PriceZoneIncrusteCreatorTool} from "./zoneContainerPriceEditor/PriceZoneIncrusteCreatorTool";
import {PriceZoneIncrusteSelectorTool} from "./zoneContainerPriceEditor/PriceZoneIncrusteSelectorTool";
import {PriceContent} from "../../../zoneContents/PriceContent";
import {EuroContent} from "../../../zoneContents/PriceContent/EuroContent/EuroContent";
import {SeparatorContent} from "../../../zoneContents/PriceContent/SeparatorContent/SeparatorContent";
import {CentimeContent} from "../../../zoneContents/PriceContent/CentimeContent/CentimeContent";
import {UniteContent} from "../../../zoneContents/PriceContent/UniteContent/UniteContent";
import {MediaContent} from "../../../zoneContents/MediaContent";
import {Incruste} from "../../../objects/incrustes/Incruste";
import {Observer} from "../../../pattern/observer/Observer";
/*import {ZoneContainerTextSelectorTool} from "./zoneContainerTextEditor/ZoneContainerTextSelectorTool";
import {ZoneContainerTextStyleCreatorTool} from "./zoneContainerTextEditor/ZoneContainerTextStyleCreatorTool";*/


class PriceZoneContentAssignerTool extends ZoneContainerEditorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.zoneContainerEditorObserver = new Observer()
        this.initObserver()
        this.subTools = super.initSubTools(
           //new ZoneContainerTextSelectorTool(this.interface,this),
            new PriceZoneIncrusteCreatorTool(this.interface,this),
            new PriceZoneIncrusteSelectorTool(this.interface,this)
        );
        this.$location.styleLocation = $('');
        this.zonesHTMLToAppendToStyleChoiceContainer = '';
        this.stylyzer = null
        // this.getExistingStyles()
    }


    activeTool(boolean){
        super.activeTool(boolean,this.onActivation,this.onDisactivation)
    }

    onActivation(){
        this.parentTool.templateMiniature.resetMiniature().addZones(['price']);
    }

    initObserver(){
        console.log(this.zoneContainerEditorObserver)

        this.zoneContainerEditorObserver.observerFunction(observer => {
            switch(observer.data[0]){
                case 'zoneCreation' : this.subTools['PriceZoneIncrusteSelectorTool'].addIncrustToList(observer.data[1]);
                    break;
            }
        })
    }

    onComfirmAddPriceToZone(price){
    console.log(price)

        if(typeof price !== 'object' && !(price instanceof Incruste))return ;
        console.log(this.parentTool.subTools['TemplateMiniatorizerTool'].miniature.zonesSelected)

        this.parentTool.subTools['TemplateMiniatorizerTool'].miniature.zonesSelected.forEach(zoneSelected => {
            this.interface.currentTemplate.getZone(zoneSelected).setZoneContent(price)

        });
    }

    onDisactivation(){
        if(this.stylyzer !== null){
            this.stylyzer.activeStylyser(false)
        }
    }
}

export {PriceZoneContentAssignerTool}