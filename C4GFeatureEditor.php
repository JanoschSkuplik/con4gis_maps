<?php

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Jürgen Witte <http://www.kuestenschmiede.de>
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014
 * @link      https://www.kuestenschmiede.de
 * @filesource 
 */




/**
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once('../../initialize.php');


/**
 * Class C4GFeatureEditor
 * 
 * @copyright  Küstenschmiede GmbH Software & Design 2012
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
 * @package    con4gis
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
 */
class C4GFeatureEditor extends Backend
{

	/**
	 * Initialize the controller
	 * 
	 * 1. Import user
	 * 2. Call parent constructor
	 * 3. Authenticate user
	 * 4. Load language files
	 * DO NOT CHANGE THIS ORDER!
	 */
	public function __construct()
	{
		$this->import('BackendUser', 'User');
		parent::__construct();

		$this->User->authenticate();
		$this->loadLanguageFile('default');
	}


	/**
	 * Run controller and parse the template
	 */
	public function run()
	{
		$this->Template = new BackendTemplate('c4g_featureeditor');

		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->headline = $GLOBALS['TL_LANG']['c4g_maps']['geoFeatureEditor'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->c4g_map_layer_switcher = true;
		$mapData = C4GMaps::prepareMapData($this, $this->Database, null, true);
		
		$mapData['editor'] = true;
		$mapData['editor_labels'] = $GLOBALS['TL_LANG']['c4g_maps']['editor_labels'];
		
  		$mapData['geocoding_url'] = 'system/modules/con4gis_maps/C4GNominatim.php';
  		$mapData['geosearch'] = true;
  		$mapData['geosearch_div'] = 'c4gFeatureEditorSearch';
  		$mapData['geosearch_zoomto'] = 14;
  		$mapData['geosearch_zoombounds'] = true;
  		$mapData['geosearch_attribution'] = true;

  		$mapData['div_attribution'] = 'c4gAttribution';
  		
  		$item = $this->Database->prepare(
  				"SELECT locstyle ".
  				"FROM tl_c4g_maps ".
  				"WHERE id = ?")
  				->execute($this->Input->get('mapId'));  		
  		if ($item->numRows > 0) {
  			$mapData['editor_defstyle'] = $item->locstyle;
  		}	
  		
  		$mapId = C4GMaps::getMapForLocation($this->Database, $this->Input->get('mapId'));
  		if ($mapId<>0) {
  			$map = $this->Database->prepare(
  					"SELECT calc_extent,center_geox,center_geoy,zoom ".
  					"FROM tl_c4g_maps ".
  					"WHERE id = ?")
  					->execute($mapId);
  			if ($map->numRows > 0) {
  				if ($map->calc_extent == 'CENTERZOOM') {
  					$mapData['calc_extent'] = $map->calc_extent;
  					$mapData['center_geox'] = $map->center_geox;
  					$mapData['center_geoy'] = $map->center_geoy;
  					$mapData['zoom'] = $map->zoom;  					  		
  				}
  			}		
  		}
		$this->Template->mapData = $mapData;
		
		$this->Template->output();
	}

	public function repInsertTags( $str )
	{
		return parent::replaceInsertTags($str);
	}
	
	public function import($strClass, $strKey=false, $blnForce=false)
	{
		parent::import($strClass, $strKey, $blnForce);
	}
	
	public function getInput() {
		return $this->Input;
	}
	
	public function getFrontendUrl($arrRow) {
		return parent::generateFrontendUrl($arrRow);
	}
		
}


/**
 * Instantiate controller
 */
$objFeatureEditor = new C4GFeatureEditor();
$objFeatureEditor->run();

?>