/**
 * Contao Open Source 
 
 * Copyright (C) 2005-2014 Leo Feyer
 *
 * Formerly known as TYPOlight Open Source CMS.
 *
 * This program is free software: you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation, either
 * version 3 of the License, or (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU
 * Lesser General Public License for more details.
 * 
 * You should have received a copy of the GNU Lesser General Public
 * License along with this program. If not, please visit the Free
 * Software Foundation website at <http://www.gnu.org/licenses/>.
 *
 * @copyright  Küstenschmiede GmbH Software & Design 2014
 * @author     Jürgen Witte & Tobias Dobbrunz <http://www.kuestenschmiede.de>
 * @package    con4gis 
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/*	-----------------------------------------------
	Utility function object
	----------------------------------------------- */
C4GMapsUtils = {

	/**
	 * Property: extTokenRegEx
	 * taken from OpenLayers.String.tokenRegEx, enhanced by ':' for OSM style tokens
	 *
	 * Used to find tokens in a string.
	 * Examples: ${a}, ${a.b.c}, ${a-b}, ${5}, ${a:b}
	 */	
	extTokenRegEx:  /\$\{([\w.:]+?)\}/g,

	getAngleofLine : function(pointList) 
	{
		var b_x = 0;
		var b_y = 1;
		var a_x = pointList[1].x - pointList[0].x;
		var a_y = pointList[1].y - pointList[0].y;
		var angle_rad = Math.acos((a_x*b_x+a_y*b_y)/Math.sqrt(a_x*a_x+a_y*a_y)) ;
		var angle = 360/(2*Math.PI)*angle_rad;
		if (a_x < 0) {
			return 360 - angle;
		} else {
			return angle;
		}
	},
	
	getMiddleofLine : function(pointList) 
	{
		return new OpenLayers.Geometry.Point(
			pointList[0].x + ((pointList[1].x-pointList[0].x)/2),
			pointList[0].y + ((pointList[1].y-pointList[0].y)/2));
	},

	createArrows : function(feature,geometry)
	{
		var style = feature.style;
		var result = [];
		if (geometry.CLASS_NAME == 'OpenLayers.Geometry.LineString') {
			var vertices = geometry.getVertices();
			for (var j = 0; j < vertices.length-1; j++) {
				var arrStyle = {};
				OpenLayers.Util.extend( arrStyle, style );
				arrStyle.graphicName = 'triangle';
				arrStyle.pointRadius = style.arrowRadius;
				arrStyle.rotation = this.getAngleofLine([vertices[j],vertices[j+1]]);
				delete arrStyle.hoverStyle;
				delete arrStyle.label;
				delete arrStyle.popupInfo;
				delete arrStyle.popupRouteTo;								
				delete arrStyle.linkUrl;
				delete arrStyle.graphicTitle;
				delete arrStyle.editor_vars;
				delete arrStyle.editor_lines;
				delete arrStyle.editor_icon;
				delete arrStyle.editor_collect;

				var arrow = new OpenLayers.Feature.Vector(this.getMiddleofLine([vertices[j],vertices[j+1]]),
					feature.attributes,arrStyle);
				delete arrow.attributes.locstyle;
				if (style.arrowMinzoom > arrow.attributes.minZoom)
					arrow.attributes.minZoom = style.arrowMinzoom;

				arrow._feature = feature;
				result.push(arrow);
				if ((style.arrowBack) || (feature.attributes.ArrowBack)) {
					var arrStyleBack = {};
					OpenLayers.Util.extend( arrStyleBack, arrStyle );
					arrStyleBack.rotation = this.getAngleofLine([vertices[j+1],vertices[j]]);
					var arrow = new OpenLayers.Feature.Vector(new OpenLayers.Geometry.Point(vertices[j+1].x,vertices[j+1].y),
						feature.attributes,arrStyleBack);
					if (style.arrowMinzoom > arrow.attributes.minZoom)
						arrow.attributes.minZoom = style.arrowMinzoom;
					delete arrow.attributes.locstyle;
					arrow._feature = feature;
					result.push(arrow);
				}
			}
		}
		else if (geometry.CLASS_NAME == 'OpenLayers.Geometry.Collection') {
			for (var j = 0; j < geometry.components.length; j++) {
				result = result.concat(this.createArrows(feature,geometry.components[j]));
			}
		}
		return result;
	},

	removeArrows : function(layer, parentFeature) {
		var arrows = [];
		for (var i = layer.features.length - 1; i >= 0; i--) {
			if (layer.features[i]._feature == parentFeature) {
				arrows.push(layer.features[i]);
			}
		}
		layer.removeFeatures(arrows);
	},

	removeAllArrows : function(layer) {
		var arrows = [];
		for (var i = layer.features.length - 1; i >= 0; i--) {
			if (layer.features[i]._feature) {
				arrows.push(layer.features[i]);
			}
		}
		layer.removeFeatures(arrows);
	},

	updateArrowStyle : function(layer, parentFeature) {
		for (var i = layer.features.length - 1; i >= 0; i--) {
			if (layer.features[i]._feature == parentFeature) {				
				layer.features[i].style.strokeColor = parentFeature.style.strokeColor;
			}
		}
	},

	elementHasClass : function(element, className) {
		return ((" " + element.className + " ").replace(/[\n\t\r]/g, " ").indexOf(" " + className + " ") > -1 );
	},

	contains : function(array, val) {
	    for (var i = 0; i < array.length; i++) {
	        if (array[i] === val) {
	            return true;
	        }
	    }
	    return false;
	}
};

/*	-----------------------------------------------
	Main function - creates map using given mapData
	----------------------------------------------- */
 
