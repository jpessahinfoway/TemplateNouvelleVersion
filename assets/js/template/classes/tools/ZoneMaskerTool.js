import {PermanentTool} from "./parent/PermanentTool";

class ZoneMaskerTool extends PermanentTool{
    constructor(templateInterface){
        super(templateInterface);
        this.description = 'Masquer les zones';
        this.$eventLocation=$('.zone');
    }

    activeTool(active){
        super.activeTool(active)

            if(active){
                this.interface.isMaskerOn=true;
                $('.zone').addClass('hidden-zone');
            }
            else{
                this.interface.isMaskerOn=false;
                $('.zone').removeClass('hidden-zone');
            }
    }
}

export {ZoneMaskerTool}