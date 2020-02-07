import {TemplateInterface} from "../../interface/classes/TemplateInterface";
import {TemplateTool} from "../../template/classes/tools/parent/TemplateTool";
import {ZoneCreatorTool} from "../../template/classes/tools/ZoneCreatorTool";
import {ZoneDraggerTool} from "../../template/classes/tools/ZoneDraggerTool";
import {ZoneRemoverTool} from "../../template/classes/tools/ZoneRemoverTool";
import {ZoneResizerTool} from "../../template/classes/tools/ZoneResizerTool";
import {ZonePriorityManagerTool} from "../../template/classes/tools/ZonePriorityManagerTool";
import {ZoneZoomOnTool} from "../../template/classes/tools/ZoneZoomOnTool";
import {ZoneInfoDisplayerTool} from "../../template/classes/tools/ZoneInfoDisplayerTool";
import {ZoneDuplicatorTool} from "../../template/classes/tools/ZoneDuplicatorTool";
import {ZoneContentAssignerTool} from "../../template/classes/tools/ZoneContentAssignerTool";
import {TemplateMiniatorizerTool} from "../../template/classes/tools/TemplateMiniatorizerTool";
import {ZoneBackgroundAssignerTool} from "../../template/classes/tools/subtools/zoneContainerEditor/ZoneBackgroundAssignerTool";
import {TextZoneContentAssignerTool} from "../../template/classes/tools/subtools/zoneContainerEditor/TextZoneContentAssignerTool";
import {MediaZoneContentAssignerTool} from "../../template/classes/tools/subtools/zoneContainerEditor/MediaZoneContentAssignerTool";
import {PriceZoneContentAssignerTool} from "../../template/classes/tools/subtools/zoneContainerEditor/PriceZoneContentAssignerTool";
import {ZonePriorityForegroundTool} from "../../template/classes/tools/subtools/zonePriorityManager/ZonePriorityForegroundTool";
import {ZonePriorityBackgroundTool} from "../../template/classes/tools/subtools/zonePriorityManager/ZonePriorityBackgroundTool";
import {ZonePriorityAboveTool} from "../../template/classes/tools/subtools/zonePriorityManager/ZonePriorityAboveTool";
import {ZonePriorityBellowTool} from "../../template/classes/tools/subtools/zonePriorityManager/ZonePriorityBellowTool";
import {ZoneZoomIncreaserTool} from "../../template/classes/tools/subtools/zoneZoomOn/ZoneZoomIncreaserTool";


let templateInterface = new TemplateInterface() ;

templateInterface .createTemplate( 'test' , 'H' );

let toolBox = templateInterface .attachToolBox() ;

let toolsList = [
    toolBox. addTool(new ZoneCreatorTool(templateInterface)),
    toolBox. addTool(new ZoneDraggerTool(templateInterface)),
    toolBox. addTool(new ZoneRemoverTool(templateInterface)),
    toolBox. addTool(new ZoneResizerTool(templateInterface)),

    toolBox. addTool(new ZonePriorityManagerTool(templateInterface))
        .addSubTools(
            new ZonePriorityForegroundTool(templateInterface,this),
            new ZonePriorityBackgroundTool(templateInterface,this),
            new ZonePriorityAboveTool(templateInterface,this),
            new ZonePriorityBellowTool(templateInterface,this)
        ),

    toolBox. addTool( new ZoneZoomOnTool( templateInterface ) )
        .addSubTools(
            new ZoneZoomIncreaserTool(templateInterface,this)
        ),
    toolBox. addTool( new ZoneInfoDisplayerTool(templateInterface)),
    toolBox. addTool( new ZoneDuplicatorTool(templateInterface)),

    toolBox. addTool(new ZoneContentAssignerTool(templateInterface ) )
    //.addSubTool( new TemplateMiniatorizerTool( templateInterface ) .activeTool( true ) .createMiniature() .append() )
    //   .addSubTool( new ZoneBackgroundAssignerTool( templateInterface ) )
] ;

let mainToolsMenu = templateInterface.attachToolsMenu('mainToolsMenu', $('#main-toolbox')) ;

console.log(toolsList)
toolsList.forEach(tool => {
    if(typeof tool ==='object' && tool instanceof TemplateTool) mainToolsMenu.attachTool(tool)
}) ;
