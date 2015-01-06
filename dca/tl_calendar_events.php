<?php if (!defined('TL_ROOT')) die('You can not access this file directly!');

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Jürgen Witte <http://www.kuestenschmiede.de>
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014 - 2015
 * @link      https://www.kuestenschmiede.de
 * @filesource
 */




if (@class_exists("tl_calendar_events"))
{

/**
 * Change palettes
 */
$disabledObjects = deserialize($GLOBALS['TL_CONFIG']['disabledC4gMapObjects'], true);
if (!in_array('tl_calendar_events', $disabledObjects))
{
	foreach ($GLOBALS['TL_DCA']['tl_calendar_events']['palettes'] as $key=>&$palette) {
		if ($key != '__selector__') {
		  	$palette = str_replace(';{expert_legend', ';{c4g_maps_legend},c4g_loc_geox,c4g_loc_geoy,c4g_loc_label,c4g_locstyle;{expert_legend', $palette);
		}
	}

	$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['c4g_loc_geox'] = array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['c4g_loc_geox'],
		'exclude'                 => true,
		'inputType'               => 'text',
		'eval'                    => array('maxlength'=>20, 'tl_class'=>'w50 wizard' ),
	    'save_callback'           => array(array('tl_calendar_events_c4g_maps','setGeoX')),
		'wizard'                  => array(array('tl_calendar_events_c4g_maps','geoPicker'))
	);

	$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['c4g_loc_geoy'] = array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['c4g_loc_geoy'],
		'exclude'                 => true,
		'inputType'               => 'text',
		'eval'                    => array('maxlength'=>20, 'tl_class'=>'w50 wizard' ),
	    'save_callback'           => array(array('tl_calendar_events_c4g_maps','setGeoY')),
		'wizard'                  => array(array('tl_calendar_events_c4g_maps','geoPicker'))
	);

	$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['c4g_loc_label'] = array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['c4g_loc_label'],
		'exclude'                 => true,
		'inputType'               => 'text',
		'eval'                    => array('tl_class'=>'clr' )
	);

	$GLOBALS['TL_DCA']['tl_calendar_events']['fields']['c4g_locstyle'] = array
	(
		'label'                   => &$GLOBALS['TL_LANG']['tl_calendar_events']['c4g_locstyle'],
		'exclude'                 => true,
		'inputType'               => 'select',
	    'options_callback'        => array('tl_calendar_events_c4g_maps','getLocStyles')
	);


}
}

/**
 * Class tl_calendar_events_c4g_maps
 */
class tl_calendar_events_c4g_maps extends Backend
{
	/**
	 * Return all Location Styles as array
	 * @param object
	 * @return array
	 */
	public function getLocStyles(DataContainer $dc)
	{
		$locStyles = $this->Database->prepare("SELECT id,name FROM tl_c4g_map_locstyles ORDER BY name")
									->execute();
		$return[''] = '-';
		while ($locStyles->next())
		{
			$return[$locStyles->id] = $locStyles->name;
		}
		return $return;
	}

	/**
	 * Validate Location GeoX
	 */
	public function setGeoX($varValue, DataContainer $dc)
	{
		if ($varValue<>0) {
			if (!C4GMaps::validateGeoX($varValue)) {
				throw new Exception($GLOBALS['TL_LANG']['c4g_maps']['geox_invalid']);
			}
		}
		return $varValue;
	}

	/**
	 * Validate Location GeoY
	 */
	public function setGeoY($varValue, DataContainer $dc)
	{
		if ($varValue<>0) {
			if (!C4GMaps::validateGeoY($varValue)) {
				throw new Exception($GLOBALS['TL_LANG']['c4g_maps']['geoy_invalid']);
			}
		}
		return $varValue;
	}


	/**
	 * Return the Geo Picker Wizard
	 * @param object
	 * @return string
	 */
	public function geoPicker(DataContainer $dc)
	{
		$strField = 'ctrl_' . $dc->field . (($this->Input->get('act') == 'editAll') ? '_' . $dc->id : '');
		if (substr($strField,-1,1)=='y') {
			$strFieldX = substr($strField,0,-1).'x';
			$strFieldY = $strField;
		}
		else {
			$strFieldX = $strField;
			$strFieldY = substr($strField,0,-1).'y';
		}
		return ' ' . $this->generateImage('system/modules/con4gis_maps/html/geopicker.png', $GLOBALS['TL_LANG']['c4g_maps']['geopicker'], 'style="vertical-align:top; cursor:pointer;" onclick="C4GMapsBackend.pickGeo(\'' . $strFieldX . '\',\''.$strFieldY . '\')"');
	}

}

?>