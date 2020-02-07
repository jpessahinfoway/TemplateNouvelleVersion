import {Observer} from "../../template/classes/pattern/observer/Observer";
import {Template} from "../../template/classes/Template";
import {TemplateToolBox} from "../../template/classes/TemplateToolBox";
import {TemplateToolsMenu} from "../../template/classes/TemplateToolsMenu";
import {ChoiceDiv} from "../../template/classes/utilities/ChoiceDiv";

class TemplateInterface{

    constructor(){
        this.currentTemplate    =   null;
        this.toolsList          = {};
        this.toolBox            =  null;
        this.clickOnToolObserver = this.initClickOnToolObserver() ;
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


    createTemplate(name,orientation){
        this.currentTemplate = new Template();
        this.currentTemplate.setOrientation(orientation);
        this.currentTemplate.setName(name);
        this.currentTemplate.show();
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