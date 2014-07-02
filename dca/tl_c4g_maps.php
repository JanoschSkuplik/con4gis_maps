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
 * Table tl_c4g_maps 
 */
$GLOBALS['TL_DCA']['tl_c4g_maps'] = array
(

	// Config
	'config' => array
	(		
	    'label'                       => $GLOBALS['TL_CONFIG']['websiteTitle'],
	    'dataContainer'               => 'Table',
		'enableVersioning'            => true,
	    'onload_callback'			  => array(array('tl_c4g_maps', 'updateDCA'))
	),

	// List
	'list' => array
	(
		'sorting' => array
		(
			'mode'                    => 5,
			'fields'                  => array('name'),
			'flag'                    => 1
		),
		'label' => array
		(
			'fields'                  => array('name'),
			'format'                  => '%s',
			'label_callback'          => array('tl_c4g_maps','generateLabel')
		),
		'global_operations' => array
		(
			'update_db' => array
			(
				'label'				  => &$GLOBALS['TL_LANG']['c4g_maps']['update_db'],
				'href'				  => 'key=update_db',
				'class'				  => 'navigation',
				'attributes'          => 'onclick="Backend.getScrollOffset();" accesskey="i"'
			),
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
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'copyChilds' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['copyChilds'],
				'href'                => 'act=paste&amp;mode=copy&amp;childs=1',
				'icon'                => 'copychilds.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"',
				'button_callback'     => array('tl_c4g_maps', 'copyPageWithSubpages')
			),
			'cut' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['cut'],
				'href'                => 'act=paste&amp;mode=cut',
				'icon'                => 'cut.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset();"'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'toggle' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['toggle'],
				'icon'                => 'visible.gif',
				'attributes'          => 'onclick="Backend.getScrollOffset(); return AjaxRequest.toggleVisibility(this, %s);"',
				'button_callback'     => array('tl_c4g_maps', 'toggleIcon')
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

	// Palettes
	'palettes' => array
	(
		'__selector__'                => array( 'is_map', 'profile','location_type', 'tab_source', 'calc_extent', 'auto_width', 'auto_height', 'popup_extend', 'protect_element', 'use_specialprofile'),
		'default'                     => '{general_legend},name,profile,profile_mobile,published;'.
		                                 '{map_legend},is_map;'.
		                                 '{location_legend},location_type,data_layername,data_hidelayer;'.
		                                 '{protection_legend:hide},protect_element;'.
		                                 '{expert_legend:hide},use_specialprofile;',
		'single'                      => '{general_legend},name,profile,profile_mobile,published;'.
		                                 '{map_legend},is_map;'.
		                                 '{location_legend},location_type,loc_geox,loc_geoy,locstyle,loc_only_in_parent,loc_label,tooltip,popup_info,routing_to,loc_linkurl,loc_onclick_zoomto,loc_minzoom,loc_maxzoom;'.
		                                 '{protection_legend:hide},protect_element;',
		'table'                      =>  '{general_legend},name,profile,profile_mobile,published;'.
		                                 '{map_legend},is_map;'.
		                                 '{location_legend},location_type,tab_source,tab_pid,tab_labeldisplay,tab_tooltipdisplay,tab_directlink,tab_force_target_blank,tab_whereclause,tab_orderby,tab_filter_alias,locstyle,routing_to,loc_onclick_zoomto,loc_minzoom,loc_maxzoom;'.
		                                 '{protection_legend:hide},protect_element;',
		'gpx'                        =>  '{general_legend},name,profile,profile_mobile,published;'.
		                                 '{map_legend},is_map;'.
		                                 '{location_legend},location_type,data_file,data_url,data_layername,data_hidelayer,data_js_style_function,locstyle,loc_label,tooltip,popup_info,routing_to,loc_linkurl,loc_onclick_zoomto,loc_minzoom,loc_maxzoom;'.
		                                 '{protection_legend:hide},protect_element;',
		'kml'                        =>  '{general_legend},name,profile,profile_mobile,published;'.
		                                 '{map_legend},is_map;'.
		                                 '{location_legend},location_type,data_file,data_url,data_layername,data_hidelayer,data_js_style_function,loc_label,tooltip,popup_info,routing_to,loc_linkurl,loc_onclick_zoomto,loc_minzoom,loc_maxzoom;'.
		                                 '{protection_legend:hide},protect_element;',
		'geojson'                    =>  '{general_legend},name,profile,profile_mobile,published;'.
		                                 '{map_legend},is_map;'.
		                                 '{location_legend},location_type,data_file,data_url,data_content,data_projection,data_layername,data_hidelayer,data_js_style_function,locstyle,loc_label,tooltip,popup_info,routing_to,loc_linkurl,loc_onclick_zoomto,loc_minzoom,loc_maxzoom;'.
		                                 '{protection_legend:hide},protect_element;',
		'osm'                    	 =>  '{general_legend},name,profile,profile_mobile,published;'.
		                                 '{map_legend},is_map;'.
		                                 '{location_legend},location_type,data_file,data_url,data_forcenodes,data_layername,data_hidelayer,data_js_style_function,locstyle,loc_label,tooltip,popup_info,popup_extend,routing_to,loc_linkurl,loc_onclick_zoomto,loc_minzoom,loc_maxzoom;'.
		                                 '{protection_legend:hide},protect_element;',
		'overpass'                	 =>  '{general_legend},name,profile,profile_mobile,published;'.
		                                 '{map_legend},is_map;'.
		                                 '{location_legend},location_type,ovp_request,ovp_bbox_limited,data_forcenodes,data_layername,data_hidelayer,data_js_style_function,locstyle,loc_label,tooltip,popup_info,popup_extend,routing_to,loc_linkurl,loc_onclick_zoomto,loc_minzoom,loc_maxzoom;'.
		                                 '{protection_legend:hide},protect_element;',
		'link'                       =>  '{general_legend},name,profile,profile_mobile,published;'.
		                                 '{map_legend},is_map;'.
		                                 '{location_legend},location_type,link_id;'.
		                                 '{protection_legend:hide},protect_element;',
		'c4gForum'                   =>  '{general_legend},name,profile,profile_mobile,published;'.
		                                 '{map_legend},is_map;'.
		                                 '{location_legend},location_type,forums,forum_jumpto,forum_reassign_layer,loc_label,tooltip,popup_info,routing_to,loc_onclick_zoomto,loc_minzoom,loc_maxzoom;'.
		                                 '{protection_legend:hide},protect_element;'
	),

	// Subpalettes
	'subpalettes' => array
	(
		'use_specialprofile'		  => 'specialprofile, specialprofile_mobile, specialprofile_groups',
		'protect_element'		  	  => 'permitted_groups',
		'popup_extend'				  => 'forums',
		'is_map'                      => ''  // is set in updateDCA
	),

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['name'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>255 )
		),
		'profile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['profile'],
			'exclude'                 => true,
			'inputType'               => 'select',
		    'foreignKey'              => 'tl_c4g_map_profiles.name',
			'eval'                    => array('tl_class'=>'w50', 
			                                   'includeBlankOption'=>true, 'blankOptionLabel'=>&$GLOBALS['TL_LANG']['tl_c4g_maps']['default_profile'],
		                                       'submitOnChange' => true, 'alwaysSave' => true ),
		    'load_callback'           => array(array('tl_c4g_maps','getDefaultProfile'))
		
		),
		'profile_mobile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['profile_mobile'],
			'exclude'                 => true,
			'inputType'               => 'select',
		    'foreignKey'              => 'tl_c4g_map_profiles.name',
			'eval'                    => array('tl_class'=>'w50', 
			                                   'includeBlankOption'=>true
		                                      )		
		),
		'published' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['published'],
			'exclude'                 => true,
			'default'                 => true,
			'inputType'               => 'checkbox',
			'eval'                    => array('tl_class'=>'clr'), 
		),
		'is_map' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['is_map'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange' => true)
		),
		'mapsize' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['mapsize'],
			'exclude'                 => true,
			'inputType'               => 'c4g_imageSize',
			'options'                 => array('px', '%', 'em', 'pt', 'pc', 'in', 'cm', 'mm'),
			'eval'                    => array('rgxp'=>'digit' )
		),
		'auto_width' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_width'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange' => true, 'tl_class'=>'w50' )
		),
		'auto_width_min' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_width_min'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
		    'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'auto_width_max' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_width_max'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
		    'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'auto_width_gap' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_width_gap'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
		    'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'auto_height' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_height'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange' => true, 'tl_class'=>'w50' )
		),
		'auto_height_min' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_height_min'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
		    'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'auto_height_max' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_height_max'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
		    'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'auto_height_gap' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['auto_height_gap'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
		    'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'calc_extent' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['calc_extent'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('LOCATIONS','CENTERZOOM'),
			'default'                 => 'LOCATIONS',
			'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['references'],
			'eval'                    => array('submitOnChange'=>true,'tl_class'=>'clr')
		),
		'min_gap' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['min_gap'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
		    'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'clr')
		),
		'center_geox' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['center_geox'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',		    
			'eval'                    => array('maxlength'=>20, 'tl_class'=>'w50 wizard', 'require_input'=>true ),
		    'save_callback'           => array(array('tl_c4g_maps','setCenterGeoX')),
			'wizard'                  => array(array('tl_c4g_maps', 'geoPicker'))
		),

		'center_geoy' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['center_geoy'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
			'eval'                    => array('maxlength'=>20, 'tl_class'=>'w50 wizard', 'require_input'=>true),
		    'save_callback'           => array(array('tl_c4g_maps','setCenterGeoY')),
			'wizard'                  => array(array('tl_c4g_maps', 'geoPicker'))
		),
		'zoom' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['zoom'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
		    'default'                 => '10',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'clr')
		),
		'geolocation' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['geolocation'],
			'exclude'                 => true,
			'default'                 => false,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange' => true)
		),
		'geolocation_zoom' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['geolocation_zoom'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
		    'default'                 => '14',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'clr')
		),
		'restrict_area' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['restrict_area'],
			'exclude'                 => true,
			'default'                 => false,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange' => true)
		),
		'restr_bottomleft_geox' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['restr_bottomleft_geox'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
			'eval'                    => array('maxlength'=>20, 'tl_class'=>'w50 wizard', 'require_input'=>true ),
		    'save_callback'           => array(array('tl_c4g_maps','setRestrGeoX')),
			'wizard'                  => array(array('tl_c4g_maps', 'geoPicker'))
		),
		'restr_bottomleft_geoy' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['restr_bottomleft_geoy'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
			'eval'                    => array('maxlength'=>20, 'tl_class'=>'w50 wizard', 'require_input'=>true ),
		    'save_callback'           => array(array('tl_c4g_maps','setRestrGeoY')),
			'wizard'                  => array(array('tl_c4g_maps', 'geoPicker'))
		),
		'restr_topright_geox' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['restr_topright_geox'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
			'eval'                    => array('maxlength'=>20, 'tl_class'=>'w50 wizard', 'require_input'=>true ),
		    'save_callback'           => array(array('tl_c4g_maps','setRestrGeoX')),
			'wizard'                  => array(array('tl_c4g_maps', 'geoPicker'))
		),
		'restr_topright_geoy' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['restr_topright_geoy'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
			'eval'                    => array('maxlength'=>20, 'tl_class'=>'w50 wizard', 'require_input'=>true ),
		    'save_callback'           => array(array('tl_c4g_maps','setRestrGeoY')),
			'wizard'                  => array(array('tl_c4g_maps', 'geoPicker'))
		),
		'include_sublocations' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['include_sublocations'],
			'exclude'                 => true,
			'default'                 => true,
			'inputType'               => 'checkbox'
		),
		'location_type' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['location_type'],
			'exclude'                 => true,
			'default'                 => 'none',
			'inputType'               => 'radio',
		    'options_callback'        => array('tl_c4g_maps','getLocationTypes'),
		    'eval'                    => array('submitOnChange' => true),
			'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['references'],
		),
		'loc_geox' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_geox'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>20, 'tl_class'=>'w50 wizard' ),		 
		    'save_callback'           => array(array('tl_c4g_maps','setLocGeoX')),
			'wizard'                  => array(array('tl_c4g_maps', 'geoPicker'))			
		),

		'loc_geoy' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_geoy'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>20, 'tl_class'=>'w50 wizard'),
		    'save_callback'           => array(array('tl_c4g_maps','setLocGeoY')),
			'wizard'                  => array(array('tl_c4g_maps', 'geoPicker'))
		),
		'locstyle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['locstyle'],
			'exclude'                 => true,
			'inputType'               => 'select',
		    'options_callback'        => array('tl_c4g_maps','getLocStyles'),
			'eval'                    => array('tl_class'=>'clr')
		),			
		'loc_only_in_parent' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_only_in_parent'],
			'exclude'                 => true,
			'default'                 => false,
			'inputType'               => 'checkbox'
		),
		'loc_label' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_label'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text'
		),
		'tooltip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['tooltip'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
			'eval'                    => array('tl_class'=>'long')
		),
		'popup_info' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_maps']['popup_info'],
			'exclude'                 => true,
			'inputType'				  => 'textarea',
			'eval'					  => array('rte'=>'tinyMCE'),
		),
		'popup_extend' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['popup_extend'],
			'exclude'                 => true,
			'default'                 => false,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange' => true)
		),
		'routing_to' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['routing_to'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox'
		),
		'loc_linkurl' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_linkurl'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'wizard'),
			'wizard' 				  => array(array('tl_c4g_maps', 'pickUrl'))
		),
		'loc_onclick_zoomto' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_onclick_zoomto'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
		    'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'clr')
		),
		'loc_minzoom' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_minzoom'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
		    'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'clr')
		),
		'loc_maxzoom' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['loc_maxzoom'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
		    'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'clr')
		),
		'tab_source' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_source'],
			'exclude'                 => true,
			'inputType'               => 'select',
		    'options_callback'        => array('tl_c4g_maps','getTabSources'),
		    'eval'                    => array('submitOnChange' => true),
		),			
		'tab_pid' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_pid'],
			'exclude'                 => true,
			'inputType'               => 'select',
		    'options_callback'        => array('tl_c4g_maps','getTabParentList'),
		),			
		'tab_labeldisplay' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_labeldisplay'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('OFF','1ST','1ST_MORE','1ST_COUNT','ALL'),
			'default'                 => '1ST_MORE',
			'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['references'],
		),
		'tab_tooltipdisplay' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_tooltipdisplay'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('OFF','1ST','1ST_MORE','1ST_COUNT','ALL'),
			'default'                 => '1ST_MORE',
			'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['references'],
		),
		'tab_directlink' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_directlink'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox'
		),
		'tab_force_target_blank' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_force_target_blank'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox'
		),
		'tab_whereclause' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_whereclause'],
			'exclude'               => true,
			'inputType'				=> 'textarea',
		),
		'tab_orderby' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_orderby'],
			'exclude'               => true,
			'inputType'				=> 'text',
			'eval'                    => array('maxlength'=>128),
		),
		'tab_filter_alias' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_maps']['tab_filter_alias'],
			'exclude'               => true,
			'default'               => '',
			'inputType'             => 'checkbox'
		),
		
		'data_file' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['data_file'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'					  => array( 'trailingSlash' => false, 'files' => true, 'fieldType' => 'radio' )
					
		),
		'data_url' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['data_url'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'wizard'),
			'wizard' 				  => array(array('tl_c4g_maps', 'pickUrl'))
		),
		'data_content' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_maps']['data_content'],
			'exclude'                 => true,
			'inputType'				  => 'textarea',
			'eval'                    => array('tl_class'=>'wizard', 'preserve_tags'=>true),
			'wizard'                  => array(array('tl_c4g_maps', 'geoFeatureEditor'))
		),
		'data_projection' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['data_projection'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('MERC','WGS84'),
			'default'                 => 'MERC',
			'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['references'],
		),
		'data_forcenodes' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['data_forcenodes'],
			'exclude'                 => true,
			'default'                 => false,
			'inputType'               => 'checkbox',
		),
		'data_layername' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['data_layername'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>100)
		),
		'data_hidelayer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['data_hidelayer'],
			'exclude'                 => true,
			'default'                 => false,
			'inputType'               => 'checkbox'
		),
		'data_js_style_function' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['data_js_style_function'],
			'exclude'                 => true,
			'inputType'               => 'c4g_text',
			'eval'                    => array('maxlength'=>100 )
		),
		'forums' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['forums'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
		    'options_callback'        => array('tl_c4g_maps','getMapForums'),
			'eval'                    => array('mandatory'=>false, 'multiple'=>true)
		),
		'forum_jumpto' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['forum_jumpto'],
			'exclude'                 => true,
			'inputType'               => 'pageTree',
			'eval'                    => array('fieldType'=>'radio')
		),		
		'forum_reassign_layer' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['forum_reassign_layer'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options'                 => array('NO','THREAD'),
			'default'                 => 'NO',
			'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_maps']['references'],
		),
		'ovp_request' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_maps']['ovp_request'],
			'exclude'                 => true,
			'inputType'				  => 'textarea',
			'eval'					  => array(allowHtml=>true, preserveTags=>true)
		),
		'ovp_bbox_limited' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['ovp_bbox_limited'],
			'exclude'                 => true,
			'default'                 => false,
			'inputType'               => 'checkbox'
		),
		'link_id' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['link_id'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_c4g_maps', 'get_link_items')
		),
		'protect_element' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['protect_element'],
			'exclude'                 => true,
			'default'                 => false,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange' => true)
		),
		'permitted_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['permitted_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('mandatory'=>false, 'multiple'=>true)
		),
		'use_specialprofile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['use_specialprofile'],
			'exclude'                 => true,
			'default'                 => false,
			'inputType'               => 'checkbox',
			'eval'                    => array('submitOnChange' => true)
		),
		'specialprofile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['specialprofile'],
			'exclude'                 => true,
			'inputType'               => 'select',
		    'foreignKey'              => 'tl_c4g_map_profiles.name',
			'eval'                    => array('tl_class'=>'w50', 'submitOnChange' => true, 'alwaysSave' => true ),
		    'load_callback'           => array(array('tl_c4g_maps','getDefaultProfile'))
		),
		'specialprofile_mobile' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['specialprofile_mobile'],
			'exclude'                 => true,
			'inputType'               => 'select',
		    'foreignKey'              => 'tl_c4g_map_profiles.name',
			'eval'                    => array('tl_class'=>'w50', 
			                                   'includeBlankOption'=>true)
		),
		'specialprofile_groups' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_maps']['specialprofile_groups'],
			'exclude'                 => true,
			'inputType'               => 'checkbox',
			'foreignKey'              => 'tl_member_group.name',
			'eval'                    => array('tl_class'=>'clr', 'mandatory'=>false, 'multiple'=>true)
		)
	)
);

