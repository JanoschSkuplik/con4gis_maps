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
 * Back end modules
 */
$GLOBALS['TL_LANG']['MOD']['c4g_maps'] 				= array( 'Map structures', 'Maintain con4gis-Maps items in map structures.' );
$GLOBALS['TL_LANG']['MOD']['c4g_map_baselayers'] 	= array( 'Base layers', 'Maintain con4gis-Maps base layers.' );
$GLOBALS['TL_LANG']['MOD']['c4g_map_locstyles'] 	= array( 'Location styles', 'Maintain con4gis-Maps location styles.' );
$GLOBALS['TL_LANG']['MOD']['c4g_map_profiles'] 		= array( 'Map profiles', 'Maintain con4gis-Maps map profiles.' );

/**
 * Frontend modules
 */
$GLOBALS['TL_LANG']['FMD']['c4g_maps'] 				= array('Map (con4gis)', 'Integrate an OpenLayers map with con4gis-Maps.');
?>