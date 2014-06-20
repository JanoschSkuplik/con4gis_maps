/**
 * @copyright  2011 geOps
 * @license    https://github.com/geops/ole/blob/master/license.txt
 * @link       https://github.com/geops/ole
 */

/**
 * @requires Editor/Control/EditorPanel.js
 */

/**
 * Class: OpenLayers.Editor
 * The OpenLayers Editor provides basic methods and informations for map editing.
 *     Highlevel functions are implemented in different controls and can be
 *     activated by the editor constructor. 
 *
 * @constructor
 * @param {OpenLayers.Map} map
 * @param {Object=} options
 */
OpenLayers.Editor = OpenLayers.Class({

    /**
     * Property: map
     * {<OpenLayers.Map>} this gets set in the constructor.
     */
    map: null,

    /**
     * Property: id
     * {String} Unique identifier for the Editor.
     */
    id: null,

    /**
     * Property: editLayer
     * {<OpenLayers.Layer.Vector>} Editor workspace.
     */
    editLayer: null,

    /**
     * Property: editorPanel
     * {<OpenLayers.Editor.Control.EditorPanel>} Contains icons for active controls
     *     and gets set by startEditMode() and unset by stopEditMode().
     */
    editorPanel: null,

    /**
     * Property: editMode
     * {Boolean} The editor is active.
     */
    editMode: false,

    /**
     * Property: dialog
     * {<OpenLayers.Editor.Control.Dialog>} ...
     */
    dialog: null,

    /**
     * Property: status
     * @type {function(string, string)} Function to display states, receives status type and message
     */
    showStatus: function(status, message){
        if(status==='error'){
            alert(message);
        }
    },

    /**
     * Property: activeControls
     * {Array} ...
     */
    activeControls: [],

    /**
     * Property: editorControls
     * {Array} Contains names of all available editor controls. In particular
     *   this information is needed by this EditorPanel.
     */
    editorControls: ['CleanFeature', 'DeleteFeature', 'Dialog', 'DrawHole', 
        'DrawPolygon', 'DrawPath', 'DrawPoint', 'EditorPanel', 'ImportFeature',
        'MergeFeature', 'SnappingSettings', 'SplitFeature', 'CADTools',
        'TransformFeature'],

    /**
     * Geometry types available for editing
     * {Array}
     */
    featureTypes: ['point', 'path', 'polygon'],

    /**
     * Property: sourceLayers
     * {Array} ...
     */
    sourceLayers: [],

    /**
     * Property: parameters
     * {Object} ...
     */
    params: {},

    geoJSON: new OpenLayers.Format.GeoJSON(),

    /**
     * Property: options
     * {Object} ...
     */
    options: {},

    /**
     * Property: URL of processing service.
     * {String}
     */
    oleUrl: '',
    
    /**
     * Instantiated controls
     * {Objects}
     */
    controls: {},

    /**
     * Property: undoRedoActive
     * {Boolean} Indicates if the UndoRedo control is active. Only read on
     *     initialization right now. Default is true.
     */
    undoRedoActive: true,

    /**
     * @param {OpenLayers.Map} map A map that shall be equipped with an editor; can be left undefined in which case a map is created.
     * @param {Object} options
     */
    initialize: function (map, options) {

        OpenLayers.Util.extend(this, options);

        if (map instanceof OpenLayers.Map) {
            this.map = map;
        } else {
            this.map = new OpenLayers.Map();
        }
        
        if (!options) {
            options = {};
        }

        if (!options.dialog) {
            this.dialog = new OpenLayers.Editor.Control.Dialog();
            this.map.addControl(this.dialog);
        }

        this.id = OpenLayers.Util.createUniqueID('OpenLayers.Editor_');

        if (options.editLayer) {
            this.editLayer = options.editLayer
        } else {
            this.editLayer = new OpenLayers.Layer.Vector('Editor', {
                displayInLayerSwitcher: false
            });
        }
        if (options.styleMap) {
            this.editLayer.styleMap = options.styleMap;
        } else {
            this.editLayer.styleMap = new OpenLayers.StyleMap({
                'default': new OpenLayers.Style({
                    fillColor: '#07f',
                    fillOpacity: 0.8,
                    strokeColor: '#037',
                    strokeWidth: 2,
                    graphicZIndex: 1,
                    pointRadius: 5
                }),
                'select': new OpenLayers.Style({
                    fillColor: '#fc0',
                    strokeColor: '#f70',
                    graphicZIndex: 2
                }),
                'temporary': new OpenLayers.Style({
                    fillColor: '#fc0',
                    fillOpacity: 0.8,
                    strokeColor: '#f70',
                    strokeWidth: 2,
                    graphicZIndex: 2,
                    pointRadius: 5
                })
            });
        }

        var selectionContext = {
            editor: this,
            layer: this.editLayer,
            controls: [
                'OpenLayers.Editor.Control.DeleteFeature',
                'OpenLayers.Editor.Control.CleanFeature',
                'OpenLayers.Editor.Control.MergeFeature',
                'OpenLayers.Editor.Control.SplitFeature'
        ]};
        this.editLayer.events.register('featureselected', selectionContext, this.selectionChanged);
        this.editLayer.events.register('featureunselected', selectionContext, this.selectionChanged);

        for (var i = 0, il = this.featureTypes.length; i < il; i++) {
            if (this.featureTypes[i] == 'polygon') {
                this.activeControls.push('DrawPolygon');
            }
            else if (this.featureTypes[i] == 'path') {
                this.activeControls.push('DrawPath');
            }
            else if (this.featureTypes[i] == 'point') {
                this.activeControls.push('DrawPoint');
            }
        }

        for (var i = 0, il = this.sourceLayers.length; i < il; i++) {
            var selectionContext = {
                editor: this,
                layer: this.sourceLayers[i],
                controls: ['OpenLayers.Editor.Control.ImportFeature']
            };
            this.sourceLayers[i].events.register('featureselected', selectionContext, this.selectionChanged);
            this.sourceLayers[i].events.register('featureunselected', selectionContext, this.selectionChanged);
            this.sourceLayers[i].styleMap = new OpenLayers.StyleMap({
                'default': new OpenLayers.Style({
                    fillColor: '#0c0',
                    fillOpacity: 0.8,
                    strokeColor: '#070',
                    strokeWidth: 2,
                    graphicZIndex: 1,
                    pointRadius: 5
                }),
                'select': new OpenLayers.Style({
                    fillColor: '#fc0',
                    strokeColor: '#f70',
                    graphicZIndex: 2
                }),
                'temporary': new OpenLayers.Style({
                    fillColor: '#fc0',
                    fillOpacity: 0.8,
                    strokeColor: '#f70',
                    strokeWidth: 2,
                    graphicZIndex: 2,
                    pointRadius: 5
                })
            });
            this.map.addLayer(this.sourceLayers[i]);
        }

        this.map.editor = this;
        this.map.addLayer(this.editLayer);
        this.map.addControl(new OpenLayers.Editor.Control.LayerSettings(this));

        if (this.undoRedoActive) {
            this.map.addControl(new OpenLayers.Editor.Control.UndoRedo(this.editLayer));
        }
        
        this.addEditorControls();

        return this;
    },
    
    /**
     * Enable or disable controls that depend on selected features.
     * 
     * Requires an active SelectFeature control and the following context variables:
     * - editor: this
     * - layer: The layer with selected features.
     * - controls: An array of class names.
     */
    selectionChanged: function() {

        var selectFeature = this.editor.editorPanel.getControlsByClass('OpenLayers.Control.SelectFeature')[0];
        
        if (this.layer.selectedFeatures.length > 0 && selectFeature && selectFeature.active) {
            // enable controls
            for (var ic = 0, lic = this.controls.length; ic < lic; ic++) {
                var control = this.editor.editorPanel.getControlsByClass(this.controls[ic])[0];
                if (control) {
                    OpenLayers.Element.removeClass(control.panel_div, 'oleControlDisabled');
                }
            }
        } else {
            // disable controls
            for (var ic = 0, lic = this.controls.length; ic < lic; ic++) {
                var control = this.editor.editorPanel.getControlsByClass(this.controls[ic])[0];
                if (control) {
                    OpenLayers.Element.addClass(control.panel_div, 'oleControlDisabled');
                }
            }
        }

        this.editor.editorPanel.redraw();
    },
    
    /**
     * Makes the toolbar appear and allows editing
     */
    startEditMode: function () {
        this.editMode = true;
        this.editorPanel.activate();
    },

    /**
     * Hides the toolbar and prevents editing
     */
    stopEditMode: function () {
        this.editMode = false;
        this.editorPanel.deactivate();
    },
    
    /**
     * Initializes configured controls and shows them
     */
    addEditorControls: function(){
        var control = null, controls = [];
        var editor = this;

        for (var i=0, len=editor.activeControls.length; i<len; i++) {
            control = editor.activeControls[i];
            
            if (OpenLayers.Util.indexOf(editor.editorControls, control) > -1) {
                controls.push(new OpenLayers.Editor.Control[control](
                    editor.editLayer, editor.options[control]
                ));
            }

            switch (control) {

                case 'Separator':
                    controls.push(new OpenLayers.Control.Button({
                        displayClass: 'olControlSeparator'
                    }));
                    break;

                case 'Navigation':
                    controls.push(new OpenLayers.Control.Navigation(
                        OpenLayers.Util.extend(
                            {title: OpenLayers.i18n('oleNavigation')},
                            editor.options.Navigation)
                    ));
                    break;

                case 'DragFeature':
                    controls.push(new OpenLayers.Editor.Control.DragFeature(editor.editLayer,
                        OpenLayers.Util.extend({}, editor.options.DragFeature)
                    ));
                    break;

                case 'ModifyFeature':
                    controls.push(new OpenLayers.Control.ModifyFeature(editor.editLayer,
                        OpenLayers.Util.extend(
                            {title: OpenLayers.i18n('oleModifyFeature')},
                            editor.options.ModifyFeature)
                    ));
                    break;

                case 'SelectFeature':
                    controls.push(new OpenLayers.Control.SelectFeature(
                        editor.sourceLayers.concat([editor.editLayer]),
                        OpenLayers.Util.extend(
                            {
                                title: OpenLayers.i18n('oleSelectFeature'),
                                clickout: true,
                                toggle: false,
                                multiple: false,
                                hover: false,
                                toggleKey: "ctrlKey",
                                multipleKey: "ctrlKey",
                                box: true
                            },
                            editor.options.SelectFeature)
                    ));
                    break;
            }
            
            // Save instance in editor's controls mapping
            this.controls[control] = controls[controls.length-1];
        }
        
        // Add toolbar to map
        this.editorPanel = this.createEditorPanel(controls);
        editor.map.addControl(this.editorPanel);
    },
    
    /**
     * Adds a control to the editor and its panel
     * @param {OpenLayers.Editor.Control} control
     */
    addEditorControl: function(control){
        this.controls[control.CLASS_NAME] = control;
        this.editorPanel.addControls([control]);
        this.map.addControl(control);
    },

    /**
     * Instantiates the container which displays the tools.
     * To be called by OLE only and intended to be overridden by subclasses that want to display something else instead of the default toolbar
     * @param {Array.<OpenLayers.Control>} controls Editing controls
     * @return {OpenLayers.Editor.Control.EditorPanel} Widget to display editing tools
     */
    createEditorPanel: function(controls){
        var editorPanel = new OpenLayers.Editor.Control.EditorPanel(this);
        editorPanel.addControls(controls);
        return editorPanel;
    },

    status: function(options) {
        if (options.type == 'error') {
            alert(options.content);
        }
    },

    /**
     * Destroys existing features and loads the provided one into editor
     * @param {Array.<OpenLayers.Feature.Vector>} features
     */
    loadFeatures: function (features) {
        this.editLayer.destroyFeatures();
        if (features) {
            this.editLayer.addFeatures(features);
            this.map.zoomToExtent(this.editLayer.getDataExtent());
        }
    },

    /**
     * Callback to update selected feature with result of server side processing
     */
    requestComplete: function (response) {
        var responseJSON = new OpenLayers.Format.JSON().read(response.responseText);
        this.map.editor.stopWaiting();
        if (!responseJSON) {
            this.showStatus('error', OpenLayers.i18n('oleNoJSON'))
        } else if (responseJSON.error) {
            this.showStatus('error', responseJSON.message)
        } else {
            if (responseJSON.params) {
                OpenLayers.Util.extend(this.params, responseJSON.params);
            }
            if (responseJSON.geo) {
                var geo = this.geoJSON.read(responseJSON.geo);
                this.editLayer.removeFeatures(this.editLayer.selectedFeatures);
                this.editLayer.addFeatures(this.toFeatures(geo));
                this.editLayer.events.triggerEvent('featureselected');
            }
        }
    },

    /**
     * Flattens multipolygons and returns a list of their features
     * @param {Object|Array} multiPolygon Geometry or list of geometries to flatten. Geometries can be of types
     *     OpenLayers.Geometry.MultiPolygon, OpenLayers.Geometry.Collection,
     *     OpenLayers.Geometry.Polygon.
     * @return {Array} List for features of type OpenLayers.Feature.Vector.
     */
    toFeatures: function (multiPolygon) {
        if(multiPolygon===null || typeof(multiPolygon)!=='object'){
            throw new Error('Parameter does not match expected type.');
        }
        var features = [];
        if (!(multiPolygon instanceof Array)) {
            multiPolygon = [multiPolygon];
        }
        for (var i = 0, li = multiPolygon.length; i < li; i++) {
            if (multiPolygon[i].geometry.CLASS_NAME === 'OpenLayers.Geometry.MultiPolygon' ||
                multiPolygon[i].geometry.CLASS_NAME === 'OpenLayers.Geometry.Collection') {
                for (var j = 0, lj = multiPolygon[i].geometry.components.length; j < lj; j++) {
                    features.push(new OpenLayers.Feature.Vector(
                        multiPolygon[i].geometry.components[j]
                    ));
                }
            } else if (multiPolygon[i].geometry.CLASS_NAME === 'OpenLayers.Geometry.Polygon') {
                features.push(new OpenLayers.Feature.Vector(multiPolygon[i].geometry));
            }
        }
        return features;
    },

    toMultiPolygon: function (features) {
        var components = [];
        for (var i = 0, l = features.length; i < l; i++) {
            if (features[i].geometry.CLASS_NAME === 'OpenLayers.Geometry.Polygon') {
                components.push(features[i].geometry);
            }
        }
        return new OpenLayers.Geometry.MultiPolygon(components);
    },

    startWaiting: function (panel_div) {
        OpenLayers.Element.addClass(panel_div, 'olEditorWaiting');
        OpenLayers.Element.addClass(this.map.div, 'olEditorWaiting');
        this.waitingDiv = panel_div;
    },

    stopWaiting: function() {
        OpenLayers.Element.removeClass(this.waitingDiv, 'olEditorWaiting');
        OpenLayers.Element.removeClass(this.map.div, 'olEditorWaiting');
    },

    CLASS_NAME: 'OpenLayers.Editor'
});

