// "namespace"
var c4g = {};

/**
 * [Map description]
 * @param {[type]} config [description]
 */
c4g.Map = function(config) 
{
    var self = this;

    self = {};

    //[ NOTES ] !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    // self.closeAll = function() {};

    // self.isLoading = ...

    // foreach x in extensions
    // x.init(self);
    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    //[ DEV ]!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
    console.log('c4g-maps loaded');

    if (typeof jQuery != 'undefined') {
    	console.log('jQuery loaded');
    }
    if (typeof ol != 'undefined') {
    	console.log('OpenLayers 3 loaded');
    }
    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
};