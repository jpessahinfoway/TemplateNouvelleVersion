import { Style } from "./Style";
import {StyleProperty} from "./StyleProperty";

class TextStylyzer{
    constructor($location){

        let transform = new StyleProperty({name : 'transform',value : null});

        this.$location = {
            styleForm : $location,
            preview   : null,
            target : null
        };

        this.style = new Style();

        this.propertiesChamps = {
            textAlign : {
                $location : this.$location.styleForm.find('form input[data-type="text-align"]'),
                active:false,
                styleProperty: new StyleProperty({name:'text-align',value:null}),
                defaultValue:'center',
            },
            textDecoration : {
                $location : this.$location.styleForm.find('form input[data-type="text-decoration"]'),
                active:false,
                styleProperty: new StyleProperty({name:'text-decoration',value:null}),
                defaultValue:'none',
            },
            fontWeight : {
                $location : this.$location.styleForm.find('form input[data-type="font-weight"]'),
                active:false,
                styleProperty: new StyleProperty({name:'font-weight',value:null}),
                defaultValue:'normal',
            },
            fontStyle : {
                $location : this.$location.styleForm.find('form input[data-type="font-style"]'),
                active:false,
                styleProperty: new StyleProperty({name:'font-style',value:null}),
                defaultValue:'normal',
            },
            fontFamily : {
                $location : this.$location.styleForm.find('form select[data-type="font-family"]'),
                active:false,
                styleProperty: new StyleProperty({name:'font-family',value:null}),
                defaultValue:'Arial',
                cssTag : 'font-family',
            },
            fontSize : {
                $location : this.$location.styleForm.find('form input[data-type="font-size"]'),
                active:false,
                styleProperty: new StyleProperty({name:'font-size',value:null,unity:'px'}),
                defaultValue:30,
            },
            orientation : {
                $location : this.$location.styleForm.find('form input[data-type="rotate"]'),
                active:false,
                styleProperty: new StyleProperty({name:'rotate',value:null,unity:'deg',before:'(',after:')', parentProperty: transform}),
                defaultValue:0,
            },
            color : {
                $location : this.$location.styleForm.find('form input[data-type="color"]'),
                active:false,
                styleProperty: new StyleProperty({name:'color',value:null}),
                defaultValue:'black',
            },

        }

    }

    handleInput(propertyName,value){
        if(typeof this.propertiesChamps[propertyName] !== 'undefined' && this.propertiesChamps[propertyName].active && this.propertiesChamps[propertyName].$location.length >0){
            this.propertiesChamps[propertyName].$location.each((indexInputElement,inputElement)=> {
                if(inputElement.tagName === 'INPUT'){
                    if(inputElement.type === 'checkbox' || inputElement.type ==='radio')inputElement.checked = (value!==null && value && inputElement.value===value)
                    else inputElement.value = value!==null && value ? value : ''
                }
            })
        }
    }


    handleFormWithNewStyle(style){
        if(typeof style ==="object" && style instanceof Style){
            this.style = style
            Object.keys(style).map(propertyName => {
                let currentStylyzerProperty = this.propertiesChamps[propertyName];
                let currentStyleProperty = style[propertyName];
                if(typeof currentStylyzerProperty  !== 'undefined' && currentStylyzerProperty.active){
                    if(currentStyleProperty instanceof StyleProperty && currentStyleProperty.value !== null)this.handleInput(propertyName,currentStyleProperty.value)
                    else this.handleInput(propertyName,null)
                }
            })
        }
    }

    hydrateStyle(){
        let propertiesChampArray = Object.values(this.propertiesChamps)
        this.style.hydrate(
            propertiesChampArray.filter(propertyChamp => propertyChamp.active && propertyChamp.styleProperty.value !== null).map(activatedStyleProperty => activatedStyleProperty.styleProperty.isSubProperty() ? activatedStyleProperty.styleProperty.parentProperty : activatedStyleProperty.styleProperty)
        )
        console.log(this.style)


    }

    init(){
        Object.keys(this.propertiesChamps).map(propertyName => {
            if(this.propertiesChamps[propertyName].$location.length >0){
                this.propertiesChamps[propertyName].active = true;
                this.propertiesChamps[propertyName].$location.each( (indexPropertyChamp,propertyChampValue) =>
                {
                    if(propertyChampValue.tagName === 'INPUT'){

                        if((propertyChampValue.type === "checkbox" || propertyChampValue.type === "radio")){
                            if(propertyChampValue.checked)this.propertiesChamps[propertyName].styleProperty.setValue(propertyChampValue.value)
                        } else if(propertyChampValue.value.length > 0)this.propertiesChamps[propertyName].styleProperty.setValue(propertyChampValue.value)

                    }
                });
            }
        });
        console.log(this.propertiesChamps)
        this.hydrateStyle()
    }

    setPropertiesChampStylePropertyValue(...propertiesValues) {
        propertiesValues.forEach(propertyValue => {
            let propertiesValuesKeys = Object.keys(propertyValue)
            if (propertiesValuesKeys.length > 1) return;
            if (typeof this.propertiesChamp[propertiesValuesKeys[0]] === undefined) return;
            this.propertiesChamp[propertiesValuesKeys[0]].styleProperty = propertyValue[propertiesValuesKeys[0]]
            console.log(this.propertiesChamp)
            console.log(this.style)
        });
    }



    generateStyle(){

        let styles = Object.values(this.propertiesChamps);

                if(styles.length >0) {
                    let style = new Style()
                    style.hydrate(
                        styles.map(styleProperty => {
                            if (styleProperty.active) return styleProperty.styleProperty.isSubProperty() ? styleProperty.styleProperty.parentProperty : styleProperty.styleProperty;
                        })
                    );
                    return style;
                }else return null
    }



    activeStylyser(active){
        this.activeStyleFormEvents(active)
    }

    setPreviewZone($previewLocation){
        this.$location.preview = $previewLocation
        this.$location.target = $previewLocation.find('*');
    }
    setTarget($target){
        this.$location.target = $target
    }



    setStyleProperty(styleProperty){
       if(typeof styleProperty === 'object' && styleProperty instanceof StyleProperty){
           this.style.addStyleProperties([styleProperty])
           this.$location.target.css(styleProperty.name,styleProperty.propertyWritting)
       }
    }

    activeStyleFormEvents(active){
        if(active){
            Object.keys(this.propertiesChamps).map(styleProperty=>{
                let currentProperty = this.propertiesChamps[styleProperty]
                currentProperty.active = true

                currentProperty.$location.on('change',(e) => {
                    console.log(this)
                    let cssValue;
                   if( ( $(e.currentTarget).is('input[type=radio]') || $(e.currentTarget).is('input[type=checkbox]') ) && ( !$(e.currentTarget).is(':checked') &&  !$(e.currentTarget).is(':selected') ) ){
                       currentProperty.styleProperty.value = '';
                       cssValue = currentProperty.styleProperty.value;
                   }else{
                       cssValue = currentProperty.styleProperty.value =  $(e.currentTarget).val();
                   }
                    currentProperty.styleProperty.buildPropertyWritting()

                    let propertyToApply = currentProperty.styleProperty.isSubProperty()?currentProperty.styleProperty.parentProperty : currentProperty.styleProperty

console.log(propertyToApply)

                    console.log(propertyToApply)
                    this.setStyleProperty(propertyToApply)
                })
            })
        }else{
            this.propertiesChamps.textAlign.$location.off('change')
        }
    }
}

export {TextStylyzer}