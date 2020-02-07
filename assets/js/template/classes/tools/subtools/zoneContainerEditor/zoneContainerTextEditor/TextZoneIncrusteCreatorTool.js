
import {MediaContent} from "../../../../zoneContents/MediaContent";
import {ZoneContainerTextEditorSubTool} from "./parent/ZoneContainerTextEditorSubTool";
import {TextStylyzer} from "../../../../TextStylyzer";
import {TextIncrusteStyle} from "../../../../TextIncrusteStyle";
import {TextIncruste} from "../../../../objects/incrustes/textIncruste/TextIncruste";
import {TextIncrusteContent} from "../../../../objects/incrustesContents/textIncrusteConents/TextIncrusteContent";
import {PriceIncruste} from "../../../../objects/incrustes/priceIncruste/PriceIncruste";
import {PriceIncusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/PriceIncrusteContent";
import {CentimePriceIncusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/CentimePriceIncrusteContent";
import {EuroPriceIncrusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/EuroPriceIncrusteContent";
import {SeparateurPriceIncrusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/SeparateurPriceIncrusteContent";
import {UnitePriceIncrusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/UnitePriceIncrusteContent";
import {Style} from "../../../../Style";
import {Observable} from "../../../../pattern/observer/Observable";
import {Observer} from "../../../../pattern/observer/Observer";
var stringify = require('json-stringify-safe');



class TextZoneIncrusteCreatorTool extends ZoneContainerTextEditorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);

        this.title = 'Choisir un style de texte';
        this.$location.container = $('.modal.background-editor .right-container #text-incrust-style-creator');
        this.$location.preview = this.$location.container.find('.preview')
        this.textIncrusteStyle = null;
        this.buildStylyzer();
        this.createdIncrustStyle = this.buildIncrust({incrustObject: new TextIncruste(), incrustContentObject : new TextIncrusteContent()})
        this.styleCreatorObservable = new Observable();

        //this.functionToExecuteOnSelectedZone = this.setMediaToSelectedZone;
    }

    activeTool(boolean){
        super.activeTool(boolean,this.onActivation,this.onDisactivation)
    }

    buildIncrust({incrustObject = null, incrustContentObject = null, incrustSubContentsObject = [] } = {}){


        incrustObject.addIncrusteElements(incrustContentObject)

        incrustContentObject.addSubContents(...incrustSubContentsObject);

        Object.values(incrustContentObject.subContents).map(subContent=> {
            subContent.style = new Style()
        });

        incrustContentObject.style = this.stylyzer.style;

        incrustObject.addIncrusteElements(incrustContentObject)


        return incrustObject
    }

    onClickOnComfirmButtonRegisterModel(active){
        if(active){
            this.stylyzer.$location.styleForm.find('button').on('click.onClickOnComfirmButtonRegisterModel',e => {
                let createdStyle = this.stylyzer.generateStyle();
                let textIncruste =  new TextIncruste();
                textIncruste.name = 'incruste2';
                let textContent = new TextIncrusteContent()
                textContent.style=createdStyle;
                textContent.content = 'Exemple de text'
                textIncruste.addIncrusteElements(textContent);

                $.ajax({
                    type: "GET",
                    url: '/template/stage1/model/register',
                    data: {
                        incrusteStyle : stringify(textIncruste),
                    },
                    success: (encodedNewIncruste)=>{
                        console.log(encodedNewIncruste)
                        let parsedNewIncruste = JSON.parse(encodedNewIncruste)
                        this.styleCreatorObservable.notify('zoneCreation', parsedNewIncruste);
                        /*let ZoneContainerTextSelectorTool = this.parentTool.subTools['ZoneContainerTextSelectorTool'];

                        ZoneContainerTextSelectorTool.refreshCssStylesheet();

                        ZoneContainerTextSelectorTool.addStyleSelectorDiv(ZoneContainerTextSelectorTool.$location.container.find('ul'),newClass);*/

                    },
                });

            })
        }else {
            this.stylyzer.$location.styleForm.find('button').off('click.onClickOnComfirmButtonRegisterModel')
        }
    }



    buildStylyzer(){
        this.stylyzer = new TextStylyzer(this.$location.container)
        this.stylyzer.setPreviewZone(this.$location.preview)
        this.stylyzer.init();
        this.stylyzer.activeStylyser(true);

        console.log(this.stylyzer)
    }

    onDisactivation(){
        this.onClickOnComfirmButtonRegisterModel(false);
        this.styleCreatorObservable.removeObserver(this.parentTool.zoneContainerEditorObserver);
    }
    onActivation(){
        this.onClickOnComfirmButtonRegisterModel(true);
        this.styleCreatorObservable.addObserver(this.parentTool.zoneContainerEditorObserver);
    }
}

export {TextZoneIncrusteCreatorTool}