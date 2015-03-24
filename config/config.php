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
 * Global settings
 */
$GLOBALS['c4g_maps_extension']['installed'] 		= true;
$GLOBALS['c4g_maps_extension']['version'] 			= '3.0.0';
$GLOBALS['c4g_maps_extension']['ol-version'] 		= '3.0.0';

/**
 * Sourcetable definition
 */
// $GLOBALS['c4g_maps_extension']['sourcetable']['tl_calendar_events'] = array
// 	(
// 		'ptable'        => 'tl_calendar',
//         'ptable_option' => 'title',
//         'geox'          => 'c4g_loc_geox',
//         'geoy'          => 'c4g_loc_geoy',
//         'label'         => 'c4g_loc_label',
//         'locstyle'      => 'c4g_locstyle',
//         'tooltip'       => 'title',
//         'popup'         => '{{event::[id]}},[startDate:date]',
//   		'linkurl'       => '{{event_url::[id]}}',
//         'sqlwhere'      => 'published = 1',
//   		'alias_getparam'=> 'events'
// 	);
        

/**
 * Backend Modules
 */
array_insert( $GLOBALS['BE_MOD']['con4gis'], 1, array
(
    'c4g_maps' => array
    (
		'tables' 			=> array('tl_c4g_maps'),
 		'icon'	 			=> 'system/modules/con4gis_maps/assets/images/be-icons/mapstructure.png',
	    'update_db' 		=> array('C4GMapsBackend', 'updateDB'),
 		// 'javascript' 		=> 'system/modules/con4gis_maps/assets/js/C4GMapsBackend.js'
	),
    'c4g_map_baselayers' => array
    (
		'tables' 			=> array('tl_c4g_map_baselayers','tl_c4g_map_overlays'),
		'icon'	 			=> 'system/modules/con4gis_maps/assets/images/be-icons/baselayers.png'
	),
	'c4g_map_locstyles' => array
	(
		'tables' 			=> array('tl_c4g_map_locstyles'),
		'icon'	 			=> 'system/modules/con4gis_maps/assets/images/be-icons/locstyles.png'
	),
	'c4g_map_profiles' => array
	(
		'tables' 			=> array('tl_c4g_map_profiles'),
		'icon'	 			=> 'system/modules/con4gis_maps/assets/images/be-icons/profiles.png'
	)
));

if ($GLOBALS['BE_MOD']['content']['calendar']['javascript'] == '') {	
	$GLOBALS['BE_MOD']['content']['calendar']['javascript'] = 'system/modules/con4gis_maps/html/js/C4GMapsBackend.js'; 
}   
	
/**
 * Frontend modules
 */
array_insert( $GLOBALS['FE_MOD']['con4gis'], 1, array
(
	'c4g_maps' => 'Module_c4g_maps'
));

/**
 * Content elements
 */
array_insert($GLOBALS['TL_CTE']['con4gis'], 1, array
(
	'c4g_maps' => 'Content_c4g_maps'
));

/**
 * Rest-API
 */
$GLOBALS['TL_API']['c4g_maps_layerapi'] = 'LayerApi';
$GLOBALS['TL_API']['layerService'] = 'LayerApi';
$GLOBALS['TL_API']['c4g_maps_layercontentapi'] = 'LayerContentApi';

/**
 * Specialized Widgets for Text Input and Image Sizes
 */
$GLOBALS['BE_FFL']['c4g_text'] 			= 'C4GTextField';
$GLOBALS['BE_FFL']['c4g_imageSize'] 	= 'C4GImageSize';

/**
 * Paths to Javascript libraries
 */
// $GLOBALS['c4g_maps_extension']['js_openlayers'] 					= 'system/modules/con4gis_maps/html/OpenLayers-2.13.1/OpenLayers.js';
// $GLOBALS['c4g_maps_extension']['js_google'] 						= 'http://maps.google.com/maps/api/js?v=3.5&amp;sensor=false';
$GLOBALS['c4g_maps_extension']['starboard_layerapi'] = "system/modules/con4gis_core/api/layerService";
$GLOBALS['c4g_maps_extension']['js_openlayers']['DEFAULT'] 			= 'system/modules/con4gis_maps/assets/vendor/OpenLayers-' . $GLOBALS['c4g_maps_extension']['ol-version'] . '/ol.js';
$GLOBALS['c4g_maps_extension']['js_openlayers']['DEBUG'] 			= 'system/modules/con4gis_maps/assets/vendor/OpenLayers-' . $GLOBALS['c4g_maps_extension']['ol-version'] . '/ol-debug.js';
// $GLOBALS['c4g_maps_extension']['js_openlayers']['SERVER'] 			= 'http://openlayers.org/api/2.13.1/OpenLayers.js';
$GLOBALS['c4g_maps_extension']['css_openlayers']['DEFAULT'] 		= 'system/modules/con4gis_maps/assets/vendor/OpenLayers-' . $GLOBALS['c4g_maps_extension']['ol-version'] . '/ol.css';
// $GLOBALS['c4g_maps_extension']['css_openlayers']['DEFAULT'] 		= 'system/modules/con4gis_maps/html/OpenLayers-2.13.1/theme/default/style.css';
// $GLOBALS['c4g_maps_extension']['css_openlayers']['SERVER'] 		= 'http://openlayers.org/api/2.13.1/theme/default/style.css';
$GLOBALS['c4g_maps_extension']['overpass_proxy'] 					= 'system/modules/con4gis_maps/classes/C4GOverpass.php';
// $GLOBALS['c4g_maps_extension']['js_editor'] 						= 'system/modules/con4gis_maps/assets/js/C4GMapsEditor.js';
// $GLOBALS['c4g_maps_extension']['css_editor'] 					= 'system/modules/con4gis_maps/assets/css/C4GMapsEditor.css';
// $GLOBALS['c4g_maps_extension']['js_openlayers_owm'] 				= 'system/modules/con4gis_maps/assets/vendor/OWM.OpenLayers.1.3.4.js';

// if ($GLOBALS['con4gis_core_extension']['installed'])
// {
	$GLOBALS['TL_JAVASCRIPT']['c4g_jq_bbc'] 	= 'system/modules/con4gis_core/lib/wswgEditor/editor.js';
	$GLOBALS['TL_CSS']['c4g_jq_bbc'] 			= 'system/modules/con4gis_core/lib/wswgEditor/css/editor.css';
	$GLOBALS['TL_CSS']['c4g_jq_bbc2'] 			= 'system/modules/con4gis_core/lib/wswgEditor/css/bbcodes.css';
// }