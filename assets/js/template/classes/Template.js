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

    get attr(){
        return this._attr
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
            this.$container.width(size.width) ;
            this._attr.size.width = size.width;
        }
        if ( typeof size.height ==='number'){
            this.$container.height(size.height) ;
            this._attr.size.height = size.height;
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

        let allZonesIndexes = Object.keys(this.zones)
        for(let i=0; i<=allZonesIndexes.length ;i++){
            if(! allZonesIndexes .includes( i ) ) {
                zone.identificator = i ;
                zone.zIndex = i;
            }
        }

        this._zones[zone.identificator] = zone;
 
        this.$container.append(zone.$container)
    }
    // set size({width=null,height=null}){
    //     if(width!==null){
    //         this._attr.size.width=width
    //         this.$container.width(width)
    //     };
    //     if(height!==null){
    //         this._attr.size.height=height
    //         this.$container.height(height)
    //     };
    // }

    set orientation (orientation){
        console.log(orientation);
            if  (orientation !== 'H'   &&   orientation !== 'V')    return;

            this._attr.orientation  =  orientation;
            debugger;

            orientation  ===  'H' ?  this.size = {width:1280,height:720}  :  this.size = {width:720,height:1280}
    }

    set name(name){
        console.log(name);debugger;
     if (typeof name !== 'string' ) return  ;
            this._name=name;
            this.id = name
        }


    get name() {
        return this._name;
    }

    set id(id){
    if(typeof id !== 'string') return  ;
        if(name!==null)this._attr.id='#'+id;
        this.$container.attr('id',id)
    }

    show(){
        this.$container.fadeIn()
    }

    createNewZone({position=null,size=null,type=null, id=null}={}){

        let zone = new Zone() ;

        if ( position !== null ) zone. position = position ;
        if ( size !== null ) zone. size = size ;
        if ( type !== null ) zone. type = type ;
        if ( id !== null ) zone. id = id ;

        zone.attachToTemplate(this) ;

        console.log(zone)
        debugger;
        return zone;
    }

    // createNewZone(position={top:0,left:0},size={width:0,height:0},zoneType='zone', identificator=null){
    //     console.log(position)
    //     let zoneId = null;
    //
    //     let zIndex = Object.keys(this._zones).length;
    //
    //     for(let i=0; i<=Object.keys(this._zones).length+1;i++){
    //         if(!(i in this._zones)){
    //             zoneId=i;
    //             break;
    //         }else{
    //             console.log(this._zones)
    //         }
    //     }
    //
    //     let zone = new Zone({position:position,size:size,type:zoneType});
    //
    //     zone.create();
    //     zone.position = position;
    //     zone.zIndex = zIndex;
    //     zone.setIdentificator(zoneId);
    //     zone.attachToTemplate(this);
    //     return zone;
    // }

}

export {Template}