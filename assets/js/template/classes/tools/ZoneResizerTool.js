import {TemplateTool} from './parent/TemplateTool'
import {Zone} from "../Zone";
import {getCursorPositionInReferent} from "../utilities/utilities";
class ZoneResizerTool extends TemplateTool{

    constructor(templateInterface){
        super(templateInterface);
        this.description='Transformer une zone'
        this.$eventLocation.mousemove = $('body');
        this.$eventLocation.mouseup = $('body');
        this.$eventLocation.mousedown = $('body');
        this.currentWorkZone=null;
        this.cursorPosition = { new : null , old : null }
        this.workSpace = $('.container-zone');
        this._targetZone = null;
        this.iconsDivHTML =
            '<div class="resizer-tool__list">' +
                '<span class="resizer-tool__resizer top left"></span>' +
                '<span class="resizer-tool__resizer top right" ></span>' +
                '<span class="resizer-tool__resizer bottom left" ></span>' +
                '<span class="resizer-tool__resizer bottom right"></span>' +
                '<span class="resizer-tool__resizer center left"></span>' +
                '<span class="resizer-tool__resizer center right" ></span>' +
                '<span class="resizer-tool__resizer center bottom"></span>' +
                '<span class="resizer-tool__resizer center top"></span>' +
            '</div>'
        /*this.iconsArray = [];
        this.size = 5;
        this.backGroundColor = null;
        this.borderStyle = null;*/

    }


    get targetZone() {
        return this._targetZone;
    }

    set targetZone(targetZone) {
        if(typeof targetZone !=='object' || ! ( targetZone instanceof Zone )) throw new Error('Invalid argument. the given argument need to be a Zone')
        this._targetZone = targetZone;
    }

    refreshCursorPosition(e){
        this.cursorPosition.old = this.cursorPosition.new !== null ? { ...this.cursorPosition.new } : getCursorPositionInReferent(e,this.interface.currentTemplate.$container)
        this.cursorPosition.new = getCursorPositionInReferent(e,this.interface.currentTemplate.$container)
    }

    appendResizerIcons(active){
        if(active){
            console.log(this.interface.currentTemplate.getZones())
            Object.values( this.interface.currentTemplate.getZones() ) .forEach( zone =>{
                console.log(zone)
                zone.appendInToolsEffectsContainer(this.iconsDivHTML)
            })
        }
        else{
        $('.resizer-tool__list').remove()
        }
    }

    isAttributeNeedToBeUpdated(attributeName, attributeValue){

        if(typeof this.targetZone[attributeName] === 'undefined') return console.log('unrecognized attribute of Zone')

        return Object.keys( attributeValue ) .filter( attributeKey => typeof attributeValue[attributeKey] === 'number' &&  typeof this.targetZone[attributeName][attributeKey] !== 'undefined' && attributeValue[attributeKey] !== this.targetZone[attributeName][attributeKey] ).length > 0
    }

    resizeZoneOnMouseActivity(active){
        if(active){
           console.log('sfsgdg')
            $('.resizer-tool__resizer').on('mousedown.resizeZoneOnMouseActivity',(e)=>{
                console.log('icii')
                this.refreshCursorPosition(e)

                this.lastIconClicked = $(e.currentTarget);

                let zoneId=$(e.currentTarget).parents('.zone').data('zone');

                let $targetContainer = this.interface.currentTemplate.$container ;
                let targetContainerPos = $targetContainer.position();
                let targetContainerSize = {width : $targetContainer.width(), height : $targetContainer.height()} ;
                console.log(targetContainerSize)

                this.targetZone = this.interface.currentTemplate.getZone(zoneId);

                $(document).on('mousemove.resizeZoneOnMouseActivity',(e) => {

                    this.refreshCursorPosition(e) ;
                    let newPos = {left : null, top:null}
                    let newSize = {width : null, height : null}

                    let deplacementValue = {
                        x :  this.cursorPosition.new.x - this.cursorPosition.old.x,
                        y : this.cursorPosition.new.y - this.cursorPosition.old.y
                    } ;

                    if( this.lastIconClicked.hasClass('left') ){
                        newPos.left = Math.ceil(this.targetZone.position.left + deplacementValue.x);
                        if( newPos.left < targetContainerPos.left ) newPos.left =  targetContainerPos.left ;
                        console.log(this.targetZone.size.width);
                        console.log(newPos.left)
                        console.log(this.targetZone.position.left);
                        newSize.width = Math.ceil(this.targetZone.size.width - ( newPos.left - this.targetZone.position.left));


                    }
                    if( this.lastIconClicked.hasClass('top') ) {
                        newPos.top = this.targetZone.position.top + deplacementValue.y ;
                        if( newPos.top < targetContainerPos.top ) newPos.top =  targetContainerPos.top ;

                        newSize.height = this.targetZone.size.height - ( newPos.top - this.targetZone.position.top) ;
                    }



                    if( this.lastIconClicked.hasClass('right') ) {
                        newSize.width = this.targetZone.size.width + deplacementValue.x;
                        if( this.targetZone.position.x + newSize.width > targetContainerSize.width ) newSize.width =  targetContainerSize.width ;
                    }
                    if( this.lastIconClicked.hasClass('bottom') ) {
                        newSize.height = this.targetZone.size.height + deplacementValue.y;
                        if( this.targetZone.position.y + newSize.height > targetContainerSize.top ) newSize.height =  targetContainerSize.height ;
                    }
                    if( this.isAttributeNeedToBeUpdated('position', newPos) )  this.targetZone.position = newPos ;
                    if( this.isAttributeNeedToBeUpdated('size', newSize) )  this.targetZone.size = newSize ;

                }) ;


            });

            this.$eventLocation.mouseup.on('mouseup',()=>{
                this.$eventLocation.mousemove.unbind('mousemove.'+this.constructor.name)
            })
        }
    }
    activeTool(active){
        super.activeTool(active) ;
            if(active){

                this.appendResizerIcons(true)
                this.resizeZoneOnMouseActivity(true)
            }else{
                this.appendResizerIcons(false)
                this.$eventLocation.mouseup.unbind('mouseup.'+this.constructor.name);
                this.$eventLocation.mousedown.unbind('mousedown.'+this.constructor.name);
                this.$eventLocation.mousemove.unbind('mousemove.'+this.constructor.name);
                $('.zoneResizer').remove()
            }
    }

}

export {ZoneResizerTool}