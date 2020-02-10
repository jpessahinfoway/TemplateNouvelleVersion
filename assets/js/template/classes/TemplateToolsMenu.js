import {Observable} from "./pattern/observer/Observable";
import {TemplateTool} from "./tools/parent/TemplateTool";

class TemplateToolsMenu{
    constructor(name,$location){
        this.name = name ;
        this.$location = {
            menu : $location
        };
        this.clickOnToolObservable = new Observable();
        this.toolsList = {}
    }

    activeMenu(active){
        this. toggleSubMenuOnMouseHover(active)
        this. onClickOnIconActiveTool(active)
    }

    toggleSubMenuOnMouseHover(active){
        if(active){
            this.$location.menu
                .on('mouseover.toggleSubMenuOnMouseHover','.tool' ,e => { $(e.currentTarget).children('ul.slider-menu').stop().slideDown('fast') })
                .on('mouseout.toggleSubMenuOnMouseHover','.tool', e => { $(e.currentTarget).children('ul.slider-menu').stop().slideUp('fast') });
        }else{
            this.$location.menu
                .off('mouseover.toggleSubMenuOnMouseHover')
                .off('mouseout.toggleSubMenuOnMouseHover' )
        }
    }

    activeIcons($icons,active){
        active ? $icons.addClass('active-tool') : $icons.removeClass('active-tool')
    }
    onClickOnIconActiveTool(active){
        if ( active ){
            this.$location.menu.on('click.activeToolBox','.tool',(e)=>{

                e.stopPropagation() ;

                let $clickedTool = $(e.currentTarget);
                let selectedToolName = $clickedTool .data( 'tool' );

                if ( typeof selectedToolName === 'undefined' ) throw new Error( 'invalid dataset' ) ;

                let selectedTool = this.toolsList[ selectedToolName ] ;

                if(typeof selectedTool !=='object' || !( selectedTool instanceof TemplateTool) ) throw new Error('tool non recognized') ;

                this.clickOnToolObservable .notify( selectedTool ) ;

                $('.tool.active-tool') .removeClass('active-tool') ;
                if(selectedTool .isActivated())  this .activeIcons( $( `.tool[data-tool=${selectedTool.name}` ) , true ) ;

                if( selectedTool .hasParentTool() ){
                    let parentTool = selectedTool .getParentTool() ;
                    if ( parentTool .isActivated() ) $(`.tool[data-tool=${parentTool.name}`) .addClass('active-tool') ;
                }
            })
        } else {
            this.$location.menu.off('click.activeToolBox')
        }
    }

    attachTool(tool){
        Object .values(tool.subTools). filter( tool => typeof tool === 'object' && tool instanceof  TemplateTool ). forEach(subTool => this.attachTool(subTool)  )
        if(this.$location.menu.find(`[data-tool=${tool.name}]`).length <1)return console.log(`no data-tool for the tool ${tool.name} founded please add once first `)
        this.toolsList[tool.name] = tool
    }
    detachTool(tool) {
        delete this.toolsList[tool.name];
    }

}

export {TemplateToolsMenu}