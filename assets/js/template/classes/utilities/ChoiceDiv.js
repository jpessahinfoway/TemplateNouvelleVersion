
class ChoiceDiv{
    constructor(){
        this._onYes = () => console.log('yes');
        this._onNo = () => console.log('false');
        this.$location      = {} ;
        this.$location.container = $('.modal--comfirm');
        this.$location.label = this.$location.container.find('.modal__top-menu__title') ;
        this.$location.content = this.$location.container.find('.modal--comfirm__choices-content') ;
        this.$location.buttons = {
            comfirm : this.$location.container.find('#modal--comfirm__choices-buttons--yes') ,
            cancel : this.$location.container.find('#modal--comfirm__choices-buttons--no') ,
        }
    }

    set label(label){
        this.$location.label.text(label)
    }

    set content($content){
        this.$location.content.html($content)
    }

    get onYes(){
        return this._onYes
    }

    get onNo(){
        return this._onNo
    }

    set onYes(onYes){
        if(typeof onYes !== 'function')throw new Error('Argument must be a function')
        this._onYes = onYes
    }

    set onNo(onNo){
        if(typeof onNo !== 'function')throw new Error('Argument must be a function')
        this._onNo = onNo
    }

    show(){
        this.$location.container.fadeIn()
    }

    hide(){
        this.$location.container.fadeOut()
    }

    reset(){
        this.label = '' ;
        this.content = $('') ;
        this.onYes = () => console.log('yes')
        this.onNo = () => console.log('no')
    }



        setLabel(label) {
            this.choiceLabel = label
            this.$elements.labelZone.text(label)
        }

        addText(text) {
            this.textHTML += text;
            this.$elements.textZone.html(text)
        }


        addTextLine(text){
            this.textHTML += '<br>' + text;
            this.$elements.textZone.html(this.textHTML)
        }

        lineBreak(){
            this.textHTML+= '<br>';
            this.$elements.textZone.html(this.textHTML);
        }


        activeChoiceDiv(active){
            if(active){
                console.log(this.onYes)
                if(typeof this.onYes !== 'function')throw new Error('no function for onYes Event')
                if(typeof this.onNo !== 'function')throw new Error('no function for onNo Event')
                console.log(this.$location.buttons.comfirm)
                this.$location.buttons.comfirm.on('click.activeChoiceDiv',this.onYes)
                this.$location.buttons.cancel.on('click.activeChoiceDiv',this.onNo)
            }else{
                this.$location.buttons.comfirm.off('click.activeChoiceDiv')
                this.$location.buttons.cancel.off('click.activeChoiceDiv')
                this.reset()
            }
        }


        onYes(success){
        this.events.onYes = success

        }

        onNo(success){
        this.events.onNo = success;
        }

        desactive(){
            this.$elements.button.yes.unbind(`click.${this.constructor.name}`);
            this.$elements.button.no.unbind(`click.${this.constructor.name}`);
        }
        // this.iconContainer=$(`<span title="${this.title}" class="${this.name}" data-subtool="${this.constructor.name}"></span>`);


}

export {ChoiceDiv}