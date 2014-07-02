<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
 * Class C4GImageSize
 *
 * Extend ImageSize so that '%' can be used as unit
 */
class C4GImageSize extends ImageSize
{


	/**
	 * Trim values
	 * @param mixed
	 * @return mixed
	 */
	protected function validator($varInput)
	{
		$validatedInput = parent::validator($varInput);
		$validatedInput[2] = preg_replace('/[^a-z0-9_%]+/', '', $varInput[2]);
		
		return $validatedInput;
	}


}

?>
