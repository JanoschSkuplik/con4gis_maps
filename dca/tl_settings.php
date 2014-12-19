<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

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


$GLOBALS['TL_DCA']['tl_settings']['palettes']['default'] .= ';{c4g_maps_legend},disabledC4gMapObjects';

/**
 * Add fields
 */
$GLOBALS['TL_DCA']['tl_settings']['fields']['disabledC4gMapObjects'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_settings']['disabledC4gMapObjects'],
	'inputType'               => 'checkbox',
	'options_callback'        => array('tl_settings_con4gis_maps', 'getMapTables'),
	'eval'                    => array('multiple'=>true)
);

/**
 * Class tl_settings_con4gis_maps
 *
 * Provide methods that are used by the data configuration array.
 * @copyright  Küstenschmiede GmbH Software & Design 2012
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
 * @package    con4gis
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
 */
class tl_settings_con4gis_maps extends tl_settings
{
	/**
	 * Return available Map tables
	 *
	 * @return array Array of map tables
	 */
	public function getMapTables()
	{
		$tables = array();
		foreach ($GLOBALS['con4gis_maps_extension']['sourcetable'] as $key=>$sourcetable)
		{
			$tables[$key] = $GLOBALS['TL_LANG']['c4g_maps']['sourcetable'][$key]['name'];
		}
		return $tables;
	}
}

?>