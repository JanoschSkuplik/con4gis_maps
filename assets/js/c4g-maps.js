// "namespace"
var c4g = c4g || {};

(function($) {
  "use strict";

  /**
   * [Map description]
   * @param {json-object} mapData [object to configure con4gis-maps. 
   *                               See "docs/mapData-values.md" 
   *                               to get a list of valid values for this object]
   */
  c4g.Map = function(mapData) {
    var self = this;
    self = {};

    //---
    this.map = null;
    // this.baseLayers = null;
    // this.layers = null;
    this.controls = null;
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

    if (mapData.addIdToDiv) {
      mapData.mapDiv += mapData.mapId;
    }


    //[ DEV ]!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    console.log( mapData );

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
    }
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
    }
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
    }
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
          ]
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
                source: new ol.source.MapQuest( mapQuestSourceConfigs[mapData.baseLayer.style] )
              });
          } else if (mapData.baseLayer.style == 'osm_custom') {
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
                  source: new ol.source.XYZ( layerOptions )
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
                      }
                    )
                });
          }
          console.warn('wrong bing-key or invalid imagery-set!');
          break;
        default:
          //@todo
          console.warn('unsupported provider');
          break;
      }
    } 

    var view = new ol.View({
        // projection: ol.proj.get('EPSG:4326'),
        // center: [parseFloat(mapData.center_lon), parseFloat(mapData.center_lat)],
        center: ol.proj.transform([parseFloat(mapData.center.lon), parseFloat(mapData.center.lat)], 'EPSG:4326', 'EPSG:3857'),
        zoom: parseInt(mapData.center.zoom)
      })

    // enable default Controls/Interactions if there is no profile
    // [note]: maybe change this in the future? -> "no default"-option?
    var controls = [];
    var interactions = [];
    if (!mapData.profile) {
      controls = ol.control.defaults();
      interactions = ol.interaction.defaults();
    }

    // initiallize Map
    // 
    this.map = new ol.Map({
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
    this.map.updateSize();
    // ---

    // add interactions ===
    // 
    // mouse navigation
    if (mapData.mouse_nav) {
      // drag pan and kinetic scrolling
      if (mapData.mouse_nav.drag_pan) {
        var kinetic = mapData.mouse_nav.kinetic ? new ol.Kinetic(-0.005, 0.05, 100) : null;
        this.map.addInteraction( new ol.interaction.DragPan({ kinetic: kinetic }) );
      }
      // mousewheel zoom
      if (mapData.mouse_nav.wheel_zoom) {
        this.map.addInteraction( new ol.interaction.MouseWheelZoom() );
      }
      // drag zoom and rotate
      if (mapData.mouse_nav.drag_zoom) {
        if (mapData.mouse_nav.drag_rotate) {
          this.map.addInteraction( new ol.interaction.DragRotateAndZoom() );
        } else {
          this.map.addInteraction( new ol.interaction.DragZoom() );
        }
      } else if (mapData.mouse_nav.drag_rotate) {
        this.map.addInteraction( new ol.interaction.DragRotate() );
      }
    }
    // keyboard navigation
    if (mapData.keyboard_nav) {
      // pan (arrow keys)
      if (mapData.keyboard_nav.pan) {
        this.map.addInteraction( new ol.interaction.KeyboardPan() );
      }
      // zoom ("+" and "-" key)
      if (mapData.keyboard_nav.zoom) {
        this.map.addInteraction( new ol.interaction.KeyboardZoom() );
      }
    }
    // ===

    // add controls ===
    // 
    if (mapData.zoom_panel) {
      this.map.addControl( new ol.control.Zoom() );
    }
    if (mapData.zoom_extent) {
      this.map.addControl( new ol.control.ZoomToExtent() );
    }
    if (mapData.fullscreen) {
      this.map.addControl( new ol.control.FullScreen() );
// @todo alternative for unsupported Browsers
    }
    if (mapData.scaleline) {
      this.map.addControl( new ol.control.ScaleLine() );
    }
    if (mapData.mouseposition) {
      this.map.addControl( new ol.control.MousePosition({projection:'EPSG:4326'}) );
    }
    if (mapData.attribution) {
      this.map.addControl( new ol.control.Attribution() );
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

})(jQuery);