/**
 * Class tl_c4g_maps
 *
 * Provide methods that are used by the data configuration array.
 * 
 */
class tl_c4g_maps extends Backend
{

	/**
	 * value of first source table 
	 */
	protected $firstTabSource = null;

	/**
	 * Import BackendUser object
	 */
	public function __construct()
	{
		parent::__construct();

		$this->import('BackendUser', 'User');
	}
	
	/**
	 * Return the copy page with subpages button
	 * @param array
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @param string
	 * @return string
	 */
	public function copyPageWithSubpages($row, $href, $label, $title, $icon, $attributes, $table)
	{

		$objSubpages = $this->Database->prepare("SELECT id FROM tl_c4g_maps WHERE pid=?")
									  ->limit(1)
									  ->execute($row['id']);

		if ($objSubpages->numRows > 0) {					
		  return '<a href="'.$this->addToUrl($href.'&amp;id='.$row['id']).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
		} else {   
		  return $this->generateImage(preg_replace('/\.gif$/i', '_.gif', $icon)).' ';
		}  
	}
	
	/**
	 * Return all Location Styles for current Maps Profile as array
	 * @param object
	 * @return array
	 */
	public function getLocStyles(DataContainer $dc)
	{
		$profile = $this->Database->prepare("SELECT locstyles FROM tl_c4g_map_profiles WHERE id=?")->execute($dc->activeRecord->profile);
		$ids = deserialize($profile->locstyles,true);
		if (count($ids)>0) {
			$locStyles = $this->Database->prepare("SELECT id,name FROM tl_c4g_map_locstyles WHERE id IN (".implode(',',$ids).") ORDER BY name")->execute();				
		}
		else {		
			$locStyles = $this->Database->prepare("SELECT id,name FROM tl_c4g_map_locstyles ORDER BY name")->execute();
		}										  
		while ($locStyles->next())
		{
			$return[$locStyles->id] = $locStyles->name;
		}		
		return $return;
	}	

