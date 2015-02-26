<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014 - 2015
 * @link      https://www.kuestenschmiede.de
 */



/**
 * Fields
 */
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['name'] 					= array('Name',
																			'Bitte geben Sie den Namen des Kartenprofils an.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['is_default'] 			= array('Als Standardprofil verwenden',
																			'Setzen Sie diese Checkbox, um das Kartenprofil als Standardwert, bei neu angelegten Karteneinträgen, zu verwenden.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['theme'] 				= array('OpenLayers Theme',
																			'Wählen Sie eines der integrierten OpenLayers Themes aus.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['baselayers']  			= array('Basiskarten',
																			'Wählen Sie die Basiskarten, die in Karten dieses Profils im Starboard verfügbar sein sollen. Standard: alle');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['locstyles']  			= array('Lokationsstile',
																			'Wählen Sie die Lokationsstile, die in Kartenstrukturelementen dieses Profils auswählbar sein sollen. Standard: alle');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['pan_panel'] 			= array('Steuerelement zum Verschieben (Pan)',
																			'Vier Buttons zum Verschieben der Karte in den Himmelsrichtungen aktivieren.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['zoom_panel'] 			= array('Steuerelement zum Zoomen',
																			'Erzeugen von Buttons zum Hinein- und Herauszoomen. Die Mobil-Variante erzeugt größere Buttons.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['zoom_panel_world'] 		= array('Welt-Icon im Steuerelement zum Zoomen',
																			'Erzeugt ein Welt-Icon im Zoom-Steuerelement. Ein Klick auf das Icon zoomt, falls in der Karte "Kartenausschnitt einschränken" gewählt ist auf den eingeschränkten Bereich, ansonsten wird der minimale Zoomlevel der Karte gesetzt.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav'] 			= array('Navigation mit der Maus',
																			'Aktiviert die Möglichkeit, mit Hilfe der Maus zu zoomen und den angezeigten Kartenausschnitt zu verschieben.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav_wheel'] 		= array('Mausrad zum Zoomen benutzen',
																			'Setzen Sie diese Checkbox, um das Hinein- und Herauszoomen mit dem Mausrad zu aktivieren.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav_zoombox'] 	= array('Zoombox mit Shift+Maus',
																			'Aktiviert die Möglichkeit, mit Hilfe der Shift- und der linken Maustaste ein Rechteck aufzuziehen, um in die Karte hineinzuzoomen.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav_kinetic'] 	= array('Kinetisches Scrollen nach Verschieben mit der Maus',
																			'Wenn gesetzt, dann hört nach dem Verschieben des Kartenausschnitts mit der Maus die Scrollbewegung nicht sofort auf, sondern sie wird langsam verzögert, bis sie zum Stillstand kommt.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav_toolbar'] 	= array('Maus-Toolbar',
																			'Blendet zwei Buttons ein, mit deren Hilfe man zwischen dem Verschieben des Kartenausschnitts mit der Maus und dem Aufziehen eines Rechtecks zum Hineinzoomen in die Karte wechseln kann.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['keyboard_nav'] 			= array('Navigation mit der Tastatur',
																			'Aktiviert die Möglichkeit, mit Hilfe der Tastatur zu navigieren (Pfeiltasten zum Verschieben, Zoomen mit den Tasten "+" und "-").');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['nav_history'] 			= array('Navigations-Historie',
																			'Erzeugt einen Vor- und einen Zurück-Schalter, vergleichbar mit denen eines Internet-Browsers.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['fullscreen'] 			= array('Vollbildmodus',
																			'Blendet einen Schalter ein, mit dem man in den Vollbildmodus des Browsers wechseln kann (HTML5). Achtung: wird nicht von jedem Browser unterstützt!');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['measuretool'] 			= array('Messwerkzeug',
																			'Blendet einen Schalter ein, der ein Werkzeug zum Messen von Entfernungen/Gebieten aktiviert.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['graticule'] 			= array('Geografisches Netz',
																			'Blendet einen Schalter ein, welcher ein Geografisches Netz anzeigt, das mit Hilfe von horizontalen und vertikalen Linien die Längen- und Breitengrade visualisiert.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor'] 				= array('Editor',
																			'Blendet einen Schalter ein, der den Editor auf der Karte anzeigt, mit dem Punkte, Strecken und Flächen auf die Karte gezeichnet werden können.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['overviewmap'] 			= array('Übersichtskarte',
																			'Schafft die Möglichkeit, über einen Schalter, rechts unten auf der Karte, eine Umgebungskarte einzublenden.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['scaleline'] 			= array('Maßstabsleiste',
																			'Zeigt eine Leiste, die den aktuellen Kartenmaßstab visuell darstellt.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouseposition'] 		= array('Maus-Koordinaten',
																			'Blendet die Geo-Koordinaten des Punktes ein, der sich unterhalb des Mauszeigers befindet.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['permalink'] 			= array('Permalink',
																			'Blendet einen Link ein, der den aktuellen Kartenausschnitt der Karte repräsentiert und der z.B. in E-Mails verschickt werden kann.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['zoomlevel'] 			= array('Zoom-Level anzeigen',
																			'Zeigt den aktuellen Zoom-Level an.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['libsource'] 			= array('Zu verwendende OpenLayers Bibliothek',
																			'Wählen Sie die Herkunft der OpenLayers JavaScript Bibliothek aus.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['imagepath'] 			= array('Benutzerdefinierter Pfad für OpenLayers Icons (optional)',
																			'Nur beim Standard-Theme: Setzt den Pfad zu einem benutzerdefinierten OpenLayers /img/ Verzeichnis für den Fall, dass Sie OpenLayers Standardicons auswechseln möchten. ACHTUNG: Diese Einstellung betrifft NICHT Grafiken aus dem OpenLayers /themes/default/img/ Verzeichnis, die Sie über CSS austauschen können!');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['script'] 				= array('Eigener JavaScript-Code',
																			'Der JavaScript-Code wird am Ende der con4gis-maps Funktion eingefügt.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['link_newwindow'] 		= array('Direktlink in neuem Fenster öffnen',
																			'Direktlinks nicht im selben Fenster, sondern in einem neuen Fenster öffnen.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['link_open_on'] 			= array('Direktlinks öffnen bei',
																			'Hier können Sie wählen, nach welcher Mausaktion ein Direktlink geöffnet wird, falls er bei der Lokation angegeben ist.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['hover_popups'] 			= array('Popups über Maus-Hover-Effekt anzeigen',
																			'Popups anzeigen sobald der Mauszeiger über der Lokation verweilt, und nicht erst nach einem Mausklick.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['hover_popups_stay'] 	= array('Maus-Hover-Popups geöffnet lassen',
																			'Popups nicht automatisch schließen, wenn der Mauszeiger die Lokation verlässt.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['attribution'] 			= array('Copyright-Text (Attribution)',
																			'Blendet einen Copyright-Text (eine sogenannte Attribution) ein. Deaktivierung nicht empfohlen!');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['cfg_logo_attribution'] 	= array('con4gis-Logo einblenden',
																			'Blendet das con4gis-Logo auf der Karte ein. Sie können diese Option deaktivieren, jedoch würden wir uns in diesem Fall über eine Erwähung von "con4gis" an einer anderen Stelle, auf Ihrer Seite freuen.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['div_attribution'] 		= array('DIV für Copyright-Text (Attribution)',
																			'DIV zur Positionierung des Copyrights. Nur nötig, falls das Copyright außerhalb der Karte angezeigt werden soll. Dieses muss manuell auf der Seite erzeugt werden, z.B. mit einem HTML Inhaltselement.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['add_attribution'] 		= array('Zusätzlicher Copyright-Text',
																			'wird an den Copyright-Text des verwendeten Kartendienstes angefügt.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch'] 			= array('Suche anzeigen',
																			'Aktiviert ein Suchfeld mit Schalter, über das Orte, Adressen und POIs gesucht werden können.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_engine'] 		= array('Suchengine',
																			'ACHTUNG! Bitte beachten Sie eventuell geltende Beschränkungen des gewählten Anbieters. Sollten Sie eine benutzerdefinierte URL angeben, achten Sie darauf die Attribution des Anbieters anzugeben, wenn es sich nicht um Ihren eigenen Server handelt.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_customengine_url'] 			= array('Benutzerdefinierte Suchengine-URL',
																							'Die URL unter der die Suchengine erreichbar ist.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_customengine_attribution'] 	= array('Benutzerdefinierte Suchengine-Attribution',
																							'WICHTIG! Der Copyright-Text des Anbieters der Suchengine.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_div'] 		= array('DIV für die Suche',
																			'DIV zur Positionierung der Suche. Nur nötig, falls die Suche nicht direkt vor der Karte angezeigt werden soll. Dieses muss manuell auf der Seite erzeugt werden, z.B. mit einem HTML Inhaltselement.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_zoomto'] 		= array('Zoomlevel',
																			'Zoomlevel nach einer erfolgreichen Suche, z.B. 12');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_zoombounds'] 	= array('Auf Gebiete zoomen',
																			'Zoomt auf die Gebietsumrisse, falls sie vom Such-Service (Nominatim) mitgeliefert wurden, an Stelle des angegebenen Zoomlevels.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_attribution'] = array('Copyright für die Suche anzeigen',
																			'Fügt eine Attribution des verwendeten Suchservice (Nominatim) hinzu. Deaktivierung nicht empfohlen!');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker'] 			= array('Geopicker anzeigen',
																			'Zeigt einen Geopicker, wie er im Backend zur Auswahl der Koordinaten verwendet wird, im Frontend an. Nützlich z.B. in einem Formular.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_fieldx'] 		= array('Formularfeld für die x-Koordinate',
																			'Ein INPUT Feld, in das die gewählte X-Koordinate geschrieben wird.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_fieldy'] 		= array('Formularfeld für die y-Koordinate',
																			'Ein INPUT Feld, in das die gewählte Y-Koordinate geschrieben wird.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_searchdiv'] 	= array('DIV für Geopicker',
																			'DIV zur Positionierung des Geopickers. Dieses muss manuell auf der Seite erzeugt werden, z.B. mit einem HTML Formularfeld.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_attribution'] = array('Copyright für Geopicker anzeigen',
																			'Fügt eine Attribution des verwendeten Suchservice (Nominatim) hinzu. Deaktivierung nicht empfohlen!');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['div_layerswitcher'] 	= array('DIV für den Layer-Switcher',
																			'DIV zur Positionierung des Layer-Switchers außerhalb der Karte. Dieses muss manuell auf der Seite erzeugt werden, z.B. mit einem HTML Inhaltselement.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['custom_div'] 			= array('DIV für den Kartenbereich',
																			'DIV zur Umpositionierung des Kartenbereichs. Mit Hilfe dieser Einstellung lässt sich die Karte z.B. mitten in ein Formular hinein verschieben. Das DIV muss manuell auf der Seite erzeugt werden, z.B. mit einem HTML Inhaltselement oder HTML Formularfeld.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['label_baselayer'] 		= array('Bezeichnung der Basiskarten im Starboard',
																			'z.B. "Basiskarte"');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['label_overlays'] 		= array('Bezeichnung der Ebenen im Starboard ',
																			'z.B. "Kartenelemente"');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['overpass_url'] 			= array('Alternative URL der Overpass-API (<a href="http://overpass-api.de/" target="_blank">Website des voreingestellten API-Server Anbieters</a>)',
																			'Geben Sie hier die URL des Overpass API Servers ein, wenn Sie z.B. einen eigenen Overpass API Server nutzen möchten. Standardmäßig wird http://overpass-api.de/api/interpreter verwendet.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['router'] 				= array('Router aktivieren (Beachten Sie bitte die <a href="https://github.com/DennisOSRM/Project-OSRM/wiki/Api-usage-policy" target="_blank">Nutzungsbedingungen des Standard-Anbieters</a>!)',
																			'Fügt einen zusätzlichen Schalter in die Karte ein, über den der Router aktiviert werden kann.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['router_viaroute_url'] 	= array('Alternative URL der OSRM-API',
																			'Geben Sie hier die URL des OSRM Servers ein, wenn Sie z.B. einen eigenen OSRM Server nutzen möchten. Standardmäßig wird http://router.project-osrm.org/viaroute verwendet.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['router_attribution'] 	= array('Copyright für Router überschreiben',
																			'Überschreibt die Standard-Attribution. Dies ist nur dann empfehlenswert, wenn Sie einen alternativen API-Server nutzen, oder die Attribution an einer anderen, offensichtlichen Stelle, auf der Seite platziert haben!');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_styles_point'] 	= array('Lokationsstile für POIs',
																			'Ausgewählte Lokationsstile werden im Editor angeboten.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_styles_line'] 	= array('Lokationsstile für Strecken',
																			'Ausgewählte Lokationsstile werden im Editor angeboten.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_styles_polygon'] = array('Lokationsstile für Flächen',
																			'Ausgewählte Lokationsstile werden im Editor angeboten.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_vars'] 			= array('Zusatzfelder',
																			'Zusatzfelder stehen für POIs, Strecken und Flächen zur Verfügung und können über ${Schlüssel} im Popup, Label und Tooltip in die Karte eingefügt werden.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_show_items'] 	= array('Alle Karteneinträge im Editor anzeigen',
																			'Zeigt im Editor nicht nur den zu bearbeitenden Karteneintrag an, sondern auch alle Anderen.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_helpurl'] 		= array('Hilfe-Link',
																			'Wenn hier eine URL eingetragen wird, so erscheint ein "Hilfe" Link im Editor, der zu der angegebenen URL führt.');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['be_optimize_checkboxes_limit'] 		= array('Grenzwert für Optimierung großer Checkbox-Listen',
																			'Definiert wie viele Einträge enthalten sein müssen, bis die Checkbox-Listen in Chosenfields umgewandelt werden. (0 = niemals umwandeln)');


