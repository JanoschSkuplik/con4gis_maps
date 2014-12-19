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
 * Table tl_c4g_map_locstyles
 */
$GLOBALS['TL_DCA']['tl_c4g_map_locstyles'] = array
(
	
    // Config
	'config' => array
	(
		'dataContainer'               => 'Table',
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
			'flag'                    => 1,				
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
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['edit'],
				'href'                => 'act=edit',
				'icon'                => 'edit.gif'
			),
			'copy' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['copy'],
				'href'                => 'act=copy',
				'icon'                => 'copy.gif'
			),
			'delete' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['delete'],
				'href'                => 'act=delete',
				'icon'                => 'delete.gif',
				'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
			),
			'show' => array
			(
				'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['show'],
				'href'                => 'act=show',
				'icon'                => 'show.gif'
			)
		)
	),

		
	// Palettes
	'palettes' => array
	(
		'__selector__'                => array('styletype','line_arrows'),
		'default'                     => 'name,styletype,strokewidth,strokecolor,strokeopacity,fillcolor,fillopacity,radius,onhover_locstyle;'.
										 '{arrow_legend},line_arrows;'.
		                                 '{label_legend},label,label_align_hor,label_align_ver,label_offset,font_family,font_color,font_size,label_outl_color,label_outl_width,font_opacity,font_style,font_weight;'.
	                                     '{popup_legend},popup_info,popup_kind,popup_size,popup_offset;'.
	                                     '{zoom_legend:hide},onclick_zoomto,minzoom,maxzoom;'.
	                                     '{editor_legend:hide},editor_icon,editor_vars,editor_collect;',
		'ol_icon'                     => 'name,styletype,ol_icon,ol_icon_size,ol_icon_offset,icon_opacity,onhover_locstyle;'.
		                                 '{label_legend},label,label_align_hor,label_align_ver,label_offset,font_family,font_color,font_size,label_outl_color,label_outl_width,font_opacity,font_style,font_weight;'.
	                                     '{popup_legend},tooltip,popup_info,popup_kind,popup_size,popup_offset;'.
	                                     '{zoom_legend:hide},onclick_zoomto,minzoom,maxzoom;'.
   	                                     '{editor_legend:hidde},editor_icon,editor_vars;',
	    'cust_icon'                   => 'name,styletype,icon_src,icon_size,icon_offset,icon_opacity,onhover_locstyle;'.
		                                 '{label_legend},label,label_align_hor,label_align_ver,label_offset,font_family,font_color,font_size,label_outl_color,label_outl_width,font_opacity,font_style,font_weight;'.
	                                     '{popup_legend},tooltip,popup_info,popup_kind,popup_size,popup_offset;'.
	                                     '{zoom_legend:hide},onclick_zoomto,minzoom,maxzoom;'.
		                                 '{editor_legend:hide},editor_icon,editor_vars,editor_collect;',
	),		                                 
		                                 
    // Subpalettes
    'subpalettes' => array
    (
   		'line_arrows'                 => 'line_arrows_back,line_arrows_radius,line_arrows_minzoom',
    ),
		                                  

	// Fields
	'fields' => array
	(
		'name' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['name'],
			'exclude'                 => true,
		    'search'                  => true,
		    'sorting'			      => true,
		    'filter'			      => true,
		    'flag'                    => 1,
			'inputType'               => 'text',
			'eval'                    => array('mandatory'=>true, 'maxlength'=>50)
		),
		'styletype' => array
		(
			'label'					=> &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['styletype'],
			'filter'				=> true,
			'inputType' 		    => 'select',
		    'default'               => 'point',
		    'options'               => array('point', 'square', 'star', 'x', 'cross', 'triangle', 'ol_icon', 'cust_icon' ),
		    'reference'             => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references'],
		    'eval'                  => array('submitOnChange'=>'true')
		),	
		'strokecolor' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['strokecolor'],
			'default'                 => 'ee0016',
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>6, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard', 'mandatory'=>true ),
			'wizard' => array
			(
				array('tl_c4g_map_locstyles', 'getColorPicker')
			)
		),
		'strokewidth' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['strokewidth'],
			'inputType'               => 'inputUnit',
			'default'                 => '2',
			'options'                 => array('px'),
			'eval'                    => array('rgxp'=>'digit', 'mandatory'=>true)
		),
		'strokeopacity' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['strokeopacity'],
			'inputType'               => 'inputUnit',
			'default'                 => '100',
			'options'                 => array('%'),
			'eval'                    => array('rgxp'=>'prcnt', 'tl_class'=>'w50', 'mandatory'=>true)
		),
		'fillcolor' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['fillcolor'],
			'inputType'               => 'text',
			'default'                 => 'ee0011',
			'eval'                    => array('maxlength'=>6, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard', 'mandatory'=>true),
			'wizard' => array
			(
				array('tl_c4g_map_locstyles', 'getColorPicker')
			)
		),
		'fillopacity' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['fillopacity'],
			'inputType'               => 'inputUnit',
			'default'                 => '50',
			'options'                 => array('%'),
			'eval'                    => array('rgxp'=>'prcnt', 'tl_class'=>'w50', 'mandatory'=>true)
		),
		'radius' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['radius'],
			'inputType'               => 'inputUnit',
			'default'                 => '7',
			'options'                 => array('px'),
			'eval'                    => array('rgxp'=>'digit', 'mandatory'=>true)
		),
		'ol_icon' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['ol_icon'],
			'exclude'                 => true,
			'inputType'               => 'radio',
		    'options'                 => array('marker.png','marker-blue.png','marker-gold.png','marker-green.png'),
		    'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references'],
		    'eval'                    => array('mandatory'=>true)
		),
		'ol_icon_size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['ol_icon_size'],
			'default'                 => array('21','25','px'),
			'exclude'                 => true,
			'inputType'               => 'imageSize',
			'options'                 => array('px'),
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'mandatory'=>true)
		),
		'ol_icon_offset' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['ol_icon_offset'],
			'default'                 => array('-10','-25','px'),
			'exclude'                 => true,
			'inputType'               => 'imageSize',
			'options'                 => array('px'),
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'mandatory'=>false)
		),
		'icon_src' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['icon_src'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'extensions'=>'gif,jpg,jpeg,png', 'tl_class'=>'clr', 'mandatory'=>true)
		),
		'icon_size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['icon_size'],
			'default'                 => array('16','16','px'),
			'exclude'                 => true,
			'inputType'               => 'imageSize',
			'options'                 => array('px'),
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'mandatory'=>true)
		),
		'icon_offset' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['icon_offset'],
			'exclude'                 => true,
			'default'                 => array('0','0','px'),
			'inputType'               => 'imageSize',
			'options'                 => array('px'),
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'icon_opacity' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['icon_opacity'],
			'inputType'               => 'inputUnit',
			'default'                 => '100',
			'options'                 => array('%'),
			'eval'                    => array('rgxp'=>'prcnt', 'tl_class'=>'clr', 'mandatory'=>true)
		),
		
		'onhover_locstyle' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['onhover_locstyle'],
			'exclude'                 => true,
			'inputType'               => 'select',
			'options_callback'        => array('tl_c4g_map_locstyles','getLocStyles', 'includeBlankOption' => true)
		),		
		
		'line_arrows' => array
		(
				'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['line_arrows'],
				'exclude'                 => true,
				'default'                 => '',
				'inputType'               => 'checkbox',
				'eval'                    => array('submitOnChange' => true)
		),

		'line_arrows_back' => array
		(
				'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['line_arrows_back'],
				'exclude'                 => true,
				'default'                 => '',
				'inputType'               => 'checkbox',
		),
		
		'line_arrows_radius' => array
		(
				'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['line_arrows_radius'],
				'inputType'               => 'inputUnit',
				'default'                 => '4',
				'options'                 => array('px'),
				'eval'                    => array('rgxp'=>'digit', 'mandatory'=>true)
		),
		'line_arrows_minzoom' => array
		(
				'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['line_arrows_minzoom'],
				'exclude'                 => true,
				'inputType'               => 'text',
				'default'                 => '0',
				'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'clr')
		),
		
		'label' => array		
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label'],
			'exclude'                 => true,
			'inputType'               => 'text'
		),
		'label_align_hor' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label_align_hor'],
			'exclude'                 => true,
			'inputType'               => 'radio',
		    'options'                 => array('left','center','right'),
		    'default'                 => 'center',
		    'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references'],
		    'eval'                    => array('tl_class'=>'w50')
		),

		'label_align_ver' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label_align_ver'],
			'exclude'                 => true,
			'inputType'               => 'radio',
		    'options'                 => array('top','middle','bottom'),
		    'default'                 => 'top',
		    'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references'],
		),
		'label_offset' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label_offset'],
			'exclude'                 => true,
			'default'                 => array('0','-8','px'),
			'inputType'               => 'imageSize',
			'options'                 => array('px'),
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'clr')
		),
		'font_family' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['font_family'],
			'exclude'                 => true,
			'inputType'               => 'text',
		),
		'font_color' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['font_color'],
			'default'                 => '',
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>6, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'),
			'wizard' => array
			(
				array('tl_c4g_map_locstyles', 'getColorPicker')
			)
		),
		'font_size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['font_size'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class' => 'w50')
		),
		'label_outl_color' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label_outl_color'],
			'default'                 => 'ee0016',
			'inputType'               => 'text',
			'eval'                    => array('maxlength'=>6, 'isHexColor'=>true, 'decodeEntities'=>true, 'tl_class'=>'w50 wizard'),
			'wizard' => array
			(
				array('tl_c4g_map_locstyles', 'getColorPicker')
			)
		),
		'label_outl_width' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['label_outl_width'],
			'inputType'               => 'inputUnit',
			'default'                 => '',
			'options'                 => array('px'),
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),		
		'font_opacity' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['font_opacity'],
			'inputType'               => 'inputUnit',
			'default'                 => '100',
			'options'                 => array('%'),
			'eval'                    => array('rgxp'=>'prcnt', 'tl_class'=>'clr')
		),

		'font_style' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['font_style'],
			'exclude'                 => true,
			'inputType'               => 'text',
		),
		
		'font_weight' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['font_weight'],
			'exclude'                 => true,
			'inputType'               => 'text',
		),				
		'tooltip' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['tooltip'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'eval'                    => array('tl_class'=>'long')
		),
		'popup_info' => array
		(
			'label'					  => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['popup_info'],
			'exclude'                 => true,
			'inputType'				  => 'textarea',
			'eval'					  => array('rte'=>'tinyMCE'),
		),
		'popup_kind' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['popup_kind'],
			'default'                 => 'cloud',
			'exclude'                 => true,
			'inputType'               => 'radio',
			'options'                 => array('bubble','cloud'),
		    'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['references'],
		    'eval'                    => array('mandatory'=>true)
		),
		'popup_size' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['popup_size'],
			'default'                 => array('200','200','px'),
			'exclude'                 => true,
			'inputType'               => 'imageSize',
			'options'                 => array('px'),
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50', 'mandatory'=>true)
		),
		'popup_offset' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['popup_offset'],
			'exclude'                 => true,
			'default'                 => array('0','0','px'),
			'inputType'               => 'imageSize',
			'options'                 => array('px'),
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'w50')
		),
		'onclick_zoomto' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['onclick_zoomto'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'clr')
		),
		'minzoom' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['minzoom'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'clr')
		),
		'maxzoom' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['maxzoom'],
			'exclude'                 => true,
			'inputType'               => 'text',
			'default'                 => '0',
			'eval'                    => array('rgxp'=>'digit', 'tl_class'=>'clr')
		),		
		'editor_icon' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['editor_icon'],
			'exclude'                 => true,
			'inputType'               => 'fileTree',
			'eval'                    => array('fieldType'=>'radio', 'files'=>true, 'extensions'=>'gif,jpg,jpeg,png', 'tl_class'=>'clr')
		),
		'editor_vars' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['editor_vars'],
			'inputType'               => 'keyValueWizard',
			'exclude'                 => true
		),

		'editor_collect' => array
		(
			'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_locstyles']['editor_collect'],
			'exclude'                 => true,
			'default'                 => '',
			'inputType'               => 'checkbox',
		),
		
		
	)
);