function C4GMaps(mapData) {
	// implement format replacement 
	// e.g. "Test {0} {1}"._c4gFormat("foo","bar") results in "Test foo bar"
	if (!String.prototype._c4gFormat) {
		String.prototype._c4gFormat = function() {
			var args = arguments;
			return this.replace(/{(\d+)}/g, function(match, number) { 
				return typeof args[number] != 'undefined'
							? args[number]
				: match;
			});
		};
	}

	function getIEVersion() {
		var rv = -1; // no IE
		if (navigator.appName == 'Microsoft Internet Explorer') {
			var ua = navigator.userAgent;
			var re = new RegExp("MSIE ([0-9]{1,}[.0-9]{0,})");
			if (re.exec(ua) !== null)
				rv = parseFloat(RegExp.$1);
		}
		return rv;
	}

	var ieVersion = getIEVersion();

	if ((typeof (MooTools) == "object") && (typeof(window.addEvent)=='function')) {
		window.addEvent("domready", function() {
			initC4GMaps(mapData);
		});
	} else if (typeof (jQuery) == "function") {
		jQuery(document).ready(function() {
			initC4GMaps(mapData);
		});
	} else {
		if (!window.c4gMapsOnLoad) {
			window.c4gMapsOnLoad = [];
		}
		window.c4gMapsOnLoad.push(mapData);
		window.onload = function() {
			for (var i = window.c4gMapsOnLoad.length - 1; i >= 0; i--) {
				initC4GMaps(window.c4gMapsOnLoad[i]);
			}
		};
	}

	// make relative URLs absolute
	function qualifyURL(url) {
		var a = document.createElement('a');
		a.href = url;
		return a.href;
	}

	function initC4GMaps(mapData) {

		// -----------------------------------------------
		// global settings
		// -----------------------------------------------
		OpenLayers.Lang.en = {
			"Base Layer" : mapData.labels.baseLayer,
			"Overlays" : mapData.labels.overlays
		};
		OpenLayers.ImgPath = qualifyURL(mapData.imagepath);

		// -----------------------------------------------
		// create map
		// -----------------------------------------------

		var overlayLayers = [];
		var fnBaseLayerChanged = function(event) {
			for (var i = 0; i < overlayLayers.length; i++) {
				if (overlayLayers[i].parents.indexOf(event.layer.key) >= 0) {
					overlayLayers[i].setVisibility(true);
				}
				else {
					overlayLayers[i].setVisibility(false);
				}
			}
		};

		var map = new OpenLayers.Map(mapData.div, {
			controls : [],
			eventListeners : {
				"changebaselayer" : fnBaseLayerChanged
			},
			projection : new OpenLayers.Projection("EPSG:900913"),
			displayProjection : new OpenLayers.Projection("EPSG:4326"),
			theme: "",
			fallThrough: true
		});
		var mapDiv = document.getElementById(mapData.div);

		// ------------------------------------------------------------
		// add custom CSS (code based on fragments from OpenLayers.Map)
		// ------------------------------------------------------------
		if (typeof (mapData.css) != 'undefined') {
			for ( var i = 0; i < mapData.css.length; i++) {
				// check existing links for equivalent url
				var addNode = true;
				var nodes = document.getElementsByTagName('link');
				for(var j=0, len=nodes.length; j<len; ++j) {
					if(OpenLayers.Util.isEquivalentUrl(nodes.item(j).href,
													   mapData.css[i])) {
						addNode = false;
						break;
					}
				}
				// only add a new node if one with an equivalent url hasn't already
				// been added
				if(addNode) {
			
					var newNode = document.createElement('link');
					newNode.setAttribute('rel', 'stylesheet');
					newNode.setAttribute('type', 'text/css');
					newNode.setAttribute('href', mapData.css[i]);
					document.getElementsByTagName('head')[0].appendChild(newNode);
				}	
			}
		}


		// -----------------------------------------------
		// picking Geo Coordinate handling 
		// -----------------------------------------------
		if (mapData.pickGeo) {
			// mouse_nav and mouseposition needed for Geo Picking
			mapData.mouse_nav = true;
			mapData.mouseposition = true;
			mapData.mouse_nav_toolbar = false; // Nav Toolbar steels DefaultDblClick event, so turn it off			
		}

		// -----------------------------------------------
		// create layers using the defined services
		// -----------------------------------------------
		var mapLayer = null;
		var defaultLayer = null;
		var serviceCount = 0;

		for ( var i in mapData.service) {
			var service = mapData.service[i];
			var layerOptions = {};
			serviceCount++;
			if ((typeof (service.attribution) != 'undefined')
					&& (service.attribution !== '')) {
				layerOptions.attribution = service.attribution;
			}
			if ((typeof (service.maxzoomlevel) != 'undefined')
					&& (service.maxzoomlevel > 0)) {
				layerOptions.numZoomLevels = parseInt(service.maxzoomlevel,10) + 1;
			}
			if (service.provider == 'osm') {
				if (service.osm_style == 'osm_custom') {
					var urls = [];
					if ((typeof (service.osm_url1) != 'undefined')
							&& (service.osm_url1 !== '')) {
						urls.push(service.osm_url1);
					}
					if ((typeof (service.osm_url2) != 'undefined')
							&& (service.osm_url2 !== '')) {
						urls.push(service.osm_url2);
					}
					if ((typeof (service.osm_url3) != 'undefined')
							&& (service.osm_url3 !== '')) {
						urls.push(service.osm_url3);
					}
					if ((typeof (service.osm_url4) != 'undefined')
							&& (service.osm_url4 !== '')) {
						urls.push(service.osm_url4);
					}
					layerOptions.sphericalMercator = true;
					if ((typeof (service.osm_keyname) != 'undefined')
							&& (service.osm_keyname !== '')) {
						layerOptions.keyname = service.osm_keyname;
					}
					layerOptions.tileOptions = {
						crossOriginKeyword : null
					};
					if (typeof(layerOptions.attribution) == 'undefined') {
						layerOptions.attribution = OpenLayers.Util.OSM.DEFAULT_ATTRIBUTION;
					}
					mapLayer = new OpenLayers.Layer.OSM(service.name, urls,
							layerOptions);
					map.addLayer(mapLayer);

				} else {
					mapLayer = new OpenLayers.Layer.OSM[service.osm_style](
							service.name, layerOptions);
					map.addLayer(mapLayer);
				}

			} else if (service.provider == 'google') {
				if ((typeof (service.google_style) != 'undefined')
						&& (service.google_style !== '')
						&& (service.google_style != 'streets')) {
					layerOptions.type = service.google_style;
				}
				mapLayer = new OpenLayers.Layer.Google(service.name,
						layerOptions);
				map.addLayer(mapLayer);

			} else if (service.provider == 'bing') {
				layerOptions.name = service.name;
				layerOptions.type = service.bing_style;
				layerOptions.key = service.bing_key;
				mapLayer = new OpenLayers.Layer.Bing(layerOptions);
				map.addLayer(mapLayer);

			}

			mapLayer.key = service.key;
			if (service.key == mapData.defaultServiceKey) {
				defaultLayer = mapLayer;
			}
		}

		

		// -----------------------------------------------
		// create overlay layers
		// -----------------------------------------------

		if (mapData.overlays != null)
		{
			for ( var i = 0; i < mapData.overlays.length; i++) {

				var overlay = mapData.overlays[i];
				var layerOptions =
					{ 
						displayInLayerSwitcher : false,
						tileOptions : {
							crossOriginKeyword : null
		
						}					
					};

				if ((typeof (overlay.attribution) != 'undefined') && (overlay.attribution !== '')) {
					layerOptions.attribution = overlay.attribution;
				}
				else if (overlay.provider=='custom') {
					layerOptions.attribution = '';
				}

				var overlayLayer = null;
				var withMapData = false;	
				if (overlay.provider=='openseamap') {
					overlayLayer = new OpenLayers.Layer.OpenSeaMap(overlay.name,layerOptions);				
				}
				else if (overlay.provider=='openweathermap_data') {
					overlayLayer = new OpenLayers.Layer.OpenWeatherMap_Data(overlay.name,layerOptions);
					withMapData = (overlayLayer != null);
				}
				else if (overlay.provider=='openweathermap_stations') {
					overlayLayer = new OpenLayers.Layer.OpenWeatherMap_Stations(overlay.name,layerOptions);
					withMapData = (overlayLayer != null);
				}			
				else if (overlay.provider=='openweathermap_cloudsForecasts') {
					overlayLayer = new OpenLayers.Layer.OpenWeatherMap_CloudsForecasts(overlay.name,layerOptions);
					withMapData = (overlayLayer != null);
				}			
				else if (overlay.provider=='openweathermap_precipitationForecasts') {
					overlayLayer = new OpenLayers.Layer.OpenWeatherMap_PrecipitationForecasts(overlay.name,layerOptions);
					withMapData = (overlayLayer != null);
				}			
				else if (overlay.provider=='openweathermap_radar') {
					overlayLayer = new OpenLayers.Layer.OpenWeatherMap_Radar(overlay.name,layerOptions);
					withMapData = (overlayLayer != null);
				}			
				else if (overlay.provider=='openweathermap_clouds') {
					overlayLayer = new OpenLayers.Layer.OpenWeatherMap_Clouds(overlay.name,layerOptions);
				}
				else if (overlay.provider=='openweathermap_rain') {
					overlayLayer = new OpenLayers.Layer.OpenWeatherMap_Rain(overlay.name,layerOptions);
				}
				else if (overlay.provider=='openweathermap_pressure') {
					overlayLayer = new OpenLayers.Layer.OpenWeatherMap_Pressure(overlay.name,layerOptions);
				}
				else if (overlay.provider=='openweathermap_precipitation') {
					overlayLayer = new OpenLayers.Layer.OpenWeatherMap_Precipitation(overlay.name,layerOptions);
				}
				else if (overlay.provider=='openweathermap_wind') {
					overlayLayer = new OpenLayers.Layer.OpenWeatherMap_Wind(overlay.name,layerOptions);
				}
				else if (overlay.provider=='openweathermap_temp') {
					overlayLayer = new OpenLayers.Layer.OpenWeatherMap_Temp(overlay.name,layerOptions);
				}
				else if (overlay.provider=='openweathermap_snow') {
					overlayLayer = new OpenLayers.Layer.OpenWeatherMap_Snow(overlay.name,layerOptions);
				}			
				else {
					// custom
					// layerOptions.baseLayer = true;
					// layerOptions.displayOutsideMaxExtent = true;
					//layerOptions.sphericalMercator = true;
					var urls = [];
					if ((typeof (overlay.url1) != 'undefined')
							&& (overlay.url1 !== '')) {
						urls.push(overlay.url1);
					}
					if ((typeof (overlay.url2) != 'undefined')
							&& (overlay.url2 !== '')) {
						urls.push(overlay.url2);
					}
					if ((typeof (overlay.url3) != 'undefined')
							&& (overlay.url3 !== '')) {
						urls.push(overlay.url3);
					}
					if ((typeof (overlay.url4) != 'undefined')
							&& (overlay.url4 !== '')) {
						urls.push(overlay.url4);
					}
					overlayLayer = new OpenLayers.Layer.CustomOverlay(overlay.name, urls, layerOptions);
				}
				if (overlayLayer !== null) {				
					map.addLayer(overlayLayer);

					OpenLayers.Element.addClass(overlayLayer.div,"c4gMapsOverlayLayer");
					overlayLayer.parents = overlay.parents;
					overlayLayer.setVisibility(false);
					if (C4GMapsUtils.contains( overlayLayer.parents, map.baseLayer.key )) {
						overlayLayer.setVisibility(true);
					}

					overlayLayers.push(overlayLayer);				
				}	

			}
		}

		if (defaultLayer !== null) {
			map.setBaseLayer(defaultLayer);
		}

		// -----------------------------------------------
		// restrict area
		// -----------------------------------------------
		if (mapData.restrict_area) {
			var restr_bounds = new OpenLayers.Bounds(
					mapData.restr_bottomleft_geox,
					mapData.restr_bottomleft_geoy, 
					mapData.restr_topright_geox,
					mapData.restr_topright_geoy).transform(
					new OpenLayers.Projection("EPSG:4326"),
					new OpenLayers.Projection("EPSG:900913"));
			map.setOptions({
				restrictedExtent : restr_bounds
			});
		}

		// -----------------------------------------------
		// add vector layer with zoom filter
		// -----------------------------------------------
		var zoomFilterStrategies = [];
		var getZoomFilterStrategy = function() {
			var zoomFilterMin = new OpenLayers.Filter.Comparison({
				type : OpenLayers.Filter.Comparison.BETWEEN,
				property : "minZoom",
				lowerBoundary : 0,
				upperBoundary : parseInt(mapData.zoom,10)
			});
			var zoomFilterMax = new OpenLayers.Filter.Comparison({
				type : OpenLayers.Filter.Comparison.BETWEEN,
				property : "maxZoom",
				lowerBoundary : parseInt(mapData.zoom,10),
				upperBoundary : 999
			});
			var zoomFilter = new OpenLayers.Filter.Logical({
				type : OpenLayers.Filter.Logical.AND,
				filters : [ zoomFilterMin, zoomFilterMax ]
			});

			var strat = new OpenLayers.Strategy.Filter({
				filter : zoomFilter
			});
			zoomFilterStrategies.push(strat);
			return strat;

		};

		var setZoomFilter = function(strat) {
			strat.filter.filters[0].lowerBoundary = 0;
			strat.filter.filters[0].upperBoundary = map.zoom;
			strat.filter.filters[1].lowerBoundary = map.zoom;
			strat.filter.filters[1].upperBoundary = 999;
			strat.setFilter(strat.filter);
		};

		var handleZoomEnd = function() {
			for ( var i = 0; i < zoomFilterStrategies.length; ++i) {
				if (zoomFilterStrategies[i].active) {
					setZoomFilter(zoomFilterStrategies[i]);
				}
			}
		};
		map.events.on({
			"zoomend" : handleZoomEnd
		});
		
		var vectorLayers = [];

		function addItem(parent, geox, geoy, aStyle, aLabel, aGraphicTitle, aPopupInfo, aPopupRouteTo,
				aLinkUrl, aClickZoomTo, aMinZoom, aMaxZoom, aAttr) {

			var vectorLayer = null;
			if (aStyle===undefined) {
				aStyle = {};
			}
			for(var i=0; i<vectorLayers.length; i++) {
				if(vectorLayers[i].parent==parent) {
					vectorLayer=vectorLayers[i];
					break;
				}
			}	
			if (!vectorLayer) {
				var vectorLayer = new OpenLayers.Layer.Vector('Locations', {
					displayInLayerSwitcher : false,
					strategies : [ getZoomFilterStrategy() ],
					parent : parent			
				});
				map.addLayer(vectorLayer);
				vectorLayers.push(vectorLayer);
			}	
				
			var aStyleTmp;			

			if (typeof (aPopupInfo) == 'undefined') {
				aPopupInfo = '';
			}
			if (typeof (aLinkUrl) == 'undefined') {
				aLinkUrl = '';
			}
			if (typeof (aLabel) == 'string') {
				aLabel = aLabel.replace('{$NL}', '\n');
			}
			if ((aLabel !== '') || (aGraphicTitle !== '')) {
				aStyleTmp = {
					label : (aStyle.label===undefined?aLabel:aStyle.label),
					graphicTitle : (aStyle.graphicTitle===undefined?aGraphicTitle:aStyle.graphicTitle)
				};
				OpenLayers.Util.extend(aStyleTmp, aStyle);
			} else {
				aStyleTmp = aStyle;
			}
			var aPoint = new OpenLayers.Geometry.Point(geox, geoy).transform(
					new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
					new OpenLayers.Projection("EPSG:900913") // to Spherical Mercator Projection
			);
			if (((aPopupInfo !== "") && (aPopupInfo !== null)) || (aLinkUrl !== "") || (aClickZoomTo > 0) || aPopupRouteTo) {
				aStyleTmp.cursor = "pointer";
			}			
			var aFeature = new OpenLayers.Feature.Vector(aPoint, {
				popupInfo : (aStyle.popupInfo===undefined?aPopupInfo:aStyle.popupInfo),
				popupRouteTo : aPopupRouteTo,
				linkUrl : aLinkUrl,
				clickZoomTo : (aStyle.onclick_zoomto===undefined?(aClickZoomTo ? aClickZoomTo : 0):aStyle.onclick_zoomto),
				minZoom : (aStyle.minzoom===undefined?(aMinZoom ? aMinZoom : 0):aStyle.minzoom),
				maxZoom : (aStyle.maxzoom===undefined?(aMaxZoom > 0 ? aMaxZoom : 999):(aStyle.maxzoom > 0 ? aStyle.maxzoom : 999))
			}, aStyleTmp);
			aFeature.lonlat = new OpenLayers.LonLat(aPoint.x, aPoint.y);
			if ((aAttr !== null) && (typeof (aAttr) !== 'undefined'))
				OpenLayers.Util.extend(aFeature.attributes, aAttr);

			// Replace Placeholders
			var fnReplaceAttribs = function(str, match) {

				if (match.substr(0, 2) == 'FN') {
					// use ${FN<functionName>}, so functionName(feature) is called
					// to get the text to be inserted
					var func = eval(match.substr(2));
					if (typeof func == 'function') {
						return func(aFeature,aStyleTmp);
					} else {
						return '';
					}					
				} else {
					// use ${attribName} to insert feature attributes
					return (aFeature.attributes[match] ? aFeature.attributes[match]
							: '');
				}
			};
			if (aFeature.attributes.popupInfo) 
				aFeature.attributes.popupInfo = aFeature.attributes.popupInfo.replace(
								C4GMapsUtils.extTokenRegEx, fnReplaceAttribs);
			if (aFeature.style.graphicTitle) 
				aFeature.style.graphicTitle = aFeature.style.graphicTitle.replace(
								C4GMapsUtils.extTokenRegEx, fnReplaceAttribs);
			if (aFeature.style.label) 
				aFeature.style.label = aFeature.style.label.replace(
								C4GMapsUtils.extTokenRegEx, fnReplaceAttribs);


			vectorLayer.addFeatures([ aFeature ]);
		}

		var styles = {};
		if (typeof (mapData.locStyles) == 'object') {
			var imagesLocation = OpenLayers.Util.getImagesLocation();
			var externalGraphic;
			var graphicBase = "";
			if (typeof (document.baseURI) != "undefined") {
				graphicBase = document.baseURI;
			}
			for ( var key in mapData.locStyles) {
				var locStyle = mapData.locStyles[key];
				externalGraphic = '';
				if (typeof (locStyle.internalGraphic) != 'undefined') {
					externalGraphic = imagesLocation + locStyle.internalGraphic;
				} else if (typeof (locStyle.externalGraphic) != 'undefined') {
					externalGraphic = graphicBase + locStyle.externalGraphic;
				}
				var aStyle = locStyle;
				if (externalGraphic !== '') {
					aStyle.externalGraphic = externalGraphic;
				}
				styles[key] = aStyle;
			}
		}

		if (typeof (mapData.child) == 'object') {
			for ( var key in mapData.child) {
				child = mapData.child[key];
				if (typeof(child)!='function') {
					var parent = 0;
					if (child.parent) {
						parent=child.parent;
					}
					addItem(parent, child.geox, child.geoy, styles[child.locstyle],
							child.label, child.graphicTitle, child.popupInfo, child.popupRouteTo,
							child.linkurl, child.onclick_zoomto, child.minzoom,
							child.maxzoom, child.attr);
				}		
			}
		}

		// -----------------------------------------------
		// functions for loading-animation
		// -----------------------------------------------
		var loadingDiv = document.createElement('div');
			loadingDiv.id = 'C4GMapsLoading_' + mapData.id;
			loadingDiv.className = 'olControlLoaderAnimation';
			loadingDiv.style.zIndex = '1050';
			loadingDiv.style.opacity = '0';
			map.viewPortDiv.appendChild(loadingDiv);

		var loadingShow = function()
		{
			document.getElementById('C4GMapsLoading_' + mapData.id).style.opacity = '1';
		}
		var loadingHide = function()
		{
			document.getElementById('C4GMapsLoading_' + mapData.id).style.opacity = '0';
		}

		// -----------------------------------------------
		// add con4gis-Logo
		// -----------------------------------------------
		if (mapData.cfg_logo_attribution) {
			var LogoDiv = document.createElement('div');
			LogoDiv.id = 'con4gisLogoAttribution_' + mapData.id;
			LogoDiv.className = 'olControlLogoAttribution';
			LogoDiv.style.zIndex = '1042';
			var LogoLink = document.createElement('a');
			LogoLink.href = "http://www.con4gis.org";
			LogoLink.title = "con4gis";
			LogoLink.target = "_blank";
			var Logo = document.createElement('img');
			Logo.src = "system/modules/con4gis_maps/html/logo_con4gis.png";
			Logo.title = "build with con4gis";
			Logo.alt = "con4gis-logo";
			Logo.width = "60";
			Logo.height = "21";

			LogoLink.appendChild(Logo);
			LogoDiv.appendChild(LogoLink);

			map.viewPortDiv.appendChild(LogoDiv);
		};

		// -----------------------------------------------
		// create map controls
		// -----------------------------------------------
		if (mapData.pan_panel) {
			map.addControl(new OpenLayers.Control.PanPanel());
		}

		if (mapData.zoom_panel == '1') {
			var zoomPanel = new OpenLayers.Control.ZoomPanel();
			map.addControl(zoomPanel);
			if (!mapData.zoom_panel_world) {
				OpenLayers.Element.addClass(zoomPanel.controls[1].panel_div,
						"olControlZoomToMaxExtentItemInvisible");
				OpenLayers.Element.addClass(zoomPanel.controls[2].panel_div,
						"olControlZoomOutItemWithoutWorld");
			}
		}

		if ((mapData.zoom_panel == '2') || (mapData.zoom_panel == '3')) {
			var simpleZoom = new OpenLayers.Control.Zoom();
			map.addControl(simpleZoom);
			if (mapData.pan_panel) {
				OpenLayers.Element.addClass(simpleZoom.div,
						"olControlZoomPosition2");
			}
		}

		if (mapData.fullscreen) {
			var fullscreenPanel = new OpenLayers.Control.FullScreenPanel();
			fullscreenPanel.addControls(new OpenLayers.Control.FullScreen());
			map.addControl(fullscreenPanel);
			if (!mapData.zoom_panel_world) {
				OpenLayers.Element.addClass(fullscreenPanel.div,"olControlZoomOutItemWithoutWorld");
			}	
		}


		// -----------------------------------------------
		// add router
		// -----------------------------------------------
		var router = null;
		if (typeof( C4GMapsRouter ) == 'function') {
			router = C4GMapsRouter( mapData, map );
		}	

		// set GAP
		var gap = (mapData.min_gap ? mapData.min_gap : 0);
		for (var i = 0; i < map.layers.length; i++) {
			var layer = map.layers[i];
			if (layer.isBaseLayer) {

				// Minimum gap between extent and map border (pixel)
				layer.minExtentGapX = gap;
				layer.minExtentGapY = gap;

				// overwrite "getZoomForExtent" method of base layer
				layer.getZoomForExtent = function(extent,closest){
					var viewSize = this.map.getSize();
					var idealResolution = Math.max(
							extent.getWidth() / Math.max(viewSize.w - 2 * this.minExtentGapX, 1),
							extent.getHeight() / Math.max(viewSize.h - 2 * this.minExtentGapY, 1));
					return this.getZoomForResolution( idealResolution, closest );
				};
			}
		}   


		if (mapData.keyboard_nav) {
			map.addControl(new OpenLayers.Control.KeyboardDefaults());
		}

		if (mapData.nav_history) {
			var nav = new OpenLayers.Control.NavigationHistory();
			map.addControl(nav);
			var panel = new OpenLayers.Control.Panel({
				displayClass : "olControlPanel olControlPanelNavigationHistory"
			});
			panel.addControls([ nav.previous, nav.next ]);
			map.addControl(panel);
		}

		if (mapData.mouse_nav) {
			var navOptions = {
				zoomWheelEnabled : mapData.mouse_nav_wheel,
				zoomBoxEnabled : mapData.mouse_nav_zoombox,
				dragPanOptions : {
					enableKinetic : mapData.mouse_nav_kinetic
				}
			};

			// -----------------------------------------------
			// Geo Location Search
			// -----------------------------------------------
			if (mapData.geosearch) {
				var performSearch=function() {
					loadingShow();
					var viewbox = '';
					if (mapData.restrict_area) {
						viewbox = '&bounded=1&viewbox='
								+ mapData.restr_bottomleft_geox + ','
								+ mapData.restr_bottomleft_geoy + ','
								+ mapData.restr_topright_geox + ','
								+ mapData.restr_topright_geoy;

					} else {
						
						var bounds = map.getExtent().scale(1.2);
						bounds.transform(map.projection,map.displayProjection);
						viewbox = '&viewbox='
								+ bounds.left + ','
								+ bounds.bottom + ','
								+ bounds.right + ','
								+ bounds.top;
					
					}

					var profileId = '';
					if (mapData.geosearch_engine == '3') {
						profileId = "&profile=" + mapData.profile;
					}

					OpenLayers.Request.GET({
						url: document.getElementsByTagName('base')[0].href	
							+ mapData.geocoding_url
							+ "?engine=" + mapData.geosearch_engine
							+ profileId
							+ "&token="+mapData.REQUEST_TOKEN
							+ "&format=json&limit=1&q="
							+ encodeURI(document.getElementById('c4gMapsSearchInput').value)
							+ viewbox,
						success: function(ajaxRequest) {
							var textResponse = ajaxRequest.responseText;
							var json = new OpenLayers.Format.JSON();														
							geoObj = json.read(textResponse);
							if (geoObj.length === 0) {
								alert(mapData.labels.no_geo_results);
							} else {
								if ((geoObj[0].boundingbox != 'undefined')
										&& mapData.geosearch_zoombounds) {
									var extent = new OpenLayers.Bounds(
											geoObj[0].boundingbox[2],
											geoObj[0].boundingbox[0],
											geoObj[0].boundingbox[3],
											geoObj[0].boundingbox[1])
											.transform(
													map.displayProjection,
													map.projection);
									map.zoomToExtent(extent);
								} else {
									var point = new OpenLayers.Geometry.Point(
											geoObj[0].lon,
											geoObj[0].lat)
											.transform(
													map.displayProjection,
													map.projection);
									var zoom = mapData.geosearch_zoomto;
									if (!zoom) {
										zoom = map.zoom;
									}
									map
											.moveTo(
													new OpenLayers.LonLat(
															point.x,
															point.y),
													zoom);
								}
							}
							loadingHide();
						},
						failure: function(e) {
							loadingHide();
							alert(mapData.labels.error_geocoding);
						},
						scope: this
					});

				};

				var searchDiv;
				if (mapData.geosearch_div) {
					searchDiv = document.getElementById(mapData.geosearch_div);
				}
				if (!searchDiv) {
					searchDiv = document.createElement('div');
					searchDiv.id = 'c4gMapsSearch';
					searchDiv.className = 'c4gMapsSearch';
					mapDiv.parentNode.insertBefore(searchDiv,mapDiv);
				}
				var geoSearch = document.createElement('input');
				geoSearch.id = 'c4gMapsSearchInput';
				geoSearch.className = 'c4gMapsSearchInput';
				searchDiv.appendChild(geoSearch);
				OpenLayers.Event.observe(geoSearch, 'keydown', function(event) { 
					if (event.keyCode == 13) {
						performSearch();
						return false;
					}
				});

				var geoButton = document.createElement('a');
				geoButton.href = '#';
				geoButton.id = 'c4gMapsSearchLink';
				geoButton.className = 'c4gMapsSearchLink';
				geoButton.innerHTML = mapData.labels.search_address;
				geoButton.onclick = function(event) { 
					performSearch();
					return false;
				};
				searchDiv.appendChild(geoButton);
			}

			// -----------------------------------------------
			// Geo Picking
			// -----------------------------------------------
			if (mapData.pickGeo) {
				if (!document.getElementById(mapData.geocoding_div)) {
					mapData.pickGeo = false;
				}
			}			
			if (mapData.pickGeo) {
				var geoObj = null;
				var addedLonLat = null;
				var geoList = null;
				var selectedPoint = null;

				var addPickItem = function(geox, geoy, index) {
					var point = new OpenLayers.Geometry.Point(geox, geoy)
							.transform(map.displayProjection, map.projection);
					var feature = new OpenLayers.Feature.Vector(point, {
						index : index
					});
					feature.lonlat = new OpenLayers.LonLat(point.x, point.y);
					pickLayer.addFeatures([ feature ]);
					return feature;
				};

				var removePickItems = function(geocoded, clicked) {
					for ( var i = pickLayer.features.length - 1; i >= 0; i--) {
						var feature = pickLayer.features[i];
						if ((clicked) && (feature.attributes.index == -1)) {
							pickLayer.removeFeatures([ feature ]);
							addedLonLat = null;
						}
						if ((geocoded) && (feature.attributes.index != -1)) {
							pickLayer.removeFeatures([ feature ]);
						}
					}
				};

				var setSelectedPoint = function(point) {
					selectedPoint = point;
					if (typeof (mapData.onPickGeo) == 'function') {
						mapData.onPickGeo(point.lon, point.lat);
					}
					if (mapData.geocoding_fieldx) {
						var el = document.getElementById(mapData.geocoding_fieldx);
						if (el){
							el.value = point.lon;
						}		
					}
					if (mapData.geocoding_fieldy) {
						var el = document.getElementById(mapData.geocoding_fieldy);
						if (el){
							el.value = point.lat;
						}		
					}

				};

				var setSelectedPickItem = function() {
					var index = -1;
					if (geoList !== null) {
						if (geoList.selectedIndex != -1) {
							index = geoList.value;
						}
					}
					if (index == -1) {
						if (addedLonLat !== null) {
							setSelectedPoint(addedLonLat);
						}
					} else {
						setSelectedPoint(geoObj[index]);
					}
				};

				var setFeatureSelected = function() {
					var index = -1;
					if (geoList !== null) {
						if (geoList.selectedIndex != -1) {
							index = geoList.value;
						}
					}
					for ( var i = pickLayer.features.length - 1; i >= 0; i--) {
						var feature = pickLayer.features[i];
						if (feature.attributes.index == index) {
							pickSelectFeature.unselectAll();
							pickSelectFeature.select(feature);
						}
					}
				};

				var aStylePoint = {
					strokeColor : '#ee0016',
					strokeWidth : 2,
					strokeOpacity : 1,
					graphicName : "",
					fillColor : '#ee0011',
					fillOpacity : 0.5,
					pointRadius : 7
				};
				var aStyleSelected = {
					strokeColor : '#000000',
					strokeWidth : 2,
					strokeOpacity : 1,
					graphicName : "",
					fillColor : '#000000',
					fillOpacity : 0.5,
					pointRadius : 7
				};
				var aStyleMap = new OpenLayers.StyleMap({
					'default' : aStylePoint,
					'selected' : aStyleSelected
				});
				var pickLayer = new OpenLayers.Layer.Vector('Locations', {
					displayInLayerSwitcher : false,
					styleMap : aStyleMap
				});
				map.addLayer(pickLayer);

				navOptions.defaultDblClick = function(event) {
					feature = pickLayer.getFeatureFromEvent(event);
					if ((typeof (feature) != 'undefined') && (feature !== null)) {
						map.moveTo(feature.lonlat, 14);
					} else {
						removePickItems(false, true);
						addedLonLat = map
								.getLonLatFromPixel(mousePosition.lastXy);
						addedLonLat.transform(map.projection,
								map.displayProjection);
						if (geoList !== null) {
							geoList.selectedIndex = -1;
						}
						addPickItem(addedLonLat.lon, addedLonLat.lat, -1);
						setSelectedPoint(addedLonLat);
						setFeatureSelected();
					}
				};

				if ((mapData.pickGeo_init_xCoord) || (mapData.pickGeo_init_yCoord)) {
					addPickItem(mapData.pickGeo_init_xCoord,
							mapData.pickGeo_init_yCoord, -1);
				}

				if (mapData.geocoding) {
					var performGeoSearch=function() {
						loadingShow();
						removePickItems(true, true);
						document.getElementById('c4gMapsGeoResults').innerHTML='';
						geoList = document.createElement('select');
						geoList.id = 'c4gGeopickResults';
						geoList.size = 5;
						geoList.style.width = mapDiv.offsetWidth + 'px';

						var option = document.createElement('option');
						option.value = 0;
						option.innerHTML = mapData.labels.geocoding_progress;
						geoList.appendChild(option);
						document.getElementById('c4gMapsGeoResults').appendChild(geoList);

						OpenLayers.Event.observe(geoList, 'change', function() {
							setSelectedPickItem();
							setFeatureSelected();
						});
						OpenLayers.Event.observe(geoList, 'dblclick', function() {
							map.moveTo(
								new OpenLayers.LonLat(selectedPoint.lon,selectedPoint.lat).transform(map.displayProjection,map.projection),
								14
							);
						});

						var viewbox = '';
						if (mapData.restrict_area) {
							viewbox = '&bounded=1&viewbox='
									+ mapData.restr_bottomleft_geox
									+ ','
									+ mapData.restr_bottomleft_geoy
									+ ',' + mapData.restr_topright_geox
									+ ',' + mapData.restr_topright_geoy;

						}

						OpenLayers.Request.GET({
							url: document.getElementsByTagName('base')[0].href	
								+ mapData.geocoding_url
								+ "?token="+mapData.REQUEST_TOKEN
								+ "&format=json&q="
								+ encodeURI(document.getElementById('c4gMapsGeoSearchInput').value)
								+ viewbox,
							success: function(ajaxRequest) {
								var textResponse = ajaxRequest.responseText;
								geoList.remove(0);

								var json = new OpenLayers.Format.JSON();					
								geoObj = json.read(textResponse);

								if (geoObj.length === 0) {
									var option = document.createElement('option');
									option.value = 0;
									option.innerHTML = mapData.labels.no_geo_results;
									geoList.appendChild(option);
								} else {
									for (var i=0, ii=geoObj.length; i<ii; ++i) {
										var item = geoObj[i];
										addPickItem(item.lon,item.lat,i);
										var option = document.createElement('option');
										option.value = i;
										option.innerHTML = item.display_name;
										geoList.appendChild(option);
									}
									geoList.value = 0;

									// position of map changed -> force redetermination of map element position for mouse handling
									map.events.clearMouseCache();

									map.zoomToExtent(pickLayer.getDataExtent());
									setSelectedPickItem();
									setFeatureSelected();
								}
								loadingHide();
							},
							failure: function(e) {
								loadingHide();
								var option = document.createElement('option');
								option.value = 0;
								option.innerHTML = mapData.labels.error_geocoding;
								geoList.appendChild(option);
							},
							scope: this
						});

					};

					var searchDiv = document.createElement('div');
					searchDiv.id = 'c4gMapsGeoSearch';
					searchDiv.className = 'c4gMapsGeoSearch';
					searchDiv.style.width = mapDiv.offsetWidth + 'px';
					document.getElementById(mapData.geocoding_div).appendChild(searchDiv);

					var geoSearch = document.createElement('input');
					geoSearch.id = 'c4gMapsGeoSearchInput';
					geoSearch.className = 'c4gMapsGeoSearchInput';
					geoSearch.style.width = (mapDiv.offsetWidth - 150)+'px';
					searchDiv.appendChild(geoSearch);
					OpenLayers.Event.observe(geoSearch, 'keydown', function(event) { 
						if (event.keyCode == 13) {
							performGeoSearch();
							return false;
						}
					});

					var geoButton = null;
					if (mapData.geocoding_usebutton) {
						geoButton = document.createElement('input');
						geoButton.type = 'button';
						geoButton.className = 'c4gMapsGeoSearchButton';
						geoButton.value = mapData.labels.search_address;
					} else {
						geoButton = document.createElement('a');
						geoButton.href = '#';
						geoButton.className = 'c4gMapsGeoSearchLink';
						geoButton.innerHTML = mapData.labels.search_address;
					}
					geoButton.onclick = function(event) { 
						performGeoSearch();
						return false;
					};					
					searchDiv.appendChild(geoButton);

					var geoResults = document.createElement('div');
					geoResults.id = 'c4gMapsGeoResults';
					geoResults.className = 'c4gMapsGeoResults';
					document.getElementById(mapData.geocoding_div).appendChild(geoResults);


					pickSelectFeature = new OpenLayers.Control.SelectFeature([ pickLayer ], {
						clickout : false,
						toggle : false,
						multiple : false,
						hover : false
					});
					map.addControl(pickSelectFeature);
					pickLayer.events.on({
						"featureselected" : function(e) {
							var value = -1;
							if ((geoList !== null)
									&& (geoList.selectedIndex != -1)) {
								value = geoList.value;
							}
							if (value != e.feature.attributes.index) {
								if (e.feature.attributes.index == -1) {
									if (geoList !== null) {
										geoList.selectedIndex = -1;
									}
								} else {
									if (geoList !== null) {
										geoList.value = e.feature.attributes.index;
									}
								}
								setSelectedPickItem();
							}
						}
					});
					pickSelectFeature.activate();

				}
				// -----------------------------------------------
				// End of Geo Picking
				// -----------------------------------------------

			}
			map.addControl(new OpenLayers.Control.Navigation(navOptions));
			if (mapData.mouse_nav_toolbar) {
				var navToolbar = new OpenLayers.Control.NavToolbar();
				map.addControl(navToolbar);
				if (!mapData.geocoding) {
					navToolbar.controls[0].events.on({
						"activate" : function() {
							if (selectFeature !== null)
								selectFeature.activate();
						},
						"deactivate" : function() {
							if (selectFeature !== null)
								// map.unloadDestroy is null when map is being destroyed!
								// -> avoid exception which comes up when deactivating selectFeature
								// while map is being destroyed
								if (map.unloadDestroy!==null)  
									selectFeature.deactivate();
						}
					});
				}

			}
		}

		// -----------------------------------------------
		// add editor
		// -----------------------------------------------
		var editor = null;
		if (mapData.editor) {
			editor = C4GMapsEditor(mapData,map,styles);
		}
		// ----------

		// -----------------------------------------------
		// add Toolbox
		// -----------------------------------------------
		var toolbox = null;
		if (typeof( C4GMapsToolbox ) == 'function') {
			toolbox = C4GMapsToolbox( mapData, map );
		}
		// ----------
		

		if (mapData.scaleline) {
			map.addControl(new OpenLayers.Control.ScaleLine());
		}

		if (mapData.zoomlevel || mapData.mouseposition) {
			var zoomPositionPanelDiv = document.createElement('div');
			zoomPositionPanelDiv.id = 'C4GMapsZoomPositionPanel_' + mapData.id;
			zoomPositionPanelDiv.className = 'olControlZoomPositionPanel olControlNoSelect';
			zoomPositionPanelDiv.style.position = 'absolute';
			zoomPositionPanelDiv.style.zIndex = '1024';
			
			if (mapData.zoomlevel) {
				var zoomDiv = document.createElement('div');
				zoomDiv.id = 'C4GMapsZoomlevel_' + mapData.id;
				zoomDiv.className = 'olControlZoomlevel';
				zoomPositionPanelDiv.appendChild(zoomDiv);
				map.addControl(new OpenLayers.Control.C4g_Zoomlevel({div : zoomDiv}));
			}
			if (mapData.mouseposition) {
				var positionDiv = document.createElement('div');
				positionDiv.id = 'C4GMapsMousePosition_' + mapData.id;
				positionDiv.className = 'olControlMousePosition';
				zoomPositionPanelDiv.appendChild(positionDiv);
				var mousePosition = new OpenLayers.Control.MousePosition({div : positionDiv, emptyString : 'n/a'});
				map.addControl(mousePosition);
			}
			map.viewPortDiv.appendChild(zoomPositionPanelDiv);
		}

		// if (mapData.zoomlevel) {
		// 	var zoomLevel = new OpenLayers.Control.C4g_Zoomlevel();
		// 	map.addControl(zoomLevel);
		// }
		
		// if (mapData.mouseposition) {
		// 	if (mapData.zoomlevel) {
		// 		var mousePosition = new OpenLayers.Control.MousePosition({div : zoomLevel.div});
		// 	} else {
		// 		var mousePosition = new OpenLayers.Control.MousePosition();
		// 	}
		// 	map.addControl(mousePosition);
		// }

		if (mapData.permalink) {
			if (mapData.ls_tree) {
				// map.addControl(new OpenLayers.Control.C4GArgParser());
				map.addControl(new OpenLayers.Control.C4GPermalink());
			} else {
				// map.addControl(new OpenLayers.Control.ArgParser());
				map.addControl(new OpenLayers.Control.Permalink());
			}
		}

		if (mapData.attribution) {
			var options = {};
			options.template = '${layers}';
			options.separator = ' / ';
			if (mapData.div_attribution) {
				options.div = document.getElementById(mapData.div_attribution);
			}	
			if (mapData.add_attribution) {
				options.template += ' '+mapData.add_attribution;
			}
			/**
			 * Attribution Text for the searchengine
			 */
			switch (mapData.geosearch_engine) {
				case '3':
					OpenLayers.Util.OSM.GEOSEARCH_ATTRIBUTION = mapData.geosearch_customengine_attribution;
					break;
				case '2':
					OpenLayers.Util.OSM.GEOSEARCH_ATTRIBUTION = 
						'Nominatim Search Courtesy of <a href="http://www.mapquest.com/" target="_blank">MapQuest</a> <img src="http://developer.mapquest.com/content/osm/mq_logo.png" alt="" />';
					break;
				case '1':
				default:
					OpenLayers.Util.OSM.GEOSEARCH_ATTRIBUTION = 
						'Nominatim Search Courtesy of <a href="http://wiki.openstreetmap.org/wiki/Nominatim_usage_policy" target="_blank">OpenStreetMap</a>';
					break;
			}
			if (mapData.geosearch || mapData.geocoding) {
				if (mapData.geosearch_attribution || mapData.geocoding_attribution) {
					options.template += ' / ' + OpenLayers.Util.OSM.GEOSEARCH_ATTRIBUTION;
				}
				
			}
			map.addControl(new OpenLayers.Control.Attribution(options));
		}

		if (mapData.overviewmap) {
			map.addControl(new OpenLayers.Control.OverviewMap());
		}

		// -----------------------------------------------
		// set map extent
		// -----------------------------------------------
		if (!map.getCenter()) { // Center and Zoom may be already set in case a permalink was called
			mapData.extentSetForEditor = false;
			if (mapData.editor_input) {
				var bounds = editor.editLayer.getDataExtent();
				if (bounds) {
					map.zoomToExtent(bounds);
					mapData.extentSetForEditor = true;
				}				
			}
			if (!mapData.extentSetForEditor) {
				if (mapData.calc_extent == 'LOCATIONS') {					

					if (vectorLayers.length > 0) {
						var bounds = new OpenLayers.Bounds();
						for (var i=0; i<vectorLayers.length; i++) {
							bounds.extend( vectorLayers[i].getDataExtent() );
						}
						if (isNaN(bounds.getWidth())) {
							map.zoomToMaxExtent();
						}
						else {
							map.zoomToExtent(bounds);
							map._zoomedToLocations = true;
						}	
					}
					else {
						map.zoomToMaxExtent();						
					}	
				} else {
					if (mapData.calc_extent != 'ID') {
						map.setCenter(new OpenLayers.LonLat(
								parseFloat(mapData.center_geox),
								parseFloat(mapData.center_geoy)).transform(
								new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
								new OpenLayers.Projection("EPSG:900913") // to Spherical Mercator Projection
						), mapData.zoom);
					}	
				}
			}	
		}

		// -----------------------------------------------------
		// call geocoding API on browsers supporting it
		// -----------------------------------------------------
		if (mapData.geolocation) {
			if (typeof(navigator.geolocation)!='undefined') {
				navigator.geolocation.getCurrentPosition(function(position){					
					var zoom = mapData.zoom;
					if (mapData.geolocation_zoom) {
						zoom = mapData.geolocation_zoom;
					}
					map.setCenter(new OpenLayers.LonLat(
							position.coords.longitude,
							position.coords.latitude).transform(
							new OpenLayers.Projection("EPSG:4326"), // transform from WGS 1984
							new OpenLayers.Projection("EPSG:900913") // to Spherical Mercator Projection
					), zoom);					
				}, function(){
					// nothing to do, geocoding api has no results
				});
			}    
			
		}
		
		var popupLayers = vectorLayers;
		var linkLayers = vectorLayers;
		var allLayers = vectorLayers;

		// -----------------------------------------------
		// Import GPX/KML/GeoJSON/OSM data
		// -----------------------------------------------
		var fnExecStyleHook = function(features, data, highlighted) {
			if (data.fnstyle) {
				// Javascript Hook for user defined styling
				if (eval('typeof(' + data.fnstyle + ') == "function"')) {
					var fnstyle = eval(data.fnstyle);
					fnstyle(features, data, map, highlighted);
				}
			}
		};

		function setStyle(features, data) {
			var defStyle = {};
			var style = {};
			var arrows = [];
			if (data.locstyle !== null) {
				defStyle = OpenLayers.Util.extend(defStyle, styles[data.locstyle]);
			}
			for ( var i = 0; i < features.length; i++) {
				if (typeof (features[i].attributes) == 'undefined') {
					features[i].attributes = {};
				}
				style = OpenLayers.Util.extend(null,defStyle);
				if (features[i].attributes.locstyle) {
					if (styles[features[i].attributes.locstyle]) {
						style = {};
						style = OpenLayers.Util.extend(style, styles[features[i].attributes.locstyle]);
					}	
				} 
				if ((data.popupInfo) || (style.popupInfo !== undefined) || (data.linkurl) || (style.onclick_zoomto !== undefined) || (data.onclick_zoomto > 0) || data.popupRouteTo) {
					style.cursor = "pointer";
				}

				var fnReplaceAttribs = function(str, match) {

					if (match.substr(0, 2) == 'FN') {
						// use ${FN<functionName>}, so functionName(feature) is called
						// to get the text to be inserted
						var func = eval(match.substr(2));
						if (typeof func == 'function') {
							return func(features[i],style);
						} else {
							return '';
						}						
					} else {
						// use ${attribName} to insert feature attributes
						return (features[i].attributes[match] ? features[i].attributes[match]
								: '');
					}
				};
				
				if (data.linkurl) {
					features[i].attributes.linkUrl = data.linkurl.replace(
							C4GMapsUtils.extTokenRegEx, fnReplaceAttribs);
				}
				if (style.popupInfo!==undefined) {
					features[i].attributes.popupInfo = style.popupInfo.replace(
							C4GMapsUtils.extTokenRegEx, fnReplaceAttribs);

				} else if (data.popupInfo) {
					features[i].attributes.popupInfo = data.popupInfo.replace(
							C4GMapsUtils.extTokenRegEx, fnReplaceAttribs);
				}

				if (mapData.popupExtend && mapData.popupExtend[features[i].fid]) {
					var overrideStyle;
					for (var y = 0 ; y < mapData.popupExtend[features[i].fid].length; y++) {
						// if (typeof wswgEditor != 'undefined'){
						// 	features[i].attributes.popupInfo = features[i].attributes.popupInfo + wswgEditor.parseBBCode( mapData.popupExtend[features[i].fid][y]['text'] );
						// } else {
						 	features[i].attributes.popupInfo = features[i].attributes.popupInfo + '<p>' + mapData.popupExtend[features[i].fid][y]['text'] + '</p>';
						// }
						//if (mapData.popupExtend[features[i].fid][y]['locstyle']){
							overrideStyle = mapData.popupExtend[features[i].fid][y]['locstyle']
							// features[i].attributes.style = mapData.popupExtend[features[i].fid][y]['locstyle'];
						//}
					};
					if (overrideStyle) {
						style = {};
						style = OpenLayers.Util.extend(style, styles[overrideStyle]);
						style.cursor = "pointer";
					};
				}

				features[i].attributes.popupRouteTo = data.popupRouteTo;
				if (style.label!==undefined) {
					style.label = style.label.replace(
							C4GMapsUtils.extTokenRegEx, fnReplaceAttribs);
				}
				else if (data.label) {
					style.label = data.label.replace(
							C4GMapsUtils.extTokenRegEx, fnReplaceAttribs);
				}

				if (style.graphicTitle!==undefined) {
					style.graphicTitle = style.graphicTitle.replace(
							C4GMapsUtils.extTokenRegEx, fnReplaceAttribs);

				} else if (data.graphicTitle) {					
					style.graphicTitle = data.graphicTitle.replace(
							C4GMapsUtils.extTokenRegEx, fnReplaceAttribs);
				}
				features[i].attributes.clickZoomTo = (style.onclick_zoomto === undefined ? data.onclick_zoomto : style.onclick_zoomto);
				features[i].attributes.minZoom = (style.minzoom === undefined ? (data.minzoom > 0 ? data.minzoom : 0): style.minzoom);
				features[i].attributes.maxZoom = (style.maxzoom === undefined ? (data.maxzoom > 0 ? data.maxzoom : 999): (style.maxzoom > 0 ? style.maxzoom : 999));
				features[i].attributes.inputdata = data;

				if (typeof (features[i].style) == 'undefined'
						|| (features[i].style === null)) {
					features[i].style = {};
				}


				var styleOrg = {};
				OpenLayers.Util.extend(styleOrg, features[i].style);
				OpenLayers.Util.extend(features[i].style, style);
				OpenLayers.Util.extend(features[i].style, styleOrg);

				if (style.arrowRadius>0) {

					// generate arrows
					arrows = arrows.concat(C4GMapsUtils.createArrows(features[i],features[i].geometry));
				}

			}	
			if (arrows.length>0)		
				features = features.concat(arrows);
			fnExecStyleHook(features, data, false);
			return features;
		}

		OpenLayers.Strategy.ZoomLimitedBBOX = OpenLayers.Class(OpenLayers.Strategy.BBOX, {
			// based on Overpass API demo
			ratio : 1.1,

			update : function(options) {
				var mapBounds = this.getMapBounds();
				if (this.layer
						&& this.layer.map
						&& this.layer.map.getZoom() < this.minzoom) {
					zoom_valid = false;
					this.layer.inRange = false;
					this.bounds = null;
				} else if (mapBounds !== null
						&& this.layer.visibility
						&& ((options && options.force)
								|| this
										.invalidBounds(mapBounds) || options.type == "visibilitychanged")) {
					zoom_valid = true;
					this.layer.inRange = true;
					this.calculateBounds(mapBounds);
					this.resolution = this.layer.map
							.getResolution();
					this.triggerRead(options);
				}
			},
			CLASS_NAME : "OpenLayers.Strategy.ZoomLimitedBBOX"
		});

		function getLimitedBBoxStrategy(data) {
			var strat = new OpenLayers.Strategy.ZoomLimitedBBOX({
				minzoom : data.minzoom,
				maxzoom : data.maxzoom
			});
			return strat;
		}

		var layerSort = 0;
		for (var key in mapData.data) {
			var data = mapData.data[key];
			if (typeof(data)!='object') {
				continue;
			}
			var options;
			var importFormat = null;
			if (data.type == 'gpx') {
				options = {
					externalProjection : new OpenLayers.Projection("EPSG:4326"),
					internalProjection : map.getProjectionObject()
				};
				importFormat = new OpenLayers.Format.GPX(options);
			} else if (data.type == 'kml') {
				options = {
					internalProjection : map.getProjectionObject(),
					extractStyles : true,
					extractAttributes : true,
					maxDepth : 2
				};
				importFormat = new OpenLayers.Format.KML(options);
			} else if (data.type == 'geojson') {
				options = {
					internalProjection : map.getProjectionObject()
				};
				if (typeof(data.projection)!='undefined') {
					options.externalProjection = new OpenLayers.Projection(data.projection);
				}	
				importFormat = new OpenLayers.Format.GeoJSON(options);
			} else if ((data.type == 'osm') || (data.type == 'overpass')) {
				options = {
					internalProjection : map.getProjectionObject(),
					forceNodes : data.forcenodes
				};
				importFormat = new OpenLayers.Format.OSM.Extended(options);
			}
			var layerName = '';
			if ((data.layername !== undefined) && (data.layername !== null)){
				layerName = data.layername;
			}
			var options = {
				parent : data.parent,
				key : data.id,
				displayInLayerSwitcher : (layerName !== ''),
				visibility : (!data.hidelayer)
			};
			if (options.displayInLayerSwitcher) {
				layerSort = layerSort + 1;
				options.sort = layerSort;
			}	
			var strategies = [];
			var zoomFilterStrategy = getZoomFilterStrategy();
			if ((data.type == 'overpass') && (data.ovp_bbox_limited)) {
				strategies.push(getLimitedBBoxStrategy(data));
				options.protocol = new OpenLayers.Protocol.HTTP({
					url : data.url,
					reqdata : data.ovp_request,
					format : importFormat,
					filterToParams : function(filter, params) {
						var bounds = filter.value
								.clone()
								.transform(
										map.projection,
										new OpenLayers.Projection(
												"EPSG:4326"));
						var str = '<bbox-query s="'
								+ bounds.bottom + '" n="'
								+ bounds.top + '" w="'
								+ bounds.left + '" e="'
								+ bounds.right + '"/>';
						params.data = this.reqdata.replace(
								/\(bbox\)/g, str);
						if (mapData.profile) {
							params.profile = mapData.profile;
							params.token = mapData.REQUEST_TOKEN;
						}
						return params;
					}
				});
			}
			strategies.push(zoomFilterStrategy);
			options.strategies = strategies;
			var importLayer = new OpenLayers.Layer.Vector(layerName,
					options);

			if ((data.type == 'overpass') && (data.ovp_bbox_limited)) {
				importLayer.layerData = data;
				importLayer.zoomStrat = zoomFilterStrategy;
				importLayer.events.on({
					"loadstart" : function() {
						this.zoomStrat.deactivate();
						loadingShow();
					},
					"loadend" : function() {
						loadingHide();
						setStyle(this.features, this.layerData);
						this.zoomStrat.activate();
						setZoomFilter(this.zoomStrat);
						this.redraw();
					}
				});
			}
			map.addLayer(importLayer);

			var fnAdjustBounds=function(layer) {
				if (!mapData.extentSetForEditor) {
					if ((mapData.calc_extent == 'LOCATIONS') || ( (mapData.calc_extent == 'ID') && (mapData.calc_extent_id == layer.key) )) {
						var bounds = map.getExtent();
						if (!map._zoomedToLocations) {
							bounds = null;
						}
						var extBounds = importLayer.getDataExtent();
						if (extBounds!==null) {
							extBounds.transform(importLayer.projection,map.projection);
							if (bounds === null) {
								bounds = extBounds;
							}
							else {
								bounds.extend(extBounds);
							}
							map.zoomToExtent(bounds);
							map._zoomedToLocations = true;
						}
					}				
				}
			};
			
			if (importFormat) {
				if (data.filecontent) {
					importLayer.addFeatures(setStyle(importFormat
							.read(data.filecontent), data));
					fnAdjustBounds(importLayer);		
				}
				if (data.content) {
					importLayer.addFeatures(setStyle(importFormat
							.read(data.content), data));
					fnAdjustBounds(importLayer);		
				}


				if (data.url) {
					fnHandleUrlRequest = function(importLayer, importFormat,
							data) {
						var fnRequestHandler = function urlRequestHandler(
								request) {
							importLayer.addFeatures(setStyle(importFormat
									.read(request.responseText), data));
							fnAdjustBounds(importLayer);		
						};
						var url = data.url;
						if (data.type == 'overpass') {
							url = url + '?data='
									+ encodeURIComponent(data.ovp_request);
						}
						OpenLayers.Request.GET({
							url : url,
							callback : fnRequestHandler
						});
					};
					if (!data.ovp_bbox_limited) {
						fnHandleUrlRequest(importLayer, importFormat, data);
					}
				}
			}	
			if (data.linkurl || (data.onclick_zoomto>0)) {
				linkLayers.push(importLayer);
				popupLayers.push(importLayer); // this is needed					
			} else if (data.popupInfo || data.popupRouteTo) {
				popupLayers.push(importLayer);
			}
			allLayers.push(importLayer);

		}
		
		// create children for C4GLayerSwitcher
		if (typeof(OpenLayers.Control.C4GLayerSwitcher)=='function') {
			for(var i=0; i<allLayers.length; i++) {
				if (allLayers[i].parent) {
					for(var j=0; j<allLayers.length; j++) {
						if (allLayers[j].key == allLayers[i].parent) {
							if (typeof(allLayers[j].children)=='undefined') {
								allLayers[j].children = [];
							}
							allLayers[j].children.push(allLayers[i]);
							if (!allLayers[i].displayInLayerSwitcher) {
								// inherit visibility from parent
								allLayers[i].setVisibility(allLayers[j].getVisibility());
							}
							break;
						}
					}
				}
			}
		}

		// -----------------------------------------------
		// add layer switcher
		// -----------------------------------------------
		var layerSwitcher;
		map.layerSwitcher = layerSwitcher;
		if (mapData.layerSwitcher) {
			var options = {};
			if (mapData.div_layerswitcher) {
				options.div = OpenLayers.Util
						.getElement(mapData.div_layerswitcher);
				OpenLayers.Element.addClass(options.div,
						"olControlLayerSwitcher");
			}
			if (typeof(OpenLayers.Control.C4GLayerSwitcher)=='function') {
				layerSwitcher = new OpenLayers.Control.C4GLayerSwitcher(options);
				layerSwitcher.extended = true;
			}
			else {
				layerSwitcher = new OpenLayers.Control.LayerSwitcher(options);
				layerSwitcher.orgLoadContents = layerSwitcher.loadContents;
				layerSwitcher.loadContents = function() {
					layerSwitcher.orgLoadContents();
					layerSwitcher.maximizeDiv.childNodes[0].src = OpenLayers.Util.getImageLocation('starboard-maximize.png');
				};
			}
			map.addControl(layerSwitcher);
			if (typeof(mapData.switcher_class)!='undefined') {
				OpenLayers.Element.addClass(layerSwitcher.div,mapData.switcher_class);
			}	
			if ((mapData.layerSwitcherOpen) || (mapData.div_layerswitcher)) {
				layerSwitcher.maximizeControl();
			}
			else {
				layerSwitcher.div.style.border = 'none';
			}			
			if (mapData.div_layerswitcher) {
				layerSwitcher.maximizeDiv.style.display = "none";
				layerSwitcher.minimizeDiv.style.display = "none";
			}
		}

		
		// -----------------------------------------------
		// Popups via selectFeature
		// -----------------------------------------------
		var selectFeature = null;
		if (!mapData.geocoding && (!mapData.editor || mapData.fe_editor)) {
			selectFeature = new OpenLayers.Control.SelectFeature(popupLayers, {
				clickout : false,
				toggle : false,
				multiple : false,
				hover : mapData.hover_popups
			});
			map.addControl(selectFeature);
		}

		var popup = null;
		for ( var i = 0; i < popupLayers.length; i++) {
			var popupLayer = popupLayers[i];
			popupLayer.events.on({
				"featureunselected" : function(e) {
					if ((mapData.hover_popups)
							&& (!mapData.hover_popups_stay)) {
						if (popup !== null) {
							popup.destroy();
							popup = null;
						}
					}
					if (highlightCtrl) {
						highlightCtrl.unhighlight(e.feature);
					}
				},

				"featureselected" : function(e) {
					var anchor = {
						size : new OpenLayers.Size(0, 0),
						offset : new OpenLayers.Pixel(
								e.feature.style.popupXOffset,
								e.feature.style.popupYOffset)
					};
					if (popup !== null) {
						popup.destroy();
						popup = null;
					}
					if (((typeof (e.feature.attributes.popupInfo) != 'undefined')
							&& (e.feature.attributes.popupInfo != '<p></p>')
							&& (e.feature.attributes.popupInfo !== null)
							&& (e.feature.attributes.popupInfo !== ''))
							   || e.feature.attributes.popupRouteTo) {
						var popupClass = e.feature.style.popupClass ? eval(e.feature.style.popupClass)
								: OpenLayers.Popup.Anchored;
						var closeBox = (!mapData.hover_popups)
								|| (mapData.hover_popups_stay);
						var popupPos = e.feature.lonlat;
						if (!popupPos) {
							// @TODO: delete this, if no unexpected side-effects appear
							// does not work properly with "mouseposition"
							// if (mousePosition) {
							// 	popupPos = map
							// 			.getLonLatFromPixel(mousePosition.lastXy);
							// } else {
								popupPos = e.feature.geometry
										.getBounds().getCenterLonLat();
							// }

						}
						var popupSize = null;
						if (typeof (e.feature.style.popupXSize) != 'undefined'
								&& typeof (e.feature.style.popupXSize) != 'undefined') {
							popupSize = new OpenLayers.Size(
									e.feature.style.popupXSize,
									e.feature.style.popupYSize);
						}


						if (map.layers.length>0) {
							var index = parseInt(map.layers[map.layers.length-1].getZIndex(),10)+100;
							if (index<750) {
								index = 750;
							}
							map.Z_INDEX_BASE.Popup = index;
						}	
						var popupInfo='';
						if (e.feature.attributes.popupInfo) 
							popupInfo = e.feature.attributes.popupInfo;
						if (router && e.feature.attributes.popupRouteTo) {
							popupInfo = router.prepareLocateButtons(popupInfo);
						}	
						if (typeof wswgEditor != 'undefined'){
							popupInfo = wswgEditor.parseBBCodeIgnoreHtml( popupInfo );
						}
						popup = new popupClass("0", popupPos, popupSize, popupInfo, anchor,	closeBox);

						map.addPopup(popup);
						if (router && e.feature.attributes.popupRouteTo) {
							router.registerLocateButtons(popup.contentDiv,e.feature);
						}
					}
					if (!mapData.hover_popups) {
						if (selectFeature !== null) {
							selectFeature.unselectAll();
						}
					}
				}
			});
		}
		if (selectFeature !== null) {
			selectFeature.activate();
		}

		// -----------------------------------------------
		// Highlighting on hover
		// -----------------------------------------------
		if (!mapData.geocoding && (!mapData.editor || mapData.fe_editor)) {
			var highlightCtrl = new OpenLayers.Control.SelectFeature(allLayers,{
				hover : true,
				highlightOnly : true,
				eventListeners : {
					beforefeaturehighlighted : function(e) {
						if ((e.feature.style) && (e.feature.style.hoverStyle)) {
							if (typeof (e.feature.attributes) == 'undefined') {
								e.feature.attributes = {};
							}
							e.feature.attributes.orgStyle = e.feature.style;
							e.feature.style = OpenLayers.Util.extend(null,styles[e.feature.style.hoverStyle]);
							e.feature.style.label = e.feature.attributes.orgStyle.label;
							e.feature.style.graphicTitle = e.feature.attributes.orgStyle.graphicTitle;
							if (e.feature.attributes.inputdata) {
								fnExecStyleHook([ e.feature ],e.feature.attributes.inputdata,true);
							}
						}
					},
					featureunhighlighted : function(e) {
						if ((typeof (e.feature.attributes) != 'undefined')
								&& (typeof (e.feature.attributes.orgStyle) != 'undefined')) {
							e.feature.style = e.feature.attributes.orgStyle;
							e.feature.layer.redraw();
						}
					}
				}
			});
			map.addControl(highlightCtrl);
			highlightCtrl.activate();
		}	

		// -----------------------------------------------
		// Links via Click
		// -----------------------------------------------
		OpenLayers.Control.Click = OpenLayers.Class(OpenLayers.Control,	{
			defaultHandlerOptions : {
				'single' : mapData.link_open_on != 'DBLCL',
				'double' : mapData.link_open_on == 'DBLCL',
				'pixelTolerance' : 0,
				'stopSingle' : false,
				'stopDouble' : false
			},
			initialize : function(options) {
				this.handlerOptions = OpenLayers.Util.extend(
						{}, this.defaultHandlerOptions);
				OpenLayers.Control.prototype.initialize.apply(
						this, arguments);
				this.handler = new OpenLayers.Handler.Click(this, {
					'click' : this.jumpToLink,
					'dblclick' : this.jumpToLink
				}, this.handlerOptions);
			},
			jumpToLink : function(e) {
				var feature = null;
				for ( var i = 0; i < linkLayers.length; i++) {
					if (linkLayers[i].getVisibility()) {
						feature = linkLayers[i]
								.getFeatureFromEvent(e);
						if (feature !== null) {
							break;
						}
					}	
				}
				if ((typeof (feature) != 'undefined')
						&& (feature !== null)) {
					if (feature.c4gOnClick) {
						feature.c4gOnClick(feature,e);
					}
					else {	
						if (feature.attributes.linkUrl) {							
							var linkUrl = feature.attributes.linkUrl;
							if ((ieVersion > 0) && (ieVersion <= 7)) {
								if ((linkUrl.indexOf(':') < 0)
										&& (linkUrl[0] != '/')) {
									// since qualifyUrl() doesn't work in IE7, and relative URL
									// goes wrong strip index.php here, because otherwise it will
									// be there twice
									linkUrl = linkUrl.replace(
											'index.php/', "");
								}
							}
							linkUrl = qualifyURL(linkUrl);

							if (mapData.link_newwindow) {
								window.open(linkUrl, 'Window');
							} else {
								window.location = linkUrl;
							}
						} else {
							var zoomedToPos=false;
							if (feature.attributes.clickZoomTo > 0) {
								var zoomToPos = feature.lonlat;
								var zoomlevel = feature.attributes.clickZoomTo;
								if (!zoomToPos) {
									if (zoomlevel >= 100) {
										// Zoomlevel >= 100 -> Zoom to mouse position, 
										zoomToPos = map
												.getLonLatFromPixel(e.xy);
										zoomlevel = zoomlevel - 100;
									} else {
										// Zoom to the center of the feature
										zoomToPos = feature.geometry
												.getBounds()
												.getCenterLonLat();
									}
								}
								if (zoomToPos) {
									var tmpExtent = map.getExtent();
									if (zoomlevel == 99) {
										map.zoomToExtent(feature.geometry.getBounds());
									} else {
										map.setCenter(zoomToPos, zoomlevel, true, true);
										map.events.triggerEvent("moveend");
									}
									zoomedToPos = !map.getExtent().equals(tmpExtent);

									if (zoomedToPos) {
										// unhighlight
										if (feature.attributes.orgStyle) {
											feature.style = feature.attributes.orgStyle;
										}

										// unselect all
										if (selectFeature !== null) {
											selectFeature.unselectAll();
										}

										// close popup
										if (popup !== null) {
											popup.destroy();
											popup = null;
										}

										for ( var i = 0; i < map.layers.length; i++) {
											map.layers[i].redraw();
										}
									}	
								}

							}
							if (!zoomedToPos) {
								if (!mapData.hover_popups) {
									// trigger popup
									if (selectFeature !== null) {
										selectFeature.clickFeature(feature);
									}
									
								}
							}
						}
					}	
				}
			}

		});

		var click = new OpenLayers.Control.Click();
		map.addControl(click);
		if (!mapData.pickGeo) {
			click.activate();
		}

		map.addLinkLayer = function(layer) {
			linkLayers.push(layer);
		};

		// -----------------------------------------------
		// Auto height / Auto width
		// -----------------------------------------------
		if ((mapData.auto_height) || (mapData.auto_width)) {

			var fnResizeMap = function() {
				var winSize = {};
				if (typeof (jQuery) == "function") {
					winSize.x = jQuery(window).width();
					winSize.y = jQuery(window).height();
				}
				else {
					winSize = Window.getSize();
				}	
				var oldCenter = map.getCenter();
				var newHeight = 0;
				var newWidth = 0;
				if (mapData.auto_height) {
					newHeight = Math.max(winSize.y - map.div.getBoundingClientRect().top - mapData.auto_height_gap,mapData.auto_height_min);
					if ((mapData.auto_height_max>0) && (mapData.auto_height_max<newHeight)) {
						newHeight = mapData.auto_height_max;
					}

					map.div.style.height = newHeight+'px';
				} 
				else {
					// newHeight is set for the layerSwitcher to be sized correctly
					newHeight = OpenLayers.Element.getHeight(map.div);
				}
				if (mapData.auto_width) {
					newWidth = Math.max(winSize.x - map.div.getBoundingClientRect().left - mapData.auto_width_gap,mapData.auto_width_min);
					if ((mapData.auto_width_max>0) && (mapData.auto_width_max<newWidth)) {
						newWidth = mapData.auto_width_max;
					}
					map.div.style.width = newWidth+'px';

					var searchInput = document.getElementById('c4gMapsSearchInput');
					var searchLink = document.getElementById('c4gMapsSearchLink');
					if (searchInput) {						
						searchInput.style.width = (newWidth - searchLink.offsetWidth - 18)+'px';
					}
				} 

				map.setCenter(oldCenter);
				map.updateSize();

				if ((newHeight>0) && (layerSwitcher) && (layerSwitcher.extended)) {
					if (layerSwitcher.layersDiv.style.display != 'none') {
						var heightSub = 130;
						if (!mapData.overviewmap)
							heightSub = 70;
						layerSwitcher.div.style.height = (newHeight-heightSub)+'px';
					   
						// jQuery can be used here because the extended LayerSwitcher uses it anyway
						var jsp = jQuery('.olControlLayerSwitcher').data('jsp');
						if (jsp) {
							jQuery(layerSwitcher.dataLayersDiv).find('li a').blur();
							jsp.destroy();
						}  

						if (typeof(jQuery.fn.jScrollPane)=='function') {
							jQuery('.olControlLayerSwitcher').css('width','auto');
							jQuery('.olControlLayerSwitcher').css('height',layerSwitcher.div.style.height);
							jQuery('.olControlLayerSwitcher').jScrollPane();
						}
					}
				}
								
			};

			var fnResizeBrowserWindow = function() {
				if (window.c4gTimeoutId) {
					window.clearTimeout(window.c4gTimeoutId);
					delete window.c4gTimeoutId;							
				}
				window.c4gTimeoutId = window.setTimeout(function() {
					delete window.c4gTimeoutId;
					fnResizeMap();
				}, 50);
			};

			if ((typeof (MooTools) == "object") && (typeof(window.addEvent)=='function')) {
				window.addEvent("domready", function() {					
					window.addEvent('resize', fnResizeBrowserWindow);  
					fnResizeMap();
				});
			} else if (typeof (jQuery) == "function") {
				jQuery(document).ready(function() {
					jQuery(window).resize(fnResizeBrowserWindow);
					fnResizeMap();					
				});
			} else {
				// resizing is ignored, because MooTools or jQuery is required for this function to work.
			}
			fnResizeMap();

			if ((layerSwitcher) && (layerSwitcher.extended)) {
				// jQuery can be used here because the extended LayerSwitcher uses it anyway
				layerSwitcher.minimizeControl = function(e) {
					var jsp = jQuery('.olControlLayerSwitcher').data('jsp');
					if (jsp) {
						jQuery(layerSwitcher.dataLayersDiv).find('li a').blur();
						jsp.destroy();
					}
					jQuery('.olControlLayerSwitcher')
						.css('height','0px')
						.css('width','0px')
						.css('border','none')
						.css('overflow','');

					this.div = jQuery('.olControlLayerSwitcher').get(0);
					this.showControls(true);
					if (e) {
						OpenLayers.Event.stop(e);
					}
				};

				layerSwitcher.maximizeControl = function(e) {
					jQuery('.olControlLayerSwitcher').css('border','');
					this.showControls(false);
					fnResizeMap();
					fnSetCheckedLayer();
					if (e) {
						OpenLayers.Event.stop(e);
					}
				};

				if (mapData.layerSwitcherOpen) {
					// workaround for wrong width of switcher when it is opened initially
					layerSwitcher.minimizeControl();
					window.setTimeout(function() {
						layerSwitcher.maximizeControl();					
					}, 1000);
				}	

				jQuery('.olControlLayerSwitcher .baseLbl').click( function() {
					jQuery(this).next().toggle();
					fnResizeMap();
				}).css('cursor','pointer');
				jQuery('.olControlLayerSwitcher .dataLbl').click( function() {
					jQuery(this).next().toggle();
					fnResizeMap();
				}).css('cursor','pointer');

				var fnSetCheckedLayer = function() {					
					jQuery(layerSwitcher.baseLayersDiv).children("input").each(
						function(i,el) { if(map.baseLayer.id==el._layer) {el.checked=true;} }
					);
				};
				fnSetCheckedLayer();

				if (layerSwitcher.dynaTree) {
					layerSwitcher.dynaTree.options.onExpand = function(flag,node) { 
						fnResizeMap();    
					};
				}
			}
		}

		// -----------------------------------------------
		// execute userdefined script
		// -----------------------------------------------
		if ((typeof (mapData.script) != 'undefined') && (mapData.script !== '')) {
			eval(mapData.script);
		}

		
	}

}