/**
 * @constructor
 */
OpenLayers.Editor.Control = OpenLayers.Class(OpenLayers.Control, {

    initialize: function (options) {
        OpenLayers.Control.prototype.initialize.apply(this, [options]);
    },

    CLASS_NAME: 'OpenLayers.Editor.Control'
});

/**
 * Version number of OpenLayers Editor.
 * @const
 * @type {string}
 */
OpenLayers.Editor.VERSION_NUMBER="1.0-beta1";

/**
 * @copyright  2011 geOps
 * @license    https://github.com/geops/ole/blob/master/license.txt
 * @link       https://github.com/geops/ole
 */

/**
 * Class: OpenLayers.Editor.Control.DragFeature
 * 
 * Inherits from:
 *  - <OpenLayers.Control.DragFeature>
 */
OpenLayers.Editor.Control.DragFeature = OpenLayers.Class(OpenLayers.Control.DragFeature, {
    title: OpenLayers.i18n('oleDragFeature'),
    EVENT_TYPES: ["activate", "deactivate", 'dragstart', 'dragdrag', 'dragcomplete', 'dragenter', 'dragleave'],
    
    initialize: function(layer, options) {
        OpenLayers.Control.DragFeature.prototype.initialize.apply(this, [layer, options]);
        // allow changing the layer title by using translations
        this.title = OpenLayers.i18n('oleDragFeature');
    },
    
    // Add events corresponding to callbacks of OpenLayers.Control.DragFeature
    onStart: function(feature, pixel){
        this.events.triggerEvent('dragstart', {
            feature: feature,
            pixel: pixel
        });
    },
    onDrag: function(feature, pixel){
        this.events.triggerEvent('dragdrag', {
            feature: feature,
            pixel: pixel
        });
    },
    onComplete: function(feature, pixel) {
        this.events.triggerEvent('dragcomplete', {
            feature: feature,
            pixel: pixel
        });
        // General event is there so that undo-redo control works for all controls
        this.layer.events.triggerEvent('afterfeaturemodified', {
            feature: feature
        });
    },
    onEnter: function(feature){
        this.events.triggerEvent('dragenter', {
            feature: feature
        });
    },
    onLeave: function(feature){
        this.events.triggerEvent('dragleave', {
            feature: feature
        });
    },
    
    CLASS_NAME: "OpenLayers.Editor.Control.DragFeature"
});
/**
 * @copyright  2011 geOps
 * @license    https://github.com/geops/ole/blob/master/license.txt
 * @link       https://github.com/geops/ole
 */

