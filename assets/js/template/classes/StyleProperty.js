class StyleProperty{
    constructor({name = null, value=null, unity=null, before=null, after=null, cssValue=null, parentProperty=null} = {}){
        this.name = name;
        this.subProperties = {};
        this.value = value;
        this.unity = unity;
        this.before = before;
        this.after = after;
        this.setParentProperty(parentProperty);
        this.propertyWritting = this.buildPropertyWritting();
    }

    setParentProperty(parentProperty){
        if(parentProperty instanceof StyleProperty){
            this.parentProperty = parentProperty;
            parentProperty.addSubProperty(this);
            return parentProperty
        }else return null
    }

    isSubProperty(){
        return ( this.parentProperty instanceof StyleProperty && Object.values(this.parentProperty.subProperties).indexOf(this)> -1 )
    }

    addSubProperty(subPropertie){
        if(subPropertie instanceof StyleProperty){
            this.subProperties[subPropertie.name]=subPropertie
        }
    }

    setValue(value){
        this.value = value;
        this.buildPropertyWritting()
    }

    buildPropertyWritting(){
        let subPropertiesArray = Object.values(this.subProperties)

        this.propertyWritting = subPropertiesArray.length > 0? null : '';

        if(typeof this.value !== undefined &&  this.value!==null || subPropertiesArray.length>0) {

            if (this.parentProperty !== null && typeof this.parentProperty !== 'undefined') {
                this.propertyWritting += this.name
            }

            if (Array.isArray(subPropertiesArray) && subPropertiesArray.length > 0) {
                subPropertiesArray.map((subProperty, index) => {
                    if(subProperty.propertyWritting !== '' && subProperty.propertyWritting!=null){
                        if(this.propertyWritting === null){
                            this.propertyWritting = '';
                        };
                        this.propertyWritting += subProperty.propertyWritting;
                        if (index !== subPropertiesArray.length - 1) this.propertyWritting += ',';
                    }

                });
            } else {
                if (typeof this.value !== undefined && this.value !== null)
                    if (this.before !== null) this.propertyWritting += this.before;
                this.propertyWritting += this.value;
                if (this.unity !== null) this.propertyWritting += this.unity;
                if (this.after !== null) this.propertyWritting += this.after;
            }

            if (typeof this.parentProperty !== 'undefined') this.parentProperty.buildPropertyWritting()
        }

        return this.propertyWritting
    }
}

export{StyleProperty}