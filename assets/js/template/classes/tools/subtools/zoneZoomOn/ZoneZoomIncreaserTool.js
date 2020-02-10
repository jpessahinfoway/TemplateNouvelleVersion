import {ZoneZoomOnSubTool} from "./parent/ZoneZoomOnSubTool.js";

class ZoneZoomIncreaserTool extends ZoneZoomOnSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        this.description = 'Appliquer un zoom';
        this.deplacementVal = {
            x : 0,
            y : 0
        }
        this.mousePos = {
            new : {
                x : null,
                y:  null
            },
            old : {
                x : null,
                y:  null
            }
        }
    }

    /*moveZoomOnMouseMove(){
        this.$eventLocation.mousemove.on('mousemove.'+this.constructor.name, e => {
            if(this.toolOn && !this.paused){
                console.log('ok')
                // Position en X et Y de la souris
                let pageX = e.pageX;
                let pageY = e.pageY;
                // Position en left et top du parent
                let offsetLeft = $('section').offset().left;
                let offsetTop = $('section').offset().top;
                // Width et height du parent
                let width = $('section').width();
                let height = $('section').height();
                // On modifie la propriété 'transform-origin'
                this.$applyZoneOn.css('transform-origin' , (pageX - offsetLeft) / width * 100 + '% ' + (pageY - offsetTop) / height * 100 + '%');
            }
        })
    }*/

    stopZoomOnRightClick(){
        this.$eventLocation.rightclick.on(`contextmenu.${this.constructor.name}`,()=>{
            this.paused=!this.paused;
            return false;
        })
    }
    updateScale(scale){
        console.log(this.interface)
        this.interface.currentTemplate.setCurrentScale(scale);
        this.currentScale = scale
    }

    dragZoomOnMouseMove(){
        this.$eventLocation.mousemove.on(`mousemove.${this.constructor.name}`,(e)=>{
            if(this.moving){
                this.zoomAction=false;
                let pageX = e.pageX;
                let pageY = e.pageY;
                this.mousePos.old = Object.assign({},this.mousePos.new)
                this.mousePos.new ={x:e.pageX, y: e.pageY}
                this.deplacementVal.x = this.mousePos.old.x-this.mousePos.new.x;
                this.deplacementVal.y = this.mousePos.old.y-this.mousePos.new.y;
                console.log(this.deplacementVal)
                let offsetLeft = $('section').offset().left;
                let offsetTop = $('section').offset().top;

                let width = $('section').width();
                let height = $('section').height();

                console.log('pagex : '+ pageX + ' offsetleft : '+offsetLeft+ ' width: '+width+ 'pageY :'+pageY+' offsetTop : '+offsetTop+' height :'+height)

                console.log((width + offsetLeft - pageX ) / width * 100 + '% ' + (pageY - offsetTop) / height * 100 + '%')
                console.log((pageX - offsetLeft) / width * 100 + '% ' + (pageY - offsetTop) / height * 100 + '%')
                this.$applyZoneOn.css('transform-origin' , (pageX- offsetLeft) / width * 100 + '% ' + (pageY - offsetTop) / height * 100 + '%');
            }
        })
    }

    stopActionOnMouseUp(){
        this.$eventLocation.mouseup.on(`mouseup.${this.constructor.name}`,(e)=> {
            if(this.zoomAction){
                this.updateScale(this.currentScale+0.2);
                this.$applyZoneOn.css('transform', 'scale(' + this.currentScale + ')')
            }
            this.moving=false;
            }
        )
    }


    activeZomeIncreaser(){
        console.log(this)
        this.currentScale = this.interface.currentTemplate.getCurrentScale();
        this.$eventLocation.mousedown.on(`mousedown.${this.constructor.name}`,(e)=>{

            this.mousePos.new.x = e.pageX;
            this.mousePos.new.y = e.pageY;

            this.zoomAction=true;
            this.moving = true;
            this.dragZoomOnMouseMove();
            this.stopActionOnMouseUp();
          /*  this.toolOn=true;
            if (this.currentScale.toFixed(1) >= 1.6) {
                this.updateScale(1.6);
            }
            // Sinon, on autorise le zoom
            else {
                this.updateScale(this.currentScale+0.2);
            }
            this.$applyZoneOn.css('transform', 'scale(' + this.currentScale + ')')
*/
        })
       // this.moveZoomOnMouseMove();
      //  this.stopZoomOnRightClick()
    }

    activeTool(boolean){
        console.log('ici')
        super.activeTool(boolean,this.activeZomeIncreaser)
    }
}

export {ZoneZoomIncreaserTool}