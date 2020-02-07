import {PermanentSubTool} from "../../parent/PermanentSubTool";



class ZoneContainerEditorSubSubTool extends PermanentSubTool{
    constructor(templateInterface,parentTool){
        super(templateInterface,parentTool);

        this.title = null;

        this.$location = {};


        //this.functionToExecuteOnSelectedZone = this.setMediaToSelectedZone;
    }

    getIncrustElementClassName(incrustElementInDom){
        console.log(incrustElementInDom)

        let type = $(incrustElementInDom).data('type');
        let incrustElementsClasses = $(incrustElementInDom).attr('class')
        let currentElementclassName = typeof incrustElementsClasses !== 'undefined' ? $(incrustElementInDom).attr('class').split(' ').filter(className => className.match(new RegExp(`^${type}[0-9]*$`))) : [];
        console.log(currentElementclassName)
        return  currentElementclassName.length > 0 ? currentElementclassName[0] : null;
    }

    generateIncrust(incrustInDom, {incrust = { instance : null, required: ['test'] }  , incrustElementContent = {instance : null, required:[]}, incrustElementSubContents = { instance : {}, required : [] } } = {} ){
        console.log(incrust)
        console.log(incrustElementContent);
        console.log(incrustElementSubContents);


        if(typeof incrust ==='undefined' || incrust.instance ==='undefined') throw new Error('Aucune incruste renseignée');
        if(typeof incrustElementContent ==='undefined' || incrustElementContent.instance ==='undefined') throw new Error("Aucun element d'incruste renseigné");

        [incrust, incrustElementContent , incrustElementSubContents].map(incrustElement=>{
            if(typeof incrustElement['required'] === 'undefined')incrustElement['required'] = []
        });

        function hydrateObjectWithDatas(objectToHydrate,requiredAttrArray, ...elementDatas){

            elementDatas.forEach(elementData=>{
                elementDatas.required = requiredAttrArray.includes(elementData.name);

                if(objectToHydrate===null) return;
                console.log(elementData.name)
                if(typeof elementData.name === 'undefined')throw new Error("Aucun nom d'attribut indiqué");
                else if( (typeof elementData.value ==='undefined' ||elementData.value ===null) && elementDatas.required){throw new Error(`Aucune valeur trouvée pour l'attribut obligatoire ${elementData.name}`)}
                else{objectToHydrate[elementData.name] = typeof elementData.value !== 'undefined' ? elementData.value: null};
            });
            return objectToHydrate
        }

        incrust.instance = hydrateObjectWithDatas(incrust.instance, incrust.required,
            {name:'id', value:incrustInDom.dataset.id},
        );
        if(incrust.instance ===null ) return null;

        let incrustElementInDom = $(incrustInDom).find(`[data-type=${incrustElementContent.instance.type}]`).eq(0);
        if(incrustElementInDom.length <0) return null;

        incrustElementContent.instance = hydrateObjectWithDatas(incrustElementContent.instance, incrustElementContent.required,
            {name:'content', value:incrustElementInDom.get(0).dataset.content},
            {name:'id', value:parseInt(incrustElementInDom.get(0).dataset.id)},
            {name : 'incrustOrder', value: incrustElementInDom.get(0).dataset.order},
            {name : 'class', value : this.getIncrustElementClassName(incrustElementInDom.get(0))}
        );
        if(incrustElementContent===null)return null
        else incrust.instance.addIncrusteElements(incrustElementContent.instance)

        let incrustElementSubContentsDOM = $(incrustElementInDom).find(`[data-incruste=${incrust.instance.type}]`);

        incrustElementSubContentsDOM.each((subContentDOMIndex, subContentDOMValue) => {
            let currentIncrusteElementSubContent = incrustElementSubContents.instance[subContentDOMValue.dataset.type];

            currentIncrusteElementSubContent.instance = hydrateObjectWithDatas(currentIncrusteElementSubContent,incrustElementSubContents.required,
                {name:'content', value:subContentDOMValue.dataset.content},
                {name:'id', value:parseInt(subContentDOMValue.dataset.id)},
                {name : 'incrustOrder', value: subContentDOMValue.dataset.order},
                {name : 'class', value : this.getIncrustElementClassName(subContentDOMValue)}
            );
        });

        incrustElementContent.instance.addSubContents(...
            Object.values(incrustElementSubContents.instance)
                .filter(incrustElementSubContent => (incrustElementSubContent.id !== null && incrustElementSubContent.value !== null && incrustElementSubContent.className !== null))
        );

        console.log(incrust.instance);

        return incrust.instance

    }

    activeTool(boolean,onActivationFunction=null,onDisactivationFunction=null){
        super.activeToolDecorator(boolean,(mode)=>{
            if(mode==='on'){
                onActivationFunction.bind(this)();
                if(this.visibleOnActivation)this.$location.container.removeClass('none');
            }else if(mode === 'off'){
                if(onDisactivationFunction !== null)onDisactivationFunction.bind(this)()
                this.$location.container.addClass('none');
                // this.parentTool.zoneCreationObservable.removeObserver(this.zoneCreatorObserver)
            }
        })
    }
}

export {ZoneContainerEditorSubSubTool}