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
 * Table tl_c4g_map_baselayers
 */
$GLOBALS['TL_DCA']['tl_c4g_map_baselayers'] = array
(
	
    // Config
	'config' => array
	(
		'dataContainer'               => 'Table',
		'ctable'                      => array('tl_c4g_map_overlays'),
		'enableVersioning'            => true,
	),

	// List
	'list' => array
	(

	    'sorting' => array
		(
			'mode'                    => 1,
			'fields'                  => array('name'),
			'panelLayout'             => 'filter;sort,search,limit',
			'headerFields'            => array('name'),
			'flag'                    => 1
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
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			),
			'overlays' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['overlays'],
				'href'                => 'table=tl_c4g_map_overlays',
				'icon'                => 'system/modules/con4gis_maps/html/overlays.png'
			),			
		)
	),

	
	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('provider','osm_style','protect_baselayer'),
		'default'                     => '{general_legend},name,display_name,sort,provider,attribution,maxzoomlevel;'.
										 '{protection_legend:hide},protect_baselayer;',
		'osm'                         => '{general_legend},name,display_name,sort,provider,osm_style,attribution,maxzoomlevel;'.
										 '{protection_legend:hide},protect_baselayer;',
		'osm_custom'                  => '{general_legend},name,display_name,sort,provider,osm_style,osm_style_url1,osm_style_url2,osm_style_url3,osm_style_url4,osm_keyname,attribution,maxzoomlevel;'.
										 '{protection_legend:hide},protect_baselayer;',
	    'google'                      => '{general_legend},name,display_name,sort,provider,google_style,attribution,maxzoomlevel;'.
	    								 '{protection_legend:hide},protect_baselayer;',
	    'bing'                        => '{general_legend},name,display_name,sort,provider,bing_style,bing_key,attribution,maxzoomlevel;'.
	    								 '{protection_legend:hide},protect_baselayer;',
	),

	// Subpalettes
	'subpalettes' => array
	(
		'protect_baselayer'           => 'permitted_groups'
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['name'],
			'exclude'                 => true,
		    'search'                  => true,
		    'sorting'			      => true,
		    'filter'			      => true,
		    'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>50, 'tl_class'=>'w50')
		),

		'display_name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['display_name'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>50, 'tl_class'=>'w50')
		),
		
		'sort' => array
		(
				'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['sort'],
				'filter'				  => false,
				'inputType' 		      => 'text',
				'default'                 => '0',
				'eval'                    => array('rgxp'=>'digit')
		),
		
		'provider' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider'],
			'filter'				  => true,
			'inputType' 		      => 'select',
		    'default'                 => 'osm',
		    'options'                 => array('osm' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm'],
		                                       'google' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google'],
		                                       'bing' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_bing'] 
                                         	  ),
            'eval'                    => array('submitOnChange' => true, 'tl_class'=>'clr')                             	  
		),
		'osm_style' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style'],
			'filter'				  => false,
			'inputType' 		      => 'select',
		    'default'                 => 'Mapnik',
		    'options'                 => array('Mapnik' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_mapnik'],
												//	Osmarender Server no longer available (March 2012)   'Osmarender' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_osma'],
		                                       'CycleMap' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_cycle'],
		                                       'German' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_german'],
				                               'GermanTransport' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_german_transport'],
											   'TransportMap' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_transport'],
											   'LandscapeMap' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_landscape'],
											   'MapQuestOpen' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_mapquestopen'],
											   'Toner'=> &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_toner'],
											   // these layers only make sense after we added "transparent layer" support
											   //'TonerLines'=> &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_tonerlines'],
											   //'TonerLabels'=> &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_tonerlabels'],
											   'Watercolor'=> &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_watercolor'],
											   'osm_custom' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_osm_custom'] 
											   ),
            'eval'                    => array('submitOnChange' => true)                             	  
		),
		'osm_style_url1' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_url1'],
			'filter'				  => false,
			'inputType' 		      => 'text',
            'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'long')
		),
		'osm_style_url2' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_url2'],
			'filter'				  => false,
			'inputType' 		      => 'text',
            'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'long')
		),
		'osm_style_url3' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_url3'],
			'filter'				  => false,
			'inputType' 		      => 'text',
            'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'long')
		),
		'osm_style_url4' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_style_url4'],
			'filter'				  => false,
			'inputType' 		      => 'text',
            'eval'                    => array('decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'long')
		),
		'osm_keyname' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['osm_keyname'],
			'filter'				  => false,
			'inputType' 		      => 'text',
            'eval'                    => array('maxlength'=>30)
		),
		'google_style' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['google_style'],
			'filter'				  => false,
			'inputType' 		      => 'select',
		    'default'                 => 'streets',
		    'options'                 => array('streets' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google_streets'],
		                                       'hybrid' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google_hybrid'],
		                                       'satellite' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google_satellite'],
		                                       'terrain' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_google_terrain'] 
                                         	  ),
		),

		'bing_style' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['bing_style'],
			'filter'				  => false,
			'inputType' 		      => 'select',
		    'default'                 => 'Shaded',
		    'options'                 => array('Road' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_bing_road'],
		                                       'AerialWithLabels' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_bing_hybrid'],
		                                       'Aerial' => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['provider_bing_aerial']
                                         	  ),
		),
		'bing_key' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['bing_key'],
			'filter'				  => false,
			'inputType' 		      => 'text',
            'eval'                    => array('decodeEntities'=>true, 'maxlength'=>100, 'tl_class'=>'long', 'mandatory'=>'true')
		),
		
		'attribution' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['attribution'],
			'filter'				  => false,
			'inputType' 		      => 'text',
            'eval'                    => array('maxlength'=>255, 'tl_class'=>'long', 'allowHtml' => true)
		),

		'maxzoomlevel' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['maxzoomlevel'],
			'filter'				  => false,
			'inputType' 		      => 'text',
		    'default'                 => '0',
            'eval'                    => array('rgxp'=>'digit')
		),
		'protect_baselayer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['protect_baselayer'],
			'exclude'                 => true,
			'default'                 => false,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange' => true)
		),
		'permitted_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_baselayers']['permitted_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>false, 'multiple'=>true)
		),
		
    )		
);

/**
 * Class tl_c4g_map_baselayers
 *
 * Provide methods that are used by the data configuration array.
 * 
 */
class tl_c4g_map_baselayers extends Backend
{

}


?>
