/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you require will output into a single css file (app.css in this case)

import '../css/app.css';
import '../css/other/general.css';
import '../css/template_work_zone.css';
import '../css/zones.css';
import '../css/menus/top_nav_menu.css';
import '../css/toolbars/toolbars.css';
import '../css/modals/modals.css';
import '../css/modals/choice_modal.css';
import '../css/tools/zone_infos_displayer.css';
import '../css/tools/zone_resizer.css';
import '../css/tools/associate/associate_zones.css'
import '../css/tools/zone_container_editor.css';
import '../css/tools/zone_container_editor/zone_container_media_editor.css';
import '../css/tools/zone_container_editor/zone_container_zone_selector.css';
import '../css/tools/zone_container_editor/zone_container_background_editor.css';
import '../css/tools/zone_container_editor/zone_container_price_editor.css';
import '../css/tools/zone_container_editor/zone_container_text_editor.css';
import '../css/tools/zone_priority_manager/zone_priority_manager.css';
import '../css/tools/template_miniature/template_miniature.css';

const $ = require('jquery');

// Need jQuery? Install it with "yarn add jquery", then uncomment to require it.
// const $ = require('jquery');

import {TemplateModule} from "./template/classes/TemplateModule";

let templateModule = new TemplateModule();

templateModule.createTemplate( 'test' , 'H' );
templateModule.initToolBox();
templateModule.initToolsMenu();
//templateModule.attachToolBox();




console.log('Hello Webpack Encore! Edit me in assets/js/app.js');