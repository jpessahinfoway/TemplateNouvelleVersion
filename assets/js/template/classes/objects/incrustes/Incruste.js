
class Incruste{
    constructor(){
        this._name = null;
        this._incrusteElements = {};
        this._type = null;
        this._id = null;
        this._html = ''
    }


    get name() {
        return this._name;
    }

    set name(value) {
        this._name = value;
    }

    get incrusteElements() {
        return this._incrusteElements;
    }

    addIncrusteElements(value) {
        this._incrusteElements[value.type]=value;
        return this;
    }

    get type() {
        return this._type;
    }

    set type(value) {
        this._type = value;
    }

    get html() {
        return this._html;
    }

    set html(value) {
        this._html = value;
    }

    get id() {
        return this._id;
    }

    set id(value) {
        this._id = value;
    }

    buildHTML(){
        this.html='';
        Object.values(this.incrusteElements).map(content => this.html += content.buildHTML())
        return this.html
    }
/*
    setName(name){
        this.name = name;
    }
    addContent(content){
        this.contents[content.type]=content;
        return this;
    }



    setType(type){
        this.type = type;
    }*/

}
export {Incruste}