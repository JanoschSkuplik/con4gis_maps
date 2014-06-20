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
$GLOBALS['TL_LANG']['tl_c4g_maps']['name'] = array('Name', 'Name der Karte bzw. der Lokation');
$GLOBALS['TL_LANG']['tl_c4g_maps']['profile'] = array('Kartenprofil', 'Wählen Sie hier bitte ein Kartenprofil, das die Darstellung bestimmt. Kartenprofile werden über den Menüpunkt "Kartenprofile" unter "Layout" gepfegt. Falls Sie kein eigenes Kartenprofil wählen, dann wird standardmäßig mit OpenStreetMaps Mapnik Karten gearbeitet.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['profile_mobile'] = array('Kartenprofil mobil', 'Optional: Kartenprofil, das für mobile Endgeräte verwendet wird.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['published'] = array('Veröffentlicht', 'Legt fest, ob die Karte bzw. die Lokation veröffentlicht wird.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['is_map'] = array('Als Karte verwenden', 'Soll dieses Element eine im Frontend darstellbare Karte repräsentieren?');
$GLOBALS['TL_LANG']['tl_c4g_maps']['mapsize'] = array('Größe der Karte (Breite, Höhe)', 'Geben Sie hier die Größe der Karte im Frontend ein.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_width'] = array('Rechten Kartenrand am Browser ausrichten', 'Wählen Sie diese Option, um den rechten Kartenrand am Browserfenster auszurichten.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_width_gap'] = array('Abstand zum rechten Rand (Pixel)', 'Geben Sie den Abstand des rechten Randes des Browserfensters zum rechten Rand der Karte in Pixeln ein.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_width_min'] = array('Mindestbreite (Pixel)', 'Geben Sie die Mindestbreite der Karte ein, die nicht unterschritten werden darf.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_width_max'] = array('Maximale Breite (Pixel)', 'Geben Sie die maximale Breite der Karte ein, die nicht überschritten werden darf.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_height'] = array('Unteren Kartenrand am Browser ausrichten', 'Wählen Sie diese Option, um den unteren Kartenrand am Browserfenster auszurichten.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_height_gap'] = array('Abstand zum unteren Rand (Pixel)', 'Geben Sie den Abstand des unteren Randes des Browserfensters zum unteren Rand der Karte in Pixeln ein.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_height_min'] = array('Mindesthöhe (Pixel)', 'Geben Sie die Mindesthöhe der Karte ein, die nicht unterschritten werden darf.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_height_max'] = array('Maximale Höhe (Pixel)', 'Geben Sie die maximale Höhe der Karte ein, die nicht überschritten werden darf.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['calc_extent'] = array('Ermittlung des Kartenausschnitts', 'Geben Sie hier die Methode ein, nach der der anzuzeigende Kartenausschnitt ermittelt wird.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['min_gap'] = array('Mindestabstand der Lokationen zum Kartenrand in Pixeln', 'Diese Einstellung ist sinnvoll, wenn nach der automatischen Ermittlung des anzuzeigenden Kartenausschnitts Ihre Icons aufgrund ihrer Größe über den Kartenrand hinausgehen.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['center_geox'] = array('Geo X-Koordinate', 'Geben Sie hier die X-Koordinate (Breitengrad, WGS-84) der Kartenmitte ein.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['center_geoy'] = array('Geo Y-Koordinate', 'Geben Sie hier die Y-Koordinate (Längengrad, WGS-84) der Kartenmitte ein.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['zoom'] = array('Zoom-Level', 'Geben Sie hier den Zoom-Level der Karte ein.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['restrict_area'] = array('Kartenausschnitt einschränken', 'Mit dieser Option können Sie setzen, aus welchem "Rechteck" der Nutzer nicht herausnavigieren können soll. Außerdem wird bei einem Klick auf das Weltkugel-Symbol des Zoom-Steuerelements auf die hier angegebenen Koordinaten gezoomt.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['restr_bottomleft_geox'] = array('Geo X-Koordinate links', 'Geo X-Koordinate (Breitengrad, WGS-84) der linken, unteren Ecke für den eingeschränkten Navigationsbereich.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['restr_bottomleft_geoy'] = array('Geo Y-Koordinate unten', 'Geo Y-Koordinate (Längengrad, WGS-84) der linken, unteren Ecke für den eingeschränkten Navigationsbereich.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['restr_topright_geox'] = array('Geo X-Koordinate rechts', 'Geo X-Koordinate (Breitengrad, WGS-84) der rechten, oberen Ecke für den eingeschränkten Navigationsbereich.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['restr_topright_geoy'] = array('Geo Y-Koordinate oben', 'Geo Y-Koordinate (Längengrad, WGS-84) der rechten, oberen Ecke für den eingeschränkten Navigationsbereich.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['location_type'] = array('Lokationstyp', 'Legen Sie fest, welche Art von Lokation bzw. Lokationen dieses Element repräsentieren soll. Es ist möglich, im Baum beliebig viele Lokationselemente pro Karte als Kindelement zu definieren.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_geox'] = array('Geo X-Koordinate', 'Geben Sie hier die X-Koordinate (Breitengrad, WGS-84) der Lokation ein.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_geoy'] = array('Geo Y-Koordinate', 'Geben Sie hier die Y-Koordinate (Längengrad, WGS-84) der Lokation ein.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['locstyle'] = array('Lokationsstil', 'Über den Lokationsstil, der beim Kartenprofil definiert wird, legen Sie das Aussehen der Lokation fest. Ist keine Lokationsstil definiert, dann wird standardmäßig ein roter Punkt gezeichnet. ');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_only_in_parent'] = array('Lokation nur in übergeordneten Karten anzeigen', 'Wenn Sie diese Checkbox NICHT setzen, dann wird diese Lokation auch angezeigt, sobald Sie dieses Element als Karte darstellen lassen.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_label'] = array('Label', 'Geben Sie hier ein Label ein, das auf der Karte bei der Lokation mit angezeigt wird.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tooltip'] = array('Tooltip für Icons', 'Nur für Icons! Kurze Information, die angezeigt wird, wenn der Mauszeiger über der Lokation für eine kurze Zeit stehen bleibt.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['popup_info'] = array('Popup Information', 'Wird eine Popup-Information gesetzt, so wird sie durch einen Mausklick oder via Hover (abhängig von der Einstellung im Kartenprofil) auf der Lokation angezeigt.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['popup_extend'] = array('Popup Information durch Forenbeitrag erweitern', 'Ermöglicht es zusätzliche Popup-Information aus einem Forenbeitrag zu generieren.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['routing_to'] = array('Routenberechnung im Popup anbieten', 'Fügt einen Link mit dem Text "Route hierhin" in das Popup ein. Funkioniert nur, wenn im Kartenprofil die Routenberechnung aktiviert wurde.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_linkurl'] = array('Direktverlinkung', 'Geben Sie eine URL ein (mit http://) oder wählen Sie über das Symbol eine Contao-Seite aus. Wird eine Direktverlinkung angegeben, so wird sie über Mausklick oder Maus-Doppelklick (abhängig von der Einstellung im Kartenprofil) angesprungen.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_onclick_zoomto'] = array('Zoomen bei Mausklick auf Zoomstufe', 'Geben Sie die Zoomstufe ein, auf die bei Mausklick gezoomt werden soll. Funktioniert nur, wenn keine Direktverlinkung angegeben ist.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_minzoom'] = array('Mindest-Zoomstufe', 'Geben Sie die Zoomstufe ein, ab der die Elemente des Karteneintrags angezeigt werden. 0=keine Einschränkung.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_maxzoom'] = array('Maximale Zoomstufe', 'Geben Sie die Zoomstufe ein, bis zu der die Elemente des Karteneintrags angezeigt werden. 0=keine Einschränkung.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_source'] = array('Quelltabelle', 'Wählen Sie die Quelltabelle aus, aus der die anzuzeigenden Lokationen geladen werden. Sie können eigene Tabellen hinzuprogrammieren, siehe CONFIG.PHP.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_pid'] = array('Eintrag aus Elterntabelle', 'Falls eine Elterntabelle vorhanden ist, wählen Sie hier den Eintrag der Elterntabelle aus, auf den die Auswahl eingeschränkt werden soll.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_labeldisplay'] = array('Anzeige von Labels', 'Geben Sie hier an, ob das Label angezeigt werden soll, und wie das Label zusammengesetzt sein soll, wenn in der Tabelle mehrere Datensätze für die gleichen Koordinaten gefunden werden.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_tooltipdisplay'] = array('Anzeige von Tooltips', 'Geben Sie hier an, ob das Tooltip angezeigt werden soll, und wie das Tooltip zusammengesetzt sein soll, wenn in der Tabelle mehrere Datensätze für die gleichen Koordinaten gefunden werden.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_directlink'] = array('Direktlink generieren', 'Wenn Sie dieses Häkchen setzen, dann kann der Nutzer mit einem Mausklick oder Maus-Doppelklick (Einstellung im Kartenprofil) direkt zum mit der Lokation verknüpften Inhalt springen.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_force_target_blank'] = array('Popup-Links immer in neuem Fenster öffnen', 'Mit dieser Einstellung werden Links innerhalb eines PopUps immer in einem neuen Fenster, oder Tab (das ist abhängig von der Browsereinstellung des Benutzers), geöffnet.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_whereclause'] = array('Bedingungen', 'Geben Sie hier zusätzliche Bedingungen ein, die an die WHERE-Klausel des generierten SQL-Statements angehängt werden sollen.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_orderby'] = array('Sortierung', 'Geben Sie hier zusätzliche MySQL Sortierregeln ein, um die Reihenfolge der Elemente zu beeinflussen.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_filter_alias'] = array('Nur aktuellen Eintrag anzeigen', 'Wenn Sie diesen Eintrag setzen, dann wird nur der Eintrag in der Tabelle angezeigt, bei dem der Inhalt des Alias-Feldes der ID der aktuellen Seite entspricht. So können Sie beispielsweise erreichen, dass beim Eventleser eine Karte nur mit dem aktuellen Event angezeigt wird.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_file'] = array('Datei', 'Falls Ihre Daten in einer Datei auf dem Server liegen, dann geben Sie diese hier an.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_url'] = array('URL', 'Falls Ihre Daten von einer URL heruntergeladen werden sollen, dann geben Sie diese hier an.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_content'] = array('Daten', 'Falls Sie Ihre Daten direkt in die Datenbank speichern möchten, dann geben Sie diese hier ein.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_projection'] = array('Koordinatensystem');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_forcenodes'] = array('Flächen und Wege in Knoten umwandeln', 'Generiert aus allen Flächen und Wegen in den OSM-Daten Knoten. Das ist oft sinnvoll, wenn die generierten Daten Gebäudeumrisse o.ä. enthalten.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['ovp_request'] = array('Anfrage an Overpass API (XML-Format)', 'Diese Anfrage wird via AJAX browserseitig über einen Proxy (C4GOverpass.php) an die Overpass API geschickt. Doku zum Format: http://www.overpass-api.de.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['ovp_bbox_limited'] = array('Anfrage an Overpass API auf den angezeigten Kartenbereich (BBOX) beschränken.', 'Sendet die Anfrage immer, wenn sich der Kartenausschnitt durch Benutzereingaben ändert. In der Anfrage muss an geeigneter Stelle der Platzhalter "(bbox)" eingetragen sein. Dieser wird dann durch ein "bbox-query"-Tag ersetzt.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_layername'] = array('Name der Ebene', 'Falls Sie die Daten dieser Ebene im Starboard schaltbar machen möchten, dann geben Sie hier den Namen ein, der im Starboard angezeigt werden soll.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_hidelayer'] = array('Ebene standardmäßig ausblenden', 'Falls diese Ebene beim Einstieg in die Karte nicht angezeigt werden soll, dann setzen Sie diese Checkbox. Die Ebene ist dann nur über das Starboard auswählbar.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['data_js_style_function'] = array('Javascript-Funktion zum Stylen', 'Ermöglicht das Setzen von Stil-Einstellungen für jedes einzelne Feature mit Hilfe von Javascipt.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['forums'] = array('Diskussionsforen','Wählen Sie aus den Diskussionsforen, in denen Kartenlokationen definiert werden können, diejenigen aus, die Sie berücksichtigen möchten.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['forum_jumpto'] = array('Weiterleitungsseite mit Forum','Wählen Sie hier die Seite aus, die Ihr con4gis-Forum-Frontendmodul enthält, falls Sie Links auf Themen und Beiträge im Forum aktiviert haben.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['forum_reassign_layer'] = array('Forum - in andere Ebene verschieben','Wählen Sie eine Methode, um bestimmte Foreneinträge in andere, bereits definierte Ebenen zu verschieben.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['geolocation'] = array('Aktuelle Position ermitteln','Wenn die Karte geöffnet wird, wird versucht, über die HTML5 Geolocation API vom Browser die aktuellen Geokoordinaten zu ermitteln.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['geolocation_zoom'] = array('Aktuelle Position - Zoomlevel','Konnte vom Browser die aktuelle Position ermittelt werden, dann zoomt die Karte automatisch zu den erhaltenen Koordinaten mit dem angegebenen Zoomlevel');
$GLOBALS['TL_LANG']['tl_c4g_maps']['include_sublocations'] = array('Hierarchisch untergeordnete Kartenstrukturelemente berücksichtigen', 'Bei Deaktivierung dieser Einstellung werden nur Elemente der ersten Ebene auf der Karte dargestellt.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['link_id'] = array('Kartenstrukturelement', 'Zu verknüpfendes Kartenstrukturelement.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['protect_element'] = array('Diesen Eintrag schützen', 'Macht diesen Eintrag nur für ausgewählte Gruppen sichtbar.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['permitted_groups'] = array('Berechtigte Gruppen', 'Die Gruppen, für die dieser Eintrag sichtbar ist.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['use_specialprofile'] = array('Spezialprofil aktivieren', 'Aktiviert ein Spezialprofil, welches für die ausgewählten Gruppen gilt.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['specialprofile'] = array('Spezialprofil', 'Das Profil, was für die ausgewählten Gruppen aktiviert wird.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['specialprofile_mobile'] = array('Spezialprofil mobil', 'Optional: Spezialprofil, das für mobile Endgeräte verwendet wird.');
$GLOBALS['TL_LANG']['tl_c4g_maps']['specialprofile_groups'] = array('Betroffene Gruppen', 'Die Gruppen, für die das Spezialprofil aktiviert wird.');

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_maps']['new']    = array('Neues Kartenstrukturelement', 'Ein neues Kartenstrukturelement erstellen');
$GLOBALS['TL_LANG']['tl_c4g_maps']['edit']   = array('Kartenstrukturelement bearbeiten', 'Kartenstrukturelement ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_c4g_maps']['copy']   = array('Kartenstrukturelement duplizieren', 'Kartenstrukturelement ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_c4g_maps']['copyChilds']   = array('Kartenstrukturelement inklusive Unterelemente duplizieren', 'Kartenstrukturelement ID %s inklusive Unterelemente duplizieren');
$GLOBALS['TL_LANG']['tl_c4g_maps']['cut']    = array('Kartenstrukturelement verschieben', 'Kartenstrukturelement ID %s verschieben');
$GLOBALS['TL_LANG']['tl_c4g_maps']['delete'] = array('Kartenstrukturelement löschen', 'Kartenstrukturelement ID %s löschen');
$GLOBALS['TL_LANG']['tl_c4g_maps']['toggle'] = array('Kartenstrukturelement veröffentlichen/unveröffentlichen', 'Kartenstrukturelement ID %s veröffentlichen/unveröffentlichen');
$GLOBALS['TL_LANG']['tl_c4g_maps']['show']   = array('Details', 'Die Details des Kartenstrukturelements ID %s anzeigen');

/**
 * Misc
 */
$GLOBALS['TL_LANG']['tl_c4g_maps']['default_profile'] = 'Internes Standardprofil (OpenStreetMap)';

/**
 * References
 */
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['CENTERZOOM'] = 'Angabe von Center und Zoom-Level';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['LOCATIONS'] = 'Alle Lokationen sollen sichtbar sein';

$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['none']      = 'Keine Lokation';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['single']    = 'Einzelne Geo-Koordinate';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['table']     = 'Aus anderer Tabelle';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['gpx']       = 'Daten im GPX-Format';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['kml']       = 'Daten im KML-Format';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['geojson']   = 'Daten im GeoJSON-Format';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['osm']   	  = 'Daten im OSM-Format';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['overpass']  = 'Anfrage an Overpass API (OSM)';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['link']      = 'Verknüpfung zu anderem Kartenstrukturelement';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['c4gForum']  = 'Forenbereiche aus con4gis-Forum';

$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['OFF']       = 'Nicht anzeigen';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['1ST']       = 'Zeige einen Eintrag';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['1ST_MORE']  = 'Zeige einen Eintrag und (...) bei mehreren Einträgen';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['1ST_COUNT'] = 'Zeige einen Eintrag und (Anzahl) bei mehreren Einträgen';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['ALL']       = 'Zeige alle Einträge';

$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['MERC']      = 'Spherical Mercator (EPSG:900913/EPSG:3857)';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['WGS84']     = 'WGS-84 (EPSG:4326)';

$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['NO']       = 'Nicht verschieben';
$GLOBALS['TL_LANG']['tl_c4g_maps']['references']['THREAD']   = 'Verschieben, wenn der Themenname einem Ebenennamen entspricht';

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_maps']['general_legend']		= 'Allgemeine Daten';
$GLOBALS['TL_LANG']['tl_c4g_maps']['map_legend']		    = 'Karte';
$GLOBALS['TL_LANG']['tl_c4g_maps']['location_legend']	    = 'Lokation';
$GLOBALS['TL_LANG']['tl_c4g_maps']['protection_legend']	    = 'Zugriffsschutz';
$GLOBALS['TL_LANG']['tl_c4g_maps']['expert_legend']	    	= 'Experteneinstellungen';
?>