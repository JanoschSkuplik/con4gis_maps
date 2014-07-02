/**
 * Contao Open Source CMS
 * 
 * @copyright  Küstenschmiede GmbH Software & Design 2014
 * @author     Tobias Dobbrunz <http://www.kuestenschmiede.de>
 * @package    con4gis 
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

function C4GMapsToolbox (mapData, map)
{
    // misc functions
    var fnIgnoreEvent = function ( event ) 
    {
        OpenLayers.Event.stop(event, true);
    };
    var fnIgnoreEvents = function ( element ) 
    {
        OpenLayers.Event.observe(element, 'click', fnIgnoreEvent);
        OpenLayers.Event.observe(element, 'mousedown', fnIgnoreEvent);
        OpenLayers.Event.observe(element, 'dblclick', fnIgnoreEvent);
        OpenLayers.Event.observe(element, 'keydown', fnIgnoreEvent);
        OpenLayers.Event.observe(element, 'touchstart', fnIgnoreEvent);
        OpenLayers.Event.observe(element, 'touchend', fnIgnoreEvent);
        OpenLayers.Event.observe(element, 'touchmove', fnIgnoreEvent);
        OpenLayers.Event.observe(element, 'touchcancel', fnIgnoreEvent);
        OpenLayers.Event.observe(element, 'touchmove', fnIgnoreEvent);
    };

    
    if (mapData.graticule) {
    // ---------------------------------------------------------------------------------
    // GRATICULE BUTTON
    // ---------------------------------------------------------------------------------
    
        var toggleGraticule = function()
        {
            //var ElementGraticulePanel = document.getElementById('C4GMapsToolGraticulePanel_' + mapData.id);
            var ElementGraticuleToggle = document.getElementById('C4GMapsToolGraticuleToggle_' + mapData.id);

            if (C4GMapsUtils.elementHasClass( ElementGraticuleToggle, 'olControlToolGraticuleIconInactive' )) {
                ElementGraticule.activate();
                ElementGraticuleToggle.className = ElementGraticuleToggle.className.replace(/olControlToolGraticuleIconInactive/gi, 'olControlToolGraticuleIconActive');
            } else {
                ElementGraticule.deactivate();
                ElementGraticuleToggle.className = ElementGraticuleToggle.className.replace(/olControlToolGraticuleIconActive/gi, 'olControlToolGraticuleIconInactive');
            }
        }

        var toolGraticulePanelDiv = document.createElement('div');
        toolGraticulePanelDiv.id = 'C4GMapsToolGraticulePanel_' + mapData.id;
        toolGraticulePanelDiv.className = 'olControlToolGraticulePanel';
        toolGraticulePanelDiv.style.position = 'absolute';
        toolGraticulePanelDiv.style.zIndex = '1031';

        var toolGraticuleIconDiv = document.createElement('div');
        toolGraticuleIconDiv.id = 'C4GMapsToolGraticuleIconDiv_' + mapData.id;
        toolGraticuleIconDiv.className = 'olControlToolGraticule olControlToolGraticuleInactive olControlNoSelect';

        var toolGraticuleSwitch = document.createElement('div');
        toolGraticuleSwitch.id = 'C4GMapsToolGraticuleToggle_' + mapData.id;
        toolGraticuleSwitch.className = 'olControlToolGraticuleIconInactive olButton';

        toolGraticuleIconDiv.appendChild(toolGraticuleSwitch);
        toolGraticulePanelDiv.appendChild(toolGraticuleIconDiv);
        map.viewPortDiv.appendChild(toolGraticulePanelDiv);

        fnIgnoreEvents(toolGraticulePanelDiv);
        fnIgnoreEvents(toolGraticuleIconDiv);

        OpenLayers.Event.observe(toolGraticuleSwitch, 'click',
            OpenLayers.Function.bind(function(input) { toggleGraticule(); }, this, null));


        ElementGraticule = new OpenLayers.Control.Graticule({
             displayInLayerSwitcher : false,
             autoActivate : false
        });
        ElementGraticule.div = toolGraticulePanelDiv;
        map.addControl(ElementGraticule);

    }
    if (mapData.measuretool) {
	// ---------------------------------------------------------------------------------
	// MEASURE TOOL
	// ---------------------------------------------------------------------------------

    	var toolMeasurePanelDiv = document.createElement('div');
    	toolMeasurePanelDiv.id = 'C4GMapsToolMeasurePanel_' + mapData.id;
    	toolMeasurePanelDiv.className = 'olControlToolMeasurePanel';
    	toolMeasurePanelDiv.style.position = 'absolute';
    	toolMeasurePanelDiv.style.zIndex = '1030';
    	if (!mapData.zoom_panel_world) {
    		toolMeasurePanelDiv.className += " olControlZoomOutItemWithoutWorld";
    	}	
    	if (!mapData.fullscreen) {
    		toolMeasurePanelDiv.className += " olControlWithoutFullscreen";		
    	}

    	var toolMeasureIconDiv = document.createElement('div');
    	toolMeasureIconDiv.id = 'C4GMapsToolMeasureIconDiv_' + mapData.id;
    	toolMeasureIconDiv.className = 'olControlToolMeasure olControlToolMeasureInactive olControlNoSelect';

    	var toolMeasureIcon = document.createElement('div');
    	toolMeasureIcon.id = 'C4GMapsToolMeasureToggle_' + mapData.id;
    	toolMeasureIcon.className = 'olControlToolMeasureIconInactive olButton';

    	var toolMeasureIconLine = document.createElement('div');
    	toolMeasureIconLine.id = 'C4GMapsToolMeasureLine_' + mapData.id;
    	toolMeasureIconLine.style.display = 'none';
    	toolMeasureIconLine.className = 'olControlToolMeasureIconLineActive olButton';

    	var toolMeasureIconPolygon = document.createElement('div');
    	toolMeasureIconPolygon.id = 'C4GMapsToolMeasurePolygon_' + mapData.id;
    	toolMeasureIconPolygon.style.display = 'none';
    	toolMeasureIconPolygon.className = 'olControlToolMeasureIconPolygonInactive olButton';

    	var toolMeasureDisplay = document.createElement('div');
    	toolMeasureDisplay.id = 'C4GMapsToolMeasureDisplay_' + mapData.id;
    	toolMeasureDisplay.className = 'olControlToolMeasureDisplay';
    	toolMeasureDisplay.style.display = 'none';

    	toolMeasureIconDiv.appendChild(toolMeasureIcon);
    	toolMeasureIconDiv.appendChild(toolMeasureIconLine);
    	toolMeasureIconDiv.appendChild(toolMeasureIconPolygon);
    	toolMeasurePanelDiv.appendChild(toolMeasureIconDiv);
    	toolMeasurePanelDiv.appendChild(toolMeasureDisplay);

    	//router.toolMeasureIconDiv = toolMeasureIconDiv;
    	map.viewPortDiv.appendChild(toolMeasurePanelDiv);

        fnIgnoreEvents(toolMeasurePanelDiv);
        fnIgnoreEvents(toolMeasureIconDiv);

    	OpenLayers.Event.observe(toolMeasureIcon, 'click',
    		OpenLayers.Function.bind(function(input) { toggleControl('toggle'); }, this, null));
    	OpenLayers.Event.observe(toolMeasureIconLine, 'click',
    		OpenLayers.Function.bind(function(input) { toggleControl('line'); }, this, null));
    	OpenLayers.Event.observe(toolMeasureIconPolygon, 'click',
    		OpenLayers.Function.bind(function(input) { toggleControl('polygon'); }, this, null));

        // style the sketch fancy
        var sketchSymbolizers = {
            "Point": {
                // externalGraphic: "system/modules/con4gis_maps/html/mausmass.png",
                // graphicWidth: 21,
                // graphicHeight: 21,
                // graphicXOffset: 0,
                // graphicYOffset: 0,
                // graphicOpacity: 1
                pointRadius: 1,
                strokeWidth: 1,
                strokeOpacity: 1,
                strokeColor: "#b33",
                strokeDashstyle: "solid"
            },
            "Line": {
                strokeWidth: 3,
                strokeOpacity: 1,
                strokeColor: "#b33",
                strokeDashstyle: "solid"
            },
            "Polygon": {
                strokeWidth: 2,
                strokeOpacity: 1,
                strokeColor: "#b33",
                fillColor: "white",
                fillOpacity: 0.3
            }
        };
        var style = new OpenLayers.Style();
        style.addRules([
            new OpenLayers.Rule({symbolizer: sketchSymbolizers})
        ]);
        var styleMap = new OpenLayers.StyleMap({"default": style});

        var measureControls = {
            line: new OpenLayers.Control.Measure(
                OpenLayers.Handler.Path, {
                    persist: true,
                    immediate: (mapData['measuretool'] == '2'),
                    geodesic: true,
                    handlerOptions: {
                        layerOptions: {
                            styleMap: styleMap
                        }
                    }
                }
            ),
            polygon: new OpenLayers.Control.Measure(
                OpenLayers.Handler.Polygon, {
                    persist: true,
                    immediate: (mapData['measuretool'] == '2'),
                    geodesic: true,
                    handlerOptions: {
                        layerOptions: {
                            styleMap: styleMap
                        }
                    }
                }
            )
        };
                
        var control;
        for(var key in measureControls) {
            control = measureControls[key];
            control.events.on({
                "measure": handleMeasurements,
                "measurepartial": handleMeasurements
            });
            control.div = toolMeasurePanelDiv;
            map.addControl(control);
        }

        var toggleControl = function (value) 
        {
        	var ElementMeasure = document.getElementById('C4GMapsToolMeasureIconDiv_' + mapData.id);
        	var ElementMeasureToggle = document.getElementById('C4GMapsToolMeasureToggle_' + mapData.id);
        	var ElementMeasureLine = document.getElementById('C4GMapsToolMeasureLine_' + mapData.id);
    	    var ElementMeasurePolygon = document.getElementById('C4GMapsToolMeasurePolygon_' + mapData.id);
    	    var ElementMeasureDisplay = document.getElementById('C4GMapsToolMeasureDisplay_' + mapData.id);
    	    ElementMeasureDisplay.innerHTML = '-';

        	switch (value) {
        		case 'line':
        			ElementMeasureLine.className = ElementMeasureLine.className.replace(/olControlToolMeasureIconLineInactive/gi, 'olControlToolMeasureIconLineActive');
    			    ElementMeasurePolygon.className = ElementMeasurePolygon.className.replace(/olControlToolMeasureIconPolygonActive/gi, 'olControlToolMeasureIconPolygonInactive');
        			break;
        		case 'polygon':
        			ElementMeasureLine.className = ElementMeasureLine.className.replace(/olControlToolMeasureIconLineActive/gi, 'olControlToolMeasureIconLineInactive');
    			    ElementMeasurePolygon.className = ElementMeasurePolygon.className.replace(/olControlToolMeasureIconPolygonInactive/gi, 'olControlToolMeasureIconPolygonActive');
        			break;
        		case 'toggle':
    	    		
    	    		if (C4GMapsUtils.elementHasClass( ElementMeasureToggle, 'olControlToolMeasureIconInactive' )) {
    				    ElementMeasureLine.style.display = 'inline-block';
    			    	ElementMeasurePolygon.style.display = 'inline-block';
    			    	ElementMeasureDisplay.style.display = 'block';

    			    	ElementMeasure.className = ElementMeasure.className.replace(/olControlToolMeasureInactive/gi, 'olControlToolMeasureActive');
    			    	ElementMeasureToggle.className = ElementMeasureToggle.className.replace(/olControlToolMeasureIconInactive/gi, 'olControlToolMeasureIconActive');

    					if (C4GMapsUtils.elementHasClass( ElementMeasureLine, 'olControlToolMeasureIconLineActive' )) {
    						value = 'line';
    					} else {
    						value = 'polygon';
    					}
    	    		} else {
    	    			ElementMeasureLine.style.display = 'none';
    			    	ElementMeasurePolygon.style.display = 'none';
    			    	ElementMeasureDisplay.style.display = 'none';

    			    	ElementMeasure.className = ElementMeasure.className.replace(/olControlToolMeasureActive/gi, 'olControlToolMeasureInactive');
    			    	ElementMeasureToggle.className = ElementMeasureToggle.className.replace(/olControlToolMeasureIconActive/gi, 'olControlToolMeasureIconInactive');

    	    			value = 'none';
    	    		}
    	    	default: break;
        	}

            for(key in measureControls) {
            	var control = measureControls[key];
                if(value == key) {
                    control.activate();
                } else {
                    control.deactivate();
                }
            }
        }
    }//end of measuretool-if    

    // function-declarations in if-blocks are not allowed (ECMA-262)
    function handleMeasurements(event) 
    {
        var geometry = event.geometry;
        var units = event.units;
        var order = event.order;
        var measure = event.measure;
        var element = document.getElementById('C4GMapsToolMeasureDisplay_' + mapData.id);
        var out = "";
        if(order == 1) {
            out += measure.toFixed(3) + " " + units;
        } else {
            out += measure.toFixed(3) + " " + units + "<sup>2</" + "sup>";
        }
        element.innerHTML = out;
    } 

}
