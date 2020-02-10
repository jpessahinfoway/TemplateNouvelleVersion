import {IncrusteStyle} from "./IncrusteStyle";

class TextIncrusteStyle extends IncrusteStyle{
    constructor(){
        super()
        this.type = 'text';
        this.styles.content = null
    }

    setStyle(style){
        this.styles.content = style ;
    }
}

export {TextIncrusteStyle}