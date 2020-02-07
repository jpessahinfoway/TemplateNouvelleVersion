import {TemplateSubTool} from "./tools/subtools/parent/TemplateSubTool";
import {PermanentTool} from "./tools/parent/PermanentTool";
import {Observer} from "./pattern/observer/Observer";
import {TemplateTool} from "./tools/parent/TemplateTool";
import {Observable} from "./pattern/observer/Observable";

class TemplateToolBox{
    constructor(){
        this.toolsList = {};
    }


    updateActivatedTools(clickedTool){
        let toolsToNotDisactivate = [clickedTool] ;

        if(typeof  clickedTool.parentTool === 'object'  && clickedTool.parentTool instanceof TemplateTool){
            if(clickedTool.parentTool.activated && !clickedTool.activated) toolsToNotDisactivate.push(clickedTool.parentTool)
        }

        Object.values(this.toolsList).forEach(tool => { if(!toolsToNotDisactivate.includes(tool) && !(tool instanceof PermanentTool )){ tool.activeTool(false) } } );

        clickedTool.activeTool(!clickedTool.activated) ;
        if(typeof  clickedTool.parentTool === 'object'  && clickedTool.parentTool instanceof TemplateTool && clickedTool.parentTool)

        console.log(Object.values(this.toolsList).filter(tool =>  tool.activated))

    }

    addTool(tool) {
        if(typeof tool !== 'object' || !(tool instanceof TemplateTool)) return console.log("outil non comforme")
        this.toolsList[tool.name] = tool ;
        if(typeof tool.subTools==='object')Object.values(tool.subTools).forEach(subTool => {
            if(subTool instanceof TemplateTool) this.addTool(subTool)
        })
        return tool
    }




}

export {TemplateToolBox}