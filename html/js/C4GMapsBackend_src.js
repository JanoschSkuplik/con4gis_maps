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