/**
 * Class tl_c4g_map_locstyles
 *
 * Provide methods that are used by the data configuration array.
 * 
 */
class tl_c4g_map_locstyles extends Backend
{

	/**
	 * Import CSS and script for the color picker component
	 */
	public function __construct()
	{
		parent::__construct();

		if (version_compare(VERSION,'2.11','<')) {
			$GLOBALS['TL_CSS'][] = 'plugins/mootools/rainbow.css?'. MOO_RAINBOW . '|screen';
			$GLOBALS['TL_JAVASCRIPT'][] = 'plugins/mootools/rainbow.js?' . MOO_RAINBOW;
		}	

	}
	
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
	 * Color picker wizard
	 * @param object
	 * @return string
	 */
	public function getColorPicker(DataContainer $dc)
	{
		if (version_compare(VERSION,'2.11','<')) {
			return ' ' . $this->generateImage('pickcolor.gif', $GLOBALS['TL_LANG']['MSC']['colorpicker'], 'style="vertical-align:top; cursor:pointer;" id="moo_'.$dc->field.'" class="mooRainbow"');
		} else if (version_compare(VERSION,'3.0','<')) {
			return ' ' . $this->generateImage('pickcolor.gif', $GLOBALS['TL_LANG']['MSC']['colorpicker'], 'style="vertical-align:top;cursor:pointer" id="moo_'.$dc->field.'"') . '
  <script>
  new MooRainbow("moo_'.$dc->field.'", {
    id:"ctrl_' . $dc->field . '",
    startColor:((cl = $("ctrl_' . $dc->field . '").value.hexToRgb(true)) ? cl : [255, 0, 0]),
    imgPath:"plugins/colorpicker/images/",
    onComplete: function(color) {
      $("ctrl_' . $dc->field . '").value = color.hex.replace("#", "");
    }
  });
  </script>';			
		} else {
			return ' ' . $this->generateImage('pickcolor.gif', $GLOBALS['TL_LANG']['MSC']['colorpicker'], 'style="vertical-align:top;cursor:pointer" id="moo_'.$dc->field.'"') . '
  <script>
  new MooRainbow("moo_'.$dc->field.'", {
    id:"ctrl_' . $dc->field . '",
    startColor:((cl = $("ctrl_' . $dc->field . '").value.hexToRgb(true)) ? cl : [255, 0, 0]),
    imgPath:"assets/mootools/colorpicker/1.3/images/",
    onComplete: function(color) {
      $("ctrl_' . $dc->field . '").value = color.hex.replace("#", "");
    }
  });
  </script>';
						
		}
	}	
}


?>
