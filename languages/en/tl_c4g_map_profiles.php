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
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['name'] 					= array('Name',
																			'Enter a name for the map profile.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['is_default'] 			= array('Default profile',
																			'Check to use this profile as default for new maps.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['theme'] 				= array('OpenLayers Theme',
																			'Select one of the integrated OpenLayers Themes.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['baselayers']  			= array('Base layers',
																			'Check the baselayers you wish to see in the Starboard. Default: all');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['locstyles']  			= array('Location styles',
																			'Check the location styles available for map structure items using this profile. Default: all');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['pan_panel'] 			= array('Pan panel',
																			'A pan panel is displayed on the map with arrow buttons for the pan directions.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['zoom_panel'] 			= array('Zoom panel',
																			'Adds a panel with buttons to zoom in and zoom out. The mobile version provides larger buttons.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['zoom_panel_world'] 		= array('World-Icon in zoom panel',
																			'A world icon is displayed in the zoom panel. Zooms either to the minimum zoomlevel, or if defined to the restricted area of the map.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav'] 			= array('Mouse navigation',
																			'Adds the ability to zoom and pan using the mouse.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav_wheel'] 		= array('Mouse wheel zooming',
																			'Adds the function to zoom in and zoom out using the mouse wheel.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav_zoombox'] 	= array('Zoombox with Shift+Mouse',
																			'Ability to drag a box to zoom with the mouse while pressing the SHIFT key.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav_kinetic'] 	= array('Kinetic scrolling after panning with mouse',
																			'Scrolling continues slowly after release of mouse button, until it finally stops.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav_toolbar'] 	= array('Mouse toolbar',
																			'Provides a UI for changing state to use the zoombox via a panel control instead of pressing the SHIFT key.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['keyboard_nav'] 			= array('Keyboard navigation',
																			'Navigate on map with the keyboard (arrow keys to pan, zoom with "+" and "-" keys).');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['nav_history'] 			= array('Navigation history',
																			'Creates "next" and "previous" buttons, similar to those of internet browsers.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['overviewmap'] 			= array('Overview map',
																			'Adds a button in the lower right corner of the map, which when pressed shows an overwiew map.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['scaleline'] 			= array('Scale line',
																			'Shows a line which demonstrates the scale of the map.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouseposition'] 		= array('Mouse coordinates',
																			'Shows the GEO coordinates of the current mouse position.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['permalink'] 			= array('Permalink',
																			'Displays a link representing the current map state, which may be bookmarked or sent via E-Mail for example.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['zoomlevel'] 			= array('Show zoomlevel',
																			'Displays the current zoomlevel.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['fullscreen'] 			= array('Fullscreen control',
																			'Shows a button which switches the browser into fullscreen (HTML5). Caution: this is not supported by every browser!');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['measuretool'] 			= array('Measure tool',
																			'Shows a button which activates a tool for measuring distances/areas.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['graticule'] 			= array('Graticule',
																			'Shows a button, which activates a graticule that visualizes the latitude and longitude on the map.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor'] 				= array('Editor',
																			'Shows a button, which activates an Editor for drawing locations, lines and areas on the map.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['libsource'] 			= array('OpenLayers Library',
																			'Choose where to take the OpenLayers JavaScript library from.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['imagepath'] 			= array('Userdefined OpenLayers image path (optional)',
																			'Only when using default theme: set path to own OpenLayers /img/ directory in case you want to use userdefined icons. NOTE: This setting is NOT changing icons from OpenLayers /themes/default/img/ directory, which you can exchange using CSS!');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['script'] 				= array('Custom JavaScript code',
																			'The JavaScript code which will be inserted at the bottom of the function C4GMaps<nn>.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['link_newwindow'] 		= array('Open links in new window',
																			'Do not open links in the same window, but display linked pages in a new window.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['link_open_on'] 			= array('Open links on',
																			'Select on which mouse action a link should be opened, if a link is specified.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['hover_popups'] 			= array('Popups on mouse hover',
																			'Show popup when mouse pointer rests on location.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['hover_popups_stay'] 	= array('Mouse hover popups stay open',
																			'Popups that have been opened on mouse hover are not closed when the mouse pointer leaves the location.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['attribution'] 			= array('Attribution',
																			'Shows the attribution. Deactivate only when you are sure it is allowed.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['cfg_logo_attribution'] 	= array('Show con4gis-logo',
																			'Shows the con4gis-logo on the map. You can turn it off, but we would appreciate it if you\'d mention "con4gis" at a different location on your page.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['div_attribution'] 		= array('DIV attribution',
																			'Enter the ID of an existing DIV element if you want to put the attributon outside the map.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['add_attribution'] 		= array('Additional attribution',
																			'This text is added to the generated attribution. Deactivate only when you are sure it is allowed.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch'] 			= array('Show search',
																			'Activates an input field for searching places.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_engine'] 		= array('Searchengine',
																			'ATTENTION! Please take note to restrictions that may apply by the usage policy of the choosen provider. If you have choosen to use a custom URL, remember to add an attribution of that provider, if it\'s not your own server.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_customengine_url'] 			= array('Custom searchengine-URL',
																							'The URL wich leads to the searchengine.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_customengine_attribution'] 	= array('Custom searchengine-attribution',
																							'IMPORTANT! The copyrighttext of the searchengines provider.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_div'] 		= array('DIV for search',
																			'Enter the ID of an existing DIV element if you want to put the search field somewhere else on your page.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_zoomto'] 		= array('Zoomlevel',
																			'Zoomlevel to set after searching, e.g. 12');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_zoombounds'] 	= array('Zoom to bounds',
																			'Zoom to the bounds of an area if provided by the search service (Nominatim).');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_attribution'] = array('Show attribution of search service',
																			'Adds an attribution of the search service (Nominatim).  Deactivate only when you are sure it is allowed.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker'] 			= array('Show geopicker',
																			'Adds a geopicker in the frontend, similar to the backend wizard to pick coordinates. Can be used together with INPUT fields in custom forms.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_fieldx'] 		= array('Form field for GEO latitude',
																			'The ID of an INPUT field, where the latitude of the chosen place is stored.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_fieldy'] 		= array('Form field for GEO longitude',
																			'The ID of an INPUT field, where the longitude of the chosen place is stored.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_searchdiv'] 	= array('DIV for geopicker',
																			'Enter the ID of an existing DIV element where you want to put the geopicking fields into. May be defined e.g. in an HTML form field.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_attribution'] = array('Show attribution of geocoding service',
																			'Adds an attribution of the search service (Nominatim).  Deactivate only when you are sure it is allowed.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['div_layerswitcher'] 	= array('DIV for LayerSwitcher',
																			'Enter the ID of an existing DIV element if you want to put the LayerSwitcher somewhere else on your page.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['custom_div'] 			= array('DIV for map',
																			'Enter the ID of an existing DIV element if you want to put the map somewhere else on your page.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['label_baselayer'] 		= array('Description of base layers in the starboard',
																			'Default: "Base layer"');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['label_overlays'] 		= array('Description of overlays in the starboard',
																			'Default "Overlays"');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['overpass_url'] 			= array('URL of Overpass-API (<a href="http://overpass-api.de/" target="_blank">Website of the default API-Server provider</a>)',
																			'Default: http://overpass-api.de/api/interpreter');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['router'] 				= array('Activate routing (Please check the <a href="https://github.com/DennisOSRM/Project-OSRM/wiki/Api-usage-policy" target="_blank">terms of use</a> for default API!)',
																			'Adds an additional button to activate routing functionality.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['router_viaroute_url'] 	= array('Alternative URL of OSRM-API',
																			'Default: http://router.project-osrm.org/viaroute');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['router_attribution'] 	= array('Override OSRM attribution',
																			'Only override the attribution if you use another API-server, or put the attribution somewhere prominent else on your page!');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_styles_point'] 	= array('Location styles for POIs',
																			'Choose location styles that can be used in the editor.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_styles_line'] 	= array('Location styles for lines',
																			'Choose location styles that can be used in the editor.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_styles_polygon'] = array('Location styles for polygons',
																			'Choose location styles that can be used in the editor.');;
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_vars'] 			= array('Additional fields',
																			'Fields available for POIs, lines and polygons can be addressed using ${key} in popup, label and tooltip.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_show_items'] 	= array('Show other map structure items while editing',
																			'Check this if you don\' want to hide other map structure items in the editor.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_helpurl'] 		= array('Help page URL',
																			'If you enter an URL here, a help link is displayed inside the editor, which opens the given URL.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['be_optimize_checkboxes_limit'] 		= array('Limit for optimization of large checkbox-lists',
																			'Defines how much entries must be there after the checkbox-lists will be converted to chosenfields. (0 = do not convert)');