/**
 * Class: OpenLayers.Editor.Control.DeleteFeature
 * The DeleteFeature provides a button to delete all selected features
 *     from a given layer.
 *
 * Inherits from:
 *  - <OpenLayers.Control.Button>
 */
OpenLayers.Editor.Control.DeleteFeature = OpenLayers.Class(OpenLayers.Control.Button, {

    /**
     * Property: layer
     * {<OpenLayers.Layer.Vector>}
     */
    layer: null,

    title: OpenLayers.i18n('oleDeleteFeature'),

    /**
     * Constructor: OpenLayers.Editor.Control.DeleteFeature
     * Create a new control for deleting features.
     *
     * Parameters:
     * layer - {<OpenLayers.Layer.Vector>} The layer from which selected
     *     features will be deleted.
     * options - {Object} An optional object whose properties will be used
     *     to extend the control.
     */
    initialize: function (layer, options) {

        this.layer = layer;

        this.title = OpenLayers.i18n('oleDeleteFeature');

        OpenLayers.Control.Button.prototype.initialize.apply(this, [options]);

        this.trigger = this.deleteFeature;

        this.displayClass = "oleControlDisabled " + this.displayClass;

    },

    /**
     * Method: deleteFeature
     */
    deleteFeature: function () {
        if (this.layer.selectedFeatures.length > 0) {
            this.layer.destroyFeatures(this.layer.selectedFeatures);
            this.layer.events.triggerEvent('featureunselected');
        }
    },

    CLASS_NAME: 'OpenLayers.Editor.Control.DeleteFeature'
});
/**
 * @copyright  2011 geOps
 * @license    https://github.com/geops/ole/blob/master/license.txt
 * @link       https://github.com/geops/ole
 */

