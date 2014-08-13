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

namespace c4g;

/**
 * Class Content_c4g_maps 
 */
class Content_c4g_maps extends \Module
{
	/**
	 * Template
	 * @var string
	 */
	protected $strTemplate = 'c4g_maps';
	
	/**
	 * Generate content element
	 */
	public function generate ()
	{		
		if (TL_MODE == 'BE')
		{
			$objMap = $this->Database->prepare("SELECT * FROM tl_c4g_maps WHERE id=?")
				->limit(1)
				->execute($this->c4g_map_id);
			$return = '<h1>' . $objMap->name . '<img src="system/modules/con4gis_maps/assets/images/logo_con4gis-maps.png" style="float:right"></h1>';
			
			return $return;
		}
		
		return parent::generate();
	}
	
	/**
	 * Generate module
	 */
	protected function compile ()
	{
		$this->Template->mapData = C4GMaps::prepareMapData($this, $this->Database);		
	}
	
	public function repInsertTags ( $str )
	{
		return parent::replaceInsertTags( $str );
	}

	public function import ( $strClass, $strKey=false, $blnForce=false )
	{
		parent::import($strClass, $strKey, $blnForce);
	}	
	
	public function getInput () 
	{
		return $this->Input;
	}
	
	public function getFrontendUrl ( $arrRow ) 
	{
		return parent::generateFrontendUrl($arrRow);
	}
	
}