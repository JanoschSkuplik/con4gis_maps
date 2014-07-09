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
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['name']             = array('Name', 'Bitte geben Sie den Namen des Lokationsstils an.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['styletype']        = array('Darstellung als','Bitte wählen Sie die Darstellungsart der Lokation.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['strokecolor']      = array('Farbe der Linie','Bitte wählen Sie die Farbe der Linie.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['strokewidth']      = array('Breite der Linie','Bitte wählen Sie die Breite der Linie.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['strokeopacity']    = array('Deckkraft der Linie','Bitte wählen Sie die Deckkraft der Linie.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['fillcolor']        = array('Farbe der Füllung','Bitte wählen Sie die Farbe der Füllung.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['fillopacity']      = array('Deckkraft der Füllung','Bitte wählen Sie die Deckkraft der Füllung.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['radius']           = array('Radius','Bitte wählen Sie den Radius in Pixeln.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['ol_icon']          = array('OpenLayers Icon','Bitte wählen Sie aus den verfügbaren OpenLayers Standardicons aus.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['ol_icon_size']     = array('Größe des Icons','Die Größe des Icons auf der Karte. Wird gegebenenfalls skaliert.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['ol_icon_offset']   = array('Positionsversatz des Icons (X/Y) in Pixel','Bei einem Versatz von "0" befindet sich die obere linke Ecke des Icons an der angegebenen Geo-Lokation. X=Positiv => nach rechts. X=negativ => nach links. Y=positiv => nach unten. Y=negativ => nach oben.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['icon_src']         = array('Icon Quelle','Wählen Sie bitte hier das anzuzeigende Icon aus.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['icon_size']        = array('Größe des Icons','Die Größe des Icons auf der Karte. Wird gegebenenfalls skaliert.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['icon_offset']      = array('Positionsversatz des Icons (X/Y) in Pixel','Bei einem Versatz von "0" befindet sich die obere linke Ecke des Icons an der angegebenen Geo-Lokation. X=Positiv => nach rechts. X=negativ => nach links. Y=positiv => nach unten. Y=negativ => nach oben.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['icon_opacity']     = array('Deckkraft des Icons','Bitte wählen Sie die Deckkraft des Icons in % aus.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['onhover_locstyle'] = array('Lokationsstil bei Maus-Hover','Wählen Sie einen Lokationsstil aus, der eingestellt wird, während sich der Maus-Cursor über der Lokation befindet.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['line_arrows']      = array('Richtungspfeile','Stellen Sie ein, ob bei der Darstellung von Strecken Richtungspfeile angezeigt werden sollen.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['label'] 			 = array('Label', 'Geben Sie hier ein Label ein, das auf der Karte bei der Lokation mit angezeigt wird. Überschreibt die Einstellung am Kartenelement!');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['label_align_hor']  = array('Horizontale Ausrichtung ','Bitte definieren Sie, wie sich das Label relativ zum Lokation horizontal ausrichten soll.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['label_align_ver']  = array('Vertikale Ausrichtung','Bitte definieren Sie, wie sich das Label relativ zum Lokation vertikal ausrichten soll.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['label_offset']     = array('Positionsversatz des Labels (X/Y)','Verschiebt das Label um die angegebene Anzahl von Pixeln.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['font_family']      = array('Schriftart','Geben Sie die für das Label zu verwendende Schriftart an (Notierung wie in CSS).');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['font_color']       = array('Schriftfarbe','Wählen Sie die für die Schrift des Labels zu verwendende Farbe.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['font_size']        = array('Schriftgröße','Wählen Sie die für die Schrift des Labels zu verwendende Schriftgröße.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['label_outl_color'] = array('Farbe Schriftrand','Falls die Schrift Ihres Labels einen Rand bekommen sollen, dann tragen Sie hier die Randfarbe ein.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['label_outl_width'] = array('Breite Schriftrand','Falls die Schrift Ihres Labels einen Rand bekommen sollen, dann tragen Sie hier die Breite des Randes ein.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['font_opacity']     = array('Deckkraft der Schrift','Wählen Sie die für die Schrift des Labels zu verwendende Deckkraft.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['font_style']       = array('Schriftstil','Wählen Sie den für die Schrift des Labels zu verwendenden Stil (Notierung wie in CSS).');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['font_weight']      = array('Schriftgewicht','Wählen Sie das für die Schrift des Labels zu verwendende Gewicht (Notierung wie in CSS).');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['tooltip'] 		 = array('Tooltip für Icons', 'Kurze Information, die angezeigt wird, wenn der Mauszeiger über der Lokation für eine kurze Zeit stehen bleibt. Überschreibt die Einstellung am Kartenelement!');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['popup_info'] 		 = array('Popup Information', 'Wird eine Popup-Information gesetzt, so wird sie durch einen Mausklick oder via Hover (abhängig von der Einstellung im Kartenprofil) auf der Lokation angezeigt. Überschreibt die Einstellung am Kartenelement!');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['popup_kind']       = array('Art des Popups','Wählen Sie aus, welches Erscheinen das Popup haben soll, das beim Klick auf eine Lokation mit Popup-Informationen erscheinen soll.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['popup_size']       = array('Größe des Popups','Die Größe des Popups. Wird falls notwendig verkleinert.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['popup_offset']     = array('Positionsversatz des Popups (X/Y) in Pixel','Bei einem Versatz von "0" befindet sich die obere linke Ecke des Popups an der angegebenen Geo-Lokation. X=Positiv => nach rechts. X=negativ => nach links. Y=positiv => nach unten. Y=negativ => nach oben.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['onclick_zoomto'] 	 = array('Zoomen bei Mausklick auf Zoomstufe', 'Geben Sie die Zoomstufe ein, auf die bei Mausklick gezoomt werden soll. Funktioniert nur, wenn keine Direktverlinkung angegeben ist. Überschreibt die Einstellung am Kartenelement!');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['minzoom'] 		 = array('Mindest-Zoomstufe', 'Geben Sie die Zoomstufe ein, ab der die Elemente des Karteneintrags angezeigt werden. 0=keine Einschränkung. Überschreibt die Einstellung am Kartenelement!');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['maxzoom'] 		 = array('Maximale Zoomstufe', 'Geben Sie die Zoomstufe ein, bis zu der die Elemente des Karteneintrags angezeigt werden. 0=keine Einschränkung. Überschreibt die Einstellung am Kartenelement!');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['editor_icon'] 	 = array('Icon im Editor','Das Icon wird im Editor an Stelle des Standardicons verwendet.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['editor_vars'] 	 = array('Zusatzfelder','Zusatzfelder stehen im Editor zur Verfügung und können über ${Schlüssel} im Popup, Label und Tooltip in die Karte eingefügt werden.');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['editor_collect'] 	 = array('Geometrien zusammenfassen','Fasst alle Geometrien dieses Lokationsstils, die miteinander im Editor bearbeitet werden, zusammen.');

/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['point']       = 'Punkt';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['square']      = 'Quadrat';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['star']        = 'Stern';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['x']           = 'X';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['cross']       = 'Kreuz';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['triangle']    = 'Dreieck';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['ol_icon']     = 'OpenLayers Standardicon';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['cust_icon']   = 'Eigenes Icon';

$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['marker.png']        = 'Roter Marker';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['marker-blue.png']   = 'Blauer Marker';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['marker-gold.png']   = 'Goldener Marker';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['marker-green.png']  = 'Grüner Marker';

$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['left']              = 'Lokation links vom Label';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['center']            = 'Mittig';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['right']             = 'Lokation rechts vom Label';

$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['top']               = 'Lokation über dem Label';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['middle']            = 'Mittig';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['bottom']            = 'Lokation unter dem Label';

$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['bubble']            = 'Standard';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['references']['cloud']             = 'Sprechblase';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['new']        = array('Neuer Lokationsstil', 'Einen neuen Lokationsstil erstellen');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['edit']       = array('Lokationsstil bearbeiten', 'Lokationsstil ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['copy']       = array('Lokationsstil duplizieren', 'Lokationsstil ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['delete']     = array('Lokationsstil löschen', 'Lokationsstil ID %s löschen');
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['show']       = array('Details', 'Die Details des Lokationsstils ID %s anzeigen');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['label_legend'] = 'Einstellungen für Label';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['popup_legend'] = 'Einstellungen für Popups';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['zoom_legend']  = 'Zoomstufen';
$GLOBALS['TL_LANG']['tl_c4g_map_prof_locstyles']['editor_legend']= 'Einstellungen für Editor (GeoJSON und con4gis-Forum)';

/**
 * Globals
 */
$GLOBALS['TL_LANG']['MSC']['ow_value'] = 'Label im Editor';

?>