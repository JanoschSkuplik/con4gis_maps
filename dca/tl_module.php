<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author     Jürgen Witte <http://www.kuestenschmiede.info> 
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014
 * @link      https://www.kuestenschmiede.de
 * @filesource 
 */



/***
 * Palettes
 */
$GLOBALS['TL_DCA']['tl_module']['palettes']['c4g_maps'] = '{title_legend},name,headline,type;{c4g_map_legend},c4g_map_id,c4g_map_default_mapservice,c4g_map_layer_switcher,c4g_map_mapsize,c4g_map_zoom;{protected_legend:hide},protected;{expert_legend:hide},guests,cssID,space';
$GLOBALS['TL_DCA']['tl_module']['palettes']['__selector__'][] = 'c4g_map_layer_switcher';
$GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_map_layer_switcher'] = 'c4g_map_layer_switcher_open';
if ($GLOBALS['con4gis_common_extension']['installed']) {
	$GLOBALS['TL_DCA']['tl_module']['subpalettes']['c4g_map_layer_switcher'] .= ',c4g_map_layer_switcher_ext';
}

/***
 * Fields
 */
$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_map_id'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_map_id'],
	'exclude'                 => true,
	'inputType'               => 'select',
    'options_callback'        => array('tl_module_c4g_maps', 'get_maps'),
	'eval'                    => array('submitOnChange'=>true)
);
$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_map_mapsize'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_map_mapsize'],
	'exclude'                 => true,
	'inputType'               => 'c4g_imageSize',
	'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
	'eval'                    => array('mandatory'=>false, 'rgxp'=>'digit')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_map_zoom'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_map_zoom'],
	'exclude'                 => true,
	'inputType'               => 'text',
	'eval'                    => array('mandatory'=>false, 'rgxp'=>'digit')
);
$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_map_default_mapservice'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_map_default_mapservice'],
	'exclude'                 => true,
	'inputType'               => 'select',
    'options_callback'        => array('tl_module_c4g_maps', 'get_baselayers')

);
$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_map_layer_switcher'] = array
(
	'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_map_layer_switcher'],
	'exclude'                 => true,
	'inputType'               => 'checkbox',
	'eval'                    => array('submitOnChange' => true)		
		
);
$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_map_layer_switcher_open'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_map_layer_switcher_open'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
);
$GLOBALS['TL_DCA']['tl_module']['fields']['c4g_map_layer_switcher_ext'] = array
(
		'label'                   => &$GLOBALS['TL_LANG']['tl_module']['c4g_map_layer_switcher_ext'],
		'exclude'                 => true,
		'inputType'               => 'checkbox',
);
/**
 * Class tl_module_c4g_maps
 *
 * Provide methods that are used by the data configuration array.
 * @copyright  Küstenschmiede GmbH Software & Design 2012
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
 * @package    con4gis
 * @author     Jürgen Witte <http://www.kuestenschmiede.info> 
 */
class tl_module_c4g_maps extends Backend {

	protected $firstMapId = null;
	
	/**
	 * Return all base layers for current Map Profile as array
	 * @param object
	 * @return array
	 */
	public function get_baselayers(DataContainer $dc)
	{
		$id = 0;
		if ($dc->activeRecord->c4g_map_id != 0) {
			$id = $dc->activeRecord->c4g_map_id;
		}
		else {
			// take firstMapId, because it will be chosen as DEFAULT value for c4g_map_id
			$id = $this->firstMapId;
		}
		
		$profile = $this->Database->prepare(
				"SELECT b.baselayers ".
				"FROM tl_c4g_maps a, tl_c4g_map_profiles b ".
				"WHERE a.id = ? and a.profile = b.id")
				->execute($id);
		
		$ids = deserialize($profile->baselayers,true);
		if (count($ids)>0) {
			$baseLayers = $this->Database->prepare("SELECT id,name FROM tl_c4g_map_baselayers WHERE id IN (".implode(',',$ids).") ORDER BY name")->execute();
		}
		else {
			$baseLayers = $this->Database->prepare("SELECT id,name FROM tl_c4g_map_baselayers ORDER BY name")->execute();
		}
		
		
		if ($baseLayers->numRows > 0) {
			while ( $baseLayers->next () ) {
				$return [$baseLayers->id] = $baseLayers->name;
			}
		}
		return $return;
	}		
	
	/**
	 * Return all defined maps 
	 * @param object
	 * @return array
	 */
	public function get_maps(DataContainer $dc)
	{
		
		$maps = $this->Database->prepare ( "SELECT * FROM tl_c4g_maps WHERE is_map=1 AND published=1" )->execute ();
		if ($maps->numRows > 0) {
			while ( $maps->next () ) {
				if (!isset($this->firstMapId)) {
					// save first map id
					$this->firstMapId = $maps->id;
				}
				$return [$maps->id] = $maps->name;
			}
		}
		return $return;
	}
}

?>