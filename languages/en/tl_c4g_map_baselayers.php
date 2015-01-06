<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014 - 2015
 * @link      https://www.kuestenschmiede.de
 * @filesource
 */



/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['name']           		= array('Name', 'Please enter the name of the base layer.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['display_name']   		= array('Name in "Starboard"', 'Default: content of field "Name".');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['sort']   		  		= array('Sort', 'Enter a number here to define a user defined order for the base layers in the layer switcher control.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider']       		= array('Provider', 'Select the provider of the base layer.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style']      		= array('OSM style', 'Please select from the given OpenStreetMap styles. Please take attention to the terms of use, given by the provider.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_url1'] 		= array('OSM URL 1', 'Please provide the 1st URL for the OpenStreetMap-Style.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_url2'] 		= array('OSM URL 2', 'Please provide the 2nd URL for the OpenStreetMap-Style.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_url3'] 		= array('OSM URL 3', 'Please provide the 3rd URL for the OpenStreetMap-Style.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_url4'] 		= array('OSM URL 4', 'Please provide the 4th URL for the OpenStreetMap-Style.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_keyname']    		= array('OSM Keyname', 'Please enter the internal OSM Keyname (optional).');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['bing_style']     		= array('Bing Maps style', 'Please select from the given Bing Maps styles.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['bing_key']       		= array('Bing Application Key', 'Enter your Bing Application Key here. You get it from http://bingmapsportal.com/.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['google_style']   		= array('Google Maps style', 'Please select from the given Google Maps styles.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['attribution']    		= array('Custom attribution', 'Enter a custom attribution here if needed (optional).');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['maxzoomlevel']   		= array('Custom maximum zoomlevel', 'Enter a custom maximum zoomlevel here if needed (0 means maximum zoomlevel of the map)');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['protect_baselayer'] 	= array('Protect this base layer', 'Make this base layer only visible to selected groups.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['permitted_groups'] 	= array('Permitted groups', 'The groups for which the base layer is visible.');

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm']    	= 'OpenStreetMap';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google'] 	= 'Google Maps';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_bing']   	= 'Bing Maps';

$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_mapnik'] 			= 'Mapnik';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_osma']   			= 'Osmarender';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_cycle']  			= 'Cyclemap (opencyclemap.org)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_german'] 			= 'German Style (openstreetmap.de)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_german_transport']	= 'German Transport Map (memomaps.de)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_transport']  		= 'Transport Map (opencyclemap.org)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_landscape']  		= 'Landscape Map (opencyclemap.org)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_mapquestopen'] 		= 'MapQuest Open (mapquest.com)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_toner'] 				= 'Toner (maps.stamen.com)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_tonerlines'] 		= 'Toner, only lines (maps.stamen.com)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_tonerlabels'] 		= 'Toner, only labels (maps.stamen.com)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_watercolor'] 		= 'Watercolor (maps.stamen.com)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_custom'] 			= 'Custom';

$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google_streets']   = 'Street';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google_hybrid']    = 'Hybrid';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google_satellite'] = 'Satellite';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google_terrain']   = 'Terrain';

$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_bing_road']        = 'Road';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_bing_hybrid']      = 'Hybrid';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_bing_aerial']      = 'Aerial';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['new']        = array('New base layer', 'Create a new base layer');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['edit']       = array('Edit base layer', 'Edit base layer ID %s');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['copy']       = array('Duplicate base layer', 'Duplicate base layer ID %s');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['delete']     = array('Delete base layer', 'Delete base layer ID %s');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['show']       = array('Details', 'Show details of base layer ID %s');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['overlays']   = array('Overlay layers', 'Maintain overlay layers of base layer ID %s');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['general_legend']			= 'General';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['protection_legend']	    = 'Access protection';
?>