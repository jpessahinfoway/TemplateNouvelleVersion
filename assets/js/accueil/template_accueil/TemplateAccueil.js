class TemplateAccueil{

    constructor( stage = 1 ) {

        this._currentStage = stage
        this._$location= {
            actionPannels : $('ul.panels li.panel[data-action]'),
            actionWindows : {
                'create' : $('#modal__template-actions--create'),
                'load'   : $('#modal__template-actions--load'),
            },
            actionCloseModals : $('span.modal__top-menu__actions--close'),
            modalBackground : $('.modal__background'),
            templateActionForm : $('.modal__template-actions__form'),
            templateOrientationChoiceElement : $('input[name=orientation]'),
            templateChoiceInput : $('input[name=template]'),
            templateListsContainer : $('.modal__template-actions__form--template-lists'),
            templateItem : $('.modal__template-actions__form--template-list__item'),
            templateIdContainer : $('input[name=template]')
        }
    }

    onClickOnPanelOpenActionModal(active){
        if(active){
            if(typeof this.$location.actionPannels === 'undefined')throw new Error('invalid value for ationsPanels')
            this.$location.actionPannels.on('click.onClickOnPannelOpenActionModal',(e)=>{
                let panelClicked = $(e.currentTarget);
                let actionChoice = panelClicked.data('action');

                if(typeof actionChoice !== 'string' || ( actionChoice !== 'create' && actionChoice !== 'load') )throw new Error('invalid action')


                let actionWindow = this.$location.actionWindows[actionChoice]
                console.log(actionWindow)

                if(typeof actionWindow =='object' && typeof actionWindow.removeClass ==='function' && typeof this.$location.modalBackground =='object' && typeof this.$location.modalBackground.fadeIn =='function'){
                    actionWindow.removeClass('none')
                    this.$location.modalBackground.fadeIn('fast')
                }
            })
        }
    }

    onClickOnCloseButton(active) {

        if(active) {

            this.$location.actionCloseModals.on('click.onClickOnModalCloseButton',(e)=>{
                let currentDisplayedModal = $("div[class='modal modal--large']").not('.none');
                console.log(currentDisplayedModal)
                currentDisplayedModal.addClass('none')
                this.$location.modalBackground.fadeOut('fast')
            })

        }

    }

    onClickOnTemplateRefreshTemplateInput(active){
        if(active){
            this.$location.templateItem.on('click.onClickOnTemplateRefreshTemplateInput',(e)=>{

                let $templateItemCliqued = $(e.currentTarget);
                let selectedTemplateId = $templateItemCliqued.data('id');

                if(typeof selectedTemplateId !== 'number')throw new Error('invalid object selected')

                if(typeof this.$location.templateActionForm !=='object' || ! (this.$location.templateActionForm instanceof jQuery) )throw new Error('invalid argument for templateActionForm')
                let templateChoiceForm = $templateItemCliqued.parents( this.$location.templateActionForm )

                if(typeof this.$location.templateIdContainer !== 'object' || !(this.$location.templateIdContainer instanceof jQuery ))throw new Error('invalid argument for templateIdContainers')
                let templateIdContainer = templateChoiceForm.find(this.$location.templateIdContainer)
                if(templateIdContainer.length < 1)throw new error('no container for template id founded');

                templateChoiceForm.find(this.$location.templateIdContainer).val(selectedTemplateId);
                $('.activated-element').removeClass('activated-element');
                $templateItemCliqued.addClass('activated-element');

            })
        }

    }
    active(active){
        this.onOrientationChangeReloadTemplate(active)
        this.onClickOnPanelOpenActionModal(active)
        this.onClickOnTemplateRefreshTemplateInput(active)
        this.onClickOnCloseButton(active)
    }

    get $location() {
        return this._$location;
    }

    set $location($location) {
        this._$location = $location;
    }

    onOrientationChangeReloadTemplate(active){
        if(active){
            this.$location.templateOrientationChoiceElement.on('change.onOrientationChangeReloadTemplate',(e) => {
                let checkedElement = $(e.currentTarget);
                let orientationChoice = checkedElement.val();

                if(typeof orientationChoice !== 'string' || (orientationChoice !== 'H' && orientationChoice !== 'V') )throw new Error ('Invalid value for orientation');

                let templateChoiceForm = checkedElement.parents(this.$location.templateActionForm) ;

                let templateListsInForm = templateChoiceForm.find(this.$location.templateListsContainer)

                let templateListToDisplay = templateListsInForm.find(`[data-orientation=${orientationChoice}]`)
                templateListsInForm.find('[data-orientation]').fadeOut( 'fast',()=>templateListToDisplay.fadeIn('fast') )

            })
        }else{
            this.$location.templateOrientationChoiceElement.off('change.onOrientationChangeReloadTemplate');
        }
    }

}

export {TemplateAccueil}