	/**
	 * Return all available Sources for Maps
	 * @param object
	 * @return array
	 */
	public function getTabSources(DataContainer $dc)
	{
		$return = array();
		foreach ($GLOBALS['c4g_maps_extension']['sourcetable'] as $key=>$sourcetable)
		{
			if (!isset($this->firstTabSource)) {
				$this->firstTabSource = $key;
			}
			$return[$key] = $GLOBALS['TL_LANG']['c4g_maps']['sourcetable'][$key]['name'];
		}
		return $return;
	}	

	/**
	 * Return all available locations types
	 * @param object
	 * @return array
	 */
	public function getLocationTypes(DataContainer $dc)
	{
		$return = array('none','single','table','gpx','kml','geojson','osm','overpass','link');
		if ($GLOBALS['c4g_forum_extension']['installed'] ) {
			$return[] = 'c4gForum';
		}
		return $return;
	}
	

	/**
	 * Return all available map enabled forums
	 * @param object
	 * @return array
	 */
	public function getMapForums(DataContainer $dc)
	{
		$forumHelper = new C4GForumHelper($this->Database);
		$forums = $forumHelper->getMapForums();
		foreach ($forums AS $forum) {
			$return[$forum['id']] = $forum['name'];
		}
		return $return;
	}
	
	/**
	 * Return all entries in the parent table for the selection
	 * @param object
	 * @return array
	 */
	public function getTabParentList(DataContainer $dc)
	{
		if ($dc->activeRecord->tab_source<>'') {
			$tabsource = $dc->activeRecord->tab_source;
		}
		else {
			$tabsource = $this->firstTabSource;			
		}
		
		$source = $GLOBALS['c4g_maps_extension']['sourcetable'][$tabsource];
		if (is_array($source)) {
			if (($source['ptable']) && ($source['ptable_option'])) {
				$obj = $this->Database->prepare(
				    "SELECT id, ".$source['ptable_option']." FROM ".$source['ptable'])->execute();
				while ($obj->next())
				{
					$return[$obj->id] = $obj->$source['ptable_option'];
				}		
				return $return;
			}			
		} 
	}	