/**
 * Class: OpenLayers.Editor.Control.Dialog
 * ...
 *
 * Inherits from:
 *  - <OpenLayers.Control>
 */
OpenLayers.Editor.Control.Dialog =  OpenLayers.Class(OpenLayers.Control, {

    dialogDiv: null,

    buttonClass: null,

    inputTextClass: null,

    modal: true,

    initialize: function (options) {

        OpenLayers.Control.prototype.initialize.apply(this, [options]);

    },

    show: function (options) {

        var element, cancelButton, saveButton, okButton;

        if (OpenLayers.Util.indexOf(this.map.viewPortDiv.childNodes, this.dialogDiv) > -1) {
            this.map.viewPortDiv.removeChild(this.dialogDiv);
        }

        if (!options) {
            options = {};
        }

        this.dialogDiv = document.createElement('div');
        OpenLayers.Element.addClass(this.dialogDiv, 'oleDialog');

        if (options.toolbox) {
            OpenLayers.Element.addClass(this.dialogDiv, 'oleDialogToolbar');
        } else {
            OpenLayers.Element.addClass(this.div, 'oleFadeMap');
        }

        if (options.title) {
            element = document.createElement('h3');
            element.innerHTML = options.title;
            this.dialogDiv.appendChild(element);
        }

        if (typeof options.content === 'string') {
            element = document.createElement('div');
            element.innerHTML = options.content;
            this.dialogDiv.appendChild(element);
        } else if (OpenLayers.Util.isElement(options.content)) {
            this.dialogDiv.appendChild(options.content);
        }

        if (options.save) {
            cancelButton = this.getButton(OpenLayers.i18n('oleDialogCancelButton'));
            this.dialogDiv.appendChild(cancelButton);
            saveButton = this.getButton(OpenLayers.i18n('oleDialogSaveButton'));
            this.dialogDiv.appendChild(saveButton);
            OpenLayers.Event.observe(cancelButton, 'click', this.hide.bind(this));
            OpenLayers.Event.observe(saveButton, 'click', this.hide.bind(this));
            OpenLayers.Event.observe(saveButton, 'click', options.save.bind(this));
            if (options.cancel) {
                OpenLayers.Event.observe(cancelButton, 'click', options.cancel.bind(this));
            }
        } else if (!options.toolbox) {
            okButton = this.getButton(OpenLayers.i18n('oleDialogOkButton'));
            this.dialogDiv.appendChild(okButton);

            if (options.close) {
                OpenLayers.Event.observe(okButton, 'click', options.close);
            }

            OpenLayers.Event.observe(okButton, 'click', OpenLayers.Function.bind(this.hide, this));
        }

        // Add class to text input elements.
        var inputElements = this.dialogDiv.getElementsByTagName('input');
        for (var i = 0; i < inputElements.length; i++) {
            if (inputElements[i].getAttribute('type') == 'text') {
                OpenLayers.Element.addClass(inputElements[i], this.inputTextClass);
            }
        }

        this.map.viewPortDiv.appendChild(this.dialogDiv);

        OpenLayers.Event.observe(this.div, 'click', this.ignoreEvent);
        OpenLayers.Event.observe(this.div, 'mousedown', this.ignoreEvent);
        OpenLayers.Event.observe(this.div, 'dblclick', this.ignoreEvent);
        OpenLayers.Event.observe(this.dialogDiv, 'mousedown', this.ignoreEvent);
        OpenLayers.Event.observe(this.dialogDiv, 'dblclick', this.ignoreEvent);
    },

    hide: function () {
        this.map.viewPortDiv.removeChild(this.dialogDiv);
        OpenLayers.Element.removeClass(this.div, 'oleFadeMap');
    },

    ignoreEvent: function (event) {
        OpenLayers.Event.stop(event, true);
    },

    /**
     * Instantiates a button
     * @param {string} value Value and text on button
     * @return {!HTMLButtonElement}
     */
    getButton: function(value) {
        var button = document.createElement('input');
        button.value = value;
        button.type = 'button';
        OpenLayers.Element.addClass(button, this.buttonClass);
        /** @type {!HTMLButtonElement} */
        return button;
    },

    CLASS_NAME: 'OpenLayers.Editor.Control.Dialog'
});
/**
 * @copyright  2011 geOps
 * @license    https://github.com/geops/ole/blob/master/license.txt
 * @link       https://github.com/geops/ole
 */