/**
 * Namespace: Util.OSM
 */
OpenLayers.Util.OSM = {};

/**
 * Constant: MISSING_TILE_URL
 * {String} URL of image to display for missing tiles
 */
OpenLayers.Util.OSM.MISSING_TILE_URL = "http://wiki.openstreetmap.org/w/images/b/bc/404.png";

/**
 * Property: originalOnImageLoadError
 * {Function} Original onImageLoadError function.
 */
OpenLayers.Util.OSM.originalOnImageLoadError = OpenLayers.Util.onImageLoadError;

/**
 * Function: onImageLoadError
 */
OpenLayers.Util.onImageLoadError = function() {
	if (this.src.match(/^http:\/\/[abc]\.[a-z]+\.openstreetmap\.org\//)) {
		this.src = OpenLayers.Util.OSM.MISSING_TILE_URL;
	} else if (this.src.match(/^http:\/\/[def]\.tah\.openstreetmap\.org\//)) {
		// do nothing - this layer is transparent
	} else {
		OpenLayers.Util.OSM.originalOnImageLoadError();
	}
};

OpenLayers.Util.OSM.DEFAULT_ATTRIBUTION =
	"Data &copy; <a href='http://www.openstreetmap.org/copyright'>OpenStreetMap</a> contributors";

/**
 * Class: OpenLayers.Layer.OSM.Mapnik
 *
 * Inherits from:
 *  - <OpenLayers.Layer.OSM>
 */
OpenLayers.Layer.OSM.Mapnik = OpenLayers.Class(OpenLayers.Layer.OSM, {
	/**
	 * Constructor: OpenLayers.Layer.OSM.Mapnik
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */
	initialize : function(name, options) {
		var url = [ "http://a.tile.openstreetmap.org/${z}/${x}/${y}.png",
				"http://b.tile.openstreetmap.org/${z}/${x}/${y}.png",
				"http://c.tile.openstreetmap.org/${z}/${x}/${y}.png" ];
		options = OpenLayers.Util.extend({
			numZoomLevels : 19,
			buffer : 0,
			transitionEffect : "resize",
			attribution : OpenLayers.Util.OSM.DEFAULT_ATTRIBUTION
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this, newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OSM.Mapnik"
});

/**
 * Class: OpenLayers.Layer.OSM.CycleMap
 *
 * Inherits from:
 *  - <OpenLayers.Layer.OSM>
 */
OpenLayers.Layer.OSM.CycleMap = OpenLayers.Class(OpenLayers.Layer.OSM, {
	/**
	 * Constructor: OpenLayers.Layer.OSM.CycleMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */
	initialize : function(name, options) {
		var url = [
				"http://a.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",
				"http://b.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png",
				"http://c.tile.opencyclemap.org/cycle/${z}/${x}/${y}.png" 
			];
		options = OpenLayers.Util.extend({
			numZoomLevels : 19,
			buffer : 0,
			transitionEffect : "resize",
			attribution : OpenLayers.Util.OSM.DEFAULT_ATTRIBUTION+" / Style by <a href='http://www.opencyclemap.org/'>OpenCycleMap</a>"
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OSM.CycleMap"
});

OpenLayers.Layer.OSM.German = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: OpenLayers.Layer.OSM.German
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */
	initialize : function(name, options) {
		var url = [
				"http://a.tile.openstreetmap.de/tiles/osmde/${z}/${x}/${y}.png",
				"http://b.tile.openstreetmap.de/tiles/osmde/${z}/${x}/${y}.png",
				"http://c.tile.openstreetmap.de/tiles/osmde/${z}/${x}/${y}.png" 
			];
		options = OpenLayers.Util.extend({
			numZoomLevels : 19,
			buffer : 0,
			transitionEffect : "resize",
			attribution : OpenLayers.Util.OSM.DEFAULT_ATTRIBUTION+' / Style by <a href="http://www.openstreetmap.de/germanstyle.html">openstreetmap.de</a>',
			tileOptions : {
				crossOriginKeyword : null
			}
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OSM.German"
});

OpenLayers.Layer.OSM.GermanTransport = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: OpenLayers.Layer.OSM.GermanTransport
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */
	initialize : function(name, options) {
		var url = [ "http://tile.memomaps.de/tilegen/${z}/${x}/${y}.png" ];
		options = OpenLayers.Util.extend({
			numZoomLevels : 19,
			buffer : 0,
			transitionEffect : "resize",
			attribution : OpenLayers.Util.OSM.DEFAULT_ATTRIBUTION+"/ Style by <a href='http://www.memomaps.de'>Memomaps</a>",
			tileOptions : {
				crossOriginKeyword : null
			}
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OSM.GermanTransport"
});

OpenLayers.Layer.OSM.TransportMap = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: TransportMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */
	initialize : function(name, options) {
		var url = [
				"http://a.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png",
				"http://b.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png",
				"http://c.tile2.opencyclemap.org/transport/${z}/${x}/${y}.png" 
			];
		options = OpenLayers.Util.extend({
			numZoomLevels : 19,
			buffer : 0,
			transitionEffect : "resize",
			attribution : OpenLayers.Util.OSM.DEFAULT_ATTRIBUTION+" / Style by <a href='http://www.opencyclemap.org/'>OpenCycleMap</a>"
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OSM.TransportMap"
});

OpenLayers.Layer.OSM.LandscapeMap = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */
	initialize : function(name, options) {
		var url = [
				"http://a.tile3.opencyclemap.org/landscape/${z}/${x}/${y}.png",
				"http://b.tile3.opencyclemap.org/landscape/${z}/${x}/${y}.png",
				"http://c.tile3.opencyclemap.org/landscape/${z}/${x}/${y}.png" 
			];
		options = OpenLayers.Util.extend({
			numZoomLevels : 19,
			buffer : 0,
			transitionEffect : "resize",
			attribution : OpenLayers.Util.OSM.DEFAULT_ATTRIBUTION+" / Style by <a href='http://www.opencyclemap.org/'>OpenCycleMap</a>"
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OSM.LandscapeMap"
});

OpenLayers.Layer.OSM.MapQuestOpen = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: MapQuestOpen
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */
	initialize : function(name, options) {
		var url = [
				"http://otile1.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.png",
				"http://otile2.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.png",
				"http://otile3.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.png",
				"http://otile4.mqcdn.com/tiles/1.0.0/osm/${z}/${x}/${y}.png" 
			];
		options = OpenLayers.Util.extend({
			numZoomLevels : 19,
			buffer : 0,
			transitionEffect : "resize",
			attribution : OpenLayers.Util.OSM.DEFAULT_ATTRIBUTION+" / Style by <a href='http://www.mapquest.com/'>MapQuest</a> <img src='http://developer.mapquest.com/content/osm/mq_logo.png'>"
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OSM.MapQuestOpen"
});

OpenLayers.Control.C4g_Zoomlevel = OpenLayers.Class(OpenLayers.Control, {

	/**
	 * 
	 */
	emptyString : null,

	/**
	 * APIProperty: autoActivate
	 * {Boolean} Activate the control when it is added to a map.  Default is
	 *     true.
	 */
	autoActivate : true,

	/** 
	 * Property: element
	 * {DOMElement} 
	 */
	element : null,

	/**
	 * Constructor: OpenLayers.Control.C4g_Zoomlevel
	 * 
	 * Parameters:
	 * options - {Object} Options for control.
	 */

	/**
	 * Method: destroy
	 */
	destroy : function() {
		this.deactivate();
		OpenLayers.Control.prototype.destroy.apply(this, arguments);
	},

	/**
	 * APIMethod: activate
	 */
	activate : function() {
		if (OpenLayers.Control.prototype.activate.apply(this, arguments)) {
			this.map.events.register('moveend', this, this.redraw);
			this.redraw();
			return true;
		} else {
			return false;
		}
	},

	/**
	 * APIMethod: deactivate
	 */
	deactivate : function() {
		if (OpenLayers.Control.prototype.deactivate.apply(this, arguments)) {
			this.map.events.unregister('moveend', this, this.redraw);
			this.element.innerHTML = "";
			return true;
		} else {
			return false;
		}
	},

	/**
	 * Method: draw
	 * {DOMElement}
	 */
	draw : function() {
		OpenLayers.Control.prototype.draw.apply(this, arguments);

		if (!this.element) {
			this.div.left = "";
			this.div.top = "";
			this.element = this.div;
		}

		return this.div;
	},

	/**
	 * Method: redraw  
	 */
	redraw : function(evt) {

		var newHtml = this.map.zoom.toFixed(0);

		if (newHtml != this.element.innerHTML) {
			this.element.innerHTML = newHtml;
		}
	},

	/**
	 * Method: reset
	 */
	reset : function(evt) {
		if (this.emptyString !== null) {
			this.element.innerHTML = this.emptyString;
		}
	},

	CLASS_NAME : "OpenLayers.Control.C4g_Zoomlevel"
});

// -------------------------------------------------------------------------
// Stamen Maps (see http://maps.stamen.com), Code slightly adjusted
// -------------------------------------------------------------------------
(function() {

	var SUBDOMAINS = [ "", "a.", "b.", "c.", "d." ], PROVIDERS = {
		"toner" : {
			"url" : "http://{S}tile.stamen.com/toner/${z}/${x}/${y}.png",
			"minZoom" : 0,
			"maxZoom" : 20
		},
		"toner-lines" : {
			"url" : "http://{S}tile.stamen.com/toner-lines/${z}/${x}/${y}.png",
			"minZoom" : 0,
			"maxZoom" : 20
		},
		"toner-labels" : {
			"url" : "http://{S}tile.stamen.com/toner-labels/${z}/${x}/${y}.png",
			"minZoom" : 0,
			"maxZoom" : 20
		},
		"terrain" : {
			"url" : "http://{S}tile.stamen.com/terrain/${z}/${x}/${y}.jpg",
			"minZoom" : 4,
			"maxZoom" : 18
		},
		"watercolor" : {
			"url" : "http://{S}tile.stamen.com/watercolor/${z}/${x}/${y}.jpg",
			"minZoom" : 3,
			"maxZoom" : 16
		}
	}, ATTRIBUTION = OpenLayers.Util.OSM.DEFAULT_ATTRIBUTION+' / Map tiles <a href="http://creativecommons.org/licenses/by/3.0">CC BY 3.0</a> by <a href="http://stamen.com">Stamen Design</a>';

	function getProvider(name) {
		if (name in PROVIDERS) {
			return PROVIDERS[name];
		} else {
			throw 'No such provider: "' + name + '"';
		}
	}

	// based on http://www.bostongis.com/PrinterFriendly.aspx?content_name=using_custom_osm_tiles
	OpenLayers.Layer.Stamen = OpenLayers.Class(	OpenLayers.Layer.OSM, {
		initialize : function(type, name, options) {
			var provider = getProvider(type), url = provider.url, hosts = [];
			if (url.indexOf("{S}") > -1) {
				for ( var i = 0; i < SUBDOMAINS.length; i++) {
					hosts.push(url.replace("{S}",SUBDOMAINS[i]));
				}
			} else {
				hosts.push(url);
			}
			options = OpenLayers.Util.extend({
				"numZoomLevels" : provider.maxZoom,
				"buffer" : 0,
				"transitionEffect" : "resize",
				"attribution" : ATTRIBUTION,
				"tileOptions" : {
					crossOriginKeyword : null
				}
			}, options);
			return OpenLayers.Layer.OSM.prototype.initialize
					.call(this, name, hosts, options);
		}
	});

	OpenLayers.Layer.OSM.Toner = OpenLayers.Class(OpenLayers.Layer.Stamen, {
		initialize : function(name, options) {
			return OpenLayers.Layer.Stamen.prototype.initialize.call(this,
					"toner", name, options);
		}
	});

	OpenLayers.Layer.OSM.TonerLines = OpenLayers.Class(OpenLayers.Layer.Stamen,	{
		initialize : function(name, options) {
			return OpenLayers.Layer.Stamen.prototype.initialize.call(
					this, "toner-lines", name, options);
		}
	});

	OpenLayers.Layer.OSM.TonerLabels = OpenLayers.Class(OpenLayers.Layer.Stamen, {
		initialize : function(name, options) {
			return OpenLayers.Layer.Stamen.prototype.initialize.call(
					this, "toner-labels", name, options);
		}
	});

	OpenLayers.Layer.OSM.Watercolor = OpenLayers.Class(OpenLayers.Layer.Stamen,	{
		initialize : function(name, options) {
			return OpenLayers.Layer.Stamen.prototype.initialize.call(
					this, "watercolor", name, options);
		}
	});

})();

OpenLayers.Layer.OpenSeaMap = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */
	initialize : function(name, options) {
		var url = [
				"http://tiles.openseamap.org/seamark/${z}/${x}/${y}.png",
			];
		options = OpenLayers.Util.extend({
			numZoomLevels : 18,
			isBaseLayer : false,
			displayOutsideMaxExtent: true,
			attribution : "Seamarks by <a href='http://www.openseamap.org/'>OpenSeaMap</a>"
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OpenSeaMap"
});


OpenLayers.Layer.OpenWeatherMap_Data = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */	

	initialize : function(name, options) {		
		var city = new OpenLayers.Layer.Vector.OWMWeather("Weather");
		city.displayInLayerSwitcher = false;
/*
		options = OpenLayers.Util.extend({
			numZoomLevels : 18,
			isBaseLayer : false,
			displayOutsideMaxExtent: true,
			attribution : "Weather by <a href='http://www.openweathermap.org/'>OpenWeatherMap</a>"
		}, options);

		var newArguments = [ name, city.url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
*/
		return city;
	},

	CLASS_NAME : "OpenLayers.Layer.OpenWeatherMap_Data"

});

OpenLayers.Layer.OpenWeatherMap_Stations = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */	

	initialize : function(name, options) {
		var stations = new OpenLayers.Layer.Vector.OWMStations("Stations");
		stations.displayInLayerSwitcher = false;
		//stations.redraw();

		return stations;
	},

	CLASS_NAME : "OpenLayers.Layer.OpenWeatherMap_Stations"

});

OpenLayers.Layer.OpenWeatherMap_CloudsForecasts = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */	

	initialize : function(name, options) {
		var cloudsForecasts = new OpenLayers.Layer.OWMComposite('NT', "Clouds forecasts",  {opacity: 0.2} );
		cloudsForecasts.displayInLayerSwitcher = false;

		return cloudsForecasts;
	},

	CLASS_NAME : "OpenLayers.Layer.OpenWeatherMap_CloudsForecast"

});

OpenLayers.Layer.OpenWeatherMap_PrecipitationForecasts = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */	

	initialize : function(name, options) {
		var precipitationForecasts = new OpenLayers.Layer.OWMComposite('PR', "Precipitation forecasts",  {opacity: 0.2} );
		precipitationForecasts.displayInLayerSwitcher = false;
		
		return precipitationForecasts;
	},

	CLASS_NAME : "OpenLayers.Layer.OpenWeatherMap_PrecipitationForecasts"

});

OpenLayers.Layer.OpenWeatherMap_Radar = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */	

	initialize : function(name, options) {
		var radar = new OpenLayers.Layer.OWMRadar( "Radar (USA and Canada)",{isBaseLayer: false, opacity: 0.6} );
		radar.displayInLayerSwitcher = false;

		return radar;
	},

	CLASS_NAME : "OpenLayers.Layer.OpenWeatherMap_Radar"

});


OpenLayers.Layer.OpenWeatherMap_Clouds = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */	
	initialize : function(name, options) {
	     var url = [ "http://a.tile.openweathermap.org/map/clouds/${z}/${x}/${y}.png",
				"http://b.tile.openweathermap.org/map/clouds/${z}/${x}/${y}.png",
				"http://c.tile.openweathermap.org/map/clouds/${z}/${x}/${y}.png",
				"http://d.tile.openweathermap.org/map/clouds/${z}/${x}/${y}.png" ];
			
		options = OpenLayers.Util.extend({
			numZoomLevels : 18,
			isBaseLayer : false,
			displayOutsideMaxExtent: true,
			opacity: 0.7,
			sphericalMercator: true,
			attribution : "Weather by <a href='http://www.openweathermap.org/'>OpenWeatherMap</a>"
		}, options);


		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OpenWeatherMap_Clouds"
});

OpenLayers.Layer.OpenWeatherMap_Rain = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */	
	initialize : function(name, options) {
	     var url = [ "http://a.tile.openweathermap.org/map/rain/${z}/${x}/${y}.png",
				"http://b.tile.openweathermap.org/map/rain/${z}/${x}/${y}.png",
				"http://c.tile.openweathermap.org/map/rain/${z}/${x}/${y}.png",
				"http://d.tile.openweathermap.org/map/rain/${z}/${x}/${y}.png" ];
			
		options = OpenLayers.Util.extend({
			numZoomLevels : 18,
			isBaseLayer : false,
			displayOutsideMaxExtent: true,
			opacity: 0.7,
			sphericalMercator: true,
			attribution : "Weather by <a href='http://www.openweathermap.org/'>OpenWeatherMap</a>"
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OpenWeatherMap_Rain"
});

OpenLayers.Layer.OpenWeatherMap_Pressure = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */	
	initialize : function(name, options) {
	     var url = [ "http://a.tile.openweathermap.org/map/pressure/${z}/${x}/${y}.png",
				"http://b.tile.openweathermap.org/map/pressure/${z}/${x}/${y}.png",
				"http://c.tile.openweathermap.org/map/pressure/${z}/${x}/${y}.png",
				"http://d.tile.openweathermap.org/map/pressure/${z}/${x}/${y}.png" ];
			
		options = OpenLayers.Util.extend({
			numZoomLevels : 18,
			isBaseLayer : false,
			displayOutsideMaxExtent: true,
			opacity: 0.7,
			sphericalMercator: true,
			attribution : "Weather by <a href='http://www.openweathermap.org/'>OpenWeatherMap</a>"
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OpenWeatherMap_Pressure"
});

OpenLayers.Layer.OpenWeatherMap_Snow = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */	
	initialize : function(name, options) {
	     var url = [ "http://a.tile.openweathermap.org/map/snow/${z}/${x}/${y}.png",
				"http://b.tile.openweathermap.org/map/snow/${z}/${x}/${y}.png",
				"http://c.tile.openweathermap.org/map/snow/${z}/${x}/${y}.png",
				"http://d.tile.openweathermap.org/map/snow/${z}/${x}/${y}.png" ];
			
		options = OpenLayers.Util.extend({
			numZoomLevels : 18,
			isBaseLayer : false,
			displayOutsideMaxExtent: true,
			opacity: 0.7,
			sphericalMercator: true,
			attribution : "Weather by <a href='http://www.openweathermap.org/'>OpenWeatherMap</a>"
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OpenWeatherMap_Snow"
});

OpenLayers.Layer.OpenWeatherMap_Precipitation = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */	
	initialize : function(name, options) {
	     var url = [ "http://a.tile.openweathermap.org/map/precipitation/${z}/${x}/${y}.png",
				"http://b.tile.openweathermap.org/map/precipitation/${z}/${x}/${y}.png",
				"http://c.tile.openweathermap.org/map/precipitation/${z}/${x}/${y}.png",
				"http://d.tile.openweathermap.org/map/precipitation/${z}/${x}/${y}.png" ];
			
		options = OpenLayers.Util.extend({
			numZoomLevels : 18,
			isBaseLayer : false,
			displayOutsideMaxExtent: true,
			opacity: 0.7,
			sphericalMercator: true,
			attribution : "Weather by <a href='http://www.openweathermap.org/'>OpenWeatherMap</a>"
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OpenWeatherMap_Precipitation"
});

OpenLayers.Layer.OpenWeatherMap_Wind = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */	
	initialize : function(name, options) {
	     var url = [ "http://a.tile.openweathermap.org/map/wind/${z}/${x}/${y}.png",
				"http://b.tile.openweathermap.org/map/wind/${z}/${x}/${y}.png",
				"http://c.tile.openweathermap.org/map/wind/${z}/${x}/${y}.png",
				"http://d.tile.openweathermap.org/map/wind/${z}/${x}/${y}.png" ];
			
		options = OpenLayers.Util.extend({
			numZoomLevels : 18,
			isBaseLayer : false,
			displayOutsideMaxExtent: true,
			opacity: 0.7,
			sphericalMercator: true,
			attribution : "Weather by <a href='http://www.openweathermap.org/'>OpenWeatherMap</a>"
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OpenWeatherMap_Wind"
});

OpenLayers.Layer.OpenWeatherMap_Temp = OpenLayers.Class(OpenLayers.Layer.OSM,{
	/**
	 * Constructor: LandscapeMap
	 *
	 * Parameters:
	 * name - {String}
	 * options - {Object} Hashtable of extra options to tag onto the layer
	 */	
	initialize : function(name, options) {
	     var url = [ "http://a.tile.openweathermap.org/map/temp/${z}/${x}/${y}.png",
				"http://b.tile.openweathermap.org/map/temp/${z}/${x}/${y}.png",
				"http://c.tile.openweathermap.org/map/temp/${z}/${x}/${y}.png",
				"http://d.tile.openweathermap.org/map/temp/${z}/${x}/${y}.png" ];
			
		options = OpenLayers.Util.extend({
			numZoomLevels : 18,
			isBaseLayer : false,
			displayOutsideMaxExtent: true,
			opacity: 0.7,
			sphericalMercator: true,
			attribution : "Weather by <a href='http://www.openweathermap.org/'>OpenWeatherMap</a>"
		}, options);
		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.OpenWeatherMap_Temp"
});


OpenLayers.Layer.CustomOverlay = OpenLayers.Class(OpenLayers.Layer.OSM,{

	initialize : function(name, url, options) {
			
		options = OpenLayers.Util.extend({
			numZoomLevels : 18,
			isBaseLayer : false,
			displayOutsideMaxExtent: true,
			opacity: 0.7,
			sphericalMercator: true,
			attribution : ""
		}, options);


		var newArguments = [ name, url, options ];
		OpenLayers.Layer.OSM.prototype.initialize.apply(this,
				newArguments);
	},

	CLASS_NAME : "OpenLayers.Layer.CustomOverlay"
});


/**
 * Extended OSM import class which is able to import relations
 */
OpenLayers.Format.OSM.Extended = OpenLayers.Class(OpenLayers.Format.OSM, {

	getGeometryFromWay : function(doc, way, nodes) {
		// We know the minimal of this one ahead of time. (Could be -1
		// due to areas/polygons)
		var point_list = new Array(way.nodes.length);

		var poly = this.isWayArea(way) ? 1 : 0;
		var x = 0;
		var y = 0;
		for ( var j = 0; j < way.nodes.length; j++) {
			var node = nodes[way.nodes[j]];
			if (typeof(node)!='undefined') {
				x += parseFloat(node.lon);
				y += parseFloat(node.lat);

				var point = new OpenLayers.Geometry.Point(node.lon,
						node.lat);

				// Since OSM is topological, we stash the node ID internally. 
				point.osm_id = parseInt(way.nodes[j],10);
				point_list[j] = point;

				// We don't display nodes if they're used inside other 
				// elements.
				node.used = true;
			}	
		}
		var geometry = null;
		if (point_list.length > 0) {
			if (this.forceNodes) {
				geometry = new OpenLayers.Geometry.Point(x
						/ point_list.length, y / point_list.length);

			} else if (poly) {
				geometry = new OpenLayers.Geometry.Polygon(
						new OpenLayers.Geometry.LinearRing(
								point_list));
			} else {
				geometry = new OpenLayers.Geometry.LineString(
						point_list);
			}
			if (this.internalProjection && this.externalProjection) {
				geometry.transform(this.externalProjection,
						this.internalProjection);
			}
		}	
		return geometry;

	},

	/**
	 * Method: read
	 * Return a list of features from a OSM doc
	 
	 * Parameters:
	 * doc - {Element} 
	 *
	 * Returns:
	 * Array({<OpenLayers.Feature.Vector>})
	 */
	read : function(doc) {
		if (typeof doc == "string") {
			doc = OpenLayers.Format.XML.prototype.read.apply(
					this, [ doc ]);
		}

		var nodes = this.getNodes(doc);
		var ways = this.getWays(doc);
		var rels = this.getRelations(doc, ways);

		// Geoms will contain at least ways.length entries.
		var feat_list = [];

		for ( var i = 0; i < rels.length; i++) {
			var geometries = [];
			for ( var j = 0; j < rels[i].ways.length; j++) {
				var geometry = this.getGeometryFromWay(doc,
						rels[i].ways[j], nodes);
				if (geometry!==null) {		
					geometries.push(geometry);
				}	
			}
			if (geometries.length > 0) {
				var geometry = new OpenLayers.Geometry.Collection(
						geometries);
				var feat = new OpenLayers.Feature.Vector(geometry,
						rels[i].tags);
				feat.osm_id = parseInt(rels[i].id,10);
				feat.fid = "rel." + feat.osm_id;
				feat_list.push(feat);
			}	
		}

		for ( var i = 0; i < ways.length; i++) {
			if (ways[i].used) {
				continue;
			}
			var geometry = this.getGeometryFromWay(doc,
					ways[i], nodes);
			if (geometry!==null) {		
				var feat = new OpenLayers.Feature.Vector(geometry,
						ways[i].tags);
				feat.osm_id = parseInt(ways[i].id,10);
				feat.fid = "way." + feat.osm_id;
				feat_list.push(feat);
			}	
		}

		for ( var node_id in nodes) {
			var node = nodes[node_id];
			if (!node.used || this.checkTags) {
				var tags = null;

				if (this.checkTags) {
					var result = this.getTags(node.node, true);
					if (node.used && !result[1]) {
						continue;
					}
					tags = result[0];
				} else {
					tags = this.getTags(node.node);
				}

				var feat = new OpenLayers.Feature.Vector(
						new OpenLayers.Geometry.Point(
								node.lon, node.lat), tags);
				if (this.internalProjection
						&& this.externalProjection) {
					feat.geometry.transform(
							this.externalProjection,
							this.internalProjection);
				}
				feat.osm_id = parseInt(node_id,10);
				feat.fid = "node." + feat.osm_id;
				feat_list.push(feat);
			}
			// Memory cleanup
			node.node = null;
		}
		return feat_list;
	},

	/**
	 * Method: getRelations
	 * Return the relation items from a doc.  
	 *
	 * Parameters:
	 * doc - {DOMElement} node to parse tags from
	 */
	getRelations : function(doc, ways) {
		var relation_list = doc
				.getElementsByTagName("relation");
		var return_relations = [];
		for ( var i = 0; i < relation_list.length; i++) {
			var relation = relation_list[i];
			var relation_object = {
				id : relation.getAttribute("id")
			};

			relation_object.tags = this.getTags(relation);

			var element_list = relation
					.getElementsByTagName("member");

			rel_nodes = [];
			relation_object.ways = [];

			for ( var j = 0; j < element_list.length; j++) {
				if (element_list[j].getAttribute("type") == "way") {
					var way = null;
					var wayId = element_list[j]
							.getAttribute("ref");
					for ( var k = 0; k < ways.length; k++) {
						if (ways[k].id == wayId) {
							way = ways[k];
							break;
						}
					}
					if (way) {
						if (rel_nodes[rel_nodes.length - 1] == way.nodes[way.nodes.length - 1]) {
							var nodes = way.nodes.slice();
							nodes.reverse();
							rel_nodes = rel_nodes.concat(nodes);
						} else if (rel_nodes[0] == way.nodes[0]) {
							rel_nodes.reverse();
							rel_nodes = rel_nodes
									.concat(way.nodes);
						} else if (rel_nodes[0] == way.nodes[way.nodes.length - 1]) {
							rel_nodes = way.nodes
									.concat(rel_nodes);
						} else {
							if (rel_nodes[rel_nodes.length - 1] != way.nodes[0]) {
								if (rel_nodes.length > 0) {
									// new way found
									var rel_way = {
										nodes : rel_nodes
									};
									relation_object.ways
											.push(rel_way);
								}
								rel_nodes = way.nodes.slice();
							} else {
								rel_nodes = rel_nodes
										.concat(way.nodes);
							}
						}
						way.used = true;
					}

				}
			}
			var rel_way = {
				nodes : rel_nodes
			};
			relation_object.ways.push(rel_way);
			return_relations.push(relation_object);
		}
		return return_relations;

	},
	CLASS_NAME : "OpenLayers.Format.OSM.Extended"
});

/**
 * Fullscreen Control 
 * based upon https://github.com/fredj/openlayers-fullscreen with some corrections
 */
OpenLayers.Control.FullScreen = OpenLayers.Class(OpenLayers.Control, {

	type: OpenLayers.Control.TYPE_TOGGLE,

	fullscreenClass: 'fullscreen',

	setMap: function(map) {
		OpenLayers.Control.prototype.setMap.apply(this, arguments);

		// handle 'Esc' key press
		OpenLayers.Event.observe(document, "fullscreenchange", OpenLayers.Function.bind(function() {
			if (!document.fullscreenElement) {
				this.deactivate();
			}
		}, this));

		// handle 'Esc' key press
		OpenLayers.Event.observe(document, "mozfullscreenchange", OpenLayers.Function.bind(function() {
			if (!document.mozFullScreenElement) {
				this.deactivate();
			}
		}, this));

		// handle 'Esc' key press
		OpenLayers.Event.observe(document, "webkitfullscreenchange", OpenLayers.Function.bind(function() {
			if (!document.webkitIsFullScreen) {
				this.deactivate();
			}
		}, this));
	},

	activate: function() {
		if (OpenLayers.Control.prototype.activate.apply(this, arguments)) {
			if (this.map.div.webkitRequestFullScreen) {
				var flag = Element.ALLOW_KEYBOARD_INPUT;
				if (!flag)
					flag=1;  // workaround if MooTools Element function hides the Chrome/Safari Element
				this.map.div.webkitRequestFullScreen(flag);
			} else if (this.map.div.requestFullscreen) {
				this.map.div.requestFullscreen();
			} else if (this.map.div.mozRequestFullScreen) {
				this.map.div.mozRequestFullScreen();
			} 
			OpenLayers.Element.addClass(this.map.div, this.fullscreenClass);
			this.map.updateSize();
			return true;
		} else {
			return false;
		}
	},

	deactivate: function() {
		if (OpenLayers.Control.prototype.deactivate.apply(this, arguments)) {
			if (document.exitFullscreen) {
				document.exitFullscreen();
			} else if (document.mozCancelFullScreen) {
				document.mozCancelFullScreen();
			} else if (document.webkitCancelFullScreen) {
				document.webkitCancelFullScreen();
			}
			OpenLayers.Element.removeClass(this.map.div, this.fullscreenClass);
			this.map.updateSize();
			return true;
		} else {
			return false;
		}
	},

	CLASS_NAME: "OpenLayers.Control.FullScreen"
});

OpenLayers.Control.FullScreenPanel = OpenLayers.Class(OpenLayers.Control.Panel, {
	CLASS_NAME: "OpenLayers.Control.FullScreenPanel"
});

// Patch for OpenLayers 2.12
OpenLayers.Util.createUrlObjectOrg = OpenLayers.Util.createUrlObject;
OpenLayers.Util.createUrlObject = function(url, options) {
	// make relative URL absolute, because this is not always handled correctly in OL2.12
	var a = document.createElement('a');
	a.href = url;
	url = a.href;
	return OpenLayers.Util.createUrlObjectOrg(url, options);
};