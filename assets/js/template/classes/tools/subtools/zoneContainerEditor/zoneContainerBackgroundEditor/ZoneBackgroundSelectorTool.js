import {ZoneContainerBackgroundEditorSubTool} from "./parent/ZoneContainerBackgroundEditorSubTool";
import {BackgroundContent} from "../../../../zoneContents/BackgroundContent";


class ZoneBackgroundSelectorTool extends ZoneContainerBackgroundEditorSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);

        this.title = 'Choisissez un fond';

        this.$location.container = $('.modal.background-editor .right-container #list-background')
        this.$location.colorChoice = this.$location.container.find('#select-background__choice--colorChoice');
        this.$location.imageChoice = this.$location.container.find('#select-background__choice--imageChoice');
        this.$location.comfirmButton = this.$location.container.find('button.comfirm')
        this.backgroundsSelected = {
            image : null,
            color : null
        }

        //this.functionToExecuteOnSelectedZone = this.setMediaToSelectedZone;
    }

    activeTool(boolean){
        super.activeTool(boolean,this.onActivation,this.onDisactivation)
    }


    isActivated($target){
        return !$target.parents('.disabled-element').length >0
    }

    getActivatedValue($location){
        let background = null;
        if($location.attr('id') === this.$location.colorChoice.attr('id')) background = new BackgroundContent($location.find('input[type=color]').val(),'color')
        else if($location.attr('id') === this.$location.imageChoice.attr('id')){
            let mediaSelected = $location.find('.media.selected-element img')
             background = mediaSelected.length > 0 ? new BackgroundContent($location.find('.media.selected-element img').attr('src').split('/').pop(),'image') : null;
        }

        return background
    }

    onClickComfirm(active){
        if(active){
            this.$location.comfirmButton.on('click.onClickComfirm',()=>{
                this.parentTool.onComfirmSetBackgroundtoZone(this.backgroundsSelected)
            })
        }else{
            this.$location.comfirmButton.off()
        }

    }

    onClickSetBackgroundImage(active){
        if(active){
            this.$location.imageChoice.on('click.onClickSelectBackground','.media', (e)=> {
                let selectedElement = $(e.currentTarget);
                if(!this.isActivated(selectedElement)) return;
                this.$location.container.find('.media').removeClass('selected-style--blue');
                selectedElement.addClass('selected-style--blue');
                let media = $(e.currentTarget).children('img').attr('src').split('/').pop();
                this.backgroundsSelected['image'] = new BackgroundContent(media,'image',`url("/build/backgrounds/${media}")`);
            })
        }else{
            this.$location.imageChoice.off('click.onClickSelectBackground')
        }
    }

    onClickSetBackgroundColor(active){
        if(active){

            this.$location.colorChoice.on('change.onClickSelectBackground','input[type=color]:enabled',(e)=> {
                let selectedElement = $(e.currentTarget);
                if(!this.isActivated(selectedElement)) return;
                this.backgroundsSelected['color'] = new BackgroundContent(selectedElement.val(),'color');
            })
        }else{
            this.$location.colorChoice.off('click.onClickSelectBackground');
        }
    }

    onChangeCheckboxs(active){
        if(active){
            this.$location.container.find('input[type=checkbox]').on('change.onClickenable',(e)=>{
                let $target = $(e.currentTarget);
                let $choiceContent = $target.parents('.radios-group').next('.select-background__choice--content');
                if($target.is(':checked')){
                    this.backgroundsSelected[$target.val()]=this.getActivatedValue($target.parents('.select-background__choice'));
                    $choiceContent.removeClass('disabled-element');
                    $choiceContent.find('input').prop('disabled',false);
                }else{
                    this.backgroundsSelected[$target.val()]=null;
                    $choiceContent.addClass('disabled-element');
                    $choiceContent.find('input').prop('disabled',true);
                }

            })
        }else {
            this.$location.container.find('input[type=checkbox]').off('change.onClickenable')
        }
    }

    onDisactivation(){
        this.onChangeCheckboxs(false)
        this.onClickSetBackgroundImage(false)
        this.onClickSetBackgroundColor(false)
        this.onClickComfirm(false)
       /* this.onClickOnSelectedBackground(false)
        this.mediaSelected=null*/
    }
    onActivation(){
        this.onChangeCheckboxs(true)
        this.onClickSetBackgroundImage(true)
        this.onClickSetBackgroundColor(true)
        this.onClickComfirm(true)
      //  this.onClickOnSelectedBackground(true)
        /*  console.log(this)
          console.log(this.parentTool)
          this.parentTool.templateMiniature.resetMiniature().addZones(['media']);
          this.onClickOnSelectedMedia(true)*/
    }
}

export {ZoneBackgroundSelectorTool}