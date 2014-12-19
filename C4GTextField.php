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
 * Class C4GTextField
 * DCA eval = array( 'require_input'=>true , adds "mandatory" field without Contao default 
 * "mandatory" functionality being executed 
 * (validation must be handled by code in the "save_callback" of the field)
 */
class C4GTextField extends TextField
{

	/**
	 * Check custom setting 'require_input', which implements custom mandatory handling possibility
	 * @return string
	 */
	public function generateLabel()
	{
		if (($this->require_input) && ($this->value=='')) {
 		  $this->required = true;
		}   
		return parent::generateLabel();
	}
	
}
?>
