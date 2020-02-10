
import {MediaContent} from "../../../../zoneContents/MediaContent";
import {ZoneContainerTextEditorSubTool} from "./parent/ZoneContainerTextEditorSubTool";
import {UnitePriceIncrusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/UnitePriceIncrusteContent";
import {EuroPriceIncrusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/EuroPriceIncrusteContent";
import {SeparateurPriceIncrusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/SeparateurPriceIncrusteContent";
import {CentimePriceIncusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/CentimePriceIncrusteContent";
import {PriceIncruste} from "../../../../objects/incrustes/priceIncruste/PriceIncruste";
import {PriceIncusteContent} from "../../../../objects/incrustesContents/priceIncrustContents/centimePriceIncrusteContent/PriceIncrusteContent";
import {TextIncruste} from "../../../../objects/incrustes/textIncruste/TextIncruste";
import {TextIncrusteContent} from "../../../../objects/incrustesContents/textIncrusteConents/TextIncrusteContent";
import {Incruste} from "../../../../objects/incrustes/Incruste";
import {Observer} from "../../../../pattern/observer/Observer";



class ZoneContainerTextSelectorTool extends ZoneContainerTextEditorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);

        this.title = 'Choisir un style de texte';
        this.$location.container = $('.modal.background-editor .left-container .bottom-left-container #choose-text-style');
        this.$location.selectorList = this.$location.container.find('ul.classes-text');
        this.$location.comfirm = this.$location.container.find('button.btn.comfirm');

        this.textSelected = null;
        this.selectedIncrust=null;
        //this.functionToExecuteOnSelectedZone = this.setMediaToSelectedZone;
    }

    activeTool(boolean){
        super.activeTool(boolean,this.onActivation,this.onDisactivation)
    }


    getExistingStyles(){
        return $.ajax({
            type: "POST",
            url: '/template/stage2/getstyles',
            success: (data) => {return data}
        });
    }


    buildStyleSelectorList(parsedCSSJson){

        let parsedCSS = JSON.parse(parsedCSSJson);
        let HTMLcontainer = '';

        Object.keys(parsedCSS).map( className => {
            HTMLcontainer += this.buildStyleSelectorDivHTML(className.substring(1,className.length));
        });

      //  this.$location.selectorList.html(HTMLcontainer)
    }

    addStyleSelectorDiv(incrust){
        let HTML =
            "<li class='class-text'>"+
            `<div class="class-text__flex-container"><span>${incrust.class}</span>`+
            "<div>" +
            "<button class='hidden-btn'><i class='fal fa-trash' title='Supprimer'></i></button>"+
            "<button class='hidden-btn'><i class='fal fa-file-import' title='Importer'></i></button>" +
            "</div>"+
            "</div>"+
            `<div class='incrust-style-wrapper'>`+
                incrust.html +
            `</div>`+
            '</li>';

        this.$location.selectorList.prepend(HTML);
    }

    refreshCssStylesheet(){
        $('#text-style-css').replaceWith(`<link id="${$('#text-style-css').attr('id')}" rel="stylesheet" href="${$('#text-style-css').attr('href')}?t=${Date.now()}">`);
    }

    onClickSelectStyle(active){
        if(active){
            this.$location.selectorList.on('click.onClickSelectStyle','.class-text',(e)=>{
                    this.$location.selectorList.find('.selected-style--blue').removeClass('selected-style--blue');
                    let incrustTarget = $(e.currentTarget).find('.incrust-style-wrapper')
                    incrustTarget.addClass('selected-style--blue');
                    /*this.selectedIncrust = this.generateIncrust(incrustTarget.get(0), {incrustObject : new TextIncruste(), incrustElementContent: new TextIncrusteContent()});*/
                this.selectedIncrust = this.generateIncrust(incrustTarget.get(0), {incrust : {instance:new TextIncruste(), required:['id']}, incrustElementContent: {instance : new TextIncrusteContent(), required : ['id','className','content','incrustOrder']}});
                console.log(this.selectedIncrust)

            })
        }else{
            this.$location.selectorList.off('click.onClickSelectStyle')
        }
    }
    generateIncrustWithDatas(incrust){
        let createdIncrust = new TextIncruste();
        let currentIncrustElement = null;

        ['id','name'].forEach(property => typeof createdIncrust[ property ] === 'object' && typeof incrust[property] !== 'undefined'? createdIncrust[ property ] =  incrust[property] : '');
        incrust['incrusteElements'].forEach(incrustElement => {

            currentIncrustElement = new TextIncrusteContent();

            let incrustElementProperties = ['id', 'class' ,'incrustOrder', 'content'];

            incrustElementProperties.forEach(property => typeof currentIncrustElement[ property ] === 'object' && typeof incrustElement[property] !== 'undefined'? currentIncrustElement[ property ] =  incrustElement[property] : '');
            console.log(currentIncrustElement)

            createdIncrust.addIncrusteElements(currentIncrustElement);

        });
        createdIncrust.buildHTML()
        return createdIncrust;
    }
    addIncrustToList(incrust){
        let generatedIncrust = this.generateIncrustWithDatas(incrust);
        if(typeof generatedIncrust.html !== 'undefined' && generatedIncrust.html !== null)this.addStyleSelectorDiv(generatedIncrust);

    }

    onClickOnComfirmSetZonePrice(active){
        if(active){
            this.$location.comfirm.on('click.onClickOnComfirmSetZonePrice',()=>{
                console.log(this.selectedIncrust)

                this.parentTool.onComfirmAddIncrustToZone(this.selectedIncrust)
            })
        }
    }

    buildStyleSelectorDivHTML(incrustHTML){
        console.log(className);
        let HTML =
            "<li class='class-text'>"+
            `<div class="class-text__flex-container"><span>.${className}</span>`+
            "<div>" +
            "<button class='hidden-btn'><i class='fas fa-trash-alt' title='Supprimer'></i></button>"+
            "<button class='hidden-btn'><i class='fas fa-file-import' title='Importer'></i></button>" +
            "</div>"+
            "</div>"+
            `<div class='incrust-style-wrapper'>`+
                 incrustHTML +
            `</div>`+
            '</li>';
        return HTML
    }



    onDisactivation(){
        this.onClickSelectStyle(false)
        this.onClickOnComfirmSetZonePrice(false)
    }
    onActivation(){
        this.onClickSelectStyle(true)
        this.onClickOnComfirmSetZonePrice(true)


        /*  console.log(this)
          console.log(this.parentTool)
          this.parentTool.templateMiniature.resetMiniature().addZones(['media']);
          this.onClickOnSelectedMedia(true)*/
    }
}

export {ZoneContainerTextSelectorTool}