/**
 * Class: OpenLayers.Editor.Control.DrawHole
 * The DrawHole control provides a method to cut holes in features
 *     from a given layer. All vertices from the hole feature must
 *     lay within the targted feature and only the top most feature
 *     will be processed.
 *
 * Inherits from:
 *  - <OpenLayers.Control.DrawFeature>
 */
OpenLayers.Editor.Control.DrawHole = OpenLayers.Class(OpenLayers.Control.DrawFeature, {

    /**
     * Property: minArea
     * {Number} Minimum hole area.
     */
    minArea: 0,

    title: OpenLayers.i18n('oleDrawHole'),
    
    /**
     * Constructor: OpenLayers.Editor.Control.DrawHole
     * Create a new control for deleting features.
     *
     * Parameters:
     * layer - {<OpenLayers.Layer.Vector>}
     * options - {Object} An optional object whose properties will be used
     *     to extend the control.
     */
    initialize: function (layer, options) {
        this.callbacks = OpenLayers.Util.extend(this.callbacks, {
            point: function(point) {
                this.layer.events.triggerEvent('pointadded', {point: point});
            }
        });
        
        OpenLayers.Control.DrawFeature.prototype.initialize.apply(this,
            [layer, OpenLayers.Handler.Polygon, options]);

        this.title = OpenLayers.i18n('oleDrawHole');

    },

    /**
     * Method: drawFeature
     * Cut hole only if area greater than or equal to minArea and all
     *     vertices intersect the targeted feature.
     * @param {OpenLayers.Geometry} geometry The hole to be drawn
     */
    drawFeature: function (geometry) {

        var feature = new OpenLayers.Feature.Vector(geometry);
        feature.state = OpenLayers.State.INSERT;
        // Trigger sketchcomplete and allow listeners to prevent modifications
        var proceed = this.layer.events.triggerEvent('sketchcomplete', {feature: feature});
        
        if (proceed !== false && geometry.getArea() >= this.minArea) {
            var vertices = geometry.getVertices(), intersects;
            
            features: for (var i = 0, li = this.layer.features.length; i < li; i++) {
                var layerFeature = this.layer.features[i];
                
                intersects = true;
                for (var j = 0, lj = vertices.length; j < lj; j++) {
                    if (!layerFeature.geometry.intersects(vertices[j])) {
                        intersects = false;
                    }
                }
                if (intersects) {
                    layerFeature.state = OpenLayers.State.UPDATE;
                    // Notify listeners that a feature is about to be modified
                    this.layer.events.triggerEvent("beforefeaturemodified", {
                        feature: layerFeature
                    });
                    layerFeature.geometry.components.push(geometry.components[0]);
                    this.layer.drawFeature(layerFeature);
                    // More event triggering but documentation is not clear how the following 2 are distinguished
                    // Notify listeners that a feature is modified
                    this.layer.events.triggerEvent("featuremodified", {
                        feature: layerFeature
                    });
                    // Notify listeners that a feature was modified
                    this.layer.events.triggerEvent("afterfeaturemodified", {
                        feature: layerFeature
                    });
                    break features;
                }
            }
        }
    },

    CLASS_NAME: 'OpenLayers.Editor.Control.DrawHole'
});
/**
 * @copyright  2011 geOps
 * @license    https://github.com/geops/ole/blob/master/license.txt
 * @link       https://github.com/geops/ole
 */

/**
 * Class: OpenLayers.Editor.Control.DrawPolygon
 * The DeleteFeature provides a button to delete all selected features
 *     from a given layer.
 *
 * Inherits from:
 *  - <OpenLayers.Control.DrawFeature>
 */
OpenLayers.Editor.Control.DrawPolygon = OpenLayers.Class(OpenLayers.Control.DrawFeature, {

    /**
     * Property: minArea
     * {Number} Minimum area of new polygons.
     */
    minArea: 0,

    title: OpenLayers.i18n('oleDrawPolygon'),

    /**
     * Constructor: OpenLayers.Editor.Control.DrawPolygon
     * Create a new control for drawing polygons.
     *
     * Parameters:
     * layer - {<OpenLayers.Layer.Vector>} Polygons will be added to this layer.
     * options - {Object} An optional object whose properties will be used
     *     to extend the control.
     */
    initialize: function (layer, options) {
        this.callbacks = OpenLayers.Util.extend(this.callbacks, {
            point: function(point) {
                this.layer.events.triggerEvent('pointadded', {point: point});
            }
        });
        
        OpenLayers.Control.DrawFeature.prototype.initialize.apply(this,
            [layer, OpenLayers.Handler.Polygon, options]);

        this.title = OpenLayers.i18n('oleDrawPolygon');
    },

    /**
     * Method: draw polygon only if area greater than or equal to minArea
     */
    drawFeature: function (geometry) {
        var feature = new OpenLayers.Feature.Vector(geometry),
            proceed = this.layer.events.triggerEvent('sketchcomplete', {feature: feature});
        if (proceed !== false && geometry.getArea() >= this.minArea) {
            feature.state = OpenLayers.State.INSERT;
            this.layer.addFeatures([feature]);
            this.featureAdded(feature);
            this.events.triggerEvent('featureadded', {feature : feature});
        }
    },

    CLASS_NAME: 'OpenLayers.Editor.Control.DrawPolygon'
});
/**
 * @copyright  2011 geOps
 * @license    https://github.com/geops/ole/blob/master/license.txt
 * @link       https://github.com/geops/ole
 */