	/**
	 * Recursively step through map item tree
	 */
	private function getMapItemTree($tree, $return, $pid,$level)
	{
		if (array_key_exists($pid, $tree)) {		
			foreach ($tree[$pid] AS $key=>$item) {
				$return[$key] = str_repeat('+',$level).$item;
				if (array_key_exists($key, $tree)) {		
					$return = $this->getMapItemTree($tree, $return, $key, $level+1);
				}	
			}
		}
		return $return;		
	}
	
	/**
	 * Return all map items ready to be linked
	 * @param object
	 * @return array
	 */
	public function get_link_items(DataContainer $dc)
	{

		$maps = $this->Database->prepare ( "SELECT id,pid,name FROM tl_c4g_maps WHERE published=1 AND location_type<>'link' ORDER BY pid,sorting" )->execute ();
		if ($maps->numRows > 0) {
			while ( $maps->next () ) {
				$tree [$maps->pid][$maps->id] = $maps->name;
			}			
		}
		return $this->getMapItemTree($tree,array(),0,0);
	}
	
	/**
	 * Generate the icons to be used 
	 */
	public function generateLabel($row, $label, $dc_table, $folderAttribute)
	{
		if ($row['is_map']) {
		 	if ($row['location_type']<>'none') {
		     	$image = 'system/modules/con4gis_maps/html/map_location';
			}
			else {
		    	$image = 'system/modules/con4gis_maps/html/map';		
			}
		}
		else if ($row['location_type']=='link') {
		    $image = 'system/modules/con4gis_maps/html/link';
		} 
		else if ($row['location_type']<>'none') {
		    $image = 'system/modules/con4gis_maps/html/location';
		} 
		else {
		    $image = 'system/modules/con4gis_maps/html/mapfolder';
		}
		if (!$row['published']) {
			$image .= '_1';
		}
		$image .= '.png';
		return $this->generateImage($image, '', $folderAttribute) . ' ' . $label;
	}
	
