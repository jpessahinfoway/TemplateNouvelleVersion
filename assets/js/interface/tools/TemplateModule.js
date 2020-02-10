import {Template} from "./Template";
import {TemplateToolBox} from "./TemplateToolBox";
import {ZoneCreatorTool} from "./tools/ZoneCreatorTool";
import {ZoneDraggerTool} from "./tools/ZoneDraggerTool";
import {ZoneRemoverTool} from "./tools/ZoneRemoverTool";
import {ZoneResizerTool} from "./tools/ZoneResizerTool";
import {ZonePriorityManagerTool} from "./tools/ZonePriorityManagerTool";
import {ZoneMaskerTool} from "./tools/ZoneMaskerTool";
import {ZoneInfoDisplayerTool} from "./tools/ZoneInfoDisplayerTool";
import {ZoneZoomOnTool} from "./tools/ZoneZoomOnTool";
import {ZoneAssociationTool} from "./tools/ZoneAssociationTool";
import {ZoneDuplicatorTool} from "./tools/ZoneDuplicatorTool";
import {ZoneContainerEditorTool} from "./tools/ZoneContainerEditorTool";
import {TemplateMiniatorizerTool} from "./tools/TemplateMiniatorizerTool";
import {Observer} from "./pattern/observer/Observer";
import {TemplateToolsMenu} from "./TemplateToolsMenu";

class TemplateModule{

    constructor(){
        this.currentTemplate    =   null;
        this.toolsList          = {};
        this.toolBox            =  null;
        this.clickOnToolObserver = this.initClickOnToolObserver() ;
        this.toolsMenu          =  {};
        this.activatedTools     =  {} ;
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

    initToolBox(){
        this.toolBox = new TemplateToolBox() ;
        this.toolBox.addTool(new ZoneCreatorTool(this))
        this.toolBox.addTool(new ZoneDraggerTool(this));
        this.toolBox.addTool(new ZoneRemoverTool(this));
        this.toolBox.addTool(new ZoneResizerTool(this));
        this.toolBox.addTool(new ZonePriorityManagerTool(this));
        this.toolBox.addTool(new ZoneMaskerTool(this));
        this.toolBox.addTool(new ZoneZoomOnTool(this));
        this.toolBox.addTool(new ZoneInfoDisplayerTool(this));
        this.toolBox.addTool(new ZoneAssociationTool(this));
        this.toolBox.addTool(new ZoneDuplicatorTool(this));
        this.toolBox.addTool(new ZoneContainerEditorTool(this));
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

    addToolsMenu(name,$location){
        this.toolsMenu[name] = new TemplateToolsMenu(name,$location)
        return this.toolsMenu[name]
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

export {TemplateModule}