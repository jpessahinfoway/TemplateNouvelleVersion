import {ZoneContainerPriceEditorSubTool} from "./parent/ZoneContainerPriceEditorSubTool";
import {PriceContent} from "../../../../zoneContents/PriceContent";
import {EuroContent} from "../../../../zoneContents/PriceContent/EuroContent/EuroContent";
import {SeparatorContent} from "../../../../zoneContents/PriceContent/SeparatorContent/SeparatorContent";
import {CentimeContent} from "../../../../zoneContents/PriceContent/CentimeContent/CentimeContent";
import {UniteContent} from "../../../../zoneContents/PriceContent/UniteContent/UniteContent";
import {PriceIncruste} from "../../../../objects/incrustes/priceIncruste/PriceIncruste";
import {UnitePriceIncrusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/UnitePriceIncrusteContent";
import {EuroPriceIncrusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/EuroPriceIncrusteContent";
import {SeparateurPriceIncrusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/SeparateurPriceIncrusteContent";
import {CentimePriceIncusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/CentimePriceIncrusteContent";
import {PriceIncusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/PriceIncrusteContent";
import {Incruste} from "../../../../objects/incrustes/Incruste";
import {Zone} from "../../../../Zone";
import {TextIncruste} from "../../../../objects/incrustes/textIncruste/TextIncruste";
import {TextIncrusteContent} from "../../../../objects/incrustesContents/textIncrusteConents/TextIncrusteContent";
import {IncrusteContent} from "../../../../objects/incrustesContents/IncrusteContent";



class ZoneContainerPriceSelectorTool extends ZoneContainerPriceEditorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);

        this.title = 'Choisir un style de prix';
        this.$location.container = $('.modal.background-editor .left-container #choose-price-style');
        this.$location.listPrices = this.$location.container.find('.list-prices');
        this.$location.comfirm = this.$location.container.find('button.btn.comfirm');
        this.selectedIncrust = null

    }


    activeTool(boolean){
        super.activeTool(boolean,this.onActivation,this.onDisactivation)
    }


    onClickSelectStyle(active){
        if(active){
            this.$location.listPrices.find('.incrust-style-wrapper').on('click.onClickSelectStyle',e => {
                let target = e.currentTarget;
                $('.selected-style--blue').removeClass('selected-style--blue');
                this.selectedIncrust = this.generateIncrust($(e.currentTarget).get(0),{
                    incrust : {instance: new PriceIncruste(), required : ['id']},
                    incrustElementContent : {instance : new PriceIncusteContent(), required : ['id','class', 'incrustOrder']},
                    incrustElementSubContents : {instance : {
                            unite : new UnitePriceIncrusteContent(),
                            euro: new EuroPriceIncrusteContent(),
                            separator : new SeparateurPriceIncrusteContent(),
                            centime : new CentimePriceIncusteContent()
                    }, required : ['id', 'class' ,'incrustOrder', 'content']}

                });
                $(target).addClass('selected-style--blue')
            })
        }else{
            this.$location.listPrices.find('.incrust-style-wrapper').off('click.onClickSelectStyle')
        }

    }

    addIncrustToList(incrust){
        let generatedIncrust = this.generateIncrustWithDatas(incrust);
        if(typeof generatedIncrust.html !== 'undefined' && generatedIncrust.html !== null)this.addStyleSelectorDiv(generatedIncrust);

    }

    generateIncrustWithDatas(incrust){
        let createdIncrust = new PriceIncruste();
        let currentIncrustElement = null;

        ['id','name'].forEach(property => typeof createdIncrust[ property ] === 'object' && typeof incrust[property] !== 'undefined'? createdIncrust[ property ] =  incrust[property] : '');

        let incrustElementProperties = ['id', 'class' ,'incrustOrder', 'content'];

        incrust['incrusteElements'].forEach(incrustElement => {
            let addIncrustContentElement  = `add${incrustElement['type'].charAt(0).toUpperCase() + incrustElement['type'].slice(1)}Element`;

            if(typeof createdIncrust[addIncrustContentElement] !== 'function') return ;

            currentIncrustElement = createdIncrust[addIncrustContentElement]() ;

            if( !(typeof currentIncrustElement === 'object' && currentIncrustElement instanceof IncrusteContent) ) return ;

            incrustElementProperties.forEach(property => typeof currentIncrustElement[ property ] === 'object' && typeof incrustElement[property] !== 'undefined'? currentIncrustElement[ property ] =  incrustElement[property] : '');
        });
        createdIncrust.buildHTML()
        return createdIncrust;
    }

    addStyleSelectorDiv(incrust){
        let HTML =
            "<li class='class-text'>"+
            `<div class="class-text__flex-container"><span>${incrust.name}</span>`+
            "<div>" +
            "<button class='hidden-btn'><i class='fas fa-trash-alt' title='Supprimer'></i></button>"+
            "<button class='hidden-btn'><i class='fas fa-file-import' title='Importer'></i></button>" +
            "</div>"+
            "</div>"+
            `<div class='incrust-style-wrapper'>`+
            incrust.html +
            `</div>`+
            '</li>';

        this.$location.container.prepend(HTML);
    }

    onClickOnComfirmSetZonePrice(active){
        if(active){
            this.$location.comfirm.on('click.onClickOnComfirmSetZonePrice',()=>{
                this.parentTool.onComfirmAddPriceToZone(this.selectedIncrust)
            })
        }
    }

    setSelectedPriceToZone(zone){
        if(typeof zone ==='object' && zone instanceof Zone && typeof this.selectedIncrust ==='object' && this.selectedIncrust instanceof Incruste)zone.setZoneContent(zone)
    }

    onClickOnComfirm(){
        this.location.buttons
    }

    onDisactivation(){

    }
    onActivation(){
        this.onClickOnComfirmSetZonePrice(true)
        this.onClickSelectStyle(true)
    }
}

export {ZoneContainerPriceSelectorTool}