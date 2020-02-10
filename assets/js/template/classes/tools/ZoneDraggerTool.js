import {TemplateTool} from './parent/TemplateTool'
import {getCursorPositionInReferent} from "../utilities/utilities.js"
import { Zone } from '../Zone';

class ZoneDraggerTool extends TemplateTool{
    constructor(templateInterface){
        super(templateInterface);
        this.description = 'Déplacer une zone';
        this.$location.workSpace = this.interface.currentTemplate.$container
        this.cursorPosition = {old : null, new:null}
        this.$eventLocation.mousedown =  $('body');
        this.$eventLocation.mousemove =  $('body');
        this.$eventLocation.mouseup =  $('body');
        this.workSpace = $('.container-zone')
    }

    refreshCursorPosition(e){
        this.cursorPosition.old = this.cursorPosition.new !== null ? { ...this.cursorPosition.new } : getCursorPositionInReferent(e,this.$location.workSpace)
        this.cursorPosition.new = getCursorPositionInReferent(e,this.$location.workSpace)
    }

    calculateMinAndMaxPos($container, $target){
     let $containerPos = $container.position() 
     let $containerSize = {width : $container.width(), height : $container.height()}
        return  {
             min: {  ...$containerPos } ,
             max: { left: $containerPos.left + $containerSize.width - $target.width(),top: $containerPos.top + $containerSize.height - $target.height()} 
            }
    }

    calculateNewPosOfCurrentElement(currentElement, $container, minPos, maxPos){

        let containerPos = $container.position()
        let deplacementValue = {x : this .cursorPosition .new .x - this .cursorPosition .old .x, y :this .cursorPosition .new .y-this .cursorPosition .old .y};
        let futurePos = { left: containerPos.left + currentElement .position .left + deplacementValue .x, top: containerPos.top + currentElement .position .top + deplacementValue .y };

        if( futurePos .left < minPos .left ) futurePos .left = minPos .left
        if( futurePos .top < minPos .top ) futurePos .top = minPos .top
        if( futurePos .left > maxPos .left) futurePos .left = maxPos .left
        if( futurePos .top > maxPos .top) futurePos .top = maxPos .top

        return futurePos
    }

    dragTarget(target, $container, minPosition, maxPosition){
        console.log(target)
        if ( typeof target !== 'object' || !( target instanceof Zone ) ) return console.log('the target to move need to be a Zone')

        let newPos = this.calculateNewPosOfCurrentElement(target, $container, minPosition, maxPosition)
        if(typeof newPos.left !== 'number' || typeof newPos.top !== 'number')return console.log("impossible to calculate the position of the element")
   
        target.setPosition(newPos)
    }
   activeTool(active){
       super.activeTool(active)
            if(active){

                $('.zone').addClass('draggerModeOn');

                this.$location.workSpace.find('.zone').on('mousedown.'+this.constructor.name,(e)=>{

                    this.refreshCursorPosition(e)

                    let currentZone = this.interface.currentTemplate.getZone($(e.currentTarget).data('zone'));

                   let minMaxPos = this.calculateMinAndMaxPos(this.$location.workSpace, currentZone.$container)

                   $(document).on('mousemove.'+this.constructor.name,(e) => {
        
                                this.refreshCursorPosition(e)

                                this.dragTarget(currentZone , this.$location.workSpace, minMaxPos.min, minMaxPos.max)
                                                 
                        
                    })

                    $(document).on('mouseup.'+this.constructor.name, (e) => {
                        $(document).off('mousemove.'+this.constructor.name)
                    })
                });

            }
            else{
                $('.zone').removeClass('draggerModeOn');
                this.$location.workSpace.find('.zone').off('mousedown.'+this.constructor.name);
                $(document).off('mousemove.'+this.constructor.name);
                $(document).off('mouseup.'+this.constructor.name);
            }
    }
}

export {ZoneDraggerTool}