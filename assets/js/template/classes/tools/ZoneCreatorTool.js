import {TemplateTool} from './parent/TemplateTool.js'
import {ZonePriceCreatorTool} from "./subtools/zoneCreator/ZonePriceCreatorTool.js";
import {ZoneTextCreatorTool} from "./subtools/zoneCreator/ZoneTextCreatorTool.js";
import {ZoneMediaCreatorTool} from "./subtools/zoneCreator/ZoneMediaCreatorTool.js";
import {Observable} from "../pattern/observer/Observable.js";
import {getCursorPositionInReferent} from "../utilities/utilities.js"
import { Zone } from '../Zone.js';
class ZoneCreatorTool extends TemplateTool{

    constructor(templateInterface){
        super(templateInterface);
        this.description = 'Ajouter une zone';
        this.$location.templateWorkSpace = $('.template-workzone')
        this.$location.targetContainer = null;
        this.cursorPosition = { new : null , old : null } ;
        this.creationLimits = { x : { start : null, end : null } , y : { start : null , end : null } }
        this._creatingZoneType = 'zone'
        this.error = true;
        this.currentZone = null;
        this.subTools = {
            'ZonePriceCreatorTool'  : null,
            'ZoneTextCreatorTool'   : null,
            'ZoneMediaCreatorTool'  : null
        } ;
        this.zoneCreationObservable = new Observable();
    }


    set creatingZoneType(type){
        this._creatingZoneType = type
    }

    get creatingZoneType(){
        return this._creatingZoneType
    }

    setTargetContainer($target) {
        this.$location.targetContainer =  $target.hasClass('zone') ?  $target : $('.template-workzone')
        this.updateZoneCreationLimits()
        return this.$location.targetContainer
    }

    refreshCursorPosition(e){
        this.cursorPosition.old = this.cursorPosition.new !== null ? { ...this.cursorPosition.new } : getCursorPositionInReferent(e,this.$location.templateWorkSpace)
        this.cursorPosition.new = getCursorPositionInReferent(e,this.$location.templateWorkSpace)
    }


    updateZoneCreationLimits(){
        let containerPos = this.$location.targetContainer.position()
        let containerSize = {width : this.$location.targetContainer.width(), height: this.$location.targetContainer.height()}

        this.creationLimits.x.start = containerPos.left
        this.creationLimits.y.start = containerPos.top
        this.creationLimits.x.end = this.creationLimits.x.start + containerSize.width;
        this.creationLimits.y.end = this.creationLimits.y.start + containerSize.height;
    }
    outOfContainerLimit(){
        let outOfLimit = { x: { start : null, end : null }, y: { start : null, end : null } }

         Object.keys(outOfLimit).map( limitPoint => 
            outOfLimit[limitPoint] = {
                start : this.cursorPosition.new[limitPoint] < this.creationLimits[limitPoint].start,
                end   : this.cursorPosition.new[limitPoint] > this.creationLimits[limitPoint].end
            } )
            return outOfLimit
        }
    activeTool(active) {
        super.activeTool(active);
        if (active) {
            $(document).on('mousedown.' + this.constructor.name, (e) => {

                 let $target = $(e.target)

                if ($target.hasClass('bloc-menu')) return;

                let workZone = this.setTargetContainer($target)

               this.refreshCursorPosition(e)
    
                // Lorsqu'on bouge le curseur
                $(document).on('mousemove.' + this.constructor.name, (e) => {

                    this.refreshCursorPosition(e)

                    let outOfContainerLimits = this.outOfContainerLimit() ;
                    console.log(outOfContainerLimits)

                    if( ( typeof this.currentZone !== 'object' || ! ( this.currentZone instanceof Zone ) ) && 
                        ( !outOfContainerLimits.x.start || !outOfContainerLimits.y.start )){
   
                        this.currentZone = new Zone({ position : {
                             left : this.cursorPosition.old.x<this.creationLimits.x.start ? this.creationLimits.x.start : this.cursorPosition.new.x,
                              top: this.cursorPosition.old.y<this.creationLimits.y.start ? this.creationLimits.y.start : this.cursorPosition.new.y}
                             })
                        this.currentZone.type = this.creatingZoneType
                        this.currentZone.$location = this.interface.currentTemplate
                        this.currentZone.position = {                          
                                left : this.cursorPosition.old.x<this.creationLimits.x.start ? this.creationLimits.x.start : this.cursorPosition.new.x,
                                 top: this.cursorPosition.old.y<this.creationLimits.y.start ? this.creationLimits.y.start : this.cursorPosition.new.y
                        }
                        this.currentZone.append()
                    }

                    if(typeof this.currentZone === 'object' &&  this.currentZone instanceof Zone ){
                        if(!outOfContainerLimits.x.end) this.currentZone.size= { width : this.cursorPosition.new.x-this.currentZone.position.left }
                        else if(this.currentZone.size.width < this.creationLimits.x.end) this.currentZone.size= { width : this.creationLimits.x.end - this.currentZone.position.left}

                        if(!outOfContainerLimits.y.end) this.currentZone.size= { height : this.cursorPosition.new.y -this.currentZone.position.top }
                        else if(this.currentZone.size.height < this.creationLimits.y.end) this.currentZone.size= { height : this.creationLimits.y.end  - this.currentZone.position.top}
                    }           
        
                });
            });
            $(document).on('mouseup.' + this.constructor.name, (e) => {

                if (typeof this.currentZone ==='object' && this.currentZone instanceof Zone && (this.currentZone.size.width < 5 || this.currentZone.size.height < 5)) this.interface.currentTemplate.deleteZoneInTemplate(this.currentZone.identificator);

                this.zoneCreationObservable.notify(true);
                  this.currentZone = null;
                this.usingToolAuthorization = false;
                //this.error=true;
                $(document).unbind('mousemove.' + this.constructor.name);
            })
        } else {
            $(document).off('mouseup.' + this.constructor.name);
            $(document).off('mousedown.' + this.constructor.name);
            $(document).off('mousemove.' + this.constructor.name);
        }

    }
}

export {ZoneCreatorTool}