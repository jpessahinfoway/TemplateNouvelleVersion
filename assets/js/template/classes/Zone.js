import {Observable} from "./pattern/observer/Observable";
import {BackgroundContent} from "./zoneContents/BackgroundContent";
import {Incruste} from "./objects/incrustes/Incruste";
import { Template } from "./Template";


class Zone{


    constructor({size={width:0,height:0}, type='zone', position = {top:0,left:0}}={}){
        this._$content = null;
        this._$container = null;
        this._$toolsEffectsContainer = null;
        this._$location = {container : null}
        this._type = 'zone';
        this._size = size;
        this._position = position ;
        this.zoneContent = null;
        this.zoneChildrens = {};
        this.zoneParent = null;
        this.associatedZone = {};
        this.backgroundColor = 'rgb(255, 119, 119)';
        this._identificator = null;
        this.parent=null;
        this.background = {}
        this.zonePropertiesChangeObservable = new Observable()
        this.buildHTML();
    }

    get $toolsEffectsContainer() {
        return this._$toolsEffectsContainer;
    }

    set $toolsEffectsContainer(toolsEffectsContainer) {
        this._$toolsEffectsContainer = toolsEffectsContainer ;
    }

    appendInToolsEffectsContainer(toolsEffectsContent){
        console.log(this.$toolsEffectsContainer)
        this.$toolsEffectsContainer.html(toolsEffectsContent)
    }

    get type() {
        return this._type;
    }

    set type(type) {
        this._type = type;
    }


    get identificator(){
        return this._identificator
    }
    set identificator(identificator) {
        this._identificator = identificator
        this.$container.attr('data-zone' , this.identificator)
    }

    get size() {
        if(typeof this.$container === 'undefined' || this.$container === null || this.$container.length <1)return
        return { width : this.$container.width(), height : this.$container.height() };
    }


    set size(size) {
        console.log('dfdfg')
        if(this.$container === null  || this.$container.length <1) return ;

        if (typeof size.width === 'number'){
            this.$container.css('width',size.width);
            this.size.width = this.$container.width();
        }
        if(typeof size.height === 'number'){
            this.$container.css('height',size.height);
            this.size.height = this.$container.height();
        }
    }
    get position() {
        if(typeof this.$container === 'undefined' || this.$container === null || this.$container <1)return
        const { left , top } = this.$container.position();
        return { left , top };
    }


    set position(position) {

        if(typeof this.$container === 'undefined' || this.$container === null  || this.$container.length <1) return ;

        if (typeof position.left === 'number'){
            this.$container.css('left',position.left);
            this.position.left = this.$container.position().left;
        }

        if (typeof position.top === 'number'){
            this.$container.css('top',position.top);
            this.position.top = this.$container.position().top;
        }
    }

    setParent(zone){
        this.zoneParent = zone;
        zone.addChildren(this)
    }

    addChildren(zone){
        this.zoneChildrens[zone.identificator]=zone
    }

    add$ElementInZone($element){
        this.$container.append($element)
    }

    setZoneBackground(background){

        if(typeof background !== 'object' && !(background instanceof BackgroundContent) ) return;

        this.background[background.type] = background;

        this.$container.css(background.property, background.propertyValue);
        console.log(background.propertyValue)
    }

    setZoneContent(content){
       if(typeof content === 'object' && content instanceof Incruste){
           this.content = content;

           this.$zoneContentContainer.html(this.content.buildHTML());
       }
    }


    putTextInZone(text){
        this.$contentSpan.text(text)
    }
    setType(type){
        this.type=type;
    }

    associateZone(zoneToAssociate){
        this.associatedZone[zoneToAssociate.identificator]=zoneToAssociate;
        zoneToAssociate.associatedZone[this.identificator]=this;

    }

    unAssociateZone(zoneToUnAssociate){
        delete this.associatedZone[zoneToUnAssociate.identificator];
        delete zoneToUnAssociate.associatedZone[this.identificator];
    }

    getSize(){
        return this.size
    }

    getPosition(){
        return this.position
    }
    get $content(){
        return this._$content
    }

    set $content($content){
        this._$content=$content
    }

    buildHTML(){

        this.$container =  $(`<div class="zone" data-type="${this.type}" ></div>`);
        this.$toolsEffectsContainer = $('<div class="tool-content"></div>');
        this.$content=$('<div class="zone-content"></div>');
        this.$container.append(this.$toolsEffectsContainer).append(this.$content);
    }

    append() {
        if( typeof this.$location !== 'object' || !  (this.$location instanceof Template ) )throw new Error('No location recognized ! impossible to append')
        this.$location.addZone(this)
    }

    isAssociatedTo(zone){
       return Object.values(this.associatedZone).includes(zone)
    }

    delete(){
        this.$container.remove();
    }

    setZIndex(zIndex){
    if (this.$container !== null && this.$container.length > 0) this.$container.css('z-index', zIndex);
    this.zIndex = zIndex;
    }

    attachToTemplate(template){
        template.addZone(this);
    }

    setIdentificator(id){

        this.identificator = id;

        this.name = "zone-"+id;

        this.$container.data('zone',id);
        this.$container.attr("id",this.name);

    }

    set $location($location){
        console.log($location)
        console.log($location instanceof Template) 
        console.log(typeof $location)
        if(typeof $location !== 'object' || ! ( $location instanceof Template) ) throw new Error('Argument must be an object of type Template')
        this._$location.container = $location ;
    }


    get $location() {
        return this._$location.container;
    }

    set $container($container){
        if ($container.length<1 ) return ;
        this._$container = $container;

        if(this.zIndex !== null) this._$container.css('zIndex',this.zIndex);
    }
    get $container(){
        return this._$container
    }

    appendZone(){
        if(typeof this.$container == null || this.$container.length <1)return
        this.$container.append(this.$container)
    }

    setPosition(position = {top:null, left:null}){
        console.log(position)
        if(position.left!==null){
            this.position.left=position.left
        }
        if(position.top!==null){
            this.position.top = position.top;
            console.log(this.position.top)
        }
        this.$container.css({top:position.top,left:position.left});
        this.zonePropertiesChangeObservable.notify(this,{pos:position});
    }

    setSize(size = this.size) {
        if(typeof size.width ==='number'){
           
            this.$container.width(size.width);
            this.size.width=size.width
        }
        if(typeof size.width ==='number'){
            this.$container.height(size.height);
            this.size.height = size.height;
        }
        this.zonePropertiesChangeObservable.notify(this,{size:size});
    }

    setColor(color = this.backgroundColor){
        this.$container.css({'background-color':color, 'opacity' : 0.4});
    }
}

export {Zone}