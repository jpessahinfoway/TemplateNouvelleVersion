import {Observer} from "../../template/classes/pattern/observer/Observer";
import {Template} from "../../template/classes/Template";
import {TemplateToolBox} from "../../template/classes/TemplateToolBox";
import {TemplateToolsMenu} from "../../template/classes/TemplateToolsMenu";
import {ChoiceDiv} from "../../template/classes/utilities/ChoiceDiv";

class TemplateInterface{

    constructor(stage, action){
        this.infos = {
            stage,
            action
        };
        this.currentTemplate    =   null;
        this.toolsList          = {};
        this.toolBox            =  null;
        this.clickOnToolObserver = this.initClickOnToolObserver() ;
        this.stage = $('#template-infos').data('stage');
        this.toolsMenu          =  {};
        this.activatedTools     =  {} ;
        this.toolsMenus = {};
        this.choiceDiv = new ChoiceDiv()
        this.initActions()

    }

    initClickOnToolObserver(){
        let clickOnToolObserver = new Observer();
        clickOnToolObserver.observerFunction(datas=>{
            let clickedTool = datas[0]
            this.toolBox.updateActivatedTools(clickedTool)
        })
        return clickOnToolObserver
    }


    attachToolBox(){
        this.toolBox = new TemplateToolBox() ;

        return this.toolBox
    }

    initToolsMenu(){
        this.toolsMenu = {};

        let mainToolsMenu = this.addToolsMenu('mainToolsMenu', $('#main-toolbox')) ;
        mainToolsMenu.attachTool(this.toolBox.toolsList['ZoneCreatorTool']) ;
        mainToolsMenu.attachTool(this.toolBox.toolsList['ZoneDraggerTool']) ;
        mainToolsMenu.attachTool(this.toolBox.toolsList['ZoneRemoverTool']) ;
        mainToolsMenu.attachTool(this.toolBox.toolsList['ZoneResizerTool']) ;
        mainToolsMenu.attachTool(this.toolBox.toolsList['ZonePriorityManagerTool']) ;
        mainToolsMenu.attachTool(this.toolBox.toolsList['ZoneMaskerTool']) ;
        mainToolsMenu.attachTool(this.toolBox.toolsList['ZoneZoomOnTool']) ;
        mainToolsMenu.attachTool(this.toolBox.toolsList['ZoneDuplicatorTool']) ;

        mainToolsMenu.clickOnToolObservable.addObserver(this.clickOnToolObserver)
        let zoneContainerToolsMenu = this.addToolsMenu('zoneContainerToolsMenu', $('#modal-toolbar')) ;

        zoneContainerToolsMenu.attachTool(this.toolBox.toolsList['ZoneContainerEditorTool'])

        zoneContainerToolsMenu.clickOnToolObservable.addObserver(this.clickOnToolObserver)

        Object.values(this.toolsMenu).forEach(toolMenu => toolMenu.activeMenu(true))
        console.log(zoneContainerToolsMenu)
    }

    attachToolsMenu(name,$location){
        let createdToolsMenu = new TemplateToolsMenu(name,$location) ;
        createdToolsMenu.clickOnToolObservable.addObserver(this.clickOnToolObserver) ;
        createdToolsMenu.activeMenu(true)
        this.toolsMenus[name]= createdToolsMenu ;
        return this.toolsMenus[name]
    }


    createTemplate(orientation){

        this.currentTemplate = new Template();
        this.currentTemplate.orientation = orientation;
        this.currentTemplate.name = name;
        this.currentTemplate.show();

        return Promise.resolve( this )
    }

    buildTemplateFromDataImported( template ){

        if(template === "Not found !")
            return new Error('Template is not found !');

        if(typeof template.name !== 'string') throw new Error('Invalid value for template argument name');
        if(typeof template.orientation !== 'string' || ( template.orientation !== 'H' && template.orientation !== 'V' ))throw new Error ('Bad value for template argument orientation') ;

        this.currentTemplate = new Template() ;
        this.currentTemplate.orientation = template.orientation ;
        this.currentTemplate.level = template.level ;
        this.currentTemplate.lastModifcation = new Date(template.modification_date.date) ;
        if(template.name !== null)this.currentTemplate.name = template.name ;

        let loadedZones = template.zones ;
        console.log(loadedZones) ; debugger;

        if(Array.isArray(loadedZones)){
            loadedZones. forEach( loadedZone => {
                const { width , height , type , id } = { ...loadedZone } ;
                this.currentTemplate.createNewZone({
                    position : { top : loadedZone. positionTop , left : loadedZone. positionLeft },
                    size : { width , height } ,
                    type ,
                    id
                })
            })

        }

        return this.currentTemplate ;
    }

    loadTemplate() {

        debugger;
        if(this.infos.stage === 1 && this.infos.actions ==='create') throw new Error('invalid Method') ;

        let $templateInfosDiv = $('#template-infos');

        const {id, orientation} = { ...$templateInfosDiv.data() };
        const  loadingTemplatePromise = new Promise((resolve, reject) => {

            //if(parsedUrl.includes('create') && typeof id !== 'undefined' && typeof orientation !== 'undefined'){
            if(typeof id !== 'undefined' && typeof orientation !== 'undefined'){
                $.ajax({
                    type: "POST",
                    //url: `/template/api/${this.currentTemplate.level > 1 ? 'custom' : 'default' }/${id}`,
                    url: `/get/${this.infos.stage > 1 ? 'custom' : 'admin' }/template/${id}/data`,
                    // /get/stage/{stage}/template/{id}/data
                }).done( template => {

                   let generatedTemplate = this.buildTemplateFromDataImported( template ) ;

                   if(typeof generatedTemplate !=='object' || ! ( generatedTemplate instanceof Template ) ) return reject('incorrect value for template') ;

                    const currentTemplateLastModificationDate = `Dernière modification : ${ (this.currentTemplate.lastModifcation.getHours() < 10 ? '0' : '') + this.currentTemplate.lastModifcation.getHours() }:${ (this.currentTemplate.lastModifcation.getMinutes() < 10 ? "0" : "") + this.currentTemplate.lastModifcation.getMinutes() } - ${this.currentTemplate.lastModifcation.getDate()}/${this.currentTemplate.lastModifcation.getMonth()+1}/${this.currentTemplate.lastModifcation.getFullYear()}`;

                    $("#currentTemplateLastModificationDate").text(currentTemplateLastModificationDate);
                    // this.currentTemplate.draw();
                     this.currentTemplate.show() ;
                     return resolve( this ) ;

                } )
                    .fail( (errorType, errorStatus, errorThrown ) => {

                        reject(console.error(errorType, errorStatus, errorThrown));

                    } );
            }else reject(new Error('Impossible to get all required elements'))
        })

        return loadingTemplatePromise
    }

    initActions(){
        this.saveOnClick()
    }

    saveOnClick(){
        $('.template-menu li.save').on('click',()=>{

            let templateDataToImport = {
                name : this.currentTemplate._name,
                attrs : this.currentTemplate._attr
            };
            console.log(templateDataToImport);
            $.ajax({
                type: "GET",
                url: '/template/stage1/register',
                data: {

                    zones : JSON.stringify(this.currentTemplate.getZones()),
                    template : JSON.stringify(templateDataToImport)
                },
                success: function(){
                    console.log('success')
                },
            });
        })
    }
}

export {TemplateInterface}