import {TextStylyzer} from "../../../../TextStylyzer";
import {TextIncrusteStyle} from "../../../../TextIncrusteStyle";
import {TextIncruste} from "../../../../objects/incrustes/textIncruste/TextIncruste";
import {TextIncrusteContent} from "../../../../objects/incrustesContents/textIncrusteConents/TextIncrusteContent";
import {ZoneContainerPriceEditorSubTool} from "./parent/ZoneContainerPriceEditorSubTool";
import {PriceIncruste} from "../../../../objects/incrustes/priceIncruste/PriceIncruste";
import {UnitePriceIncrusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/UnitePriceIncrusteContent";
import {EuroPriceIncrusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/EuroPriceIncrusteContent";
import {SeparateurPriceIncrusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/SeparateurPriceIncrusteContent";
import {CentimePriceIncusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/CentimePriceIncrusteContent";
import {PriceZoneSelectorTool} from "./PriceZoneSelectorTool";
import {PriceZoneContentAssignerRuptureIncrusteCreatorTool} from "./PriceZoneContentAssignerRuptureIncrusteCreatorTool";
import {Observable} from "../../../../pattern/observer/Observable";
var stringify = require('json-stringify-safe');



class PriceZoneIncrusteCreatorTool extends ZoneContainerPriceEditorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);

        this.title = 'Choisir un style de prix';
        this.$location.container = $('.modal.background-editor .right-container #price-incrust-style-creator');
        this.$location.container.incrustTypeSelectionRadios = this.$location.container.find('form#type-incrust input[type=radio]');
        this.incrustTarget = null;
        this.subTools = {
            'PriceZoneSelectorTool' : null,
            'PriceZoneContentAssignerRuptureIncrusteCreatorTool' : null
        };

        this.addSubTools(
            new PriceZoneSelectorTool(this.interface,this),
            new PriceZoneContentAssignerRuptureIncrusteCreatorTool(this.interface,this)
        );
        this.styleCreatorObservable = new Observable();
        //this.functionToExecuteOnSelectedZone = this.setMediaToSelectedZone;
    }


    activeTool(boolean){
        super.activeTool(boolean,this.onActivation,this.onDisactivation)
    }

    onChangeOnIncrustTypeSelectionRadioSwitchActivatedTool(active){
        if(active){
            console.log('dfgdfgdf')
            this.$location.container.incrustTypeSelectionRadios.on('change.onChangeOnIncrustTypeSelectionRadioSwitchActivatedTool',()=>this.activeSubTools(true))
        }else{
            console.log('unactivated');
            this.$location.container.incrustTypeSelectionRadios.off('change.onChangeOnIncrustTypeSelectionRadioSwitchActivatedTool')
        }
    }

    activeSubTools(active){
        Object.values(this.subTools).forEach( subTool => subTool.activeTool(false));

        if(active) {
            let checkedRadio = this.$location.container.incrustTypeSelectionRadios.filter((index, incrustTypeSelectionRadio) => $(incrustTypeSelectionRadio).is(':checked'));
            if (checkedRadio.length > 0 && typeof this.subTools[checkedRadio[0].dataset.tool] !== 'undefined') this.subTools[checkedRadio[0].dataset.tool].activeTool(true);
        }
    }

    onDisactivation(){
        this.onChangeOnIncrustTypeSelectionRadioSwitchActivatedTool(false)
        this.activeSubTools(false)
        this.styleCreatorObservable.removeObserver(this.parentTool.zoneContainerEditorObserver);
    }
    onActivation(){
        this.onChangeOnIncrustTypeSelectionRadioSwitchActivatedTool(true)
        this.activeSubTools(true)
        this.styleCreatorObservable.addObserver(this.parentTool.zoneContainerEditorObserver);
    }
}

export {PriceZoneIncrusteCreatorTool}