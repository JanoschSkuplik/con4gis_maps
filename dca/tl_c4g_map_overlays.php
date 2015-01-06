<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Jürgen Witte <http://www.kuestenschmiede.info>
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014 - 2015
 * @link      https://www.kuestenschmiede.de
 * @filesource
 */



/**
 * Table tl_c4g_map_overlays
*/
$GLOBALS['TL_DCA']['tl_c4g_map_overlays'] = array
(

	// Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ptable'                      => 'tl_c4g_map_baselayers',
		'enableVersioning'            => true,
	),

	// List
	'list' => array
	(

	'sorting' => array
	(
		'mode'                    => 4,
		'fields'                  => array('name'),
		'panelLayout'             => 'filter;sort,search,limit',
		'headerFields'            => array('name'),
		'child_record_callback'   => array('tl_c4g_map_overlays', 'listOverlays'),
		'child_record_class'      => 'no_padding'
	),

	'label' => array
	(
		'fields'                  => array('name'),
		'format'                  => '%s'
	),

	'global_operations' => array
	(
		'all' => array
		(
			'label'               => &$GLOBALS['TL_LANG']['MSC']['all'],
			'href'                => 'act=select',
			'class'               => 'header_edit_all',
			'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="e"'
							)
		),

		'operations' => array
		(
			'edit' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),

			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),

			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),

			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),


	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('provider'),
		'default'                     => 'name,provider,attribution',
		'custom'                      => 'name,provider,url1,url2,url3,url4,attribution',
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['name'],
			'exclude'                 => true,
			'search'                  => true,
			'sorting'			      => true,
			'filter'			      => true,
			'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>50)
		),

		'provider' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['provider'],
			'filter'				  => true,
			'inputType' 		      => 'select',
			'default'                 => 'default',
			'options'                 => array('custom'),
			// 'options'                 => array('openseamap','openweathermap_data','openweathermap_stations','openweathermap_clouds','openweathermap_cloudsForecasts','openweathermap_precipitation','openweathermap_precipitationForecasts','openweathermap_rain','openweathermap_pressure','openweathermap_wind','openweathermap_temp','openweathermap_snow','openweathermap_radar','custom'),
			'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['references'],
			'eval'                    => array('submitOnChange' => true)
		),

		'url1' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['url1'],
			'filter'				  => false,
			'inputType' 		      => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'long')
		),

		'url2' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['url2'],
			'filter'				  => false,
			'inputType' 		      => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'long')
		),

		'url3' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['url3'],
			'filter'				  => false,
			'inputType' 		      => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'long')
		),

		'url4' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['url4'],
			'filter'				  => false,
			'inputType' 		      => 'text',
			'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'long')
		),

		'attribution' => array
		(
				'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_overlays']['attribution'],
				'filter'				  => false,
				'inputType' 		      => 'text',
				'eval'                    => array('maxlength'=>255, 'tl_class'=>'long', 'allowHtml' => true)
		),

	)
);

/**
 * Class tl_c4g_map_overlays
*/
class tl_c4g_map_overlays extends Backend
{

	/**
	 *
	 */
	public function __construct()
	{
		parent::__construct();
	}

	/**
	 * List a Location Style
	 * @param array
	 * @return string
	 */
	public function listOverlays($row) {
		return '<div style="float:left;">' . $row ['name'] . "</div>\n";
	}


}


?>
