import {PermanentTool} from "./parent/PermanentTool";
import _ from "lodash";
import {Template} from "../Template";
import {Zone} from "../Zone";
var stringify = require('json-stringify-safe');

class RegisterTemplateTool extends PermanentTool{

    constructor(templateInterface) {

        super(templateInterface);
        this.$location.backgroundModal = $('.modal__background') ;
        this.$location.saveAsModal =  $('#modal__template-register_as') ;
        this.$location.saveAsForm =  $('#modal__template-register_as').find('form');
        this.$location.newTemplateNameInput = this.$location.saveAsModal.find('input[name=template-name]') ;

        this.activeTool(true);

        this._existingTemplatesNameArray = [];

        this.getAllTemplateName();
    }

    activeTool(active){
        super.activeTool(active)
        if(active){
            this.saveTemplateOnClick(true)
            this.saveAsTemplateOnClick(true)
        }else{
            this.saveTemplateOnClick(false)
            this.saveAsTemplateOnClick(false)
        }
    }

    saveTemplateOnClick(active){
        if(active) $('.template-menu li#save').on('click.saveTemplateOnClick',this.saveTemplate.bind(this))
        else  $('.template-menu li#save').off('click.saveTemplateOnClick') ;
    }

    saveAsTemplateOnClick(active){
        if(active) $(".template-menu li#save_as").on('click.saveAsTemplateOnClick',this.saveAsTemplate.bind(this))
        else  $(".template-menu li#save_as").off('click.saveAsTemplateOnClick') ;
    }

    saveAsTemplate(){
        console.log("save as on click");

        let templateZones = Object. values( this. interface. currentTemplate. getZones() ) ;
        if( templateZones. length > 0 )
        {
            this.openModal();

            let newTemplateName = null ;
            this.$location.newTemplateNameInput.on('input', (e) => {
                let $newTemplateNameInput = $(e.currentTarget);
                newTemplateName = $newTemplateNameInput.val();

                // if(!this.isTemplateNameAlreadyExist(newTemplateName))
                // {
                //
                //     // $(".modal-wrapper .container form input[name='name']").css("color", "");
                //     // // $(".modal-wrapper .container form i").remove();
                //     // $(".modal-wrapper .container form span#error").text("").css("color", "");
                // }
            });

            this.$location.saveAsForm.on('submit', e => {
                e.preventDefault();

                // $(".modal-wrapper .container form span#error").text("").css("color", "");
                // $(".modal-wrapper .container form i").remove();
                if(!this.isTemplateNameAlreadyExist(newTemplateName)) {

                    this.interface.currentTemplate.name = newTemplateName ;

                    this.saveTemplate().done( template => {
                        console.log(template);debugger;
                        this.closeModal();
                        return this.reloadCurrentPage( template );
                    });

                }
            });

        }
        else alert("Pas de zones !")
    }

    saveTemplate() {
        if(Object.values( this.interface.currentTemplate.getZones() ).length > 1 )
        {

            let templateDataToImport = {
                name : this.interface.currentTemplate.name,
                attr : this.interface.currentTemplate.attr
            };

            let zonesToExport = Object.values(this.interface.currentTemplate.getZones()).map( (zone) => {
                return Object.assign({}, { size: { ...zone. size } , zIndex : zone.zIndex ,  name : zone.name , position : { ...zone. position }, type : zone. type , identificator : zone. identificator , childrens : Object.keys(zone.zoneChildrens) })
            } );

            $.ajax({
                type: "POST",
                url: `/template/stage/${this.interface.stage}/register`,
                data: {
                    zones: JSON.stringify(zonesToExport),
                    template: JSON.stringify(templateDataToImport)
                },
            }).done( template => {
                console.log('success');
                this.interface.currentTemplate.lastModifcation = new Date(template.modification_date.date) ;

                const currentDate = `Dernière modification : ${ (this.interface.currentTemplate.lastModifcation.getHours() < 10 ? '0' : '') + this.interface.currentTemplate.lastModifcation.getHours() }:${ (this.interface.currentTemplate.lastModifcation.getMinutes() < 10 ? "0" : "") + this.interface.currentTemplate.lastModifcation.getMinutes() } - ${this.interface.currentTemplate.lastModifcation.getDate()}/${this.interface.currentTemplate.lastModifcation.getMonth() +1}/${this.interface.currentTemplate.lastModifcation.getFullYear()}`;

                $("#currentTemplateLastModificationDate").text(currentDate);

                 this._existingTemplatesNameArray.push(template.name);
                 return template
            }).fail( () =>{
                return new Error('Impossible to save Template') ;
            })

        }
        else return Promise.reject( alert( "Pas de zones !" ) ) ;
    }

