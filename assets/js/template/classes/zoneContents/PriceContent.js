import {Content} from "./Content";

class PriceContent extends Content{
    constructor(value,className){
        console.log(value)
        super(value)
        this.type = 'price';
        this._className = className;
        this.subContents = {
            unite : null,
            euro : null,
            centime : null,
            separator : null,
        };
    }

    addSubContents(...subContents){
        subContents.forEach( subContent => {
            if( typeof subContent === 'object'   &&   typeof this.subContents[subContent.constructor.name.replace('Content','').toLowerCase()] !== undefined   &&   typeof this.subContents[subContent.subType] !==  undefined ){
                this.subContents[subContent.subType] = subContent
            }
        })
    }
    buildHTML(){
        let html = '<div data-type="price">'

        Object.values(this.subContents).sort((a,b) => a.order - b.order).map(subContent=>{
            html += `<span class="${subContent.className}" data-type="price" data-subtype="${subContent.subType}" data-id="${subContent.id}">${subContent.value}</span>`
        })
        html += '</div>'
        return html
    }
}
export {PriceContent}