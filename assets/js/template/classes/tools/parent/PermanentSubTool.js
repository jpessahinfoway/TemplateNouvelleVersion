import {PermanentTool} from "./PermanentTool";

class PermanentSubTool extends PermanentTool{
    constructor(templateInterface,parentTool) {
        super(templateInterface)
        this.parentTool = parentTool
        //    this.activated = false;
    }
}
export {PermanentSubTool}