/**
 * Reference
 */
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['CLICK'] = 'einfachem Mausklick';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['DBLCL'] = 'Maus-Doppelklick';

$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['dark'] 			= 'Dark Theme (angepasst)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['modern'] 			= 'Modern Trans Theme';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['metro'] 			= 'Metro Theme';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['con4gis'] 			= 'con4gis Theme';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['blue'] 			= 'Blue Theme (basierend auf dem angepassten Dark-Theme)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['red'] 			= 'Red Theme (basierend auf dem angepassten Dark-Theme)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['default_theme'] 	= 'OpenLayers Standard-Theme';

$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_zoom_panel']['no_zoom_panel'] 	= 'Aus';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_zoom_panel']['1'] 				= 'Standard Layout';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_zoom_panel']['2'] 				= 'Vereinfachtes Layout';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_zoom_panel']['3'] 				= 'Vereinfachtes Layout (Mobil)';

$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_measuretool']['no_measuretool'] 	= 'Aus';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_measuretool']['1'] 				= 'An (Entfernung in Abschnitten angezeigt)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_measuretool']['2'] 				= 'An (sofortige Anzeige der Entfernung)';

$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_geosearch_engine']['1'] = 'Nominatim bei Openstreetmap (<a href="http://wiki.openstreetmap.org/wiki/Nominatim_usage_policy" target="_blank">Nutzungsbestimmungen</a>)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_geosearch_engine']['2'] = 'Nominatim bei MapQuest (<a href="http://developer.mapquest.com/web/products/open/nominatim" target="_blank">Nutzungsbestimmungen</a>)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_geosearch_engine']['3'] = 'Benutzerdefiniert';

