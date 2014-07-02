/**
 * Contao Open Source CMS
 * 
 * @copyright  Küstenschmiede GmbH Software & Design 2014
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
 * @package    con4gis
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * C4GMapsBackend.js
 */
var C4GMapsBackend =
{
		/**
		 * Open the Geo Coordinate Picker Wizard in a modal window
		 * @param string
		 */
		pickGeo: function(idX, idY)
		{
			var width = 600;
			var height = 720;

			C4GMapsBackend.currentIdX = idX;
			C4GMapsBackend.ppGeoX = $(idX).value;

			C4GMapsBackend.currentIdY = idY;
			C4GMapsBackend.ppGeoY = $(idY).value;
			
			Backend.getScrollOffset();
			window.open($$('base')[0].href + 'system/modules/con4gis_maps/C4GGeoPicker.php?GeoX=' + C4GMapsBackend.ppGeoX +'&GeoY='+C4GMapsBackend.ppGeoY, '', 'width='+width+',height='+height+',modal=yes,left='+(Backend.xMousePosition ? (Backend.xMousePosition-(width/2)) : 200)+',top='+(Backend.yMousePosition ? (Backend.yMousePosition-(height/2)+80) : 100)+',location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no');
		},
		
		/**
		 * Open the Feature Editor Wizard in a modal window
		 * @param string
		 */
		editFeatures: function(id, mapId)
		{
			var width = 600;
			var height = 720;
		
			C4GMapsBackend.currentId = id;
			C4GMapsBackend.ppValue = $(id).value;
		
			Backend.getScrollOffset();
			window.open($$('base')[0].href + 'system/modules/con4gis_maps/C4GFeatureEditor.php?mapId='+mapId, '', 'width='+width+',height='+height+',modal=yes,left='+(Backend.xMousePosition ? (Backend.xMousePosition-(width/2)) : 200)+',top='+(Backend.yMousePosition ? (Backend.yMousePosition-(height/2)+80) : 100)+',location=no,menubar=no,resizable=yes,scrollbars=no,status=no,toolbar=no');
		}

};