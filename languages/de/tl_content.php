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
$GLOBALS['TL_LANG']['tl_content']['c4g_map_id'] = array('Kartenstruktur', 'Wählen Sie die anzuzeigende Kartenstruktur aus.');
// $GLOBALS['TL_LANG']['tl_content']['c4g_map_mapsize'] = array('Größe der Karte', 'Geben Sie hier die Größe der Karte im Frontend ein, wenn Sie eine vom definierten Standardwert abweichende Größe definieren möchten.');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_width'] = array('Breite der Karte', 'Geben Sie hier die Breite der Karte im Frontend ein, wenn Sie eine vom definierten Standardwert abweichende Größe definieren möchten.');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_height'] = array('Höhe der Karte', 'Geben Sie hier die Höhe der Karte im Frontend ein, wenn Sie eine vom definierten Standardwert abweichende Größe definieren möchten.');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_zoom'] = array('Zoom-Level', 'Geben Sie hier den Zoom-Level der Karte ein, wenn Sie einen vom definierten Standardwert abweichenden Zoom-Level definieren möchten');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_default_mapservice'] = array('Standardbasiskarte', 'Wählen Sie hier eine der im Kartenprofil der verwendeten Karte zugewiesenen Basiskarten aus, die beim Öffnen der Karte verwendet werden soll. Ist keine Basiskarte ausgewählt, dann wird automatisch OpenStreetMap im Mapnik-Stil verwendet');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_layer_switcher'] = array('Starboard', 'Wenn Sie diese Checkbox auswählen, dann bekommt die Karte ein Starboard (erreichbar über ein Stern-Symbol rechts oben), das es ermöglicht, zwischen den im Kartenprofil definierten Basiskarten zu wechseln. Außerdem können im Starboard ggfs. in der Kartenstruktur definierte Ebenen ein- und ausgeschaltet werden.');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_layer_switcher_open'] = array('Starboard automatisch öffnen', 'Wenn Sie diese Checkbox auswählen, dann wird das Starboard sofort geöffnet angezeigt.');
$GLOBALS['TL_LANG']['tl_content']['c4g_map_layer_switcher_ext'] = array('Starboard - Ebenen im Baum darstellen', 'Wenn Sie diese Checkbox auswählen, dann werden die auswählbaren Ebenen im Baum dargestellt. Voraussetzung ist eine hierarchische Definition der Ebenen bei den Kartenstrukturelementen. Achtung! Es werden mehrere Javascript-Bibliotheken (u.a. jQuery) der Seite hinzugefügt.');

/**
 * Legend
 */
$GLOBALS['TL_LANG']['tl_content']['c4g_map_legend'] = 'Kartenkonfiguration';