/**
 * Buttons
 */
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['new']        = array('Neues Kartenprofil', 'Ein neues Kartenprofil erstellen');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['edit']       = array('Kartenprofil bearbeiten', 'Kartenprofil ID %s bearbeiten');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['copy']       = array('Kartenprofil duplizieren', 'Kartenprofil ID %s duplizieren');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['delete']     = array('Kartenprofil löschen', 'Kartenprofil ID %s löschen');
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['show']       = array('Details', 'Die Details des Kartenprofils ID %s anzeigen');

/**
 * Legends
 */
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['general_legend']		= 'Allgemeine Daten';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['baselayer_legend']		= 'Basiskarten';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['locstyle_legend']		= 'Lokationsstile';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['navigation_legend']		= 'Karten-Navigation';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['tool_legend']			= 'Karten-Werkzeuge';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['information_legend']	= 'Karten-Informationen';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['expert_legend']	        = 'Experteneinstellungen';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['misc_legend']	        = 'Sonstiges';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_legend']	    = 'Suche';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_legend']	    = 'Geopicker-Einstellungen';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['router_legend']	    	= 'Router (OSRM)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_legend']	    	= 'Einstellungen für Editor (GeoJSON und con4gis-Forum)';
$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['backend_legend']	    	= 'Backendeinstellungen';

/**
 * Globals
 */
$GLOBALS['TL_LANG']['MSC']['ow_value'] = 'Label im Editor';

?>