import {ZoneAssociationSubTool} from "./parent/ZoneAssociationSubTool.js";
import {ChoiceDiv} from "../../../utilities/ChoiceDiv.js";

class ZoneAssociatorSubTool extends ZoneAssociationSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);
        //stock les emplacements des evenements js
        this.$eventLocation.click =  {
            associateOnClick : {
                inMenuWindow : $('div.area'),
                inInterface   : $('body')
            }
        };
        //descirption de la zone
        this.description = 'Associer une zone';

        this.$location.zoneLabelAreas = {
                available : $('div.area#available-zones'),
                loaded : $('div.area#loaded-zones')
            };
        this.$location.zoneTextContentSpan = null;
        this.$location.menus =  {
            toolsMenus : $('.template-toolsmenu') ,
            selectionInInterfaceChoiceMenu : $('.association-zones--buttons')
        };
        this.$location.buttons = {
            selectionInInterface : $('#selection-in-interface--btn'),
            inInterface : {
                comfirmAssociation : $('.association-zones--buttons button.comfirm'),
                cancelAssociation : $('.association-zones--buttons button.cancel')
            }
        };
        this.associatedActionsInInterfaceMode = {};

        this.labelZoneClicked = {
            id : null,
            instance: null
        };
        this.typeTraduction = {
            price : 'prix',
            text  : 'texte',
            media : 'media'
        };
        this.isZoneSelectionInInterfaceModeActivated = false;

    }

    activeTool(boolean){
        super.activeTool(boolean,this.onActivation)
    }

    onActivation(){
        console.log(this)
       this.buildConfirmationAssociationInInterfaceDiv();
        this.onClickactiveInterfaceMode(true)
        this.associateInWindowOnClickEvent();
        this.$location.zoneTextContentSpan = this.currentZone.$contentSpan
        this.activeZoneAssociatorMode( 'inWindow' , true )
        //this.displayConfirmationDivOnZoneClick(true)
        this.onComfirmClickdisplayInterfaceAssociationConfirmation(true)
        this.onCancelClickLeaveAssociationInInterfaceMode(true)
    }

    activeZoneAssociatorMode(mode,activated){
        if(mode === 'inInterface'){
            if(activated){
                this.associatedActionsInInterfaceMode = {};
                this.buildInInterfaceAssociationDOM();
                this.$location.menus.toolsMenus.addClass('none')
                this.$location.menus.selectionInInterfaceChoiceMenu.fadeIn('fast');
                this.isZoneSelectionInInterfaceModeActivated = true;
            }else{
                this.$location.menus.selectionInInterfaceChoiceMenu.fadeOut('fast', () => {
                    this.$location.menus.toolsMenus.removeClass('none')
                    this.unbuildInInterfaceAssociationDOM()
                });
                this.$location.menus.selectionInInterfaceChoiceMenu.fadeOut('fast');
                this.$location.buttons.inInterface.comfirmAssociation.prop( 'disabled' , true );
            }
        }else if(mode === 'inWindow'){
            if(activated){
                this.buildZonesLabelseAreas();
                this.activeZoneAssociatorMode( 'inInterface' , false );
            }
        }
    }

    onCancelClickLeaveAssociationInInterfaceMode(active){
        if(active){
            this.$location.buttons.inInterface.cancelAssociation.on('click'+ this.constructor.name, () =>{
                console.log('canceled')
            })
        }else{
            this.$location.buttons.inInterface.cancelAssociation.off('click'+ this.constructor.name)
        }
    }

    onClickactiveInterfaceMode(active){
        if(active){
            this.onClickactiveInterfaceMode(false);
            this.updateAssociatedChangesPreviewInInterfaceOnClickEvent(true);
            this.$location.buttons.selectionInInterface.on('click.'+this.constructor.name, () => {
                this.activeZoneAssociatorMode('inInterface',true)
            })
        }else{
            this.updateAssociatedChangesPreviewInInterfaceOnClickEvent(false);
            this.$location.buttons.selectionInInterface.off('click.'+this.constructor.name)
        }

    }

    hideZonesThatCantBeAssociated(hide=true){
        if(hide){
            $(".zone").each(( index , zone) => {
                if( $(zone).data('type') === 'zone' ) $(zone).fadeOut()
            });
        }else{
            $('.zone').fadeIn()
        }
    }

    buildConfirmationAssociationInInterfaceDiv(){

        this.choiceDiv = new ChoiceDiv();
        this.choiceDiv.setLabel('Association de zone de zones');

        this.choiceDiv.onYes(()=>{
            Object.keys(this.associatedActionsInInterfaceMode).forEach(zoneIndex=>{
                if(this.associatedActionsInInterfaceMode[zoneIndex].associated){
                    this.currentZone.associateZone(this.associatedActionsInInterfaceMode[zoneIndex].instance)
                    console.log(this.currentZone.associatedZone)
                }else{
                    this.currentZone.unAssociateZone(this.associatedActionsInInterfaceMode[zoneIndex].instance)
                    console.log(this.currentZone.associatedZone)
                }
            })
            this.activeZoneAssociatorMode('inWindow',true)
        });

        this.choiceDiv.onNo(()=>{

            console.log('no')
        });

    }

    onComfirmClickdisplayInterfaceAssociationConfirmation(active){
        if(active){
      //      this.displayConfirmationDivOnZoneClick(false);
            this.$location.buttons.inInterface.comfirmAssociation.on(`click.${this.constructor.name}`,(e)=>{
                console.log(this.associatedActionsInInterfaceMode)
                this.choiceDiv.addText(`Modifications des associations a la zone ${this.currentZone.identificator} éffectuées :`);
                this.choiceDiv.lineBreak();
                Object.keys(this.associatedActionsInInterfaceMode).forEach(zoneAssociatedIndex => {
                    let action = this.associatedActionsInInterfaceMode[zoneAssociatedIndex].associated ? 'Association' : 'Desassociation' ;
                    this.choiceDiv.addTextLine(action + `de la zone ${this.associatedActionsInInterfaceMode[zoneAssociatedIndex].instance.identificator}`);
                });
                this.choiceDiv.lineBreak();
                this.choiceDiv.addTextLine('Comfirmer ?')
                this.choiceDiv.show()
            })
        }else{
            this.$location.buttons.inInterface.comfirmAssociation.off('click.'+this.constructor.name)
        }

    }


    updateAssociatedChangesPreviewInInterfaceOnClickEvent(active=true){
        if(active){
            this.$eventLocation.click.associateOnClick.inInterface.on( 'click.' + this.constructor.name, '.zone' , ( e ) => {

                this.labelZoneClicked.id = $(e.currentTarget).data('zone');
                this.labelZoneClicked.instance = this.interface.currentTemplate.getZone(this.labelZoneClicked.id);

                if(this.labelZoneClicked.instance === this.currentZone)return;

                if(this.$location.buttons.inInterface.comfirmAssociation.is( ':disabled' )) this.$location.buttons.inInterface.comfirmAssociation.prop( 'disabled' , false );

                let activateThisZone = !$(e.currentTarget).hasClass('associated');

                console.log(activateThisZone)
                console.log(this.currentZone.isAssociatedTo(this.labelZoneClicked.instance))
                console.log(Object.keys((this.associatedActionsInInterfaceMode)));
                console.log(Object.keys(this.associatedActionsInInterfaceMode).includes(this.labelZoneClicked.id.toString()))

                console.log(this.labelZoneClicked.id)
                if(
                    (activateThisZone && this.currentZone.isAssociatedTo(this.labelZoneClicked.instance) && Object.keys(this.associatedActionsInInterfaceMode).includes(this.labelZoneClicked.id.toString()))
                    ||
                    (!activateThisZone && !this.currentZone.isAssociatedTo(this.labelZoneClicked.instance) && Object.keys(this.associatedActionsInInterfaceMode).includes(this.labelZoneClicked.id.toString()))
                ) {
                    delete this.associatedActionsInInterfaceMode[this.labelZoneClicked.id]
                }else{
                    this.associatedActionsInInterfaceMode[this.labelZoneClicked.instance.identificator]={
                        instance : this.labelZoneClicked.instance,
                        associated : activateThisZone
                    }
                }

                if(activateThisZone){
                    $(e.currentTarget).addClass('associated')
                    this.labelZoneClicked.instance.putTextInZone('associee')
                }
                else {
                    $(e.currentTarget).removeClass('associated')
                    this.labelZoneClicked.instance.putTextInZone('')
                }

                console.log(this.associatedActionsInInterfaceMode)
            })
        }else{
            this.$eventLocation.click.associateOnClick.inInterface.off('click.',+this.constructor.name)
        }
    }

    associateInWindowOnClickEvent(active=true){
        if(active){

            this.associateInWindowOnClickEvent(false)
            console.log('ici')
            this.$eventLocation.click.associateOnClick.inMenuWindow.on('click.' + this.constructor.name, '.preview-label' , ( e ) => {
                let $location, $destination,associateCondition, unAssociateCondition;
                $location = $(e.currentTarget).parents('.area');

                this.labelZoneClicked.id = $(e.currentTarget).data('zone');
                this.labelZoneClicked.instance = this.interface.currentTemplate.getZone(this.labelZoneClicked.id);

                associateCondition =  $location.attr( 'id' )  === 'available-zones';
                unAssociateCondition = $location.attr('id') === 'loaded-zones';

                if ( associateCondition ) {
                    $destination = $('div.area#loaded-zones');

                    this.labelZoneClicked.instance.$location.addClass('associated')
                    this.currentZone.associateZone(this.labelZoneClicked.instance)
                    this.labelZoneClicked.instance.putTextInZone('Associee')

                }else if ( unAssociateCondition ) {
                    $destination = $('div.area#available-zones');

                    this.labelZoneClicked.instance.$location.removeClass('associated')
                    this.currentZone.unAssociateZone(this.labelZoneClicked.instance)
                    this.labelZoneClicked.instance.putTextInZone('')

                }else return;

                this.switchZoneLabel(this.labelZoneClicked.instance,$location,$destination)
            } );
        }else{
            console.log('desactivated')
            this.$eventLocation.click.associateOnClick.inMenuWindow.off('click.'+this.constructor.name);
        }
    }

    updateInterfaceAssociationChange(){
        this.$eventLocation.click.associateOnClick.inInterface.on( 'click.' + this.constructor.name, '.zone' , ( e ) => {

            this.labelZoneClicked.id = $(e.currentTarget).data('zone');
            this.labelZoneClicked.instance = this.interface.currentTemplate.getZone(this.labelZoneClicked.id);

        })
    }


    buildInInterfaceAssociationDOM(){
        this.hideZonesThatCantBeAssociated(true);
        this.currentZone.putTextInZone('Zone en cours');

        Object.values(this.interface.currentTemplate.getZones()).forEach(zone=>{

            if(zone.isAssociatedTo(this.currentZone)){
                zone.$location.addClass('associated');
                zone.putTextInZone('associee');
            }
        })
    }

    unbuildInInterfaceAssociationDOM(){
        $('.zone').each(( indexZone , zone ) => {
            let zoneId = $(zone).data('zone')
            this.interface.currentTemplate.getZone(zoneId).putTextInZone('')
            $(zone).removeClass('associated').fadeIn();
        })
    }


    switchZoneLabel(zone,$location,$destination){

        let $zoneToSwitch = $location.find(`[data-zone=${zone.identificator}]`);
        let clonedZoneLabelElement = $zoneToSwitch.clone();

        clonedZoneLabelElement.css('display','none');

        $zoneToSwitch.fadeOut('fast', function(){

            this.remove();
            $destination.append(clonedZoneLabelElement);
            clonedZoneLabelElement.fadeIn('fast');
        })
    }

    buildZonesLabelseAreas(){

        let availableZoneLabelsHTML = '' ;
        let associatedZoneLabelsHTML = '';

        Object.values(this.interface.currentTemplate.getZones()).map(zone=>{

            if(zone.type !== 'zone' && zone.identificator !== this.currentZone.identificator){

                let zoneName = this.typeTraduction[zone.type].replace(/^./,this.typeTraduction[zone.type].charAt(0).toUpperCase());
                zoneName += "-" + zone.identificator;

                if( !this.currentZone.isAssociatedTo(zone) ){
                    availableZoneLabelsHTML += `<span class="${zone.type}-preview preview-label" data-zone="${zone.identificator}">${zoneName}</span>`
                } else {
                    associatedZoneLabelsHTML+= `<span class="${zone.type}-preview preview-label" data-zone="${zone.identificator}">${zoneName}</span>`
                }

                console.log(availableZoneLabelsHTML)
                console.log(associatedZoneLabelsHTML)

            }
        });

        console.log(this.$location.zoneLabelAreas.associated)
        this.$location.zoneLabelAreas.available.html(availableZoneLabelsHTML)
        this.$location.zoneLabelAreas.loaded.html(associatedZoneLabelsHTML)
    }


}
export {ZoneAssociatorSubTool}