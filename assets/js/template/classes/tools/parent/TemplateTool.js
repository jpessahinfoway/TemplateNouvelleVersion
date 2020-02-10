class TemplateTool{
    constructor(templateInterface){
        this.interface = templateInterface;
        this.name = this.constructor.name;
        this.parentTool = {};
        this.$location = {} ;
      //  this.jq = null;
        this.icon = null;
        this.visibleOnActivation = true;
       
       // this.iconContainer = $(`<span title="${this.title}" class="${this.name}" data-tool="${this.constructor.name}"></span>`);

        this.activated = false;

        this.$eventLocation={};

       // this.workZone = $('#template__workzone__templateZone');
        this.subTools = {};
        this.adaptableIconsInZone = {
            element : null,
            enclosureDiv:'<div class="icon-action-div"></div>',
            position: null,
            class: null
        };

        console.log(this.subTools)
    //    this.activated = false;
    }



    addSubTool(subTool){
        console.log(subTool)
        if (typeof subTool !== 'object' || ! ( subTool instanceof TemplateTool ) ) throw new Error('Tool non regnized. Please add a correct Tool')

        let subToolName = subTool.constructor.name ;
        if( ! Object. keys( this.subTools ). includes( subToolName ))throw new Error(` ${ subToolName } isn't a SubTool of  ${this.constructor.name}`)

        return this.subTools[subTool.constructor.name] = subTool ;
    }

    addSubTools(...subTools){
        if(typeof subTools === 'undefined' || !Array. isArray(subTools) ) throw new Error('Bad Argument type. Must be an Array')
        subTools. map(subTool => this. addSubTool( subTool ) )
    }


    getCursorPositionInTemplate(e,$el) {
        let offsetElement = this.getOffset($el);
        return {x : e.pageX - offsetElement.left,y: e.pageY - offsetElement.top}
    }

    appendIconInZones(){
        this.initOnZoneHoverFocusIcon()
            Object.keys(this.interface.currentTemplate.getZones()).forEach((zoneIndex,indexIter)=>{

                let currentZone              =   this.interface.currentTemplate.getZone(zoneIndex);
                let minSizeValue             =   Math.min(   ...Object.values(  currentZone.getSize()   ));

                let iconsInZones              =   $(this.adaptableIconsInZone.enclosureDiv).append($(this.adaptableIconsInZone.element));

                iconsInZones.find('i').css('font-size',minSizeValue*0.3);

                // removerIcon.css('box-shadow','inset 0 0 0 1px');
                currentZone.$container.append(iconsInZones);
                if(indexIter === Object.keys(this.interface.currentTemplate.getZones()).length-1){

                }
            })
    }

    initOnZoneHoverFocusIcon(){
        $('.zone').on(`mouseover.${this.constructor.name}`,  (e)=> { $(e.currentTarget).find('.icon-action-div i').addClass('active-icon')  });
        $('.zone').on(`mouseout.${this.constructor.name}`,   (e)=>     {    $(e.currentTarget).find('.icon-action-div i').removeClass('active-icon')   })
    }

    getOffset( $el ) {
        let el = $el.get(0)
        var _x = 0;
        var _y = 0;
        while( el && !isNaN( el.offsetLeft ) && !isNaN( el.offsetTop ) ) {
            _x += el.offsetLeft - el.scrollLeft;
            _y += el.offsetTop - el.scrollTop;
            el = el.offsetParent;
        }
        return { top: _y, left: _x };
    }

    hasParentTool(){
        return typeof this.parentTool ==='object' && this.parentTool instanceof TemplateTool
    }

    getParentTool(){
        return this.parentTool
    }

    isActivated(){
        return this.activated;
    }


    activeTool(active=true){

        if(typeof active !== 'boolean' ){
            return;
        }
        if( this.interface.toolBox[this.name] ) return console.log(`tool ${this.name} not founded in ToolBox. Please add it first`) ;
        this.activated=active;

        if(this.activated && typeof this.parentTool === 'object' && this.parentTool instanceof TemplateTool && !this.parentTool.activated)this.parentTool.activeTool(true)
    }
    activeToolDecorator(boolean,functionToExecuteWhenEventIsTriggered){
       
        if(typeof boolean ==='undefined'){
            this.activeToolDecorator(this.switchState());
            return;
        }
        else{
            if(typeof boolean !=='boolean'){
               
                return
            }
        }
        if(typeof this.$eventLocation === 'undefined' || this.$eventLocation== null){
           
        }
        if(boolean){
            this.activated = true;
            this.interface.activatedTools[this.name] = this;
            //this.activeTool(false);
           if(typeof this.$eventLocation !== 'undefined' && this.$eventLocation!== null ){
              
                   functionToExecuteWhenEventIsTriggered('on')
           }
            this.activated = true;
           if(typeof this.parentTool ==='object' && this.parentTool instanceof TemplateTool && !this.parentTool.activated)this.parentTool.activateTool
           
        }else{
            this.activated=false;
            delete this.interface.activatedTools[this.name];
            if(typeof this.$eventLocation !== 'undefined' && this.$eventLocation!== null ){
                functionToExecuteWhenEventIsTriggered('off')
            }
           
           
        }
    }


}

export {TemplateTool}