/**
 * Class: OpenLayers.Editor.Control.DrawPath
 *
 * Inherits from:
 *  - <OpenLayers.Control.DrawFeature>
 */
OpenLayers.Editor.Control.DrawPath = OpenLayers.Class(OpenLayers.Control.DrawFeature, {

    /**
     * Property: minLength
     * {Number} Minimum length of new paths.
     */
    minLength: 0,

    title: OpenLayers.i18n('oleDrawPath'),

    /**
     * Constructor: OpenLayers.Editor.Control.DrawPath
     * Create a new control for drawing paths.
     *
     * Parameters:
     * layer - {<OpenLayers.Layer.Vector>} Paths will be added to this layer.
     * options - {Object} An optional object whose properties will be used
     *     to extend the control.
     */
    initialize: function (layer, options) {
        this.callbacks = OpenLayers.Util.extend(this.callbacks, {
            point: function(point) {
                this.layer.events.triggerEvent('pointadded', {point: point});
            }
        });
        
        OpenLayers.Control.DrawFeature.prototype.initialize.apply(this,
            [layer, OpenLayers.Handler.Path, options]);
        
        this.title = OpenLayers.i18n('oleDrawPath');
    },

    /**
     * Method: draw path only if area greater than or equal to minLength
     */
    drawFeature: function (geometry) {
        var feature = new OpenLayers.Feature.Vector(geometry),
            proceed = this.layer.events.triggerEvent('sketchcomplete', {feature: feature});
        if (proceed !== false && geometry.getLength() >= this.minLength) {
            feature.state = OpenLayers.State.INSERT;
            this.layer.addFeatures([feature]);
            this.featureAdded(feature);
            this.events.triggerEvent('featureadded', {feature : feature});
        }
    },

    CLASS_NAME: 'OpenLayers.Editor.Control.DrawPath'
});
/**
 * @copyright  2011 geOps
 * @license    https://github.com/geops/ole/blob/master/license.txt
 * @link       https://github.com/geops/ole
 */

/**
 * Class: OpenLayers.Editor.Control.DrawPoint
 *
 * Inherits from:
 *  - <OpenLayers.Control.DrawFeature>
 */
OpenLayers.Editor.Control.DrawPoint = OpenLayers.Class(OpenLayers.Control.DrawFeature, {

    title: OpenLayers.i18n('oleDrawPoint'),

    /**
     * Constructor: OpenLayers.Editor.Control.DrawPath
     * Create a new control for drawing points.
     *
     * Parameters:
     * layer - {<OpenLayers.Layer.Vector>} Points will be added to this layer.
     * options - {Object} An optional object whose properties will be used
     *     to extend the control.
     */
    initialize: function (layer, options) {
        this.callbacks = OpenLayers.Util.extend(this.callbacks, {
            point: function(point) {
                this.layer.events.triggerEvent('pointadded', {point: point});
            }
        });
        
        OpenLayers.Control.DrawFeature.prototype.initialize.apply(this,
            [layer, OpenLayers.Handler.Point, options]);
        
        this.title = OpenLayers.i18n('oleDrawPoint');
    },

    /**
     * Method: draw point
     */
    drawFeature: function (geometry) {
        var feature = new OpenLayers.Feature.Vector(geometry),
            proceed = this.layer.events.triggerEvent('sketchcomplete', {feature: feature});
        if (proceed !== false) {
            feature.state = OpenLayers.State.INSERT;
            this.layer.addFeatures([feature]);
            this.featureAdded(feature);
            this.events.triggerEvent('featureadded', {feature : feature});
        }
    },

    CLASS_NAME: 'OpenLayers.Editor.Control.DrawPoint'
});

/**
 * @copyright  2011 geOps
 * @license    https://github.com/geops/ole/blob/master/license.txt
 * @link       https://github.com/geops/ole
 */

/**
 * Class: OpenLayers.Editor.Control.EditorPanel
 * The EditorPanel is a panel of all controls from a given editor. 
 *     By default it appears as toolbar in the upper right corner of the map.
 *
 * Inherits from:
 *  - <OpenLayers.Control.Panel>
 * 
 * @constructor
 * @param {OpenLayers.Editor} editor
 * @param {Object=} options
 */
OpenLayers.Editor.Control.EditorPanel = OpenLayers.Class(OpenLayers.Control.Panel, {
    /*
     * {boolean} Whether to show by default. Leave value FALSE and show by starting editor's edit mode.
     */
    autoActivate: false,
    
    /**
     * Constructor: OpenLayers.Editor.Control.EditorPanel
     * Create an editing toolbar for a given editor.
     *
     * Parameters:
     * editor - {<OpenLayers.Editor>}
     * options - {Object}
     */
    initialize: function (editor, options) {
        OpenLayers.Control.Panel.prototype.initialize.apply(this, [options]);
    },
    
    draw: function() {
        OpenLayers.Control.Panel.prototype.draw.apply(this, arguments);
        if (!this.active) {
            this.div.style.display = 'none';
        }
        return this.div;
    },
    
    redraw: function(){
        if (!this.active) {
            this.div.style.display = 'none';
        }
        
        OpenLayers.Control.Panel.prototype.redraw.apply(this, arguments);
        
        if (this.active) {
            this.div.style.display = '';
        }
    },

    CLASS_NAME: 'OpenLayers.Editor.Control.EditorPanel'
});

/**
 * @copyright  2011 geOps
 * @license    https://github.com/geops/ole/blob/master/license.txt
 * @link       https://github.com/geops/ole
 */

/**
 * Class: OpenLayers.Editor.Control.LayerSettings
 * ...
 *
 * Inherits from:
 *  - <OpenLayers.Control>
 */
