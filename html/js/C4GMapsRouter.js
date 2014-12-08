/**
 * Contao Open Source CMS
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
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
 * @package    con4gis
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
var C4GMapsRouter=function(mapData,map) {

	OpenLayers.Format.C4gEncodedPolyline=OpenLayers.Class(OpenLayers.Format.EncodedPolyline, {
		read: function(encoded, opt_factor) {
			var geomType;
			if (this.geometryType == "linestring")
				geomType = OpenLayers.Geometry.LineString;
			else if (this.geometryType == "linearring")
				geomType = OpenLayers.Geometry.LinearRing;
			else if (this.geometryType == "multipoint")
				geomType = OpenLayers.Geometry.MultiPoint;
			else if (this.geometryType != "point" && this.geometryType != "polygon")
				return null;

			var flatPoints = this.decodeDeltas(encoded, 2, opt_factor);
			var flatPointsLength = flatPoints.length;

			var pointGeometries = [];
			for (var i = 0; i + 1 < flatPointsLength;) {
				var y = flatPoints[i++], x = flatPoints[i++];
				pointGeometries.push(new OpenLayers.Geometry.Point(x, y));
			}


			if (this.geometryType == "point")
				return new OpenLayers.Feature.Vector(
					pointGeometries[0]
				);

			if (this.geometryType == "polygon")
				return new OpenLayers.Feature.Vector(
					new OpenLayers.Geometry.Polygon([
						new OpenLayers.Geometry.LinearRing(pointGeometries)
					])
				);

			return new OpenLayers.Feature.Vector(
				new geomType(pointGeometries)
			);
		}
	});

	OpenLayers.Control.RouterClick = OpenLayers.Class(OpenLayers.Control, {
		defaultHandlerOptions: {
			'single': true,
			'double': false,
			'pixelTolerance': 1,
			'stopSingle': false,
			'stopDouble': false
		},

		initialize: function(options) {
			this.handlerOptions = OpenLayers.Util.extend(
				{}, this.defaultHandlerOptions
			);
			OpenLayers.Control.prototype.initialize.apply(
				this, arguments
			);
			this.handler = new OpenLayers.Handler.Click(
				this, {
					'click': this.trigger
				}, this.handlerOptions
			);
		},

		trigger: function(e) {
			feature = router.layer.getFeatureFromEvent(e);
			if ((feature !== null) && (feature.c4gOnClick)) {
				feature.c4gOnClick(feature,e);
			}
			else {
				var lonlat = map.getLonLatFromPixel(e.xy);
				lonlat.transform(router.map.projection, router.map.displayProjection);
				if (!router.locations.from) {
					router.performReverseGeocoding(lonlat,router.fromInput,'from');
				} else if (!router.locations.to) {
					router.performReverseGeocoding(lonlat,router.toInput,'to');
				}
			}
		}

	});

	if (typeof(mapData.router_labels)!='undefined') {
		OpenLayers.Lang.en = OpenLayers.Util.extend(OpenLayers.Lang.en, mapData.router_labels);
	}

	var router = {};
	map.router = router;
	router.map = map;
	router.dialogSize = 330;
	this.active = false;
	map.orgUpdateSize = map.updateSize;
	map.updateSize = function() {
		router.updateSize();
		this.orgUpdateSize();
	};

	var routerIconDiv = document.createElement('div');
	routerIconDiv.id = 'C4GMapsRouterIcon_' + mapData.id;
	routerIconDiv.className = 'olControlRouter olControlNoSelect';
	if (!mapData.zoom_panel_world) {
		routerIconDiv.className = routerIconDiv.className + " olControlZoomOutItemWithoutWorld";
	}
	if (!mapData.fullscreen) {
		routerIconDiv.className = routerIconDiv.className + " olControlWithoutFullscreen";
	}
	routerIconDiv.style.position = 'absolute';
	routerIconDiv.style.zIndex = '1020';

	var routerIcon = document.createElement('div');
	routerIcon.className = 'olControlRouterIconInactive olButton';
	router.routerIcon = routerIcon;

	routerIconDiv.appendChild(routerIcon);

	map.viewPortDiv.appendChild(routerIconDiv);

	OpenLayers.Event.observe(routerIconDiv, 'click',
		OpenLayers.Function.bind(function(input) { router.toggleRouter(); }, this, null));

	var routerWrapperDiv = document.createElement('div');
	routerWrapperDiv.id = 'c4gMapsRouterWrapper_' + mapData.id;
	routerWrapperDiv.className = 'c4gMapsRouterWrapper c4gPortsideExtension c4gPortsideInactive';
	router.routerWrapperDiv = routerWrapperDiv;
	map.viewPortDiv.appendChild(routerWrapperDiv);

	var routerDiv = document.createElement('div');
	routerDiv.id = 'c4gMapsRouter_' + mapData.id;
	routerDiv.className = 'c4gMapsRouter c4gMapsRouterDialog';
	router.routerDiv = routerDiv;

	// OpenLayers.Element.addClass(routerDiv, 'c4gMapsRouterDialog');
	// routerDiv.style.display = 'none';
	routerWrapperDiv.appendChild(routerDiv);

	var instrDiv = document.createElement('div');
	instrDiv.id = 'c4gMapsRouteInstructions_' + mapData.id;
	instrDiv.className = 'c4gMapsRouteInstructions';
	router.instrDiv = instrDiv;

	OpenLayers.Element.addClass(instrDiv, 'c4gMapsRouteInstrDialog');
	// instrDiv.style.display = 'none';
	routerWrapperDiv.appendChild(instrDiv);

	var fnIgnoreEvent=function(event) {
		OpenLayers.Event.stop(event, true);
	};
	var fnIgnoreEvents=function(element) {
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
	fnIgnoreEvents(routerIconDiv);
	fnIgnoreEvents(routerDiv);
	fnIgnoreEvents(instrDiv);

	router.locations = {};
	router.hints = {};
	router.wasActive = false;

	router.getText = function(id) {
		return OpenLayers.i18n('c4gRt'+id);
	};

	router.toHumanTime = function(seconds){
		var seconds = parseInt(seconds,10);
		var minutes = parseInt(seconds/60,10);
		seconds = seconds%60;
		var hours = parseInt(minutes/60,10);
		minutes = minutes%60;
		if(hours===0 && minutes===0){ return seconds + '&nbsp;' + 's'; }
		else if(hours===0){ return minutes + '&nbsp;' + 'min'; }
		else{ return hours + '&nbsp;' + 'h' + '&nbsp;' + minutes + '&nbsp;' + 'min';}
	};


	router.toHumanDistance = function(meters){
		var distance = parseInt(meters,10);

		distance = distance / 1000;
		if(distance >= 100){ return (distance).toFixed(0)+'&nbsp;' + 'km'; }
		else if(distance >= 10){ return (distance).toFixed(1)+'&nbsp;' + 'km'; }
		else if(distance >= 0.1){ return (distance).toFixed(2)+'&nbsp;' + 'km'; }
		else{ return (distance*1000).toFixed(0)+'&nbsp;' + 'm'; }
	};

	router.prepareLocateButtons = function(html) {
		return html + '<div id="c4gMapsRouterLocate"><a id="c4gMapsRouterLocateButton" href="#">'+this.getText('LOC_ROUTE_TO')+'</a></div>';

	};

	router.registerLocateButtons = function(element,feature) {
		var locButton = document.getElementById('c4gMapsRouterLocateButton');
		if (!locButton) {
			var locButton = document.createElement('a');
			locButton.href = '#';
			locButton.id = 'c4gMapsRouterLocateButton';
			locButton.innerHTML = this.getText('LOC_ROUTE_TO');
			element.appendChild(locButton);
		}
		locButton.onclick = function() {
			var point = feature.geometry.getCentroid();
			var lonlat = new OpenLayers.LonLat(point.x,point.y);
			lonlat.transform(router.map.projection, router.map.displayProjection);
			router.createPanel(null, lonlat);
			return false;
		};
	};

	router.setBaseLayerGaps = function(layer) {
		layer.orgMinExtentGapX = layer.minExtentGapX;
		var switcherWidth = 20;
		if (map.layerSwitcher) {
			switcherWidth = map.layerSwitcher.div.width;
		}
		layer.minExtentGapX = (router.dialogSize / 2.0) + switcherWidth;
		layer.orgMinExtentGapY = layer.minExtentGapY;
		layer.minExtentGapY = 20;
	};

	router.resetBaseLayerGaps = function(layer) {
		layer.minExtentGapX = layer.orgMinExtentGapX;
		delete layer.orgMinExtentGapX;
		layer.minExtentGapY = layer.orgMinExtentGapY;
		delete layer.orgMinExtentGapY;
	};

	router.setRouteMode = function() {
		router.active = true;
		for (var i = this.map.popups.length - 1; i >= 0; i--) {
			this.map.removePopup(this.map.popups[i]);
		}

		var controls = this.map.getControlsByClass('OpenLayers.Control.Click');
		for(var i = controls.length-1; i >= 0; --i) {
			controls[i].destroy();
		}

		var controls = this.map.getControlsByClass('OpenLayers.Control.SelectFeature');
		for(var i = 0; i < controls.length; ++i) {
			controls[i].wasActive = controls[i].active;
			controls[i].deactivate();
		}

		for(var i = 0; i < map.controls.length; ++i) {
			if (map.controls[i].div) {
				OpenLayers.Element.addClass(map.controls[i].div, 'c4gMapsRouterDialogActive');
			}
		}
		zpPanel = document.getElementById('C4GMapsZoomPositionPanel_' + mapData.id);
		if (zpPanel) { OpenLayers.Element.addClass(zpPanel, 'c4gMapsRouterDialogActive') }

		if (!router.layer) {
			router.layer = new OpenLayers.Layer.Vector('Route Layer');
			router.layer.displayInLayerSwitcher = false;

			router.layer.styleMap = new OpenLayers.StyleMap({
				'default': new OpenLayers.Style({
					cursor: 'pointer',
					fillColor: '#07f',
					fillOpacity: 0.4,
					strokeColor: '#037',
					strokeWidth: 5,
					strokeOpacity: 0.6,
					pointRadius: 5
				}),
				'select': new OpenLayers.Style({
					fillColor: '##07f',
					strokeColor: '#037'
				})
			});
			map.addLayer(router.layer);
			map.addLinkLayer(router.layer);
		} else {
		}

		router.style = {};
		router.style.from = {externalGraphic: OpenLayers.Util.getImagesLocation()+'marker-green.png',cursor:'pointer',graphicWidth:21,graphicHeight:25,graphicXOffset:-10,graphicYOffset:-25,graphicOpacity:0.8};
		router.style.to = {externalGraphic: OpenLayers.Util.getImagesLocation()+'marker.png',cursor:'pointer',graphicWidth:21,graphicHeight:25,graphicXOffset:-10,graphicYOffset:-25,graphicOpacity:0.8};
		router.style.current ={externalGraphic: OpenLayers.Util.getImagesLocation()+'marker-gold.png',cursor:'pointer',graphicWidth:21,graphicHeight:25,graphicXOffset:-10,graphicYOffset:-25,graphicOpacity:0.8};

		this.click = new OpenLayers.Control.RouterClick();
		map.addControl(this.click);
		this.click.activate();

		// force layers to consider size of dialog on map when zooming to extent e.g. of route
		for (var i=0; i <map.layers.length; i++) {
			var layer = map.layers[i];
			if (layer.isBaseLayer) {
				this.setBaseLayerGaps(layer);
			}
		}

		map.orgZoomToExtentRouter = map.zoomToExtent;
		map.zoomToExtent = function(bounds, closest) {
			this.orgZoomToExtentRouter(bounds,closest);
			// move center so that the route is not covered by the routing dialogs
			this.moveByPx((this.router.dialogSize*(-1.0))/2.0,0);
		};

	};

	router.clearRouteMode = function(destroy) {
		this.active = false;
		this.click.destroy();
		delete this.click;

		for(var i = 0; i < map.controls.length; ++i) {
			if (map.controls[i].div) {
				OpenLayers.Element.removeClass(map.controls[i].div, 'c4gMapsRouterDialogActive');
			}
		}
		zpPanel = document.getElementById('C4GMapsZoomPositionPanel_' + mapData.id);
		if (zpPanel) { OpenLayers.Element.removeClass(zpPanel, 'c4gMapsRouterDialogActive') }

		var controls = this.map.getControlsByClass('OpenLayers.Control.SelectFeature');
		for(var i = controls.length-1; i >= 0; --i) {
			if (controls[i].wasActive) {
				controls[i].activate();
				delete controls[i].wasActive;
			}
		}

		var click = new OpenLayers.Control.Click();
		map.addControl(click);
		click.activate();

		map.zoomToExtent = map.orgZoomToExtentRouter;
		delete map.orgZoomToExtentRouter;

		for (var i=0; i <map.layers.length; i++) {
			var layer = map.layers[i];
			if (layer.isBaseLayer) {
				this.resetBaseLayerGaps(layer);
			}
			else {
				layer.redraw();
			}
		}

		// this.routerDiv.style.display = 'none';
		// this.instrDiv.style.display = 'none';
		// this.routerIconDiv.style.display = '';

		if (destroy) {
			delete this.routeFeature;
			this.routerDiv.innerHTML = "";
			this.instrDiv.innerHTML = "";
			this.locations = {};
			this.hints = {};
			this.wasActive = false;
			this.layer.removeAllFeatures();
		}

	};

	router.createPanel = function(fromLonLat,toLonLat)
	{
		if (router.map.editor && router.map.editor.active) {
			router.map.editor.closeEditor();
		}

		OpenLayers.Element.removeClass(this.routerWrapperDiv, 'c4gPortsideInactive');
		OpenLayers.Element.addClass( this.routerIcon, 'olControlRouterIconActive');
		OpenLayers.Element.removeClass(this.routerIcon, 'olControlRouterIconInactive');

		this.setRouteMode();
		if (!router.wasActive) {
			router.wasActive = true;
			this.routerDiv.innerHTML = "";
			this.headerDiv = document.createElement('div');
			this.headerDiv.className = 'c4gMapsRouterHeader';
			this.headerDiv.innerHTML = this.getText('FIND_ROUTE');

			this.closeBtn = document.createElement('div');
			this.closeBtn.id = 'c4gMapsCloseBtn';
			this.closeBtn.className = 'c4gMapsCloseBtn';
			this.headerDiv.appendChild(this.closeBtn);
			OpenLayers.Event.observe(this.closeBtn, 'click',
				OpenLayers.Function.bind(function(input) { this.closePanel(true); }, this, this.closeBtn));

			this.hideBtn = document.createElement('div');
			this.hideBtn.id = 'c4gMapsHideDialogBtn';
			this.hideBtn.className = 'c4gMapsHideDialogBtn';
			this.headerDiv.appendChild(this.hideBtn);
			OpenLayers.Event.observe(this.hideBtn, 'click',
				OpenLayers.Function.bind(function(input) { this.closePanel(false); }, this, this.hideBtn));

			this.printerBtn = document.createElement('div');
			this.printerBtn.id = 'c4gMapsPrinterBtn';
			this.printerBtn.className = 'c4gMapsPrinterBtnInactive';
			this.headerDiv.appendChild(this.printerBtn);
			OpenLayers.Event.observe(this.printerBtn, 'click',
				OpenLayers.Function.bind(function(input) { this.print(); }, this, this.printerBtn));

			this.routerDiv.appendChild(this.headerDiv);

			this.inputDiv = document.createElement('div');
			this.inputDiv.className = 'c4gMapsRouterInput';

			this.fromDiv = document.createElement('div');
			this.fromDiv.className = 'c4gMapsRouterInputLine';
			this.fromLabel = document.createElement('label');
			this.fromLabel.innerHTML = this.getText('FROM');
			this.fromLabel.style.display = 'table-cell';
			this.fromLabel.setAttribute('for','c4gMapsRouteFrom');
			this.fromInput = document.createElement('input');
			this.fromInput.id = 'c4gMapsRouteFrom';
			this.fromInput.style.display = 'table-cell';
			this.fromDelete = document.createElement('div');
			this.fromDelete.id = 'c4gMapsCancelBtnFrom';
			this.fromDelete.className = 'c4gMapsCancelBtn c4gMapsHideBtn';
			this.fromDelete.style.display = 'table-cell';
			this.fromDelete.innerHTML = "&nbsp;";
			this.fromInput._delElement = this.fromDelete;

			OpenLayers.Event.observe(this.fromInput, 'change',
				OpenLayers.Function.bind(function(input) { this.performSearch(input,'from'); }, this, this.fromInput));
			OpenLayers.Event.observe(this.fromDelete, 'click',
				OpenLayers.Function.bind(function(input) { this.deleteLocation(input,'from'); }, this, this.fromInput));

			this.fromDiv.appendChild(this.fromLabel);
			this.fromDiv.appendChild(this.fromInput);
			this.fromDiv.appendChild(this.fromDelete);
			this.fromDiv.style.display = 'table-row';
			this.inputDiv.appendChild(this.fromDiv);


			this.toDiv = document.createElement('div');
			this.toDiv.className = 'InputLine';
			this.toLabel = document.createElement('label');
			this.toLabel.innerHTML = this.getText('TO');
			this.toLabel.setAttribute('for','c4gMapsRouteTo');
			this.toLabel.style.display = 'table-cell';
			this.toInput = document.createElement('input');
			this.toInput.id = 'c4gMapsRouteTo';
			this.toInput.style.display = 'table-cell';
			this.toDelete = document.createElement('div');
			this.toDelete.id = 'c4gMapsCancelBtnTo';
			this.toDelete.className = 'c4gMapsCancelBtn c4gMapsHideBtn';
			this.toDelete.style.display = 'table-cell';
			this.toDelete.innerHTML = "&nbsp;";
			this.toInput._delElement = this.toDelete;

			OpenLayers.Event.observe(this.toInput, 'change',
				OpenLayers.Function.bind(function(input) { this.performSearch(input,'to'); }, this, this.toInput));
			OpenLayers.Event.observe(this.toDelete, 'click',
				OpenLayers.Function.bind(function(input) { this.deleteLocation(input,'to'); }, this, this.toInput));

			this.toDiv.appendChild(this.toLabel);
			this.toDiv.appendChild(this.toInput);
			this.toDiv.appendChild(this.toDelete);
			this.toDiv.style.display = 'table-row';
			this.inputDiv.appendChild(this.toDiv);

			this.inputDiv.style.display = 'table';
			this.routerDiv.appendChild(this.inputDiv);

			this.attributionDiv = document.createElement('div');
			this.attributionDiv.className = 'c4gMapsRouterAttribution';
			if (mapData.router_attribution) {
				this.attributionDiv.innerHTML = mapData.router_attribution;
			}
			else {
				this.attributionDiv.innerHTML =
					'Routing by <a href="http://project-osrm.org/">Project OSRM</a> ' +
					'- Geocoder by <a href="http://www.mapquest.com/">MapQuest</a> ' +
					'- OSRM hosting by <a href="http://algo2.iti.kit.edu/">KIT</a>';
			}
			this.routerDiv.appendChild(this.attributionDiv);

			this.messageDiv = document.createElement('div');
			this.messageDiv.className = 'c4gMapsRouterMessage';
			this.messageDiv.style.display = 'none';
			this.routerDiv.appendChild(this.messageDiv);

		} else {
			// routing was active before
			if (fromLonLat || toLonLat) {
				// new location? -> clear previous routing!
				this.locations = {};
				this.hints = {};
				this.fromInput.value = '';
				this.toInput.value = '';
				this.layer.removeAllFeatures();
			} else {
				if (this.instrDiv.innerHTML) {
					this.instrDiv.style.display = '';
					this.printerBtn.className = 'c4gMapsPrinterBtn';
				}
			}
		}

		if(fromLonLat) {
			this.performReverseGeocoding(fromLonLat,this.fromInput,'from');
		}

		if(toLonLat) {
			this.performReverseGeocoding(toLonLat,this.toInput,'to');
		}

		// this.routerDiv.style.display = '';
		// this.routerIconDiv.style.display = 'none';
	};

	router.closePanel = function(destroy)
	{
		OpenLayers.Element.addClass(this.routerWrapperDiv, 'c4gPortsideInactive');
		OpenLayers.Element.removeClass(this.routerIcon, 'olControlRouterIconActive');
		OpenLayers.Element.addClass(this.routerIcon, 'olControlRouterIconInactive');

		router.clearRouteMode(destroy);
	};

	router.toggleRouter = function()
	{
		if (this.active) {
			router.closePanel(false);
		} else {
			router.createPanel(null,null);
		}
	};

	router.recalculateRoute = function() {
		if (this.locations.from && this.locations.to) {
			this.performViaRoute();
		}
	};

	router.deleteLocation = function(input,loctype)  {
		if (this.locations[loctype]) {
			this.layer.destroyFeatures([this.locations[loctype]]);
			delete this.locations[loctype];
		}
		input.value = '';
		if (input._delElement) {
			OpenLayers.Element.addClass(input._delElement,'c4gMapsHideBtn');
		}

		this.instrDiv.innerHTML = '';
		// this.instrDiv.style.display = 'none';
		this.printerBtn.className = 'c4gMapsPrinterBtnInactive';

		if (router.routeFeature) {
			router.layer.destroyFeatures(router.routeFeature);
		}
	};

	router.setLocationFeature = function(lonlat, loctype,isWGS84) {

		if (this.locations[loctype]) {
			this.layer.destroyFeatures([this.locations[loctype]]);
			delete this.locations[loctype];
		}
		var point = new OpenLayers.Geometry.Point(lonlat.lon, lonlat.lat);
		if (isWGS84) {
			point.transform(router.map.displayProjection, router.map.projection);
		}

		var feat = new OpenLayers.Feature.Vector(point, {loctype:loctype});
		if (this.style[loctype])
			feat.style = this.style[loctype];
		feat.c4gOnClick = function(feature) {
			var lonlat = new OpenLayers.LonLat(feature.geometry.x,feature.geometry.y);
			var newZoom = 14;
			if (map.zoom > 14)
				newZoom = map.zoom;
			router.map.setCenter(lonlat,newZoom);
			if (router.active) {
				// move center so that the route is not covered by the routing dialogs
				router.map.moveByPx((router.dialogSize*(-1.0))/2.0,0);
			}

		};

		router.layer.addFeatures([feat]);

		this.locations[loctype] = feat;
		delete(this.hints[loctype]);

		var moveTo = false;
		if (loctype=='to') {
			if (!this.locations.from)
				moveTo = true;
		}
		else {
			if (!this.locations.to)
				moveTo = true;
		}
		if ((this.routerDiv) && (moveTo)) {
			var tmpLonlat = new OpenLayers.LonLat(point.x,point.y);
			var px=map.getPixelFromLonLat(tmpLonlat);
			if ((px.x > map.getCurrentSize().x) || (px.x < this.dialogSize) ||
				(px.y > map.getCurrentSize().y) || (px.y < 0)) {
				map.setCenter( tmpLonlat);
				map.moveByPx((this.dialogSize*(-1.0))/2.0,0);
			}
		}

	};

	router.setLocation = function(lonlat,loctype,isWGS84,input)  {
		router.setLocationFeature(lonlat,loctype,isWGS84);
		this.recalculateRoute();

		if (input) {
			if (input._delElement) {
				OpenLayers.Element.removeClass(input._delElement,'c4gMapsHideBtn');
			}
		}

	};

	router.showMessage=function(text) {
		if (this.messageDiv) {
			this.messageDiv.innerHTML = text;
			this.messageDiv.style.display = '';
		}
	};

	router.hideMessage=function(text) {
		if (this.messageDiv) {
			this.messageDiv.innerHTML = '';
			this.messageDiv.style.display = 'none';
		}
	};

	router.performReverseGeocoding=function(lonlat,input,loctype) {
		OpenLayers.Request.GET({
			url: document.getElementsByTagName('base')[0].href
				+ mapData.reverse_url
				+"?token="+mapData.REQUEST_TOKEN
				+"&format=json&lat="+lonlat.lat+"&lon="+lonlat.lon,
			success: function(ajaxRequest) {
				var textResponse = ajaxRequest.responseText;
				router.hideMessage();
				var locObj = [];
				try {
					var json = new OpenLayers.Format.JSON();
					locObj = json.read(textResponse);
				} catch(err) {
					alert(router.getText('ERROR_REV_GEOCODING'));
					return;
				}
				if (locObj.length === 0) {
					alert(router.getText('ERROR_REV_GEOCODING'));
				} else {

					var value = "";
					if (locObj.address.city) {
						value = locObj.address.city;
						if (locObj.address.road)
							value = ', '+value;
					}
					if (locObj.address.road) {
						if (locObj.address.house_number)
							value = ' ' + locObj.address.house_number + value;
						value = locObj.address.road + value;
					}
					if (value==="") {
						value = locObj.display_name;
					}
					input.value = value;
					router.setLocation(lonlat,loctype,true,input);
				}
			},
			failure: function(e) {
				alert(this.getText('ERROR_REV_GEOCODING'));
				router.hideMessage();
			},
			scope: this
		});
		this.setLocationFeature(lonlat,loctype,true);
		router.showMessage(this.getText('REV_GEOCODING'));
	};

	router.performSearch=function(input,loctype) {
		var map=this.map;
		var bounds = map.getExtent().scale(1.2);
		bounds.transform(map.projection,map.displayProjection);
		var viewbox = '&viewbox='
				+ bounds.left + ','
				+ bounds.bottom + ','
				+ bounds.right + ','
				+ bounds.top;

		OpenLayers.Request.GET({
			url: document.getElementsByTagName('base')[0].href
				+ mapData.geocoding_url
				+ "?token="+mapData.REQUEST_TOKEN
				+ "&format=json&limit=1&q="
				+ encodeURI(input.value)
				+ viewbox,
			success: function(ajaxRequest) {
				var textResponse = ajaxRequest.responseText;
				router.hideMessage();
				var geoObj = [];
				try {
					var json = new OpenLayers.Format.JSON();
					geoObj = json.read(textResponse);
				} catch(err) {
					alert(router.getText('ERROR_SEARCHING'));
					return;
				}
				if (geoObj.length === 0) {
					alert(mapData.labels.no_geo_results);
				} else {
					var lonlat = new OpenLayers.LonLat(
							geoObj[0].lon,
							geoObj[0].lat);
					router.setLocation(lonlat,loctype,true,input);
				}

			},
			failure: function(e) {
				alert(router.getText('ERROR_SEARCHING'));
				router.hideMessage();
			},
			scope: this
		});
		this.showMessage(this.getText('SEARCHING'));
	};

	// retrieve driving instruction icon from instruction id
	router.getDrivingInstructionIcon = function(instruction_id) {
		var id = instruction_id.replace(/^11-\d{1,}$/,"11");		// dumb check, if there is a roundabout (all have the same icon)

		var image = 'default.png';
		switch (id) {
		case '1':
			image='continue.png';
			break;
		case '2':
			image='slight-right.png';
			break;
		case '3':
			image='turn-right.png';
			break;
		case '4':
			image='sharp-right.png';
			break;
		case '5':
			image='u-turn.png';
			break;
		case '6':
			image='sharp-left.png';
			break;
		case '7':
			image='turn-left.png';
			break;
		case '8':
			image='slight-left.png';
			break;
		case '10':
			image='head.png';
			break;
		case '11':
			image='round-about.png';
			break;
		case '15':
			image='target.png';
			break;
		}
		return document.getElementsByTagName('base')[0].href+"system/modules/con4gis_maps/html/osrm/images/"+image;
	};

	// retrieve driving instructions from instruction ids
	router.getDrivingInstruction = function(instruction_id) {
		var id = "DIRECTION_"
				  + instruction_id
						.replace(/^11-\d{2,}$/,"11-x");	// dumb check, if there are 10+ exits on a roundabout (say the same for exit 10+)

		var description = this.getText(id);
		if( !description )
			return this.getText('DIRECTION_0');
		return description;
	};

	router.addInstructions = function(routeObj) {
		this.instrDiv.innerHTML = "";
		var headerDiv = document.createElement('div');
		headerDiv.innerHTML =
			'<label>'+this.getText('ROUTENAME')+':</label> '+routeObj.route_name[0]+'-'+routeObj.route_name[1]+'<br/>'+
			'<label>'+this.getText('DISTANCE')+':</label> '+this.toHumanDistance(routeObj.route_summary.total_distance)+'<br/>'+
			'<label>'+this.getText('TIME')+':</label> '+this.toHumanTime(routeObj.route_summary.total_time)+'<br/>';

		this.instrDiv.appendChild(headerDiv);

		body = '<table class="description">';
		for(var i=0; i < routeObj.route_instructions.length; i++){
			var instr = routeObj.route_instructions[i];
			//odd or even ?
			var rowstyle='description-body-odd';
			if(i%2===0) { rowstyle='description-body-even'; }

			body += '<tr class="'+rowstyle+'">';

			body += '<td class="description-body-directions">';
			body += '<img class="description-body-direction" src="'+ router.getDrivingInstructionIcon(instr[0]) + '" alt=""/>';
			body += '</td>';

			body += '<td class="description-body-item" data-pos="'+instr[3]+'">';

			// build route description
			if( instr[1] !== "" )
				body += router.getDrivingInstruction(instr[0]).replace(/\[(.*)\]/,"$1").replace(/%s/, instr[1]).replace(/%d/, router.getText(instr[6]));
			else
				body += router.getDrivingInstruction(instr[0]).replace(/\[(.*)\]/,"").replace(/%d/, router.getText(instr[6]));


			body += '</div>';
			body += "</td>";

			body += '<td class="description-body-distance">';
			if( i != routeObj.route_instructions.length-1 )
				body += this.toHumanDistance(instr[5]);
			body += "</td>";

			body += "</tr>";
		}
		var bodyDiv = document.createElement('div');
		bodyDiv.innerHTML = body;
		this.instrDiv.appendChild(bodyDiv);
		this.instrDiv.style.display = '';
		this.printerBtn.className = 'c4gMapsPrinterBtn';

		var fnClearInstrMarker=function() {
			if (router.currentFeature) {
				router.layer.destroyFeatures([router.currentFeature]);
				delete router.currentFeature;
			}
		};
		var fnSetInstrMarker=function(point) {
			fnClearInstrMarker();
			router.currentFeature = new OpenLayers.Feature.Vector(point, {loctype:'current'});
			router.currentFeature.style = router.style.current;
			router.layer.addFeatures([router.currentFeature]);
		};

		var fnItemClick=function(element) {
			var point = router.routeFeature.geometry.getVertices()[element.getAttribute('data-pos')];
			fnSetInstrMarker(point);
			var lonlat = new OpenLayers.LonLat(point.x,point.y);
			router.map.setCenter(lonlat,15);
			// move center so that the route is not covered by the routing dialogs
			router.map.moveByPx((router.dialogSize*(-1.0))/2.0,0);
		};
		var fnItemMouseOver=function(element) {
			var point = router.routeFeature.geometry.getVertices()[element.getAttribute('data-pos')];
			fnSetInstrMarker(point);
		};
		var fnItemMouseOut=function(event) {
			fnClearInstrMarker();
		};
		var tableRows = bodyDiv.childNodes[0].childNodes[0].childNodes;
		for (var i = 0; i < tableRows.length; i++) {
			var element=tableRows[i].childNodes[1];
			OpenLayers.Event.observe(element, 'click',
				OpenLayers.Function.bind(function(element) { fnItemClick(element); }, this, element));
			OpenLayers.Event.observe(element, 'mouseover',
				OpenLayers.Function.bind(function(element) { fnItemMouseOver(element); }, this, element));
			OpenLayers.Event.observe(element, 'mouseout', fnItemMouseOut);
		}
		this.updateInstrSize();
	};

	router.updateInstrSize = function() {
		if (this.instrDiv && (this.instrDiv.style.display==='')) {
			this.instrDiv.style.height = (this.map.getCurrentSize().h - this.instrDiv.offsetTop - 25)+'px';
		}
	};

	router.updateWrapSize = function() {
		if (this.routerWrapperDiv && (this.routerWrapperDiv.style.display==='')) {
			this.routerWrapperDiv.style.height = (this.map.getCurrentSize().h - (this.routerWrapperDiv.offsetTop * 2) - 12)+'px';
		}
	};

	router.updateSize = function() {
		window.c4gMapsRouter = router;
		window.setTimeout("c4gMapsRouter.updateWrapSize()",500);
		window.setTimeout("c4gMapsRouter.updateInstrSize()",600);
	};

	router.performViaRoute=function() {
		var map=this.map;


		var fnAddChecksumParam=function() {
			if ((router.lastChecksum) && (router.hints.from!==undefined) && (router.hints.to!==undefined)){
				return "&checksum="+router.lastChecksum;
			}
			return "";
		};

		var fnAddInstructionsParam=function() {
			return "&instructions=true";
		};

		var fnAddAlternativesParam=function() {
			return "&alt=false";
		};

		var fnAddLocationParam=function(loctype) {
			var f=router.locations[loctype];
			var h=router.hints[loctype];
			var r="";
			if (f) {
				var point = new OpenLayers.Geometry.Point(f.geometry.x, f.geometry.y);
				point.transform(router.map.projection, router.map.displayProjection);
				r="&loc_"+loctype+"="+point.y+','+point.x;
				if (h) {
					r = r + '&hint_'+loctype+'='+h;
				}
			}
			return r;
		};


		OpenLayers.Request.GET({
			url: document.getElementsByTagName('base')[0].href
				+ mapData.viaroute_url
				+"?output=json"
				+"&token="+mapData.REQUEST_TOKEN
				+"&profile="+mapData.profile
				+fnAddInstructionsParam()
				+fnAddAlternativesParam()
				//+"z="+this.map.zoom
				+fnAddChecksumParam()
				+fnAddLocationParam('from')
				+fnAddLocationParam('to'),
			success: function(ajaxRequest) {
				var textResponse = ajaxRequest.responseText;
				router.hideMessage();
				var routeObj = [];
				try {
					var json = new OpenLayers.Format.JSON();
					routeObj = json.read(textResponse);
				} catch(err) {
					alert(router.getText('ERROR_CALC_ROUTE'));
					return;
				}
				if (routeObj.length === 0) {
					alert(router.getText('ERROR_CALC_ROUTE'));
				} else {
					if (router.routeFeature) {
						router.layer.destroyFeatures(router.routeFeature);
					}
					var format = new OpenLayers.Format.C4gEncodedPolyline();
					router.routeFeature = format.read(routeObj.route_geometry,1e6);
					if (!this.routeFeature.geometry.getVertices()[0].equals(new OpenLayers.Geometry.Point(routeObj.via_points[0][1],routeObj.via_points[0][0]))) {
						// precision may be 5 instead of 6 dependant on OSRM server configuration
						router.routeFeature = format.read(routeObj.route_geometry,1e5);
					}
					router.routeFeature.geometry.transform(map.displayProjection,map.projection);
					router.routeFeature.c4gOnClick = function(feature,event) {
						router.setBaseLayerGaps(router.map.baseLayer);
						router.map.zoomToExtent(router.layer.getDataExtent());
						router.resetBaseLayerGaps(router.map.baseLayer);
					};
					router.layer.addFeatures(router.routeFeature);

					if (routeObj.hint_data) {
						if (routeObj.hint_data.checksum) {
							router.lastChecksum = routeObj.hint_data.checksum;
						}
						else {
							delete router.lastChecksum;
						}
						if (routeObj.hint_data.locations[0]) {
							router.hints.from = routeObj.hint_data.locations[0];
						}
						else {
							delete router.hints.from;
						}
						if (routeObj.hint_data.locations[1]) {
							router.hints.to = routeObj.hint_data.locations[1];
						}
						else {
							delete router.hints.to;
						}
					}
					router.locations.from.style.title = routeObj.route_summary.start_point;
					router.locations.from.move( new OpenLayers.LonLat(routeObj.via_points[0][1],routeObj.via_points[0][0]).transform(map.displayProjection,map.projection));
					router.locations.to.style.title = routeObj.route_summary.end_point;
					router.locations.to.move( new OpenLayers.LonLat(routeObj.via_points[1][1],routeObj.via_points[1][0]).transform(map.displayProjection,map.projection));

					router.map.zoomToExtent(router.layer.getDataExtent());

					router.layer.refresh();

					router.addInstructions(routeObj);
				}
			},
			failure: function(e) {
				alert(router.getText('ERROR_CALC_ROUTE'));
				router.hideMessage();
			},
			scope: this
		});
		this.showMessage(this.getText('CALC_ROUTE'));

	};

	router.printWindowLoaded = function() {
		var printdoc = router.printwindow.document;

		var title = printdoc.getElementsByTagName('title')[0];
		if (title) {
			title.innerHTML = this.getText('ROUTEDESC');
		}

		var printerDiv = printdoc.getElementById('c4gMapsPrinter');
		printerDiv.innerHTML = printerDiv.innerHTML +
			'<label>'+this.getText('FROM')+':</label> '+this.fromInput.value+'<br/>'+
			'<label>'+this.getText('TO')+':</label> '+this.toInput.value+'<br/>'+
			router.instrDiv.innerHTML;

		printdoc.getElementById('c4gMapsPrinterBtn').onclick = router.printwindow.printWindow;

	};

	router.print = function() {
		if (router.instrDiv.style.display === '') {
			window.c4gMapsRouter=router;
			router.printwindow =
				window.open( document.getElementsByTagName('base')[0].href + "system/modules/con4gis_maps/C4GMapsPrinter.php","","width=540,height=500,left=100,top=100,dependent=yes,location=no,menubar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes");
		}
	};
	return router;

};