/**
 * References
 */
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['CLICK'] 	= 'Single mouseclick';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['DBLCL'] 	= 'Mouse doubleclick';

$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['dark'] 			= 'Dark Theme (modified)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['modern'] 			= 'Modern Trans Theme';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['metro'] 			= 'Metro Theme';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['con4gis'] 			= 'con4gis Theme';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['blue'] 			= 'Blue Theme (based on modified dark-theme)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['red'] 			= 'Red Theme (based on modified dark-theme)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['default_theme'] 	= 'OpenLayers Default-Theme';

$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_zoom_panel']['no_zoom_panel'] 	= 'Off';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_zoom_panel']['1'] 				= 'Default layout';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_zoom_panel']['2'] 				= 'Simple layout';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_zoom_panel']['3'] 				= 'Simple layout (Mobile)';

$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_measuretool']['no_measuretool'] 	= 'Off';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_measuretool']['1'] 				= 'On (without immediate results)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_measuretool']['2'] 				= 'On (with immediate results)';

$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_geosearch_engine']['1'] = 'Nominatim by Openstreetmap (<a href="http://wiki.openstreetmap.org/wiki/Nominatim_usage_policy" target="_blank">usage policy</a>)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_geosearch_engine']['2'] = 'Nominatim by MapQuest (<a href="http://developer.mapquest.com/web/products/open/nominatim" target="_blank">usage policy</a>)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_geosearch_engine']['3'] = 'Custom';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['new']        = array('New map profile', 'Create new map profile');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['edit']       = array('Edit map profile', 'Edit map profile ID %s');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['copy']       = array('Duplicate map profile', 'Duplicate map profile ID %s');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['delete']     = array('Delete map profile', 'Delete map profile ID %s');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['show']       = array('Details', 'Show details of map profile ID %s');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['general_legend']		= 'General';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['baselayer_legend']		= 'Base layers';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['locstyle_legend']		= 'Location styles';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['navigation_legend']		= 'Map navigation';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['tool_legend']			= 'Map tools';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['information_legend']	= 'Map information';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['expert_legend']	        = 'Expert settings';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['misc_legend']	        = 'Miscellaneous';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_legend']	    = 'Search';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_legend']	    = 'Geopicker';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['router_legend']	    	= 'Router (OSRM)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_legend']	    	= 'Editor settings (GeoJSON and con4gis-Forum)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['backend_legend']	    	= 'Backend settings';

/**
 * Globals
 */
$GLOBALS['TL_LANG']['MSC']['ow_value'] = 'Editor label';

?>