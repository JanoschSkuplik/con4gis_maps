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

    //@todo: find a better way, to do this
    var baseLayerUrls = {

    }

    var defaultBaseLayer = {};
    if (mapData.baseLayer && !(mapData.baseLayer.provider=='osm' && mapData.baseLayer.style=='Mapnik')) {

      if (mapData.baseLayer.provider=='osm')

        defaultBaseLayer = new ol.layer.Tile({
          source: new ol.source.OSM()
        });
    } else {
      defaultBaseLayer = new ol.layer.Tile({
        source: new ol.source.OSM()
      });
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

    this.map.addControl(new ol.control.MousePosition({projection:'EPSG:4326'}));

    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  };

})(jQuery);