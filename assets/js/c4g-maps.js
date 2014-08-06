// "namespace"
var c4g = {};

/**
 * [Map description]
 * @param {[type]} mapData [description]
 */
c4g.Map = function(mapData) 
{
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
			mapDiv : 'c4g_Map' + mapData.id,
			// width : '',
			// height : '',
			center_lat : 37.41,
			center_lon : 8.82,
			zoom : 4,
			calc_extent: 'CENTERZOOM'
		}, mapData);

    //[ NOTES ] !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // self.closeAll = function() {};

    // self.isLoading = ...

    // foreach x in extensions
    // x.init(self);
    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    //[ DEV ]!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    console.log( mapData );

    this.map = new ol.Map({
	    target: mapData.mapDiv,
	    layers: [
		    new ol.layer.Tile({
		      	source: new ol.source.OSM()
		    })
	    ],
	    view: new ol.View({
	    	// projection: ol.proj.get('EPSG:4326'),
	    	// center: [parseFloat(mapData.center_lon), parseFloat(mapData.center_lat)],
	    	center: ol.proj.transform([parseFloat(mapData.center_lon), parseFloat(mapData.center_lat)], 'EPSG:4326', 'EPSG:3857'),
	    	zoom: parseInt(mapData.zoom)
	    })
  	});


  	this.map.addControl(new ol.control.MousePosition({projection:'EPSG:4326'}));

    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
};