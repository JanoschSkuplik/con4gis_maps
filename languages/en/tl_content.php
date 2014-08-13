<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014
 * @link      https://www.kuestenschmiede.de
 * @filesource 
 */



/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_content']['c4g_map_id'] = array('Mapstructure', 'Choose a mapstructure to be displayed from available maps.');
// $GLOBALS['TL_LANG']['tl_content']['c4g_map_mapsize'] = array('Map size', 'Custom map size, overrides map size defined on map level.');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_width'] = array('Map width', 'Custom map width, overrides map size defined on map level.');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_height'] = array('Map height', 'Custom map height, overrides map size defined on map level.');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_zoom'] = array('Zoomlevel', 'Custom zoomlevel, overrides zoomlevel defined on map level.');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_default_mapservice'] = array('Default base layer', 'Choose from the available base layers, which are defined at the map profile of the chosen map. If none is defined, OpenStreetMap Mapnik is taken by default.');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_layer_switcher'] = array('Starboard (Layer switcher control)', 'Activate starboard to allow to change the base layers and overlays.');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_layer_switcher_open'] = array('Open starboard', 'Initially opens the starboard.');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_layer_switcher_ext'] = array('Starboard - display data layers in tree control', 'Activate a tree control to display the switchable data layers according to the tree definition of the map. Note: several Javascript-Libraries (e.g. jQuery) are added to the page automatically.');

/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_content']['c4g_map_legend'] = 'Mapconfiguration';