    getAllTemplateName() {

        $.ajax({
            type: "POST",
            url: `/get/all/stage/${this.interface.stage}/template/name`,
            data: {},
            async: false
        })
            .done( (names) => {

                $.each(names,(index, name)=>{

                    if($.inArray(name, this._existingTemplatesNameArray) === -1)
                        this._existingTemplatesNameArray.push(name);

                });

            } )
            .fail( (errorType, errorStatus, errorThrown ) => {

                console.error(errorType, errorStatus, errorThrown);

            } );

    };

    openModal() {

        this.$location.saveAsModal.removeClass("none");
        this.$location.backgroundModal.removeClass('none');
        // $(".close-modal").on('click', this.closeModal);

    }

    closeModal() {

        $(".modal-wrapper .container form input[name='name']").val("");

        $(".modal").css("display", "none");

    }

    isTemplateNameAlreadyExist(name) {
        return this._existingTemplatesNameArray.includes(name) ;
        // if(this._existingTemplatesNameArray.includes(name) !== -1)
        // {
        //
        //     const errorSpan = $(".modal-wrapper .container form span#error");
        //
        //     if($("form#saveAsForm div").children("i").length < 1)
        //         $("<i>", { class: 'fa fa-exclamation-circle' }).css({"color": "red", "font-size": "15px"}).insertBefore(errorSpan);
        //
        //     errorSpan.text(`Le template '${name}' existe déjà !`).css("color", "red");
        //
        //     $(".modal-wrapper .container form input[name='name']").css("color", "red");
        //
        //     $(".modal-wrapper .container form input[name='name']").focus();
        //
        //     return true;
        // }
        //
        // return false;

    }

    // checkIfFormFieldIsEmpty(fieldName) {
    //
    //     const val = $(`form#saveAsForm input[name='${fieldName}']`).val();
    //
    //     const input = $(`form#saveAsForm input[name='${fieldName}']`);
    //
    //     if (val === "" || val.length === 0)
    //     {
    //
    //         if(input.attr("type") !== "hidden")
    //         {
    //
    //             input.css("outline", "2px solid red");
    //
    //             if($("form#saveAsForm div").children("i").length < 1)
    //                 $("<i>", {
    //                     class: 'fa fa-exclamation-circle'
    //                 }).css({"color": "red", "font-size": "15px"}).insertBefore(input);
    //
    //             $("form#saveAsForm").parent().find("span#error").text(`Merci de remplir ce champ !`).css('color', 'red');
    //
    //         }
    //         else
    //         {
    //             $("form#saveAsForm").parent().find("span#error").text(`Le formulaire n'est pas valide !`).css('color', 'red');
    //         }
    //
    //         return true;
    //     }
    //
    //     return false;
    //
    // }

    reloadCurrentPage(template) {

        let form = $("<form>", {
            action:  `/template/stage/${this.interface.stage}/load`,
            method: 'post'
        });

        $('<input>', {
            type: 'hidden',
            name: 'template',
            value: template.id
        }).appendTo(form);

        $('<input>', {
            type: 'hidden',
            name: 'orientation',
            value: template.orientation
        }).appendTo(form);

        $('body').append(form);
        form.submit();

    }

}

export {RegisterTemplateTool};