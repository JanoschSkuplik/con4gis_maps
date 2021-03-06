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
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['name']             = array('Name', 'Please enter a name for the location style.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['styletype']        = array('Style type','Select the style type to be used.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['strokecolor']      = array('Stroke color','Please define a stroke color.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['strokewidth']      = array('Stroke width','Choose the width of the stroke.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['strokeopacity']    = array('Stroke opacity','Enter the opacity of the stroke in %.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['fillcolor']        = array('Fill color','Choose the fill color.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['fillopacity']      = array('Fill opacity','Enter the fill opacity in % here.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['radius']           = array('Radius','Enter the radius in pixel.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['ol_icon']          = array('OpenLayers Icon','Select from the internal OpenLayers icons.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['ol_icon_size']     = array('Icon size','Size of the icon. Is scaled when needed.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['ol_icon_offset']   = array('Offset for icon in pixel (X/Y)','An offset of "0" means, the upper left corner is printed on the location. X>0 => shifts icon to the right. X<0 => to the left. Y>0 => to the bottom. Y<0 => to the top.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['icon_src']         = array('Icon source','Select the icon to be displayed for the location from the list.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['icon_size']        = array('Icon size','Size of the icon. Is scaled when needed.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['icon_offset']      = array('Offset for icon in pixel (X/Y)','An offset of "0" means, the upper left corner is printed on the location. X>0 => shifts icon to the right. X<0 => to the left. Y>0 => to the bottom. Y<0 => to the top.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['icon_opacity']     = array('Icon opacity','Enter the opacity of the icon in %.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['onhover_locstyle'] = array('Location style for mouse hover','Select the location style to be used when the mouse rests over a location.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['line_arrows']      = array('Activate line direction arrows','Arrows for lines');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['line_arrows_back'] = array('Show back arrows','To display arrows in both directions.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['line_arrows_radius']= array('Radius of arrow (pixel)','Arrows are displayed as triangle symbols. Enter the radius in pixel.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['line_arrows_minzoom'] = array('Min. zoomlevel','Shows arrows only when the zoomlevel is at least the entered value. 0=show always.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label'] 		 	 = array('Label', 'Label to be displayed on map together with the location marker. Overrides label defined at map structure items.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label_align_hor']  = array('Horizontal alignment ','Define the horizontal alignment of the label relative to the location.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label_align_ver']  = array('Vertical alignment ','Define the vertical alignment of the label relative to the location.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label_offset']     = array('Offset for label in pixel (X/Y)','Please enter the offset of the label in pixel.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['font_family']      = array('Font family','The font family for the label, to be provided like in CSS.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['font_color']       = array('Font color','The font color for the label.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['font_size']        = array('Font size','The font size for the label, to be provided like in CSS.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label_outl_color'] = array('Label outline color','Enter a color for the outline of the label, if desired.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label_outl_width'] = array('Label outline width','Enter a width for the outline of the label, if desired.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['font_opacity']     = array('Font opacity','The font opacity for the label in %.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['font_style']       = array('Font style','The font style for the label, to be provided like in CSS.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['font_weight']      = array('Font weight','The font weight for the label, to be provided like in CSS.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['tooltip'] 		 = array('Tooltip for icons', 'Tooltip to be displayed when mouse pointer rests on the location for a while. Overrides tooltip defined at map structure items.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['popup_info'] 		 = array('Popup information', 'Information to be displayed in a popup after clicking with the mouse on a location or via mouse hover (depending on the map profile settings). Overrides popup defined at map structure items.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['popup_kind']       = array('Popup kind','Please choose which popup kind is to be used.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['popup_size']       = array('Popup size','Please enter the size of a popup.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['popup_offset']     = array('Offset for popup in pixel (X/Y)','An offset of "0" means, the upper left corner is printed on the location. X>0 => shifts popup to the right. X<0 => to the left. Y>0 => to the bottom. Y<0 => to the top.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['onclick_zoomto'] 	 = array('On mouse click zoom to zoom level', 'Enter a zoom level, to which is zoomed when an item has been clicked. Works only when there is no direct link. Overrides setting defined at map structure items.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['minzoom'] 		 = array('Min. zoom level', 'Enter the minimum zoom level, on which the items are shown. 0 means no restriction. Overrides setting defined at map structure items.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['maxzoom'] 		 = array('Max. zoom level', 'Enter the maximum zoom level, on which the items are shown. 0 means no restriction. Overrides setting defined at map structure items.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['editor_icon'] 	 = array('Editor icon','Icon used for add functionality in the editor (replaces default icon).');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['editor_sort']      = array('Sortorder','Sortorder in editor for this locationstyle.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['editor_vars']      = array('Additional fields','Fields available for geometries of this location style. Can be addressed using ${key} in popup, label and tooltip.');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['editor_collect'] 	 = array('Create geometry collection','Puts all geometries of this location type that are edited together into a single geometry collection.');

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['point']       = 'Point';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['square']      = 'Square';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['star']        = 'Star';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['x']           = 'X';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['cross']       = 'Cross';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['triangle']    = 'Triangle';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['ol_icon']     = 'OpenLayers internal icon';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['cust_icon']   = 'Custom icon';

$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['marker.png']        = 'Red marker';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['marker-blue.png']   = 'Blue marker';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['marker-gold.png']   = 'Golden marker';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['marker-green.png']  = 'Green marker';

$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['left']              = 'Location to the left of label';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['center']            = 'Center';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['right']             = 'Location to the right of label';

$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['top']               = 'Location above label';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['middle']            = 'Middle';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['bottom']            = 'Location below label';

$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['bubble']            = 'Bubble';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references']['cloud']             = 'Cloud';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['new']        = array('New location style', 'Create a new location style');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['edit']       = array('Edit location style', 'Edit location style ID %s');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['copy']       = array('Duplicate location style', 'Duplicate location style ID %s');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['delete']     = array('Delete location style', 'Delete location style ID %s');
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['show']       = array('Details', 'Show details of location style ID %s');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['arrow_legend'] 	= 'Line direction arrows';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label_legend'] 	= 'Label settings';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['popup_legend'] 	= 'Popup settings';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['zoom_legend']  	= 'Zoom settings';
$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['editor_legend']	= 'Editor settings (GeoJSON and con4gis-Forum)';

/**
 * Globals
 */
$GLOBALS['TL_LANG']['MSC']['ow_value'] = 'Editor label';

?>