OpenLayers.Editor.Control.LayerSettings =  OpenLayers.Class(OpenLayers.Control, {

    currentLayer: null,

    layerSwitcher: null,

    initialize: function(editor, options) {

        OpenLayers.Control.prototype.initialize.apply(this, [options]);

        this.layerSwitcher = editor.map.getControlsByClass('OpenLayers.Control.LayerSwitcher')[0];

        if(this.layerSwitcher instanceof OpenLayers.Control.LayerSwitcher) {
            OpenLayers.Event.observe(this.layerSwitcher.maximizeDiv, 'click',
                OpenLayers.Function.bind(this.redraw, this));
        }

    },

    redraw: function() {

        var layerInput, layerLabel;
        
        this.layerSwitcher.dataLayersDiv.innerHTML = "";

        for (var i = 0, l = this.layerSwitcher.dataLayers.length; i < l; i++) {

            var dataLayer = this.layerSwitcher.dataLayers[i];

            layerInput = document.createElement('input');
            layerInput.type = 'checkbox';
            layerInput.id = 'list'+dataLayer.layer.name;
            layerInput.name = dataLayer.layer.name;
            if (dataLayer.layer.visibility) {
                layerInput.checked = 'checked';
                layerInput.defaultChecked = 'selected'; // IE7 hack
            }
            this.layerSwitcher.dataLayersDiv.appendChild(layerInput);
            layerLabel = document.createElement('span');
            layerLabel.innerHTML = dataLayer.layer.name;
            OpenLayers.Element.addClass(layerLabel, 'labelSpan');
            this.layerSwitcher.dataLayersDiv.appendChild(layerLabel);
            this.layerSwitcher.dataLayersDiv.appendChild(document.createElement('br'));

            OpenLayers.Event.observe(layerInput, 'click',
                OpenLayers.Function.bind(this.toggleLayerVisibility, this, dataLayer.layer.name));
            OpenLayers.Event.observe(layerLabel, 'click',
                OpenLayers.Function.bind(this.showLayerSettings, this, dataLayer.layer.name));
        }
    },

    showLayerSettings: function(layerName) {

        var content, opacityHeader, opacityTrack, opacityHandle, opacityInput,
            legendHeader, legendGraphic,
            importHeader, importInput, importLabel;

        this.currentLayer = this.map.getLayersByName(layerName)[0];

        var content = document.createElement('div');

        var opacityHeader = document.createElement('h4');
        opacityHeader.innerHTML = OpenLayers.i18n('oleLayerSettingsOpacityHeader');
        content.appendChild(opacityHeader);

        var opacity = (this.currentLayer.opacity) ? this.currentLayer.opacity : 1;

        opacityInput = document.createElement('input');
        opacityInput.type = 'text';
        opacityInput.size = '2';
        opacityInput.value = (opacity*100).toFixed(0);
        OpenLayers.Event.observe(opacityInput, 'change',
            OpenLayers.Function.bind(this.changeLayerOpacity, this, opacityInput));
        content.appendChild(opacityInput);

        // display import checkbox for vector layer
        if (this.currentLayer instanceof OpenLayers.Layer.Vector) {

            importHeader = document.createElement('h4');
            importHeader.innerHTML = OpenLayers.i18n('oleLayerSettingsImportHeader');
            importHeader.style.marginTop = '10px';
            content.appendChild(importHeader);

            importInput = document.createElement('input');
            importInput.type = 'checkbox';
            importInput.name = 'import'+this.currentLayer.name;
            content.appendChild(importInput);

            importLabel = document.createElement('label');
            importLabel.htmlFor = 'import'+this.currentLayer.name;
            importLabel.innerHTML = OpenLayers.i18n('oleLayerSettingsImportLabel');
            content.appendChild(importLabel);
            content.appendChild(document.createElement('p'));

            for(var i = 0, li = this.map.editor.sourceLayers.length; i < li; i++) {
                if (this.currentLayer.id == this.map.editor.sourceLayers[i].id) {
                    importInput.writeAttribute('checked','checked');
                    importInput.defaultChecked = 'selected'; // IE7 hack
                    break;
                }
            }
            OpenLayers.Event.observe(importInput, 'click',
                OpenLayers.Function.bind(this.toggleExportFeature, this));
        }

        var legendGraphics = this.getLegendGraphics(this.currentLayer);

        if (legendGraphics.length > 0) {

            legendHeader = document.createElement('h4');
            legendHeader.innerHTML = OpenLayers.i18n('oleLayerSettingsLegendHeader');
            legendHeader.style.marginTop = '10px';
            content.appendChild(legendHeader);

            for(var i = 0; i < legendGraphics.length; i++) {
                legendGraphic = document.createElement('img');
                legendGraphic.src = legendGraphics[i];
                content.appendChild(legendGraphic);
            }
        }

        this.map.editor.dialog.show({
            content: content,
            title: layerName
        });
    },

    toggleExportFeature: function() {
        var add = true;
        for(var i = 0, li = this.map.editor.sourceLayers.length; i < li; i++) {
            if (this.currentLayer.id == this.map.editor.sourceLayers[i].id) {
                this.map.editor.sourceLayers.splice(i, 1);
                add = false;
                break;
            }
        }
        if (add) {
            this.map.editor.sourceLayers.push(this.currentLayer);
        }
    },

    toggleLayerVisibility: function(layerName) {
        var layer = this.map.getLayersByName(layerName)[0];
        if(layer.visibility) {
            layer.setVisibility(false);
        } else {
            layer.setVisibility(true);
        }
        this.redraw();
    },

    changeLayerOpacity: function (opacityInput) {
        this.currentLayer.setOpacity(opacityInput.value/100);
    },

    getLegendGraphics: function(layer) {

        var legendGraphics = [];

        if(layer.legendGraphics) {

            legendGraphics = layer.legendGraphics;

        } else if (layer instanceof OpenLayers.Layer.WMS) {

            var urlLayers = layer.params.LAYERS.split(',');

            for(var j = 0; j < urlLayers.length; j++) {
                var singlelayer = urlLayers[j];
                var url = layer.url;
                url += ( url.indexOf('?') === -1 ) ? '?' : '';
                url += '&SERVICE=WMS';
                url += '&VERSION=1.1.1';
                url += '&REQUEST=GetLegendGraphic';
                url += '&FORMAT=image/png';
                url += '&LAYER=' + singlelayer;
                legendGraphics.push(url);
            }
        }
        return legendGraphics;
    }, 

    CLASS_NAME: 'OpenLayers.Editor.Control.LayerSettings'
});

