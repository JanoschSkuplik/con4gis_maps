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
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['name'] 			= array('Name', 'Bitte geben Sie den Namen der Overlaykarte an.');
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['provider'] 		= array('Anbieter', 'Bitte wählen Sie den Anbieter der Overlaykarte aus.');
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['url1'] 			= array('URL 1', 'Bitte geben Sie die 1. URL der Kacheln der Overlaykarte im Format http://path.to/overlaytiles/${z}/${x}/${y}.ext" an.');
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['url2'] 			= array('URL 2', 'Bitte geben Sie die 2. URL der Kacheln der Overlaykarte im Format http://path.to/overlaytiles/${z}/${x}/${y}.ext" an.');
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['url3'] 			= array('URL 3', 'Bitte geben Sie die 3. URL der Kacheln der Overlaykarte im Format http://path.to/overlaytiles/${z}/${x}/${y}.ext" an.');
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['url4'] 			= array('URL 4', 'Bitte geben Sie die 4. URL der Kacheln der Overlaykarte im Format http://path.to/overlaytiles/${z}/${x}/${y}.ext" an.');
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['attribution']   = array('Benutzerdefinierte Attribution', 'Hier können Sie, wenn sinnvoll, eine von dem Standardwert abweichende Attribution eingeben (optional).');

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openseamap']  					= 'OpenSeaMap - Seezeichen';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openweathermap_data']  			= 'OpenWeatherMap - Wetterdaten';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openweathermap_stations']  		= 'OpenWeatherMap - Stationswerte';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openweathermap_clouds']  			= 'OpenWeatherMap - Wolken';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openweathermap_cloudsForecasts']  = 'OpenWeatherMap - Wolkenvorhersage';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openweathermap_precipitation']  	= 'OpenWeatherMap - Atmosphärischer Niederschlag';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openweathermap_precipitationForecasts']  = 'OpenWeatherMap - Niederschlagsvorhersage';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openweathermap_rain']    			= 'OpenWeatherMap - Regen';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openweathermap_wind']    			= 'OpenWeatherMap - Wind';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openweathermap_pressure']  		= 'OpenWeatherMap - Luftdruck';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openweathermap_temp']  			= 'OpenWeatherMap - Temperatur';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openweathermap_snow']  			= 'OpenWeatherMap - Schnee';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['openweathermap_radar']  			= 'OpenWeatherMap - Radar';
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references']['custom']     						= 'Benutzerdefiniert';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['new']        = array('Neue Overlaykarte', 'Eine neue Overlaykarte erstellen');
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['edit']       = array('Overlaykarte bearbeiten', 'Overlaykarte ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['copy']       = array('Overlaykarte duplizieren', 'Overlaykarte ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['delete']     = array('Overlaykarte löschen', 'Overlaykarte ID %s löschen');
$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['show']       = array('Details', 'Die Details der Overlaykarte ID %s anzeigen');

?>