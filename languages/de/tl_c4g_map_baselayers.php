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
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['name']           			= array('Name', 'Bitte geben Sie den Namen der Basiskarte an.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['display_name']   			= array('Name im Starboard', 'Falls abweichend vom Feld "Name".');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['sort']   		   			= array('Sortierung', 'Geben Sie eine Sortierung ein, falls Sie die definierten Basiskarten im Starboard in einer selbstdefinierten Reihenfolge anzeigen möchten.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider']       			= array('Anbieter', 'Bitte wählen Sie den Anbieter der Basiskarte aus.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style']      			= array('OSM Kartenstil', 'Bitte wählen Sie den Kartenstil von OpenStreetMap aus. Beachten Sie bitte die geltenden Nutzungsbedingungen, der Anbieter.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_url1'] 			= array('OSM URL 1', 'Bitte geben Sie die 1. URL des OpenStreetMap-Kartenstils ein.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_url2'] 			= array('OSM URL 2', 'Bitte geben Sie die 2. URL des OpenStreetMap-Kartenstils ein.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_url3'] 			= array('OSM URL 3', 'Bitte geben Sie die 3. URL des OpenStreetMap-Kartenstils ein.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_url4'] 			= array('OSM URL 4', 'Bitte geben Sie die 4. URL des OpenStreetMap-Kartenstils ein.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_zoomlevels']  	= array('OSM Zoom-Stufen', 'Bitte geben Sie die Anzahl der verfügbaren Zoom-Stufen des OpenStreetMap-Kartenstils an (optional).');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_keyname']    			= array('OSM Keyname', 'Bitte geben Sie den internen OSM Keyname an (optional).');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['bing_style']     			= array('Bing Maps Kartenstil', 'Bitte wählen Sie den Kartenstil von Bing Maps aus.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['bing_key']       			= array('Bing Applikationsschlüssel (Key)', 'Bitte geben Sie hier ihren Bing Applikationsschlüssel (Key) ein. Falls Sie noch keinen besitzen, dann generieren Sie ihn sich auf http://bingmapsportal.com/.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['google_style']   			= array('Google Maps Kartenstil', 'Bitte wählen Sie den Kartenstil von Google Maps aus.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['attribution']    			= array('Benutzerdefinierte Attribution', 'Hier können Sie, wenn sinnvoll, eine von dem Standardwert abweichende Attribution eingeben (optional).');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['maxzoomlevel']   			= array('Benutzerdefinierte maximale Zoomstufe', 'Hier können Sie eine vom Standardwert abweichende maximale Zoomstufe eingeben. 0=Standard');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['protect_baselayer'] 		= array('Diese Basiskarte schützen', 'Macht diese Basiskarte nur für ausgewählte Gruppen sichtbar.');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['permitted_groups'] 		= array('Berechtigte Gruppen', 'Die Gruppen, für die diese Basiskarte sichtbar ist.');

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm']    = 'OpenStreetMap';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google'] = 'Google Maps';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_bing']   = 'Bing Maps';

$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_mapnik'] 			= 'Mapnik';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_osma']   			= 'Osmarender';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_cycle']  			= 'Fahrradkarte (opencyclemap.org)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_german'] 			= 'Deutschland Stil (openstreetmap.de)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_german_transport']  	= 'ÖPNV Karte (memomaps.de)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_transport']  		= 'ÖPNV Karte (opencyclemap.org)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_landscape']  		= 'Geländekarte (opencyclemap.org)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_mapquestopen']  		= 'MapQuest Open (mapquest.com)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_toner'] 				= 'Schwarz-Weiß (maps.stamen.com)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_tonerlines'] 		= 'Schwarz-Weiß, nur Linien (maps.stamen.com)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_tonerlabels'] 		= 'Schwarz-Weiß, nur Beschriftung (maps.stamen.com)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_watercolor'] 		= 'Wasserfarben (maps.stamen.com)';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_custom'] 			= 'Benutzerdefiniert';

$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google_streets']   = 'Straßenkarte';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google_hybrid']    = 'Hybridkarte';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google_satellite'] = 'Satellitenkarte';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google_terrain']   = 'Geländekarte';

$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_bing_road']        = 'Straßenkarte';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_bing_hybrid']      = 'Hybridkarte';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_bing_aerial']      = 'Luftbild';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['new']        = array('Neue Basiskarte', 'Eine neue Basiskarte erstellen');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['edit']       = array('Basiskarte bearbeiten', 'Basiskarte ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['copy']       = array('Basiskarte duplizieren', 'Basiskarte ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['delete']     = array('Basiskarte löschen', 'Basiskarte ID %s löschen');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['show']       = array('Details', 'Die Details der Basiskarte ID %s anzeigen');
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['overlays']   = array('Overlaykarten', 'Overlaykarten der Basiskarte ID %s verwalten');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['general_legend']		= 'Allgemeine Daten';
$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['protection_legend']	= 'Zugriffsschutz';
?>