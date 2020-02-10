
import '../../css/accueil/home.css';
import '../../css/accueil/menus/topbar.css';
import '../../css/accueil/panels/panels.css';
import '../../css/accueil/modals/modals.css';

import {TemplateAccueil} from "./template_accueil/TemplateAccueil";

console.log('ici')
let templateAccueil = new TemplateAccueil(1) ;
templateAccueil.active(true)