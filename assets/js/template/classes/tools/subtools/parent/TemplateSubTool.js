import {TemplateTool} from "../../parent/TemplateTool.js";

class TemplateSubTool extends TemplateTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.parentTool=parentTool;
       // this.iconContainer=$(`<span title="${this.title}" class="${this.name}" data-subtool="${this.constructor.name}"></span>`);
    }

}

export {TemplateSubTool}