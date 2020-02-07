import {Observable} from "./pattern/observer/Observable";

class TemplateMiniature{
    constructor(template,$location){
        this.scale = '0.3';
        this.$location.container = null;
        this.$location.miniature = null;
        this.$miniature = null;
        this.template = template;
        this.zonesSelected = [];
        this.$location = {
            miniature : null,
            container : $location
        }
        this.zonesSelectedUpdatedObservable = new Observable()
        this.init();
        this.active(true) ;
        this.onClickselectZoneInMiniature(true);
    }


    createMiniature(){
        this.$location.miniature =$(`<div class='template-miniature'></div>`);
        this.$location.miniature.css( "maxWidth", '100%' );
        return this
    }

    updateMiniatureSize(){
        this.scale = this.$location.miniature.width()/this.template.attr.size.width
        let newHeight = this.template.attr.size.height * this.scale ;
        this.$location.miniature.height(newHeight)
    }
    onResizeOfBrowzerUpdateMiniatureSize(active){
        if(active){
            $(window).on('resize.OnResizeOfBrowzerUpdateMiniatureSize',()=>{
                this.updateMiniatureSize()
            })
        }else{
            $(window).off('resize.OnResizeOfBrowzerUpdateMiniatureSize');
        }
    }
    refreshMiniature(){

    }

    active(active){
        this.onResizeOfBrowzerUpdateMiniatureSize(active)
    }

    init(){
        this.createMiniature()
            .append()
            .updateMiniatureSize()
    }

    calculSize(){

    }
    append($location) {
        this.$location.container.append(this.$location.miniature)
        return this
    }

    resetSelectedZone(){
        this.zonesSelected = [];
    }

    resetMiniature(){
        this.$miniature.empty() ;
        return this;
    }


    addZones(zoneTypeArray=['zone','price','text','media']){
        let zonesHtml = "";
        Object.values(this.template.getZones()).map(zone => {
            if(zoneTypeArray.includes(zone.type)){
                let currentZoneSize = {};
                let currentZonePosition = {}
                console.log(zone.size)
                Object.keys(zone.size).map(sizeKey => currentZoneSize[sizeKey] = zone.size[sizeKey] * this.scale);
                Object.keys(zone.position).map(positionKey => currentZonePosition[positionKey] = zone.position[positionKey] * this.scale);

                zonesHtml += "<div class='zone-miniature";
                if (zone.type !== 'zone') zonesHtml += ` ${zone.type}-zone`;
                console.log(this.template._attr.size.width)
                console.log(currentZoneSize.width)

                zonesHtml +=
                    `' data-type='${zone.type}`+
                    `' data-zone='${zone.identificator}'` +
                     " style='"+
                     " position : absolute;"+
                     `width :  calc(100% / (${this.template._attr.size.width} / ${zone.size.width}));`+
                     `height : calc(100% / (${this.template._attr.size.height} / ${zone.size.height}));`+
                     `top : calc(${zone.position.top} * (100% / ${this.template._attr.size.height}));`+
                     `left : calc(${zone.position.left} * ( 100% / ${this.template._attr.size.width}));`+
                     "'><div class='wrapper'>&nbsp;</div></div>"
            }

        })

        this.$miniature.html(zonesHtml)
    }

    onClickselectZoneInMiniature(active){
        if(active){
            this.$location.container.on('click.onClickselectZoneInMiniature','.zone-miniature',(e)=>{
                let currentZone = $(e.currentTarget)
                if(this.zonesSelected.includes(currentZone.data('zone'))) {
                    currentZone.removeClass('selected-style--blue')
                    this.zonesSelected.splice(this.zonesSelected.indexOf(currentZone.data('zone')),1);
                    console.log(this.zonesSelected)
                }else{
                    this.zonesSelected.push(currentZone.data('zone'));
                    currentZone.addClass('selected-style--blue')
                }
                this.zonesSelectedUpdatedObservable.notify(this.zonesSelected)
            })
        }

    }

    resetZonesSelected(){
        this.zonesSelected = []
    }
    setLocation($location){
        this.$location.container = $location
    }
    append($location=null){

        let location=null
        if($location!==null){
            this.setLocation($location) ;
            location = $location
        }else{
            location=this.$location.container
        }
        console.log(location)

        location.append(this.$miniature)
        this.$location.miniature = this.$miniature
    }

}

export {TemplateMiniature}