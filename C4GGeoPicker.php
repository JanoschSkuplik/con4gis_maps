<?php

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
 * Initialize the system
 */
define('TL_MODE', 'BE');
require_once('../../initialize.php');


/**
 * Class C4GGeoPicker
 */
class C4GGeoPicker extends Backend
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
		$this->Template = new BackendTemplate('c4g_geopicker');

		$this->Template->theme = $this->getTheme();
		$this->Template->base = $this->Environment->base;
		$this->Template->language = $GLOBALS['TL_LANGUAGE'];
		$this->Template->title = $GLOBALS['TL_CONFIG']['websiteTitle'];
		$this->Template->headline = $GLOBALS['TL_LANG']['c4g_maps']['geopicker'];
		$this->Template->charset = $GLOBALS['TL_CONFIG']['characterSet'];
		$this->c4g_map_layer_switcher = true;
		$mapData = C4GMaps::prepareMapData($this, $this->Database);
		
		$mapData['pickGeo'] = true;
		if ($this->Input->get('GeoX') || $this->Input->get('GeoY')) {
			$mapData['pickGeo_init_xCoord'] = $this->Input->get('GeoX');
			$mapData['pickGeo_init_yCoord'] = $this->Input->get('GeoY');
			$mapData['calc_extent'] = 'CENTERZOOM';
			$mapData['center_geox'] = $this->Input->get('GeoX');
			$mapData['center_geoy'] = $this->Input->get('GeoY');
			$mapData['zoom'] = 14;
		}
		
		$mapData['geocoding'] = true;
		$mapData['geocoding_url'] = 'system/modules/con4gis_maps/C4GNominatim.php';
		$mapData['geocoding_div'] = 'c4gGeoPickerGeocoding';
		$mapData['geocoding_usebutton'] = true;
		
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
$objGeoPicker = new C4GGeoPicker();
$objGeoPicker->run();

?>
