<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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
 * Class Module_c4g_maps
 */
class Module_c4g_maps extends Module
{

	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'mod_c4g_maps';


	/**
	 * Display a wildcard in the back end
	 * @return string
	 */
	public function generate()
	{
		if (TL_MODE == 'BE')
		{
			$objTemplate = new BackendTemplate('be_wildcard');

			$objTemplate->wildcard = '### '.$GLOBALS['TL_LANG']['FMD']['c4g_maps'][0].' ###';
			$objTemplate->title = $this->headline;
			$objTemplate->id = $this->id;
			$objTemplate->link = $this->title;
			$objTemplate->href = 'contao/main.php?do=themes&amp;table=tl_module&amp;act=edit&amp;id=' . $this->id;

			return $objTemplate->parse();
		}

		return parent::generate();
	}

	/**
	 * Generate module
	 */
	protected function compile()
	{
		$this->Template->mapData = C4GMaps::prepareMapData($this, $this->Database);				
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

?>