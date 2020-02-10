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
import {PriceIncusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/PriceIncrusteContent";
import {Style} from "../../../../Style";
import {Observable} from "../../../../pattern/observer/Observable";

var stringify = require('json-stringify-safe');



class PriceZoneSelectorTool extends ZoneContainerPriceEditorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.title = 'Choisir un style de prix';
        this.$location.container = $('.modal.background-editor .right-container #price-incrust-style-creator__price');
        this.$location.preview = this.$location.container.find('.preview');
        this.$location.selectElementPriceForm = this.$location.container.find('.select-element-price');
        this.textIncrusteStyle = null;
        this.stylyzer = this.buildStylyzer();
        this.createdPriceIncrust = this.buildIncrust()
        this.targetedIncrustContents = Object.values(this.createdPriceIncrust.incrusteElements)
        this.focusCheckedIncrustElement()

        //this.functionToExecuteOnSelectedZone = this.setMediaToSelectedZone;
    }

    activeTool(boolean){
        super.activeTool(boolean,this.onActivation,this.onDisactivation)
    }

    focusCheckedIncrustElement() {
        let checkedRadio = this.$location.selectElementPriceForm.find('input[type=radio]:checked');
        if (checkedRadio.length > 0) {
            let targetedIncrustContentType = $(checkedRadio).attr('id');
            this.$location.preview.find(`.incrust-element[data-type=${targetedIncrustContentType}]`).focus()
        }
    }



    onChangeSwitchTargetedIncrusteContents(active){
        if(active){
            this.$location.selectElementPriceForm.find('input[type=radio]').on('change.onChangeSwitchTargetedIncrusteContents', e => {
                this.focusCheckedIncrustElement()
            })
        }else{
            this.$location.selectElementPriceForm.find('input[type=radio]').off('change.onChangeSwitchTargetedIncrusteContents')
        }
    }

    checkRadioByTargetType(type){
        this.$location.selectElementPriceForm.find(`input[type=radio]#${type}`).attr('checked',true)
        this.$location.selectElementPriceForm.find(`input[type=radio]#${type}`).attr('checked',true)
    }

    onFocusIncrustRefreshTarget(active) {
        if(active){
            this.$location.container.find('.incrust-element').on('focus.onFocusIncrustRefreshTarget',e => {

                let incrustTarget = e.currentTarget;
                let incrustTargetType = e.currentTarget.dataset.type;

                this.$location.container.find('.focused-element').removeClass('focused-element');
                $(incrustTarget).addClass('focused-element');

                if( typeof this.createdPriceIncrust.incrusteElements[ incrustTargetType ] !== 'undefined' ) this.targetedIncrustContents = this.createdPriceIncrust.incrusteElements[ incrustTargetType ];

                else if( typeof this.createdPriceIncrust.incrusteElements[ 'prix' ].subContents[ incrustTargetType ] !== 'undefined') this.targetedIncrustContents = this.createdPriceIncrust.incrusteElements[ 'prix' ].subContents[ incrustTargetType ];

                this.stylyzer.handleFormWithNewStyle( this.targetedIncrustContents.style );
                this.stylyzer.setTarget( $( incrustTarget ) );

                this.checkRadioByTargetType( incrustTargetType );

                // this.recoveryPropertiesElement(incrustTargetType)
            })
        }else{
            this.$location.container.find('.incrust-element').off('focus.onFocusIncrustRefreshTarget')
        }
    }


    buildIncrust(){
        let priceIncruste = new PriceIncruste();
        let priceIncrusteContent = new PriceIncusteContent();
        priceIncrusteContent.addSubContents(
            new CentimePriceIncusteContent(),
            new EuroPriceIncrusteContent(),
            new SeparateurPriceIncrusteContent(),
            new UnitePriceIncrusteContent()
        );
        console.log(priceIncrusteContent);

        Object.values(priceIncrusteContent.subContents).map(priceElement=> {
            priceElement.style = new Style()
        });
        console.log(this.stylyzer.style)
        priceIncrusteContent.style = this.stylyzer.style
        priceIncruste
            .addIncrusteElements(priceIncrusteContent)
        console.log(priceIncruste);

        return priceIncruste
    }


    onClickOnComfirmRegisterModel(active){
        if(active){
            this.$location.container.find('button.btn.comfirm').on('click.onClickOnComfirmRegisterModel',e => {
                //let textIncruste =  new TextIncruste();

                this.createdPriceIncrust.name = 'priceincruste1';

                Object.values(this.createdPriceIncrust.incrusteElements['prix'].subContents).map(incrusteContent => {
                    let incrustElement = this.$location.preview.find(`[data-type=${incrusteContent.type}`)
                    incrusteContent.content = incrustElement.text();
                    if(typeof incrustElement.data('order') !== 'undefined')incrusteContent.incrustOrder = incrustElement.data('order');
                });



                $.ajax({
                    type: "GET",
                    url: '/template/stage1/model/register',
                    data: {
                        incrusteStyle : stringify(this.createdPriceIncrust),
                    },
                    success: (encodedNewIncruste)=>{
                        let parsedNewIncruste = JSON.parse(encodedNewIncruste)
                        this.parentTool.styleCreatorObservable.notify('zoneCreation', parsedNewIncruste);
                    },
                });
            })
        }else{

        }

    }



    buildStylyzer(){
        let stylyzer = new TextStylyzer(this.$location.container)

        stylyzer.setPreviewZone(this.$location.preview)
        stylyzer.init();
        stylyzer.activeStylyser(true);

        /*,{comfirmButtonLocation : null, onSuccess : (style)=>{

            //let textIncruste =  new TextIncruste();
            let priceIncruste = new PriceIncruste();

            priceIncruste.setName('priceincruste1');
            priceIncruste
                .addContent(new UnitePriceIncrusteContent())
                .addContent(new EuroPriceIncrusteContent())
                .addContent(new SeparateurPriceIncrusteContent())
                .addContent(new CentimePriceIncusteContent());
            this.targetedIncrustContents = Object.values(priceIncruste.contents);



            let priceContent = new TextIncrusteContent()
            textContent.setStyle(style);
            textIncruste.addContent(textContent)

            $.ajax({
                type: "GET",
                url: '/template/stage1/model/register',
                data: {
                    incrusteStyle : stringify(textIncruste),
                },
                success: (encodedNewIncruste)=>{
                    console.log(encodedNewIncruste)
                    let parsedNewIncruste = JSON.parse(encodedNewIncruste);
                    let newClass = parsedNewIncruste['elements'][0].name;

                    console.log(this.parentTool.subTools)
                    let ZoneContainerTextSelectorTool = this.parentTool.subTools['ZoneContainerTextSelectorTool'];

                    ZoneContainerTextSelectorTool.refreshCssStylesheet()
                    ZoneContainerTextSelectorTool.addStyleSelectorDiv(ZoneContainerTextSelectorTool.$location.container.find('ul'),newClass);

                },
            });

        }})*/
        return stylyzer
    }

    onDisactivation(){
        this.onClickOnComfirmRegisterModel(false)
        this.onChangeSwitchTargetedIncrusteContents(false)
        this.onFocusIncrustRefreshTarget(false)

    }
    onActivation(){
        this.onClickOnComfirmRegisterModel(true)
        this.onChangeSwitchTargetedIncrusteContents(true)
        this.onFocusIncrustRefreshTarget(true)
        console.log(this.parentTool);
    }
}

export {PriceZoneSelectorTool}