	/**
	 * Update the palette information that depend on other values
	 */
	public function updateDCA(DataContainer $dc)
	{
	    if (!$dc->id) {
	    	return;
	    }	    
		$objMap = $this->Database->prepare("SELECT is_map,calc_extent,restrict_area,geolocation,auto_width,auto_height FROM tl_c4g_maps WHERE id=?")
			->limit(1)
			->execute($dc->id);
		if ($objMap->numRows > 0) {	
			if ($objMap->calc_extent=='CENTERZOOM') {
				$calcExtentFields = 'center_geox,center_geoy,zoom,';
			} 
			else {
				$calcExtentFields = 'min_gap,';				
			}
			if ($objMap->geolocation) {
				$geolocationFields = 'geolocation_zoom,';
			} 
			else {
				$geolocationFields = '';				
			}
			if ($objMap->restrict_area) {
		  		$restrictAreaFields = 'restr_bottomleft_geox,restr_bottomleft_geoy,restr_topright_geox,restr_topright_geoy,';
			} 
			else {
		  		$restrictAreaFields = '';
			}
			if ($objMap->auto_height) {
		  		$autoHeightFields = 'auto_height_gap,auto_height_min,auto_height_max,';
			} 
			else {
		  		$autoHeightFields = '';
			}	
			if ($objMap->auto_width) {
				$autoWidthFields = 'auto_width_gap,auto_width_min,auto_width_max,';
			}
			else {
				$autoWidthFields = '';
			}				
			$mapsize = 'mapsize,';
			if ($objMap->auto_height && $objMap->auto_width) {
				$mapsize = '';
			}  
			$GLOBALS['TL_DCA']['tl_c4g_maps']['subpalettes']['is_map'] =
		   		$mapsize.'auto_width,'.$autoWidthFields.'auto_height,'.$autoHeightFields.'calc_extent,'.$calcExtentFields.'geolocation,'.$geolocationFields.'restrict_area,'.$restrictAreaFields.',include_sublocations;';
		}

	}

