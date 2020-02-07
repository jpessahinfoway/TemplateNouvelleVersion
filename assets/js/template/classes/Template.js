import {Zone} from "./Zone";

class Template{
    constructor(){
        this._$container= $('.template-workzone');
        this._name = null;
        this._attr = {
            size           :   {},
            orientation    :   null,
            id             :   null,
            scale          :   1
        };
        this._zones = {}

    }

    removeZone(zone){
        if(typeof zone !== 'object' || ! ( zone instanceof Zone ) ) throw new Error('invalid Argument. the argument gived must be a Zone')
        if( this.getZone(zone.identificator) !== zone) throw new Error("Impossible to identify the zone to delete in the template")

        this.getZone(zone.identificator).delete();
        delete this._zones[zone.identificator];
    }

    get zones(){
        return this._zones
    }
    get size(){
        if( typeof this.$container === 'undefined' || this.$container === null || this.$container.length <1 ) return ;
        return { width : this.$container.width(), height : this.$container.height() };
    }
    set size(size){
        if(typeof this.$container === 'undefined' || this.$container === null || this.$container.length <1 ) return ;

        if ( typeof size.width ==='number' ){
            this.$container.css('width',size.width)
            this._attr.size.width = this.$container.width();
        }
        if ( typeof size.height ==='number'){
            this.$container.css('height',size.height)
            this._attr.size.height = this.$container.height();
        }
    }
    get $container() {
        return this._$container;
    }

    set $container(value) {
        this._$container = value;
    }

    getSize(){
        console.log(this._attr._size)
        return this._attr._size;
    }

    setCurrentScale(scale){
        this._attr._scale = scale
        console.log(this._attr._scale)
    }

    getCurrentScale(){
        console.log(this._attr._scale)
        return this._attr._scale;
    }

    getZone(id){
        return this._zones[id]
    }

    deleteZoneInTemplate(id){
        this.getZone(id).delete();
        delete this._zones[id];
    }

    getZones(){
        return this._zones;
    }

    addZone(zone){
        console.log(this.zones)
        let allZonesIndexes = Object.keys(this.zones)
        for(let i=0; i<=allZonesIndexes.length ;i++){
            if(! allZonesIndexes .includes( i ) ) zone.identificator = i
        }
        this._zones[zone.identificator] = zone;
 
        this.$container.append(zone.$container)
    }
    setSize({width=null,height=null}){
        if(width!==null){
            this._attr.size.width=width
            this.$container.width(width)
        };
        if(height!==null){
            this._attr.size.height=height
            this.$container.height(height)
        };
    }

    setOrientation(orientation=null){
        if(orientation !== null){
            if  (orientation !== 'H'   &&   orientation !== 'V')    return;

            this._attr._orientation  =  orientation;

            orientation  ===  'H' ?  this.setSize({width:'1280',height:'720'})  :  this.setSize({width:'720',height:'1280'})
        }
    }

    setName(name=null){
        if(name!==null){
            this._name=name;
            this.setId(name)
        }

    }


    setId(id=null){
        if(name!==null)this._attr._id='#'+id;
        this.$container.attr('id',id)
    }

    show(){
        console.log(this.$container)
        console.log('iciii')
        this.$container.fadeIn()
    }

    createNewZone(position={top:0,left:0},size={width:0,height:0},zoneType='zone'){
        console.log(position)
        let zoneId = null;

        let zIndex = Object.keys(this._zones).length;

        for(let i=0; i<=Object.keys(this._zones).length+1;i++){
            if(!(i in this._zones)){
                zoneId=i;
                break;
            }else{
                console.log(this._zones)
            }
        }

        let zone = new Zone({position:position,size:size,type:zoneType});

        zone.create();
        zone.position = position;
        zone.setZIndex(zIndex);
        zone.setIdentificator(zoneId);
        zone.attachToTemplate(this);
        return zone;
    }

}

export {Template}