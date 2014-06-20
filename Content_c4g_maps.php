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
 * Class Content_c4g_maps 
 *
 * @copyright  Küstenschmiede GmbH Software & Design 2014
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
 * @package    con4gis  
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
 */
class Content_c4g_maps extends Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'ce_c4g_maps';
	
	/**
	 * Generate content element
	 */
	public function generate()
	{		
		if (TL_MODE == 'BE')
		{
			$objMap = $this->Database->prepare("SELECT * FROM tl_c4g_maps WHERE id=?")
				->limit(1)
				->execute($this->c4g_map_id);
			$return = '<h1>'.$objMap->name.'</h1>';
			
			return $return;
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