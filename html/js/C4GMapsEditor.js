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
 * @copyright  Küstenschmiede GmbH Software & Design 2014 - 2015
 * @author     Jürgen Witte & Tobias Dobbrunz <http://www.kuestenschmiede.de>
 * @package    con4gis
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */
OpenLayers.Control.C4gSelectFeature = OpenLayers.Class(OpenLayers.Control.SelectFeature, {
	initialize: function (layer, handler, options) {
		OpenLayers.Control.SelectFeature.prototype.initialize.apply(this, [layer, handler, options]);
	},
	clickFeature: function(feature) {
		var selected = (OpenLayers.Util.indexOf(
			feature.layer.selectedFeatures, feature) > -1);
		OpenLayers.Control.SelectFeature.prototype.clickFeature.apply(this,[feature]);
		if(!this.hover) {
			if(selected) {
				if (this.onReclickFeature)
					this.onReclickFeature(feature);
			}
		}
	}
});

OpenLayers.Control.C4gDrawFeature = OpenLayers.Class(OpenLayers.Control.DrawFeature, {
	initialize: function (layer, handler, options) {
		OpenLayers.Control.DrawFeature.prototype.initialize.apply(this, [layer, handler, options]);

		// configure the keyboard handler
		this.keyboardCallbacks = {
			keydown: this.handleKeyDown
		};
		this.keyboardHandler = new OpenLayers.Handler.Keyboard(this, this.keyboardCallbacks, {});
	},
	handleKeyDown: function (evt) {
		switch (evt.keyCode) {
		case 90: // z
			if (evt.metaKey || evt.ctrlKey) {
				this.undo();
				handled = true;
			}
			break;
		case 89: // y
			if (evt.metaKey || evt.ctrlKey) {
				this.redo();
				handled = true;
			}
			break;
		case 27: // esc
			this.cancel();
			handled = true;
			break;
		case 13: // enter
			this.submit();
			handled = true;
			break;
		}
	},
	activate: function () {
		OpenLayers.Control.DrawFeature.prototype.activate.apply(this, arguments);
		this.keyboardHandler.activate();
	},
	deactivate: function () {
		OpenLayers.Control.DrawFeature.prototype.deactivate.apply(this, arguments);
		this.keyboardHandler.deactivate();
	},
	cancel: function() {
		OpenLayers.Control.DrawFeature.prototype.cancel.apply(this);
		if (this.onCancel)
			this.onCancel();
	},
	submit: function() {
		this.finishSketch();
	}
});

OpenLayers.Control.C4gModifyFeature = OpenLayers.Class(OpenLayers.Control.ModifyFeature, {
	initialize: function (layer, handler, options) {
		OpenLayers.Control.ModifyFeature.prototype.initialize.apply(this, [layer, handler, options]);

		this.map = layer.map;

		// configure the keyboard handler
		this.keyboardCallbacks = {
			keydown: this.handleKeyDown
		};
		this.keyboardHandler = new OpenLayers.Handler.Keyboard(this, this.keyboardCallbacks, {});

		this.clickCallbacks = {
			click: this.handleMouseClick
		};
		this.clickHandler = new OpenLayers.Handler.Click(this, this.clickCallbacks, {});

	},
	handleMouseClick: function(evt) {
		var feature = this.layer.getFeatureFromEvent(evt);
		if (feature!==this.feature) {
			if ((this.mode===OpenLayers.Control.ModifyFeature.DRAG) && (!this.isPoint)) {
				this.setReshapeMode();
			}
			else {
				this.submit();
			}
		}
		else {
			if (!this.isPoint) {
				if (this.mode!==OpenLayers.Control.ModifyFeature.DRAG) {
					this.setDragMode();
				}
				else {
					this.setReshapeMode();
				}
			}
		}
	},
	handleKeyDown: function (evt) {
		switch (evt.keyCode) {
		case 90: // z
			if (evt.metaKey || evt.ctrlKey) {
				this.undo();
				handled = true;
			}
			break;
		case 89: // y
			if (evt.metaKey || evt.ctrlKey) {
				this.redo();
				handled = true;
			}
			break;
		case 27: // esc
			this.cancel();
			handled = true;
			break;
		case 13: // enter
			this.submit();
			handled = true;
			break;
		}
	},
	activate: function () {
		OpenLayers.Control.ModifyFeature.prototype.activate.apply(this, arguments);
		this.keyboardHandler.activate();
		this.clickHandler.activate();
	},
	deactivate: function () {
		OpenLayers.Control.ModifyFeature.prototype.deactivate.apply(this, arguments);
		this.clickHandler.deactivate();
		this.keyboardHandler.deactivate();
	},
	cancel: function() {
		if (this.onCancel)
			this.onCancel();
	},
	submit: function() {
		if (this.onSubmit)
			this.onSubmit();
	},
	undo: function() {
		var feature = this.feature;
		if (feature.geometry && feature.modified && feature.modified.geometry) {
			feature.redoGeometry = feature.geometry;
			this.layer.removeFeatures([feature]);
			feature.geometry = feature.modified.geometry;
			this.layer.addFeatures([feature]);
			delete feature.modified;
			this.selectFeature(feature);
			this.resetVertices();
			this.layer.map.editor.selectFeature.select(feature);
			//this.layer.redraw();
		}
	},
	redo: function() {
		var feature = this.feature;
		if (feature.redoGeometry) {
			this.layer.removeFeatures([feature]);
			if (!feature.modified) {
				feature.modified = {
					geometry: feature.geometry
				};
			}
			feature.geometry = feature.redoGeometry;
			this.layer.addFeatures([feature]);
			delete feature.redoGeometry;
			this.selectFeature(feature);
			this.resetVertices();
			this.layer.map.editor.selectFeature.select(feature);
		}
	},
	setDragMode: function() {
		this.mode = OpenLayers.Control.ModifyFeature.DRAG;
		this.deactivate();
		this.activate();
	},
	setReshapeMode: function() {
		this.mode = OpenLayers.Control.ModifyFeature.RESHAPE;
		this.deactivate();
		this.activate();
	},
});


