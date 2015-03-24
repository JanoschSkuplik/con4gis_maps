// "namespace"
this.c4g = this.c4g || {};

(function ($, ol) {
  "use strict";


  /**
   * [Map description]
   * @param {json-object} mapData [object to configure con4gis-maps.
   *                               See "docs/mapData-values.md"
   *                               to get a list of valid values for this object]
   */
  c4g.MapContainer = function (mapData) {
    var self = this;
    // self = {};

    //---
    self.map = null;
    // self.baseLayers = null;
    // self.layers = null;
    // self.controls = null;
    self.leftSlideElements = [];
    self.rightSlideElements = [];
    //---

    mapData = $.extend({
      // restUrl : 'api',
      addIdToDiv: false,
      mapId: 1,
      mapDiv: 'c4g_Map',
      center: {
        lat: 37.41,
        lon: 8.82,
        zoom: 4
      },
      calc_extent: 'CENTERZOOM'
    }, mapData);
    
    self.data = mapData;

    if (mapData.addIdToDiv) {
      mapData.mapDiv += mapData.mapId;
    }


    //[ DEV ]!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    console.log(mapData);

    // define basemaps
    var osmSourceConfigs = {

      CycleMap: {
        attributions: [
          new ol.Attribution({
            html: 'Style by <a href="http://www.opencyclemap.org/">OpenCycleMap</a>'
          }),
          ol.source.OSM.DATA_ATTRIBUTION
        ],
        maxZoom: 19,
        url: 'http://{a-c}.tile.opencyclemap.org/cycle/{z}/{x}/{y}.png'
      },

      German: {
        attributions: [
          new ol.Attribution({
            html: 'Style by <a href="http://www.openstreetmap.de/germanstyle.html">openstreetmap.de</a>'
          }),
          ol.source.OSM.DATA_ATTRIBUTION
        ],
        crossOrigin: null,
        maxZoom: 19,
        url: 'http://{a-c}.tile.openstreetmap.de/tiles/osmde/{z}/{x}/{y}.png'
      },

      GermanTransport: {
        attributions: [
          new ol.Attribution({
            html: 'Style by <a href="http://www.memomaps.de">Memomaps</a>'
          }),
          ol.source.OSM.DATA_ATTRIBUTION
        ],
        crossOrigin: null,
        maxZoom: 19,
        url: 'http://tile.memomaps.de/tilegen/{z}/{x}/{y}.png'
      },

      LandscapeMap: {
        attributions: [
          new ol.Attribution({
            html: 'Style by <a href="http://www.opencyclemap.org/">OpenCycleMap</a>'
          }),
          ol.source.OSM.DATA_ATTRIBUTION
        ],
        maxZoom: 19,
        url: 'http://{a-c}.tile.opencyclemap.org/landscape/{z}/{x}/{y}.png'
      },

      Mapnik: {
          // is default, so there is nothing to write here ;)
      },

      TransportMap: {
        attributions: [
          new ol.Attribution({
            html: 'Style by <a href="http://www.opencyclemap.org/">OpenCycleMap</a>'
          }),
          ol.source.OSM.DATA_ATTRIBUTION
        ],
        maxZoom: 10,
        url: 'http://{a-c}.tile2.opencyclemap.org/transport/{z}/{x}/{y}.png'
      }
    };
    // ---
    var stamenSourceConfigs = {

      Toner: {
        layer: 'toner',
        maxZoom: 20
      },

      TonerLabels: {
        layer: 'toner-labels',
        maxZoom: 20
      },

      TonerLines: {
        layer: 'toner-lines',
        maxZoom: 20
      },

      Terrain: {
        layer: 'terrain',
        maxZoom: 18
      },

      Watercolor: {
        layer: 'watercolor',
        maxZoom: 16
      }
    };
    // ---
    var mapQuestSourceConfigs = {

      MapQuestOpen: {
        layer: 'osm'
      },

      MapQuestHyb: {
        layer: 'hyb'
      },

      MapQuestSat: {
        layer: 'sat'
      }
    };
    // ===


    // set default baseLayer
    var defaultBaseLayer = new ol.layer.Tile({
      source: new ol.source.OSM()
    });
    // override it with appropriate settings, if existant
    if (mapData.baseLayer) {

      var layerOptions = {};
      if (mapData.baseLayer.attribution) {
        layerOptions.attributions = [
          new ol.Attribution({
            html: mapData.baseLayer.attribution
          }),
          ol.source.OSM.DATA_ATTRIBUTION
        ];
      }
      if (mapData.baseLayer.sort) {
        layerOptions.sort = mapData.baseLayer.sort;
      }
      if (mapData.baseLayer.maxZoom) {
        layerOptions.maxZoom = mapData.baseLayer.maxZoom;
      }

      switch (mapData.baseLayer.provider) {
      case 'osm':
        if (osmSourceConfigs[mapData.baseLayer.style]) {
          defaultBaseLayer = new ol.layer.Tile({
            source: new ol.source.OSM(
              $.extend(
                osmSourceConfigs[mapData.baseLayer.style],
                layerOptions
              )
            )
          });
        } else if (stamenSourceConfigs[mapData.baseLayer.style]) {
          // Stamen
          defaultBaseLayer = new ol.layer.Tile({
            source: new ol.source.Stamen(
              $.extend(
                stamenSourceConfigs[mapData.baseLayer.style],
                layerOptions
              )
            )
          });
        } else if (mapQuestSourceConfigs[mapData.baseLayer.style]) {
          // mapQuest
          defaultBaseLayer = new ol.layer.Tile({
            source: new ol.source.MapQuest(mapQuestSourceConfigs[mapData.baseLayer.style])
          });
        } else if (mapData.baseLayer.style === 'osm_custom') {
          // custom
          var noUrl = true;
          if (mapData.baseLayer.url) {
            layerOptions.url = mapData.baseLayer.url;
            noUrl = false;
          } else if (mapData.baseLayer.urls) {
            layerOptions.urls = mapData.baseLayer.urls;
            noUrl = false;
          }
          if (!noUrl) {
            defaultBaseLayer = new ol.layer.Tile({
              source: new ol.source.XYZ(layerOptions)
            });
          } else {
            console.warn('custom url(s) missing -> switch to default');
          }
        } else {
          console.warn('unsupported osm-style -> switch to default');
        }
        break;
      case 'google':
        //@todo
        console.warn('google-maps are currently unsupported');
        break;
      case 'bing':
        if (mapData.baseLayer.apiKey && mapData.baseLayer.style) {
          defaultBaseLayer = new ol.layer.Tile({
            source: new ol.source.BingMaps({
              // culture: (@todo),
              key: mapData.baseLayer.apiKey,
              imagerySet: mapData.baseLayer.style
            })
          });
        }
        console.warn('wrong bing-key or invalid imagery-set!');
        break;
      default:
        console.warn('unsupported provider');
        break;
      }
    }

    var view = new ol.View({
      // projection: ol.proj.get('EPSG:4326'),
      // center: [parseFloat(mapData.center_lon), parseFloat(mapData.center_lat)],
      center: ol.proj.transform([parseFloat(mapData.center.lon), parseFloat(mapData.center.lat)], 'EPSG:4326', 'EPSG:3857'),
      zoom: parseInt(mapData.center.zoom, 10)
    });

    // enable default Controls/Interactions if there is no profile
    // [note]: maybe change self in the future? -> "no default"-option?
    var controls = [];
    var interactions = [];
    if (!mapData.profile) {
      controls = ol.control.defaults();
      interactions = ol.interaction.defaults();
    }

    // initiallize Map
    //
    self.map = new ol.Map({
      controls: controls,
      interactions: interactions,
      layers: [
        defaultBaseLayer
      ],
      target: mapData.mapDiv,
      view: view
    });

    // set map-size and -margin
    if (mapData.width) {
      document.getElementById(mapData.mapDiv).style.width = mapData.width;
    }
    if (mapData.height) {
      document.getElementById(mapData.mapDiv).style.height = mapData.height;
    }
    if (mapData.margin) {
      document.getElementById(mapData.mapDiv).style.margin = mapData.margin;
    }
    self.map.updateSize();
    // ---

    // add interactions ===
    //
    // mouse navigation
    if (mapData.mouse_nav) {
      // drag pan and kinetic scrolling
      if (mapData.mouse_nav.drag_pan) {
        var kinetic = mapData.mouse_nav.kinetic ? new ol.Kinetic(-0.005, 0.05, 100) : null;
        self.map.addInteraction(new ol.interaction.DragPan({ kinetic: kinetic }));
      }
      // mousewheel zoom
      if (mapData.mouse_nav.wheel_zoom) {
        self.map.addInteraction(new ol.interaction.MouseWheelZoom());
      }
      // drag zoom and rotate
    //TODO remove
    mapData.mouse_nav.drag_rotate = true;
      if (mapData.mouse_nav.drag_zoom) {
        if (mapData.mouse_nav.drag_rotate) {
          self.map.addInteraction(new ol.interaction.DragRotateAndZoom());
        } else {
          self.map.addInteraction(new ol.interaction.DragZoom());
        }
      } else if (mapData.mouse_nav.drag_rotate) {
        self.map.addInteraction(new ol.interaction.DragRotate());
      }
    }
    // keyboard navigation
    if (mapData.keyboard_nav) {
      // pan (arrow keys)
      if (mapData.keyboard_nav.pan) {
        self.map.addInteraction(new ol.interaction.KeyboardPan());
      }
      // zoom ("+" and "-" key)
      if (mapData.keyboard_nav.zoom) {
        self.map.addInteraction(new ol.interaction.KeyboardZoom());
      }
    }
    // ===

    // add control-containers ===
    //
    // top-left
    var controlContainerTopLeft = document.createElement('div');
    controlContainerTopLeft.className = 'c4g-control-container-top-left ol-unselectable';
    $('#' + mapData.mapDiv + ' .ol-overlaycontainer-stopevent').prepend(controlContainerTopLeft);

    // bottom-left
    var controlContainerBottomLeft = document.createElement('div');
    controlContainerBottomLeft.className = 'c4g-control-container-bottom-left ol-unselectable';
    $(controlContainerTopLeft).after(controlContainerBottomLeft);
    // element needs to be moved when Portside will be opened
    self.leftSlideElements.push(controlContainerBottomLeft);

    // bottom-right
    var controlContainerBottomRight = document.createElement('div');
    controlContainerBottomRight.className = 'c4g-control-container-bottom-right ol-unselectable';
    $(controlContainerBottomLeft).after(controlContainerBottomRight);
    // element needs to be moved when Starboard will be opened
    self.rightSlideElements.push(controlContainerBottomRight);
    // ===

    // add controls ===
    //
    // zoom-controls
    if (mapData.zoom_panel) {
      self.map.addControl(new ol.control.Zoom({ zoomInLabel: '', zoomOutLabel: '', target: controlContainerTopLeft }));
    }
    if (mapData.zoom_extent) {
      self.map.addControl(new ol.control.ZoomToExtent({ target: controlContainerTopLeft }));
    }
    // combined zoom-controls
    if (mapData.zoom_panel && mapData.zoom_extent) {
      $('#' + mapData.mapDiv + ' .ol-zoom').addClass('ol-zoom-with-extent').removeClass('ol-zoom');
      $('#' + mapData.mapDiv + ' .ol-zoom-in').after($('#' + mapData.mapDiv + ' .ol-zoom-extent button').addClass('ol-zoom-extent'));
      $('#' + mapData.mapDiv + ' .ol-zoom-extent.ol-control').remove();
    }
    // rotate-control
    // TODO -> use something like "mapData.rotate"
    if (mapData.mouse_nav.drag_rotate) {
      self.map.addControl(new ol.control.Rotate({ label: '', target: controlContainerTopLeft }));
    }

    // scaleline
    if (mapData.scaleline) {
      self.map.addControl(new ol.control.ScaleLine({ target: controlContainerBottomLeft }));
    }
    // zoom-level & mouse-position
    if (mapData.zoomlevel || mapData.mouseposition) {
      // wrapper for zoom-level and mouse-position
      var controlContainerBottomLeftSub = document.createElement('div');
      controlContainerBottomLeftSub.className = 'c4g-control-container-bottom-left-sub ol-unselectable';
      $(controlContainerBottomLeft).append(controlContainerBottomLeftSub);

      // display zoom-level
      if (mapData.zoomlevel) {
        self.map.addControl(new c4g.control.Zoomlevel({ target: controlContainerBottomLeftSub, undefinedHTML: 'N/A' }));
      }
      // display mouse-position
      if (mapData.mouseposition) {
        self.map.addControl(new ol.control.MousePosition({ projection: 'EPSG:4326', target: controlContainerBottomLeftSub, undefinedHTML: 'N/A' }));
      }
    }

    // show attribution
    if (mapData.attribution) {
      self.map.addControl(new ol.control.Attribution({ label: '', collapseLabel: '', target: controlContainerBottomRight }));
    }
    // show graticule (grid)
    if (mapData.graticule) {
      self.map.addControl(new c4g.control.Grid({ label: '', disableLabel: '', target: controlContainerBottomRight }));
    }
    // fullscreen
    if (mapData.fullscreen) {
      self.map.addControl(new ol.control.FullScreen({ target: controlContainerBottomRight }));
      // @TODO find alternative for unsupported Browsers
    }
    // ===

    //[ NOTES ] !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // DO THIS @ THE END! They probably need the Map-Object
    //
    // self.closeAll = function() {};
    //
    // self.isLoading = ...
    //
    // foreach x in extensions
    // x.init(self);
    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  };


  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  // TODO export following functions to own files:
  //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

  c4g.control = c4g.control || {};

  /**
   * [Zoomlevel description]
   * @constructor
   * @extends {ol.control.Control}
   * @param {Object=} opt_options Control options.
   */
  c4g.control.Zoomlevel = function (opt_options) {

    var self = this;
    var options = opt_options || {};

    // default options
    options = $.extend({
      className: 'c4g-zoom-level',
      undefinedHTML: ''
    }, options);

    var element = document.createElement('div');
    element.className = options.className;
    element.innerHTML = options.undefinedHTML;

    var updateZoomlevel = function () {
      element.innerHTML = self.getMap().getView().getZoom();
    };

    // TODO this needs to be "automatic" not "onClick"
    element.addEventListener('click', updateZoomlevel, false);
    // [old approaches & notes] -> delete after todo is done       === === === === ===
    // ---
    // anchor.addEventListener('touchstart', handleRotateNorth, false);
    // ---
    // ol.ObjectEvent();
    // ---
    // self.on(ol.ObjectEvent.propertychange('self'), function(){
    //   self.getMap().getView().on('change:resolution', updateZoomlevel());
    // });
    // self.getMap().getView().on('change:resolution', updateZoomlevel());
    // === === === === === === === === === === === === === === === === === === === ===

    ol.control.Control.call(this, {
      element: element,
      target: options.target
    });
  };
  ol.inherits(c4g.control.Zoomlevel, ol.control.Control);

  /**
   * [Grid description]
   * @constructor
   * @extends {ol.control.Control}
   * @param {Object=} opt_options Control options.
   */
  c4g.control.Grid = function (opt_options) {

    var self = this;
    var options = opt_options || {};

    var element,
      button,
      tooltip;

    var objGrid = new ol.Graticule();

    // default options
    options = $.extend({
      className: 'c4g-graticule',
      switchable: true,
      // enabled: false,
      tipLabel: 'Toggle grid',
      label: '#',
      disableLabel: '[]'
    }, options);

    // function to enable the grid
    var enable = function () {
      objGrid.setMap(self.getMap());
      $(element).addClass('c4g-enabled');
    };

    // function to disable the grid
    var disable = function () {
      objGrid.setMap(null);
      $(element).removeClass('c4g-enabled');
    };

    // function to toggle the grid
    var toggle = function (event) {
      event.stopPropagation();
      // loose focus, otherwise it looks messy
      this.blur();
      if (objGrid.getMap()) {
        disable();
      } else {
        enable();
      }
    };

    // wrapper div
    element = document.createElement('div');
    element.className = options.className + ' ol-unselectable ol-control';

    if (options.switchable) {
      // button
      button = document.createElement('button');
      button.className = 'ol-has-tooltip';
      element.appendChild(button);

      // tooltip
      tooltip = document.createElement('span');
      tooltip.setAttribute('role', 'tooltip');
      tooltip.innerHTML = options.tipLabel;
      button.appendChild(tooltip);

      // set onClick to the toggle-function
      button.addEventListener('click', toggle, false);
      button.addEventListener('touchstart', toggle, false);
    }

    // inheritance-stuff
    ol.control.Control.call(this, {
      element: element,
      target: options.target
    });
  };
  ol.inherits(c4g.control.Grid, ol.control.Control);


}(jQuery, ol)); // 'The End' :)    - ! Do not write stuff after this line ! -