	/**
	 * determine the default profile
	 */
	public function getDefaultProfile($varValue, DataContainer $dc)
	{
		if (!$varValue) {	
			if ($dc->activeRecord->pid) {
				// take default profile from parent entry
				$objParent = $this->Database->prepare("SELECT profile FROM tl_c4g_maps WHERE id=?")
					->limit(1)->execute($dc->activeRecord->pid);
				if ($objParent->numRows > 0) {
					$varValue = $objParent->profile;	
				}						
			}
			if (!$varValue) {
				// get default profile 	
				$objProfile = $this->Database->prepare("SELECT id FROM tl_c4g_map_profiles WHERE is_default=1")
					->limit(1)->execute();
				if ($objProfile->numRows > 0) {
					$varValue = $objProfile->id;	
				}		
			}
		}	
		return $varValue;
	}


	/**
	 * Validate Center GeoX
	 */
	public function setCenterGeoX($varValue, DataContainer $dc)
	{
		if ($dc->activeRecord->calc_extent == 'CENTERZOOM') {
			if (!C4GMaps::validateGeoX($varValue)) {
				throw new Exception($GLOBALS['TL_LANG']['c4g_maps']['geox_invalid']);
			}
		}
		return $varValue;
	}

	/**
	 * Validate Center GeoY
	 */
	public function setCenterGeoY($varValue, DataContainer $dc)
	{
		if ($dc->activeRecord->calc_extent == 'CENTERZOOM') {
			if (!C4GMaps::validateGeoY($varValue)) {
				throw new Exception($GLOBALS['TL_LANG']['c4g_maps']['geoy_invalid']);
			}
		}	
		return $varValue;
	}