/**
 * @copyright  2011 geOps
 * @license    https://github.com/geops/ole/blob/master/license.txt
 * @link       https://github.com/geops/ole
 */

/**
 *
 * Class: OpenLayers.Editor.Control.UndoRedo
 *
 * Inherits From:
 *  - <OpenLayers.Control>
 */
OpenLayers.Editor.Control.UndoRedo = OpenLayers.Class(OpenLayers.Control, {

    /**
     * Property: layer
     * {<OpenLayers.Layer.Vector>}
     */
    layer: null,

     /**
     * Property: handler
     * {<OpenLayers.Handler.Keyboard>}
     */
    handler: null,

     /**
     * APIProperty: autoActivate
     * {Boolean} Activate the control when it is added to a map.  Default is
     *     true.
     */
    autoActivate: true,

    /**
     * Constant: KEY_Z
     * {int}
     */
    KEY_Z: 90,

    /**
     * Constant: KEY_Y
     * {int}
     */
    KEY_Y: 89,

	/**
     * APIMethod: onUndo
     *
     * Called after a successful undo, passing in the feature that was altered.
     */
	onUndo: function(){},

	/**
     * APIMethod: onRedo
     *
     * Called after a successful redo, passing in the feature that was altered.
     */
	onRedo: function(){},

	/**
     * APIMethod: onRemoveFeature
     *
     * Called when the Undo/Redo control is about to remove a feature from the layer. This call happens before the feature is removed.
     */
	onRemoveFeature: function(){},

	/**
     * Property: undoStack
     * {<Array>}
     *
     * A stack containing states of a feature that can be undone. Objects on this stack are hashes, of the form {feature: ..., :geometry ...}.
     */
    undoStack: null,

 	/**
     * Property: redoStack
     * {<Array>}
     *
     * A stack containing states of a feature that can be redone. Objects on this stack are hashes, of the form {feature: ..., :geometry ...}.
     */
    redoStack: null,

    /**
     * Property: currentState
     */
    currentState: null,

    /**
     * Constructor: OpenLayers.Control.UndoRedo
     * Create a new Undo/Redo control.
     *
     * Parameters:
     * layer - {<OpenLayers.Layer.Vector>} The layer from which selected
     *     features will be deleted.
     * options - {Object} An optional object whose properties will be used
     *     to extend the control.
     */
    initialize: function(layer, options) {

        this.layer = layer;

        OpenLayers.Control.prototype.initialize.apply(this, [options]);

        this.layer.events.register('featureadded', this, this.register);
        this.layer.events.register('afterfeaturemodified', this, this.register);

        this.undoStack = new Array();
        this.redoStack = new Array();
    },

    /**
     * Method: draw
     * Activates the control.
     */
    draw: function() {
        this.handler = new OpenLayers.Handler.Keyboard( this, {
                "keydown": this.handleKeydown} );
    },

    /**
     * Method: handleKeydown
     * Called by the feature handler on keydown.
     *
     * Parameters:
     * {Integer} Key code corresponding to the keypress event.
     */
    handleKeydown: function(e) {
        if (e.keyCode === this.KEY_Z && e.ctrlKey === true && e.shiftKey === false) {
            this.undo();
        }
        else if (e.ctrlKey === true && ((e.keyCode === this.KEY_Y) || (e.keyCode === this.KEY_Z && e.shiftKey === true))) {
            this.redo();
        }
    },

    /**
     * APIMethod: undo
     * Causes an the Undo/Redo control to process an undo.
     */
    undo: function() {
        var feature = this.moveBetweenStacks(this.undoStack, this.redoStack, true);
        if (feature) this.onUndo(feature);
    },

    /**
     * APIMethod: redo
     * Causes an the Undo/Redo control to process an undo.
     */
    redo: function() {
        var feature = this.moveBetweenStacks(this.redoStack, this.undoStack, false);
        if (feature) this.onRedo(feature);
    },

    /**
     * Method: moveBetweenStacks
     * The "meat" of the Undo/Redo control -- it actually does the undoing/redoing. Although some idiosyncrasies exist, this function
     * handles moving states from the undo stack to the redo stack, and vice versa. It also handles adding and removing features from the map.
     *
     * Parameters: TODO
     */
    moveBetweenStacks: function(fromStack, toStack, undo) {

        if (fromStack.length > 0) {

            this.map.editor.editLayer.removeAllFeatures();
            var state = fromStack.pop();
            toStack.push(this.currentState);

            if (state) {
                var currentFeatures = new Array(len);
                var len = state.length;
                for(var i=0; i<len; ++i) {
                    currentFeatures[i] = state[i].clone();
                }
                this.currentState = currentFeatures;
                this.map.editor.editLayer.addFeatures(state, {silent: true});
            } else {
                this.currentState = null;
            }
        }
        else if (this.currentState && undo) {
            toStack.push(this.currentState);
            this.map.editor.editLayer.removeAllFeatures();
            this.currentState = null;
        }
    },

    /**
     * 
     */
    register: function() {

        var features = this.map.editor.editLayer.features;
        var len = features.length;
        var clonedFeatures = new Array(len);
        for(var i=0; i<len; ++i) {
            clonedFeatures[i] = features[i].clone();
        }

        if (this.currentState) {
            this.undoStack.push(this.currentState);
        }

        this.currentState = clonedFeatures;
        this.redoStack = new Array();

    },

    CLASS_NAME: "OpenLayers.Editor.Control.UndoRedo"
});