function C4GMapsEditor(mapData,map,styles) {

	if (typeof(mapData.editor_labels)!='undefined') {
		OpenLayers.Lang.en = OpenLayers.Util.extend(OpenLayers.Lang.en, mapData.editor_labels);
	}

	var editor = {};
	map.editor = editor;
	editor.map = map;

	var editorDiv = document.createElement('div');
	editorDiv.id = 'c4gMapsEditor';
	editorDiv.className = 'c4gMapsEditor c4gMapsEditorDialog c4gPortsideExtension';
	editor.editorDiv = editorDiv;
	map.viewPortDiv.appendChild(editorDiv);

	editor.updateEditorSize = function() {
		if (this.editorDiv && (this.editorDiv.style.display === '')) {
			this.editorDiv.style.height = (this.map.getCurrentSize().h - (this.editorDiv.offsetTop * 2) - 12)+'px';
		}
	};
	editor.updateEditorSize();

	editor.updateSize = function() {
		window.c4gMapsEditor = this;
		window.setTimeout("c4gMapsEditor.updateEditorSize()",500);
	};

	map.org2UpdateSize = map.updateSize;
	map.updateSize = function() {
		editor.updateSize();
		this.org2UpdateSize();
	};

	var fnIgnoreEvent = function(event) {
		OpenLayers.Event.stop(event, true);
	};
	var fnIgnoreEvents = function(element) {
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
	fnIgnoreEvents(editorDiv);

	/* --------------------------------------------------------------------------------------
	   state information
	   --------------------------------------------------------------------------------------- */
	var closeDiv = document.createElement('div');
	closeDiv.id = 'c4gMapsCloseBtn_' + map.id;
	closeDiv.className = 'c4gMapsCloseBtn';
	editorDiv.appendChild(closeDiv);
	OpenLayers.Event.observe(closeDiv, 'click',
		OpenLayers.Function.bind(function(input) { editor.closeEditor(); }, this, closeDiv));

	var stateDiv = document.createElement('div');
	stateDiv.id = 'c4gMapsEditorState';
	stateDiv.className = 'c4gMapsEditorState';
	editorDiv.appendChild(stateDiv);
	editor.stateDiv = stateDiv;

	editor.getFeatureName=function(feature) {
		var name = 'Objekt';
		if (feature) {
			var locstyle = parseInt(feature.attributes.locstyle,10);
			if (!isNaN(locstyle)) {
				name = mapData.locStyles[locstyle].name;
			}
		}
		return name;
	};

	editor.setStateInfo = function() {
		stateDiv.innerHTML = '';
		if (editor.selectFeature.active) {
			stateDiv.innerHTML = OpenLayers.i18n('c4gActSelect')+'<br />';

			if (this.editLayer.selectedFeatures.length==1) {
				stateDiv.innerHTML += OpenLayers.i18n('c4gObjectSelected')._c4gFormat(editor.getFeatureName(this.editLayer.selectedFeatures[0]));

			}
			else {
				stateDiv.innerHTML += OpenLayers.i18n('c4gObjectsSelected')._c4gFormat(this.editLayer.selectedFeatures.length);
			}
		}
		else if (this.modifyMode) {
			if (this.modifyFeature.mode == OpenLayers.Control.ModifyFeature.DRAG) {
				stateDiv.innerHTML = OpenLayers.i18n('c4gActMove')+'<br />';
			}
			else {
				stateDiv.innerHTML = OpenLayers.i18n('c4gActChange')+'<br />';
			}
			stateDiv.innerHTML +=	OpenLayers.i18n('c4gObject')._c4gFormat(editor.getFeatureName(this.actFeature));
		}
		else if (this.activeDrawControl !== null) {
			if (editor.curLocstyle) {
				stateDiv.innerHTML = OpenLayers.i18n('c4gActAdd')+'<br />'+
					OpenLayers.i18n('c4gObject')._c4gFormat(mapData.locStyles[editor.curLocstyle].name);
			}
		}
	};

	/* --------------------------------------------------------------------------------------
	   icon toolbar
	   --------------------------------------------------------------------------------------- */
	var toolbarDiv = document.createElement('div');
	toolbarDiv.id = 'c4gMapsEditorToolbar';
	toolbarDiv.className = 'c4gMapsEditorToolbar';
	editorDiv.appendChild(toolbarDiv);
	editor.toolbarDiv = toolbarDiv;

	editor.setStyle = function(feature,cursor) {
		var locstyle = parseInt(feature.attributes.locstyle,10);
		if (isNaN(locstyle) && (!feature._feature /* Arrows */ ) && (!feature._sketch /* modify handlers */)) {
			locstyle = parseInt(mapData.editor_defstyle,10);
		}
		if (!isNaN(locstyle)) {
			var style = {};
			OpenLayers.Util.extend(style, styles[locstyle]);
			feature.style = style;
			feature.style.cursor = cursor;
			feature.style.graphicZIndex = 1;
		}
	};

	editor.setAllStyles = function(cursor) {
		for (var i = 0; i < editor.editLayer.features.length; i++) {
			this.setStyle(editor.editLayer.features[i],cursor);
		}
	};

	editor.unselectAllIcons = function() {
		for (var i = 0; i < locstyleDiv.childNodes.length; i++) {
			OpenLayers.Element.removeClass(locstyleDiv.childNodes[i],'c4gMapsEditorSelected');
		}

		for (var i = 0; i < actionButtonDiv.childNodes.length; i++) {
			OpenLayers.Element.removeClass(actionButtonDiv.childNodes[i],'c4gMapsEditorSelected');
		}

	};

	editor.getGeometryType = function(feature) {
		switch (feature.geometry.CLASS_NAME) {
		case 'OpenLayers.Geometry.Polygon':
		case 'OpenLayers.Geometry.MultiPolygon':
			return 'polygon';
		case 'OpenLayers.Geometry.LinearRing':
		case 'OpenLayers.Geometry.LineString':
		case 'OpenLayers.Geometry.MultiLineString':
			return 'path';
		case 'OpenLayers.Geometry.Curve':
		case 'OpenLayers.Geometry.Point':
		case 'OpenLayers.Geometry.MultiPoint':
			return 'point';
		}
		return 'unknown';
	};

	var locstyleDiv = document.createElement('div');
	locstyleDiv.id = 'c4gMapsEditorLocstyles';
	locstyleDiv.className = 'c4gMapsEditorLocstyles';
	toolbarDiv.appendChild(locstyleDiv);
	editor.locstyleDiv = locstyleDiv;

	// sort locations after sort-order and alphabetically
	editor.locations = new Array();
	for (var i in mapData.locStyles) {
		var loc = mapData.locStyles[i];
		loc.mdId = i;
		editor.locations.push(loc);
	}

	var alphaSort = function(a,b){
		if (!a.name || !b.name) {
			return (!b.name)? -1 : 1;
		} else {
			var A = a.name.toLowerCase();
			var B = b.name.toLowerCase();
			return (A > B)? 1 : -1;
		}
	};

	editor.locations.sort( function(a,b){
		if (a.editor_sort && a.editor_sort <= 0) {
			a.editor_sort = false;
		}
		if (b.editor_sort && b.editor_sort <= 0) {
			b.editor_sort = false;
		}

		if ((!a.editor_sort && !b.editor_sort) || (a.editor_sort == b.editor_sort)) {
			return alphaSort(a, b);
		} else if (!a.editor_sort || !b.editor_sort) {
			return (!b.editor_sort)? -1 : 1;
		} else {
			return (a.editor_sort > b.editor_sort)? 1 : -1;
		}
	});

	for ( var i in editor.locations) {
		var locstyle = editor.locations[i];
		var added = false;
		var fnAddLocstyleIcon = function( drawStyle ) {
			var locstyleImg = document.createElement('img');
			locstyleImg.id = 'c4gMapsEditorLocstyle'+locstyle.mdId;
			locstyleImg.className = 'c4gMapsEditorLocstyle c4gMapsEditorButton';
			if (locstyle.editor_icon) {
				locstyleImg.setAttribute('src',locstyle.editor_icon);
			}
			else if (locstyle.externalGraphic) {
				locstyleImg.setAttribute('src',locstyle.externalGraphic);
				if (locstyle.graphicWidth)
					locstyleImg.style.width = locstyle.graphicWidth+'px';
				if (locstyle.graphicHeight)
					locstyleImg.style.width = locstyle.graphicHeight+'px';
			}
			else {
				var icon = 'draw_point.png';
				if (drawStyle == 'polygon') {
					icon = 'draw_polygon.png';
				}
				else if (drawStyle == 'line') {
					icon = 'draw_path.png';
				}
				locstyleImg.setAttribute('src','system/modules/con4gis_maps/html/' + icon);
			}
			//shows the tooltip instead of name
			if ((locstyle.tooltip) && (locstyle.tooltip != 'unknown') && (locstyle.tooltip.indexOf("${") == -1)) {
			  locstyleImg.setAttribute('title',locstyle.tooltip);
			} else {
			  locstyleImg.setAttribute('title',locstyle.name);
			}
			locstyleImg.drawStyle = drawStyle;
			locstyleDiv.appendChild(locstyleImg);
			OpenLayers.Event.observe(locstyleImg, 'click',
				OpenLayers.Function.bind(function(element) {
					editor.unselectAllIcons();
					OpenLayers.Element.addClass(element,'c4gMapsEditorSelected');
					var locstyleId = element.id.substr(21);
					var locstyle = mapData.locStyles[locstyleId];
					editor.curLocstyle = locstyleId;
					editor.activateDrawControl(locstyleImg.drawStyle);
				}, this, locstyleImg)
			);
			added = true;
		};
		if (locstyle.editor_points) {
			fnAddLocstyleIcon('point');
		}
		if (locstyle.editor_lines) {
			fnAddLocstyleIcon('line');
		}
		if (locstyle.editor_polygones) {
			fnAddLocstyleIcon('polygon');
		}
		if ((i==mapData.editor_defstyle) && !added) {
			fnAddLocstyleIcon('point');
			if (!locstyle.externalGraphic) {
				fnAddLocstyleIcon('line');
				fnAddLocstyleIcon('polygon');
			}
		}

	}


	/* --------------------------------------------------------------------------------------
	   path and polygon editing buttons
	   --------------------------------------------------------------------------------------- */
	var featureButtonDiv = document.createElement('div');
	featureButtonDiv.id = 'c4gMapsEditorAddFeatureButtons';
	featureButtonDiv.className = 'c4gMapsEditorAddFeatureButtons';
	toolbarDiv.appendChild(featureButtonDiv);
	editor.featureButtonDiv = featureButtonDiv;

	var okIcon = document.createElement('img');
	okIcon.id = 'c4gMapsEditorButtonFeatureOk';
	okIcon.className = 'c4gMapsEditorButton';
	okIcon.setAttribute('src','system/modules/con4gis_maps/html/ok.png');
	okIcon.setAttribute('title',OpenLayers.i18n('c4gTitleOk'));
	featureButtonDiv.appendChild(okIcon);
	OpenLayers.Event.observe(okIcon, 'click',
		function() {
			if (editor.modifyMode) {
				editor.modifyFeature.onSubmit();
			}
			else {
				editor.activeDrawControl.submit();
			}
		}
	);

	var cancelIcon = document.createElement('img');
	cancelIcon.id = 'c4gMapsEditorButtonFeatureCancel';
	cancelIcon.className = 'c4gMapsEditorButton';
	cancelIcon.setAttribute('src','system/modules/con4gis_maps/html/cancel.png');
	cancelIcon.setAttribute('title',OpenLayers.i18n('c4gTitleCancel'));
	featureButtonDiv.appendChild(cancelIcon);
	OpenLayers.Event.observe(cancelIcon, 'click',
		function() {
			if (editor.modifyMode) {
				editor.modifyFeature.onCancel();
			}
			else {
				editor.activeDrawControl.cancel();
			}
		}
	);

	var undoIcon = document.createElement('img');
	undoIcon.id = 'c4gMapsEditorButtonFeatureUndo';
	undoIcon.className = 'c4gMapsEditorButton';
	undoIcon.setAttribute('src','system/modules/con4gis_maps/html/undo.png');
	undoIcon.setAttribute('title',OpenLayers.i18n('c4gTitleUndo'));
	featureButtonDiv.appendChild(undoIcon);
	OpenLayers.Event.observe(undoIcon, 'click',
		function() {
			if (editor.modifyMode) {
				editor.modifyFeature.undo();
			}
			else {
				editor.activeDrawControl.undo();
			}
		}
	);


	var redoIcon = document.createElement('img');
	redoIcon.id = 'c4gMapsEditorButtonFeatureRedo';
	redoIcon.className = 'c4gMapsEditorButton';
	redoIcon.setAttribute('src','system/modules/con4gis_maps/html/redo.png');
	redoIcon.setAttribute('title',OpenLayers.i18n('c4gTitleRedo'));
	featureButtonDiv.appendChild(redoIcon);
	OpenLayers.Event.observe(redoIcon, 'click',
		function() {
			if (editor.modifyMode) {
				editor.modifyFeature.redo();
			}
			else {
				editor.activeDrawControl.redo();
			}
		}
	);

	featureButtonDiv.style.display = 'none';

	/* --------------------------------------------------------------------------------------
	   path and polygon editing buttons
	   --------------------------------------------------------------------------------------- */
	var actionButtonDiv = document.createElement('div');
	actionButtonDiv.id = 'c4gMapsEditorActionButtons';
	actionButtonDiv.className = 'c4gMapsEditorActionButtons';
	toolbarDiv.appendChild(actionButtonDiv);
	editor.actionButtonDiv = actionButtonDiv;

	var selectIcon = document.createElement('img');
	selectIcon.id = 'c4gMapsEditorButtonSelect';
	selectIcon.className = 'c4gMapsEditorButton';
	selectIcon.setAttribute('src','system/modules/con4gis_maps/html/select.png');
	selectIcon.setAttribute('title',OpenLayers.i18n('c4gTitleSelect'));
	editor.selectIcon = selectIcon;
	actionButtonDiv.appendChild(selectIcon);
	OpenLayers.Event.observe(selectIcon, 'click',
		function() {
			editor.setSelectionMode();
		}
	);


	var modifyIcon = document.createElement('img');
	modifyIcon.id = 'c4gMapsEditorButtonModify';
	modifyIcon.className = 'c4gMapsEditorButton c4gMapsEditorInvisible';
	modifyIcon.setAttribute('src','system/modules/con4gis_maps/html/modify.png');
	modifyIcon.setAttribute('title',OpenLayers.i18n('c4gTitleModify'));
	editor.modifyIcon = modifyIcon;
	actionButtonDiv.appendChild(modifyIcon);
	OpenLayers.Event.observe(modifyIcon, 'click',
		function() {
			editor.modifySelectedFeature();
		}
	);

	var deleteIcon = document.createElement('img');
	deleteIcon.id = 'c4gMapsEditorButtonDelete';
	deleteIcon.className = 'c4gMapsEditorButton c4gMapsEditorInvisible';
	deleteIcon.setAttribute('src','system/modules/con4gis_maps/html/delete.png');
	deleteIcon.setAttribute('title',OpenLayers.i18n('c4gTitleDelete'));
	editor.deleteIcon = deleteIcon;
	actionButtonDiv.appendChild(deleteIcon);
	OpenLayers.Event.observe(deleteIcon, 'click',
		function() {
			editor.deleteFeatures();
		}
	);

	if (mapData.editor_helpurl) {
		var helpButton = document.createElement('a');
		helpButton.href = '#';
		helpButton.className = 'c4gMapsEditorHelpLink';
		helpButton.innerHTML = OpenLayers.i18n('c4gHelp');
		actionButtonDiv.appendChild(helpButton);

		helpButton.onclick = function() {
			window.open( mapData.editor_helpurl,"","width=540,height=500,left=100,top=100,dependent=yes,location=no,menubar=no,scrollbars=yes,status=no,toolbar=no,resizable=yes");
			return false;
		};
	}

	/* --------------------------------------------------------------------------------------
	   initialize content div
	   --------------------------------------------------------------------------------------- */
	var contentDiv = document.createElement('div');
	contentDiv.id = 'c4gMapsEditorContent';
	contentDiv.className = 'c4gMapsEditorContent c4gMapsEditorInvisible';
	editorDiv.appendChild(contentDiv);
	editor.contentDiv = contentDiv;

	/* --------------------------------------------------------------------------------------
	   Arrows
	   --------------------------------------------------------------------------------------- */

	editor.addArrows = function(feature) {
		if (this.addingArrows)
			return;
		if (feature.style) {
			if (feature.style.arrowRadius) {
				var arrows = C4GMapsUtils.createArrows(feature,feature.geometry);
				this.addingArrows = true;
				this.editLayer.addFeatures(arrows);
				delete this.addingArrows;
			}
		}
	};

	editor.removeArrows = function(feature) {
		if (this.removingArrows)
			return;
		if (feature.style) {
			if (feature.style.arrowRadius) {
				this.removingArrows = true;
				C4GMapsUtils.removeArrows(this.editLayer,feature);
				delete this.removingArrows;
			}
		}
	};

	editor.updateArrows = function(feature) {
		this.removeArrows(feature);
		this.addArrows(feature);
	};

	/* --------------------------------------------------------------------------------------
	   initialize editing layer
	   --------------------------------------------------------------------------------------- */
	editor.editLayer = new OpenLayers.Layer.Vector('Editor',{displayInLayerSwitcher:false});
	editor.editLayer.events.register("featureadded", null, function(e) {
		editor.setStyle(e.feature,'');
		editor.addArrows(e.feature);
	});
	editor.editLayer.events.register("featureremoved", null, function(e) {
		editor.removeArrows(e.feature);
	});
	map.addLayer(editor.editLayer);
	if (mapData.editor_input){
		var format = new OpenLayers.Format.GeoJSON();
		editor.editLayer.addFeatures(format.read(mapData.editor_input));
	}

	editor.dialogSize = 250;
	editor.setBaseLayerGaps = function(layer) {
		layer.orgMinExtentGapX = layer.minExtentGapX;
		layer.minExtentGapX = (editor.dialogSize / 2.0) + 20;
		layer.orgMinExtentGapY = layer.minExtentGapY;
		layer.minExtentGapY = 20;
	};

	// force layers to consider size of dialog on map when zooming to extent e.g. of route
	for (var i = 0; i < map.layers.length; i++) {
		var layer = map.layers[i];
		if (layer.isBaseLayer) {
			editor.setBaseLayerGaps(layer);
		}
	}

	map.orgZoomToExtentEditor = map.zoomToExtent;
	map.zoomToExtent = function(bounds, closest) {
		this.orgZoomToExtentEditor(bounds,closest);
		// move center so that the route is not covered by the routing dialogs
		this.moveByPx((this.editor.dialogSize*(-1.0))/2.0,0);

		// workaround: otherwise layer is not displayed correctly
		this.moveTo(this.baseLayer.getExtent());

	};

	for(var i = 0; i < map.controls.length; ++i)
		if (map.controls[i].div)
			OpenLayers.Element.addClass(map.controls[i].div, 'c4gMapsEditorDialogActive');


	// --------------------------------------------------------------------------
	editor.createCollectionFeatures = function(locstyle) {
		var collFeat = {};
		var delFeats = [];
		for (var i = this.editLayer.features.length - 1; i >= 0; i--) {
			var feat = this.editLayer.features[i];
			if ((locstyle === undefined) || (feat.attributes.locstyle == locstyle)) {
				if (feat.style && feat.style.editor_collect) {
					if (typeof(collFeat[feat.attributes.locstyle]) == 'undefined') {
						collFeat[feat.attributes.locstyle] =
							new OpenLayers.Feature.Vector(new OpenLayers.Geometry.Collection(),feat.attributes,feat.style);
					}
					var curColl = collFeat[feat.attributes.locstyle];
					if (feat.geometry.CLASS_NAME == 'OpenLayers.Geometry.Collection') {
						curColl.geometry.addComponents(feat.geometry.components);
						curColl.attributes = feat.attributes;
					}
					else {
						curColl.geometry.addComponents([feat.geometry]);
					}
					this.editLayer.removeFeatures([feat]);
					delFeats.push(feat);
				}
			}
		}
		for (var i = 0; i < delFeats.length; i++) {
			editor.removeArrows(delFeats[i]);
		}

		for (var key in collFeat) {
			this.editLayer.addFeatures([collFeat[key]]);
			editor.addArrows(collFeat[key]);
		}
	};

	/* --------------------------------------------------------------------------------------
	   draw controls
	   --------------------------------------------------------------------------------------- */
	editor.getDrawControl = function(handler) {
		var control = new OpenLayers.Control.C4gDrawFeature(editor.editLayer, handler);
		control.featureAdded = function(feature) {
			feature.toState(OpenLayers.State.UNKNOWN); // future ModifyFeature should work correctly
			if (editor.curLocstyle) {
				feature.attributes = { locstyle : editor.curLocstyle };
				feature.style = OpenLayers.Util.extend({},styles[editor.curLocstyle]);
				editor.editLayer.redraw();
			}
			editor.featureButtonDiv.style.display = 'none';
			editor.locstyleDiv.style.display = '';
			editor.map.updateSize();
			editor.setStateInfo();
			editor.addArrows(feature);
			editor.createCollectionFeatures(feature.attributes.locstyle);
		};
		control.onCancel = function() {
			editor.featureButtonDiv.style.display = 'none';
			editor.locstyleDiv.style.display = '';
			editor.map.updateSize();
			editor.setStateInfo();
		};
		if (handler != OpenLayers.Handler.Point) {

			control.handler.callbacks = OpenLayers.Util.extend(control.handler.callbacks, {
				point: function(point) {
					if (editor.featureButtonDiv.style.display == 'none') {
						editor.featureButtonDiv.style.display = '';
						editor.locstyleDiv.style.display = 'none';
						editor.map.updateSize();
					}
					return true;
				}
			});

		}
		return control;
	};

	editor.drawControls = {
		point: editor.getDrawControl(OpenLayers.Handler.Point),
		line: editor.getDrawControl(OpenLayers.Handler.Path),
		polygon: editor.getDrawControl(OpenLayers.Handler.Polygon)
	};

	editor.deactivateControls = function() {
		editor.selectFeature.deactivate();
		editor.modifyFeature.deactivate();
		for(var key in this.drawControls) {
			if (this.drawControls[key].active) {
				this.drawControls[key].cancel();
				this.drawControls[key].deactivate();
			}
		}
		editor.activeDrawControl = null;
	};

	editor.activateDrawControl = function(drawStyle) {
		editor.deactivateControls();
		var control = this.drawControls[drawStyle];
		control.activate();
		editor.activeDrawControl = control;
		editor.setStateInfo();
	};

	for(var key in editor.drawControls) {
		map.addControl(editor.drawControls[key]);
	}

	/* --------------------------------------------------------------------------------------
	   feature selection mode
	   --------------------------------------------------------------------------------------- */
	editor.actFeature = null;
	editor.varInput = null;
	editor.setSelectionMode = function() {
		// set selected icon
		this.unselectAllIcons();
		OpenLayers.Element.addClass(this.selectIcon,'c4gMapsEditorSelected');

		// deactivate draw controls
		for(var key in this.drawControls) {
			if (this.drawControls[key].active) {
				this.drawControls[key].cancel();
				this.drawControls[key].deactivate();
			}
		}

		editor.modifyFeature.deactivate();
		editor.selectFeature.activate();

		if (editor.actFeature) {
			var feature = editor.actFeature;
			editor.selectFeature.unselectAll();
			editor.selectFeature.select(feature);
			editor.updateArrows(editor.actFeature);
		}

	};

	editor.setFeatureSelected = function(feature) {
		feature.style.fillColor = '#fc0';
		feature.style.strokeColor = '#f70';
		feature.style.graphicZIndex = 2;
		if(feature.style.externalGraphic) {
			//feature.style.rotation = 45;
			feature.style.graphicOpacity = feature.style.graphicOpacity / 2;
		}
	};

	editor.updatePanelForSelection = function() {
		if ((this.editLayer.selectedFeatures.length > 0) && (!this.modifyMode)) {
			OpenLayers.Element.removeClass(this.deleteIcon,'c4gMapsEditorInvisible');
		}
		else {
			OpenLayers.Element.addClass(this.deleteIcon,'c4gMapsEditorInvisible');
		}

		if (this.modifyMode)
			return;

		editor.setStateInfo();

		if (this.editLayer.selectedFeatures.length != 1) {
			if (this.actFeature !== null) {
				// save all variables for the feature
				if (this.varInput !== null) {
					for (var i = 0; i < this.varInput.length; i++) {
						if (this.varInput[i].type == 'checkbox') {
							if (!this.varInput[i].checked)
								delete this.actFeature.attributes[this.varInput[i].getAttribute('data-key')];
							else
								this.actFeature.attributes[this.varInput[i].getAttribute('data-key')] = true;
						}
						else {
							if (this.varInput[i].value==='')
								delete this.actFeature.attributes[this.varInput[i].getAttribute('data-key')];
							else
								this.actFeature.attributes[this.varInput[i].getAttribute('data-key')] = this.varInput[i].value;
						}
					}
				}
				this.actFeature = null;
			}
			this.varInput = null;
			this.contentDiv.innerHTML = '';
			OpenLayers.Element.addClass(this.contentDiv,'c4gMapsEditorInvisible');
			OpenLayers.Element.addClass(this.modifyIcon,'c4gMapsEditorInvisible');
		}
		else {
			this.actFeature = this.editLayer.selectedFeatures[0];
			OpenLayers.Element.removeClass(this.contentDiv,'c4gMapsEditorInvisible');
			OpenLayers.Element.removeClass(this.modifyIcon,'c4gMapsEditorInvisible');
		}

		if (this.actFeature) {
			this.setFieldsForLocstyle(this.actFeature.attributes.locstyle);
		}

	};

	editor.selectionChanged = function(feature,selected) {
		if(!selected) {
			if (feature.style) {
				if (feature.orgStyle) {
					feature.style = feature.orgStyle;
					C4GMapsUtils.updateArrowStyle(layer,feature);
				}
			}
		}
		else {
			if (feature.style) {
				feature.orgStyle = OpenLayers.Util.extend({},feature.style);
				this.setFeatureSelected(feature);
				C4GMapsUtils.updateArrowStyle(layer,feature);
			}
		}

		this.editLayer.redraw();
		this.updatePanelForSelection();
	};

	editor.setFieldsForLocstyle = function(locstyle) {
		var feature = this.actFeature;
		this.varInput = [];
		this.contentDiv.innerHTML = '';
		if (locstyle) {
			if (mapData.locStyles[locstyle]) {
				if (mapData.locStyles[locstyle].arrowRadius) {
					var newDiv = document.createElement('div');
					newDiv.id = 'c4gMapsEditInputDivArrowBack';
					newDiv.className = 'c4gMapsEditInputDiv';

					var newInput = document.createElement('input');
					newInput.id = 'c4gMapsEditArrowBack';
					newInput.className = 'c4gMapsEditCheckbox';
					newInput.setAttribute('data-key', 'ArrowBack' );
					newInput.type = 'checkbox';
					newInput.value = 'X';
					if (typeof(feature.attributes.ArrowBack)!='undefined') {
						if (feature.attributes.ArrowBack)
							newInput.checked = true	;
					}

					OpenLayers.Event.observe(newInput, 'change',
						OpenLayers.Function.bind(function(input) {
							feature.attributes[input.getAttribute('data-key')] = input.checked;
							editor.updateArrows(editor.actFeature);
						}, this, newInput)
					);
					newDiv.appendChild(newInput);

					var newLabel = document.createElement('label');
					newLabel.innerHTML = OpenLayers.i18n('c4gArrowsBack');
					newLabel.setAttribute('for','c4gMapsEditArrowBack' );
					newDiv.appendChild(newLabel);

					this.contentDiv.appendChild(newDiv);
					this.varInput.push(newInput);

				}
				var editorVars = mapData.locStyles[locstyle].editor_vars;
				if (editorVars) {
					for (var i = 0; i < editorVars.length; i++) {
						var editorVar = editorVars[i];
						var newDiv = document.createElement('div');
						newDiv.id = 'c4gMapsEditInputDiv' + editorVar.key;
						newDiv.className = 'c4gMapsEditInputDiv';
						var newLabel = document.createElement('label');
						newLabel.innerHTML = editorVar.value;
						newLabel.setAttribute('for','c4gMapsEditInput' + editorVar.key );

						newDiv.appendChild(newLabel);

						var newInput = document.createElement('input');
						newInput.id = 'c4gMapsEditInput' + editorVar.key;
						newInput.className = 'c4gMapsEditInput';
						newInput.setAttribute('data-key', editorVar.key );
						newInput.type = 'text';
						newInput.size = '50';
						if (typeof(feature.attributes[editorVar.key]) != 'undefined') {
							newInput.value = feature.attributes[editorVar.key];
						}
						OpenLayers.Event.observe(newInput, 'change',
							OpenLayers.Function.bind(function(input) {
								feature.attributes[input.getAttribute('data-key')] = input.value;
							}, this, newInput)
						);
						newDiv.appendChild(newInput);
						this.contentDiv.appendChild(newDiv);
						this.varInput.push(newInput);
					}
				}
			}
		}
	};

	editor.selectFeature = new OpenLayers.Control.C4gSelectFeature( [editor.editLayer], {
			clickout: true,
			toggle: false,
			multiple: false,
			hover: false,
			toggleKey: "ctrlKey",
			multipleKey: "ctrlKey",
			box: false,
			eventListeners: {
				"activate" : function(e) {
					editor.setAllStyles('pointer');
					editor.editLayer.redraw();
					editor.setStateInfo();
				},
				"deactivate" : function(e) {
					if (!editor.modifyMode) {
						this.unselectAll();
						editor.actFeature = null;
					}
					editor.setAllStyles('');
					editor.editLayer.redraw();
					editor.setStateInfo();
				}
			},
			onSelect: function(feature){
				if (feature._feature) {
					// arrow -> select parent
					this.unselect(feature);
					this.select(feature._feature);
				} else {
					editor.selectionChanged(feature,true);
				}
			},
			onUnselect: function(feature){
				editor.selectionChanged(feature,false);
			},
			onReclickFeature: function(feature){
				if (feature==editor.actFeature) {
					editor.modifySelectedFeature();
				}

			}
		}
	);
	map.addControl( editor.selectFeature );

	/* --------------------------------------------------------------------------------------
	   delete features
	   --------------------------------------------------------------------------------------- */
	editor.deleteFeatures = function() {
		if (this.editLayer.selectedFeatures.length > 0) {
			this.editLayer.destroyFeatures(this.editLayer.selectedFeatures);
			this.editLayer.events.triggerEvent('featureunselected');
			this.updatePanelForSelection();
		}
	};

	/* --------------------------------------------------------------------------------------
	   feature modification mode
	   --------------------------------------------------------------------------------------- */
	editor.modifyFeature = new OpenLayers.Control.C4gModifyFeature(editor.editLayer,
		{
			standalone: true,
			mode: OpenLayers.Control.ModifyFeature.RESHAPE,
			eventListeners: {
				"activate" : function(e) {
					editor.featureButtonDiv.style.display = '';
					editor.locstyleDiv.style.display = 'none';
					editor.modifyMode = true;
					editor.setAllStyles('pointer');
					editor.editLayer.redraw();
					this.selectFeature(editor.actFeature);
					editor.setStateInfo();
					editor.removeArrows(editor.actFeature);
				},
				"deactivate" : function(e) {
					editor.featureButtonDiv.style.display = 'none';
					editor.locstyleDiv.style.display = '';
					editor.modifyMode = false;
					editor.setAllStyles('');
					editor.editLayer.redraw();
					editor.setStateInfo();
				}
			},
			onSubmit: function() {
				editor.setSelectionMode();
				if (editor.actFeature.modified)
					delete editor.actFeature.modified;
				editor.addArrows(editor.actFeature);

			},
			onCancel: function() {
				editor.setSelectionMode();
				if (editor.actFeature.modified) {
					editor.editLayer.removeFeatures([editor.actFeature]);
					editor.actFeature.geometry = editor.actFeature.modified.geometry;
					editor.editLayer.addFeatures([editor.actFeature]);
					editor.selectFeature.select(editor.actFeature);
					delete editor.actFeature.modified;
				}
				editor.addArrows(editor.actFeature);
			}

		}
	);
	map.addControl( editor.modifyFeature );
	editor.modifySelectedFeature = function() {
		this.unselectAllIcons();
		OpenLayers.Element.addClass(this.modifyIcon,'c4gMapsEditorSelected');

		// deactivate draw controls
		for(var key in this.drawControls) {
			this.drawControls[key].deactivate();
		}


		if (this.getGeometryType(editor.actFeature) == 'point') {
			editor.modifyFeature.mode = OpenLayers.Control.ModifyFeature.DRAG;
			editor.modifyFeature.isPoint = true;
		}
		else {
			editor.modifyFeature.mode = OpenLayers.Control.ModifyFeature.RESHAPE;
			editor.modifyFeature.isPoint = false;
		}
		editor.modifyFeature.activate();
		editor.selectFeature.deactivate();
		// [WORKAROUND] need to raise this layer, otherwise editing is not posible
		editor.editLayer.div.style.zIndex = 2000;

		this.updatePanelForSelection();

	};

	// --------------------------------------------------------------------------
	editor.stopEditMode = function()
	{
		editor.createCollectionFeatures();
		editor.deactivateControls();
		C4GMapsUtils.removeAllArrows(editor.editLayer);
		// [WORKAROUND] see above
		editor.editLayer.div.style.zIndex = 725;
	};

	editor.openEditor = function()
	{
		if (editor.map.router && editor.map.router.active) {
			editor.map.router.closePanel(false);
		}

		OpenLayers.Element.removeClass(editor.editorDiv, 'c4gPortsideInactive');

		for(var i = 0; i < map.controls.length; ++i) {
			if (map.controls[i].div) {
				OpenLayers.Element.addClass(map.controls[i].div, 'c4gMapsEditorDialogActive');
			}
		}
		zpPanel = document.getElementById('C4GMapsZoomPositionPanel_' + mapData.id);
		if (zpPanel) { OpenLayers.Element.addClass(zpPanel, 'c4gMapsEditorDialogActive') }

		editor.updateEditorSize();
		editor.setSelectionMode();
		OpenLayers.Element.addClass(editorButton, 'olControlEditorButtonIconActive');
		OpenLayers.Element.removeClass(editorButton, 'olControlEditorButtonIconInactive');


		map.orgZoomToExtentEditor = map.zoomToExtent;
		map.zoomToExtent = function(bounds, closest) {
			this.orgZoomToExtentEditor(bounds,closest);
			this.moveByPx((this.editor.dialogSize*(-1.0))/2.0,0);
			// workaround: otherwise layer is not displayed correctly
			this.moveTo(this.baseLayer.getExtent());

		};

		editor.active = true;
	}

	editor.closeEditor = function()
	{
		OpenLayers.Element.addClass(editor.editorDiv, 'c4gPortsideInactive');

		editor.stopEditMode();
		editor.editLayer.removeAllFeatures();
		OpenLayers.Element.addClass(editorButton, 'olControlEditorButtonIconInactive');
		OpenLayers.Element.removeClass(editorButton, 'olControlEditorButtonIconActive');

		for(var i = 0; i < map.controls.length; ++i) {
			if (map.controls[i].div) {
				OpenLayers.Element.removeClass(map.controls[i].div, 'c4gMapsEditorDialogActive');
			}
		}
		zpPanel = document.getElementById('C4GMapsZoomPositionPanel_' + mapData.id);
		if (zpPanel) { OpenLayers.Element.removeClass(zpPanel, 'c4gMapsEditorDialogActive') }

		map.zoomToExtent = map.orgZoomToExtentEditor;
		delete map.orgZoomToExtentEditor;

		editor.active = false;
	}

	editor.toggleEditor = function()
	{
		editor.active ? editor.closeEditor() : editor.openEditor();
	}

	// --------------------------------------------------------------------------
	if (typeof(mapData.onCreateEditor) == 'function') {
		mapData.onCreateEditor(editor);
	}
	editor.setSelectionMode();
	editor.active = true;


	var editorPanelDiv = document.createElement('div');
	editorPanelDiv.id = 'C4GEditorPanel_' + mapData.id;
	editorPanelDiv.className = 'olControlEditorButtonPanel olControlNoSelect';
	editorPanelDiv.style.position = 'absolute';
	editorPanelDiv.style.zIndex = '1025';

	var editorIconDiv = document.createElement('div');
    editorIconDiv.id = 'C4GMapsEditorButtonIconDiv_' + mapData.id;
    editorIconDiv.className = 'olControlEditorButton olControlEditorButtonInactive olControlNoSelect';

    var editorButton = document.createElement('div');
    editorButton.id = 'C4GMapsEditorButton_' + mapData.id;
    editorButton.className = 'olControlEditorButtonIconInactive olButton';

	editorIconDiv.appendChild(editorButton);
	editorPanelDiv.appendChild(editorIconDiv);
	map.viewPortDiv.appendChild(editorPanelDiv);

	fnIgnoreEvents(editorPanelDiv);
	fnIgnoreEvents(editorIconDiv);

	OpenLayers.Event.observe(editorButton, 'click',
		OpenLayers.Function.bind(function(input) { editor.toggleEditor(); }, this, null));

	editor.closeEditor();


	return editor;
}