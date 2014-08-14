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
      addIdToDiv : false, 
      mapId : 1, 
      mapDiv : 'c4g_Map',
      center_lat : 37.41,
      center_lon : 8.82,
      zoom : 4,
      calc_extent: 'CENTERZOOM'
    }, mapData);

    if (mapData.addIdToDiv) {
      mapData.mapDiv += mapData.mapId;
    }

    //[ NOTES ] !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // self.closeAll = function() {};
    // 
    // self.isLoading = ...
    // 
    // foreach x in extensions
    // x.init(self);
    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    //[ DEV ]!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    console.log( mapData );

//@todo: maybe find a better way, to do this
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
//@todo (has own class -> ol.source.MapQuest)
      MapQuestOpen: {
          attributions: [
              new ol.Attribution({
                html: 'Style by <a href="http://www.mapquest.com/">MapQuest</a> ' +
                  '<img src="http://developer.mapquest.com/content/osm/mq_logo.png">'
              }),
              ol.source.OSM.DATA_ATTRIBUTION
            ],
          maxZoom: 19,
          url: 'http://otile{1-4}.mqcdn.com/tiles/1.0.0/osm/{z}/{x}/{y}.png'
        },

      TransportMap: {
          attributions: [
              new ol.Attribution({
                html: 'Style by <a href="http://www.opencyclemap.org/">OpenCycleMap</a>'
              }),
              ol.source.OSM.DATA_ATTRIBUTION
            ],
          maxZoom: 19,
          url: 'http://{a-c}.tile2.opencyclemap.org/transport/{z}/{x}/{y}.png'
        }
    }

    // set default baseLayer
    var defaultBaseLayer = new ol.layer.Tile({
        source: new ol.source.OSM()
      });
    // override it with appropriate settings, if existant
    if (mapData.baseLayer && !(mapData.baseLayer.provider=='osm' && mapData.baseLayer.style=='Mapnik')) {

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
          } else {
            // custom?
            console.warn('currently unsupported osm-style');
          }
          break;
        case 'google':
          //@todo
          console.warn('currently unsupported provider');
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
          break;
        default:
          //@todo
          console.warn('currently unsupported provider');
          break;
      }

      //   defaultBaseLayer = new ol.layer.Tile({
      //     source: new ol.source.OSM()
      //   });
    } 

    this.map = new ol.Map({
        target: mapData.mapDiv,
        layers: [
          defaultBaseLayer
        ],
        view: new ol.View({
            // projection: ol.proj.get('EPSG:4326'),
            // center: [parseFloat(mapData.center_lon), parseFloat(mapData.center_lat)],
            center: ol.proj.transform([parseFloat(mapData.center_lon), parseFloat(mapData.center_lat)], 'EPSG:4326', 'EPSG:3857'),
            zoom: parseInt(mapData.zoom)
          })
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

    this.map.addControl( new ol.control.MousePosition({projection:'EPSG:4326'}) );

    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  };

})(jQuery);