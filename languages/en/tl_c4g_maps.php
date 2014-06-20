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
$GLOBALS['TL_LANG']['tl_c4g_maps']['name'] = array('Name', 'Name of the map/the location');
$GLOBALS['TL_LANG']['tl_c4g_maps']['profile'] = array('Map profile', 'Please select the map profile to be used. Map profiles are maintained by selecting "Map profiles" in the section "Layout". When no map profiles are defined "OpenStreetMap Mapnik" is used by default.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['profile_mobile'] = array('Map profile mobile', 'Optional: map profile used on mobile devices.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['published'] = array('Published', 'Defines if the map/the location is published.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['is_map'] = array('Use as map', 'Check if you want to create a map.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['mapsize'] = array('Map size (width, height)', 'Enter the size of the map in the frontend.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_width'] = array('Align map width to browser width', '.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_width_gap'] = array('Right gap (px)', '');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_width_min'] = array('Minimum map width (px)', '');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_width_max'] = array('Maximum map width (px)', '');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_height'] = array('Align map height to browser height', '');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_height_gap'] = array('Bottom gap (px)', '');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_height_min'] = array('Minimum map height (px)', '');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_height_max'] = array('Maximum map height (px)', '');
$GLOBALS['TL_LANG']['tl_c4g_maps']['calc_extent'] = array('Extent determination method', 'The method to find out which area should be displayed when the map is first shown.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['min_gap'] = array('Minimum gap in pixel from locations to map border', 'Use this setting when your icons reach beyond the map border after determining the extent.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['center_geox'] = array('GEO latitude', 'Enter the GEO X-coordinate (latitude, WGS-84) of the map center.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['center_geoy'] = array('GEO longitude', 'Enter the GEO Y-coordinate (longitude, WGS-84) of the map center.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['zoom'] = array('Zoomlevel', 'Enter the zoomlevel of the initially shown map.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['restrict_area'] = array('Restrict navigation area', 'Selecting this option enables you to define an area where the user cannot pan away from. Also clicking on the world symbol of the zoom panel zooms to the defined area.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['restr_bottomleft_geox'] = array('GEO latitude left', 'GEO X-coordinate (latitude, WGS-84) of the lower left corner of the restricted navigation area.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['restr_bottomleft_geoy'] = array('GEO longitude bottom', 'GEO Y-coordinate (longitude, WGS-84) of the lower left corner of the restricted navigation area.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['restr_topright_geox'] = array('GEO latitude right', 'GEO X-coordinate (latitude, WGS-84) of the upper right corner of the restricted navigation area.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['restr_topright_geoy'] = array('Geo longitude top', 'GEO Y-coordinate (longitude, WGS-84) of the upper right corner of the restricted navigation area.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['location_type'] = array('Location type', 'Select how to define one or a set of locations. Note that it is possible to define several location items for a map as child of the map.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_geox'] = array('GEO latitude', 'Enter the GEO X-coordinate (latitude, WGS-84) of the location.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_geoy'] = array('GEO longitude', 'Enter the GEO Y-coordinate (longitude, WGS-84) of the map center.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['locstyle'] = array('Location style', 'The location style, which is defined in the map profiles, defines the design of the location marker. Without location styles defined, a red dot is drawn for every location by default.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_only_in_parent'] = array('Display location only as child of a parent map', 'If you do NOT select this option, the location is displayed also if this item is displayed as a map.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_label'] = array('Label', 'Label to be displayed on map together with the location marker.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tooltip'] = array('Tooltip for icons', 'Only for icons! Tooltip to be displayed when mouse pointer rests on the location for a while.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['popup_info'] = array('Popup information', 'Information to be displayed in a popup after clicking with the mouse on a location or via mouse hover (depending on the map profile settings).');
$GLOBALS['TL_LANG']['tl_c4g_maps']['popup_extend'] = array('Extend popup information with forumpost', 'Gives the ability to render additional popup information from a forumpost.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['routing_to'] = array('Add routing link to popup', 'Adds a link with the text "Route to" to the popup. Works only when routing is activated in the map profile.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_linkurl'] = array('Direct link', 'Enter an URL (with http://) or click on the symbol to select a contao page. If you specify a link then the user jumps to the link via mouse click or mouse double click (depending on the map profile settings).');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_onclick_zoomto'] = array('On mouse click zoom to zoom level', 'Enter a zoom level, to which is zoomed when clicking onto a map structure item. Works only when there is no direct link.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_minzoom'] = array('Min. zoom level', 'Enter the minimum zoom level, on which the items are shown. 0 means no restriction.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_maxzoom'] = array('Max. zoom level', 'Enter the maximum zoom level, on which the items are shown. 0 means no restriction.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_source'] = array('Source table', 'Select from the available source tables. You are able to add your own tables, see CONFIG.PHP.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_pid'] = array('Entry of parent table', 'If a parent table is defined in the configuration, then select the entry you want to take the locations from.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_labeldisplay'] = array('Label display', 'Define if the label is displayed and how the label is composed when there is more than one item at the same geo location.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_tooltipdisplay'] = array('Tooltip display', 'Define if the tooltip is displayed and how the tooltip is composed when there is more than one item at the same geo location.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_directlink'] = array('Generate direct link', 'If selected the user can jump to the linked content with mouse click or mouse double click (depending on the setting in the map profile).');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_force_target_blank'] = array('Open popup-links in new window', 'Force links in popups to always open a new window/tab (This depends on the users browserconfiguration).');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_whereclause'] = array('Condition', 'Select an additional condition which is to be added to the WHERE clause of the generated SQL statement to restrict the result if needed.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_orderby'] = array('Sorting', 'Define additional MySQL-OrderBy statements, to control the sorting of the Items.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_filter_alias'] = array('Only show current entry', 'Set this to only show the table entry with the location that corresponds to the current "PageId" on pages hosting "Reader" modules. Helpful e.g. if you want to place a map together with an event reader, that displays only the current event. An alias field has to be available if you use this option!');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_file'] = array('File', 'If you have a server file containig your data, please select the file here.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_url'] = array('URL', 'If you want the data to be loaded from an URL please enter it here.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_content'] = array('Data', 'Enter your data directly into this field to save it into the database.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_projection'] = array('Projection');
$GLOBALS['TL_LANG']['tl_c4g_maps']['ovp_request'] = array('Request to Overpass API (XML-Format)', 'Request is sent via AJAX through a proxy (C4GOverpass.php) to the Overpass API. Format: see http://www.overpass-api.de.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['ovp_bbox_limited'] = array('Request to Overpass API bounding box limited.', 'Sends the request on change of the displayed area (bounding box). The request itself has to contain the placeholder "(bbox)", which will be replaced by a "bbox-query" tag on runtime.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_forcenodes'] = array('Convert ways to nodes', 'Converts OSM-ways to nodes. Especially useful when POIs are inside the OSM data, some represented as ways (building shape), others as nodes.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_layername'] = array('Layer name', 'Enter a name if you want this layer to be selectable in the layer switcher.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_hidelayer'] = array('Hide layer', 'Check this if you don\'t want to show this layer by default.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_js_style_function'] = array('Javascript function for styling', 'Function to be called for every map feature for custom styling.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['forums'] = array('Forums','Please select from the map enabled forums.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['forum_jumpto'] = array('Forum page','Select the page that hosts con4gis-Forum');
$GLOBALS['TL_LANG']['tl_c4g_maps']['forum_reassign_layer'] = array('Reassign layer','Method for moving forum generated map entries to other layers.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['geolocation'] = array('Geolocation','Perform a call to the HTML5 Geolocation API to determine the current location.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['geolocation_zoom'] = array('Geolocation - zoomlevel','Zoom to this zoomlevel after successful determination of the current location.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['include_sublocations'] = array('Include sublocations of element', 'When unchecked, only elements of the first level appear on the map.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['link_id'] = array('Map structure item', 'Item to be linked into the map.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['protect_element'] = array('Protect this entry', 'Make this entry only visible to selected groups.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['permitted_groups'] = array('Permitted groups', 'The groups for which the entry is visible.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['use_specialprofile'] = array('Activate special profile', 'Activate a special profile for selected membergroups.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['specialprofile'] = array('Specialprofile', 'The profile you want to use for selected groups.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['specialprofile_mobile'] = array('Specialprofile mobile', 'Optional: special map profile used on mobile devices.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['specialprofile_groups'] = array('Groups', 'The groups for which the specialprofile will be active.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_maps']['new']    = array('New map structure item', 'Create new map structure item');
$GLOBALS['TL_LANG']['tl_c4g_maps']['edit']   = array('Edit map structure item', 'Edit map structure item ID %s');
$GLOBALS['TL_LANG']['tl_c4g_maps']['copy']   = array('Duplicate map structure item', 'Duplicate map structure item ID %s');
$GLOBALS['TL_LANG']['tl_c4g_maps']['copyChilds']   = array('Duplicate map structure item with subitems', 'Duplicate map structure item ID %s with subitems');
$GLOBALS['TL_LANG']['tl_c4g_maps']['cut']    = array('Move map structure item', 'Move map structure item ID');
$GLOBALS['TL_LANG']['tl_c4g_maps']['delete'] = array('Delete map structure item', 'Delete map structure item ID %s');
$GLOBALS['TL_LANG']['tl_c4g_maps']['toggle'] = array('Publish/unpublish map structure item', 'Publish/unpublish map structure item ID %s');
$GLOBALS['TL_LANG']['tl_c4g_maps']['show']   = array('Details', 'Show details of map structure item ID %s');

/**
 * Misc
 */
$GLOBALS['TL_LANG']['tl_c4g_maps']['default_profile'] = 'Internal default (OpenStreetMap)';

/**
 * References
 */
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['CENTERZOOM'] = 'Provide center und zoomlevel';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['LOCATIONS'] = 'Show all locations';

$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['none']      = 'No location';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['single']    = 'Single GEO coordinate';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['table']     = 'Other table';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['gpx']       = 'GPX';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['kml']       = 'KML';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['geojson']   = 'GeoJSON';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['osm']   	  = 'OSM';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['overpass']  = 'Overpass API request (OSM)';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['link']      = 'Link to other map structure item';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['c4gForum']  = 'Forums from con4gis-Forum';

$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['OFF']       = 'No display';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['1ST']       = 'Show one item';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['1ST_MORE']  = 'Show one item and (...) if there are more';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['1ST_COUNT'] = 'Show one item and (Count) if there are more';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['ALL']       = 'Show all items';

$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['MERC']      = 'Spherical Mercator (EPSG:900913/EPSG:3857)';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['WGS84']     = 'WGS-84 (EPSG:4326)';

$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['NO']       = 'Don\'t move';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['THREAD']   = 'Move where layername=thread title';

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_maps']['general_legend']		= 'General';
$GLOBALS['TL_LANG']['tl_c4g_maps']['map_legend']		    = 'Map';
$GLOBALS['TL_LANG']['tl_c4g_maps']['location_legend']	    = 'Location';
$GLOBALS['TL_LANG']['tl_c4g_maps']['protection_legend']	    = 'Access protection';
$GLOBALS['TL_LANG']['tl_c4g_maps']['expert_legend']	    	= 'Expert';
?>