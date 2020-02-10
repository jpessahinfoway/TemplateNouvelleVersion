class IncrusteContent{
    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }
    constructor(){
        this._name = null;
        this._type = null;
        this._style = null;
        this._content = null;
        this._html = null;
        this._subContents = {}
        this._incrustOrder = 0;
        this._class = null;
        this._id = null;
    }


    get class() {
        return this._class;
    }

    set class(value) {
        this._class = value;
    }

    get name() {
        return this._name;
    }

    set name(value) {
        this._name = value;
    }

    get type() {
        return this._type;
    }

    set type(value) {
        this._type = value;
    }

    get style() {
        return this._style;
    }

    set style(value) {
        this._style = value;
    }

    get content() {
        return this._content;
    }

    set content(value) {
        this._content = value;
    }

    get html() {
        return this._html;
    }

    set html(value) {
        this._html = value;
    }

    get subContents() {
        return this._subContents;
    }

    get incrustOrder() {
        return this._incrustOrder;
    }

    set incrustOrder(value) {
        this._incrustOrder = value;
    }

    addSubContents(...subContents) {
        subContents.forEach( subContent => {

            if( typeof subContent === 'object'   &&   typeof this._subContents[subContent.constructor.name.replace('Content','').toLowerCase()] !== undefined   &&   typeof this._subContents[subContent.type] !==  undefined ){
                this._subContents[subContent.type] = subContent
            }
        })
    }

}
export {IncrusteContent}