	/**
	 * Validate restricted GeoX
	 */
	public function setRestrGeoX($varValue, DataContainer $dc)
	{
		if ($dc->activeRecord->restrict_area) {
			if (!C4GMaps::validateGeoX($varValue)) {
				throw new Exception($GLOBALS['TL_LANG']['c4g_maps']['geox_invalid']);
			}
		}	
		return $varValue;
	}

	/**
	 * Validate restricted GeoY
	 */
	public function setRestrGeoY($varValue, DataContainer $dc)
	{
		if ($dc->activeRecord->restrict_area) {
			if (!C4GMaps::validateGeoY($varValue)) {
				throw new Exception($GLOBALS['TL_LANG']['c4g_maps']['geoy_invalid']);
			}	
		}
		return $varValue;
	}

	/**
	 * Validate Location GeoX
	 */
	public function setLocGeoX($varValue, DataContainer $dc)
	{
		if (!C4GMaps::validateGeoX($varValue)) {
			throw new Exception($GLOBALS['TL_LANG']['c4g_maps']['geox_invalid']);
		}
		return $varValue;
	}
	
	/**
	 * Validate Location GeoY
	 */
	public function setLocGeoY($varValue, DataContainer $dc)
	{
		if (!C4GMaps::validateGeoY($varValue)) {
			throw new Exception($GLOBALS['TL_LANG']['c4g_maps']['geoy_invalid']);
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

	/**
	 * Return the Geo Feature Editor Wizard
	 * @param object
	 * @return string
	 */
	public function geoFeatureEditor(DataContainer $dc)
	{
	    $wizard = false;
		if ($dc) {
			$objMaps = $this->Database->prepare("SELECT location_type FROM tl_c4g_maps WHERE id=?")
			->limit(1)
			->execute($dc->id);
			
			$wizard = ($objMaps->location_type == 'geojson');
		}	
		if ($wizard) {
			$strField = 'ctrl_' . $dc->field . (($this->Input->get('act') == 'editAll') ? '_' . $dc->id : '');
			return ' ' . $this->generateImage('system/modules/con4gis_maps/html/geopicker.png', $GLOBALS['TL_LANG']['c4g_maps']['geofeatureeditor'], 'style="vertical-align:top; cursor:pointer;" onclick="C4GMapsBackend.editFeatures(\'' . $strField . '\',\'' . $dc->id . '\')"');
		}
		else {
			return false;
		}	
	}	
	
	/**
	 * Return the "toggle visibility" button
	 */
	public function toggleIcon($row, $href, $label, $title, $icon, $attributes)
	{
		if (strlen($this->Input->get('tid')))
		{
			$this->toggleVisibility($this->Input->get('tid'), ($this->Input->get('state') == 1));
			$this->redirect($this->getReferer());
		}

		// Check permissions AFTER checking the tid, so hacking attempts are logged
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_c4g_maps::published', 'alexf'))
		{
			return '';
		}

		$href .= '&amp;tid='.$row['id'].'&amp;state='.($row['published'] ? '' : 1);

		if (!$row['published'])
		{
			$icon = 'invisible.gif';
		}		

		return '<a href="'.$this->addToUrl($href).'" title="'.specialchars($title).'"'.$attributes.'>'.$this->generateImage($icon, $label).'</a> ';
	}


	/**
	 * Disable/enable an element
	 * @param integer
	 * @param boolean
	 */
	public function toggleVisibility($intId, $blnVisible)
	{
		// Check permissions to publish
		if (!$this->User->isAdmin && !$this->User->hasAccess('tl_c4g_maps::published', 'alexf'))
		{
			$this->log('Not enough permissions to publish/unpublish C4GMaps ID "'.$intId.'"', 'tl_c4g_maps toggleVisibility', TL_ERROR);
			$this->redirect('contao/main.php?act=error');
		}

		$this->createInitialVersion('tl_c4g_maps', $intId);
	
		// Trigger the save_callback
		if (is_array($GLOBALS['TL_DCA']['tl_c4g_maps']['fields']['published']['save_callback']))
		{
			foreach ($GLOBALS['TL_DCA']['tl_c4g_maps']['fields']['published']['save_callback'] as $callback)
			{
				$this->import($callback[0]);
				$blnVisible = $this->$callback[0]->$callback[1]($blnVisible, $this);
			}
		}

		// Update the database
		$this->Database->prepare("UPDATE tl_c4g_maps SET tstamp=". time() .", published='" . ($blnVisible ? 1 : '') . "' WHERE id=?")
					   ->execute($intId);

		$this->createNewVersion('tl_c4g_maps', $intId);
	}
	
	/**
	 * Return the page pick wizard for the linkUrl
	 * @param DataContainer $dc
	 */
	public function pickUrl(DataContainer $dc)
	{
		if (version_compare(VERSION,'3','<')) {
			$strField = 'ctrl_' . $dc->field . (($this->Input->get('act') == 'editAll') ? '_' . $dc->id : '');
			return ' ' . $this->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top; cursor:pointer;" onclick="Backend.pickPage(\'' . $strField . '\')"');
		}
		else {
			return ' <a href="contao/page.php?do='.Input::get('do').'&amp;table='.$dc->table.'&amp;field='.$dc->field.'&amp;value='.str_replace(array('{{link_url::', '}}'), '', $dc->value).'" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['pagepicker']).'" onclick="Backend.getScrollOffset();Backend.openModalSelector({\'width\':765,\'title\':\''.specialchars(str_replace("'", "\\'", $GLOBALS['TL_LANG']['MOD']['page'][0])).'\',\'url\':this.href,\'id\':\''.$dc->field.'\',\'tag\':\'ctrl_'.$dc->field . ((Input::get('act') == 'editAll') ? '_' . $dc->id : '').'\',\'self\':this});return false">' . $this->generateImage('pickpage.gif', $GLOBALS['TL_LANG']['MSC']['pagepicker'], 'style="vertical-align:top;cursor:pointer"') . '</a>';
		}
		
	}

	
}
?>