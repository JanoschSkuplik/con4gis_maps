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
 * Table tl_c4g_stuffprofiles
 */
$GLOBALS['TL_DCA']['tl_c4g_map_profiles'] = array
(

  // Config
  'config' => array
  (
    'dataContainer'         => 'Table',
    'enableVersioning'      => true,
    'onload_callback'       => array(array('tl_c4g_map_profiles', 'updateDCA'))
  ),

  // List
  'list' => array
  (
    'sorting' => array
    (
      'mode'                    => 1,
      'fields'                  => array('name'),
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
        'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['edit'],
        'href'                => 'act=edit',
        'icon'                => 'edit.gif'
      ),
      'copy' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['copy'],
        'href'                => 'act=copy',
        'icon'                => 'copy.gif'
      ),
      'delete' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['delete'],
        'href'                => 'act=delete',
        'icon'                => 'delete.gif',
        'attributes'          => 'onclick="if (!confirm(\'' . $GLOBALS['TL_LANG']['MSC']['deleteConfirm'] . '\')) return false; Backend.getScrollOffset();"'
      ),
      'show' => array
      (
        'label'               => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['show'],
        'href'                => 'act=show',
        'icon'                => 'show.gif'
      )
    )
  ),

  // Palettes
  'palettes' => array
  (
    '__selector__'                => array('mouse_nav','attribution','hover_popups','geosearch','geopicker','router'),
    'default'                     => '{general_legend},name,is_default,theme;'.
                                      '{baselayer_legend:hide},baselayers;'.
                                      '{locstyle_legend:hide},locstyles;'.
                                      '{navigation_legend},pan_panel,zoom_panel,mouse_nav,keyboard_nav,nav_history,fullscreen;'.
                                      '{tool_legend},measuretool,graticule,editor;'.
                                      '{information_legend},attribution, overviewmap,scaleline,mouseposition,permalink,zoomlevel;'.
                                      '{geosearch_legend:hide},geosearch;'.
                                      '{geopicker_legend:hide},geopicker;'.
                                      '{router_legend:hide},router;'.
                                      '{editor_legend:hide},editor_styles_point,editor_styles_line,editor_styles_polygon,editor_vars,editor_show_items,editor_helpurl;'.
                                      '{expert_legend:hide},libsource,imagepath,script,overpass_url,custom_div;'.
                                      '{misc_legend:hide},link_newwindow,link_open_on,hover_popups,div_layerswitcher,label_baselayer,label_overlays'


  ),


  // Subpalettes
  'subpalettes' => array
  (
    'mouse_nav'                   => 'mouse_nav_wheel,mouse_nav_zoombox,mouse_nav_kinetic,mouse_nav_toolbar',
    'attribution'                 => 'cfg_logo_attribution,div_attribution,add_attribution',
    'hover_popups'                => 'hover_popups_stay',
    'geosearch'                   => 'geosearch_engine,geosearch_div,geosearch_zoomto,geosearch_zoombounds,geosearch_attribution',
    'geopicker'                   => 'geopicker_fieldx,geopicker_fieldy,geopicker_searchdiv,geopicker_attribution',
    'router'                      => 'router_viaroute_url,router_attribution'
  ),

  // Fields
  'fields' => array
  (
    'name' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['name'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('mandatory'=>true, 'maxlength'=>30, 'tl_class'=>'w50')
    ),

    'is_default' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['is_default'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox',
      'eval'                    => array('tl_class'=>'w50', 'maxlength'=>30),
        'save_callback'           => array(array('tl_c4g_map_profiles','set_default'))
    ),

    'theme' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['theme'],
      'exclude'                 => true,
      'inputType'               => 'select',
      'options'                 => array('dark','modern','metro','con4gis','blue','red'),
      'eval'                    => array('tl_class'=>'clr','includeBlankOption' => true, blankOptionLabel => $GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']['default_theme']),
      'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references']

    ),

    'baselayers' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['baselayers'],
      'exclude'                 => true,
      'inputType'               => 'checkbox',
      'options_callback'        => array('tl_c4g_map_profiles','getAllBaseLayers'),
      'eval'                    => array('mandatory'=>false, 'multiple'=>true)
    ),

    'locstyles' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['locstyles'],
      'exclude'                 => true,
      'inputType'               => 'checkbox',
      'options_callback'        => array('tl_c4g_map_profiles','getAllLocStyles'),
      'eval'                    => array('mandatory'=>false, 'multiple'=>true)
    ),

    'pan_panel' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['pan_panel'],
      'exclude'                 => true,
      'default'                 => true,
      'inputType'               => 'checkbox'
    ),

    'zoom_panel' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['zoom_panel'],
      'exclude'                 => true,
      'default'                 => '1',
      'inputType'               => 'radio',
      'options'                 => array('1','2','3'),
      'eval'                    => array('submitOnChange' => true,'includeBlankOption' => true, blankOptionLabel => $GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_zoom_panel']['no_zoom_panel']),
      'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_zoom_panel']
    ),

    'zoom_panel_world' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['zoom_panel_world'],
      'exclude'                 => true,
      'default'                 => true,
      'inputType'               => 'checkbox'
    ),

    'mouse_nav' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav'],
      'exclude'                 => true,
      'default'                 => true,
      'inputType'               => 'checkbox',
      'eval'                    => array('submitOnChange' => true)
    ),

    'mouse_nav_wheel' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav_wheel'],
      'exclude'                 => true,
      'default'                 => true,
      'inputType'               => 'checkbox'
    ),

    'mouse_nav_zoombox' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav_zoombox'],
      'exclude'                 => true,
      'default'                 => true,
      'inputType'               => 'checkbox'
    ),

    'mouse_nav_kinetic' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav_kinetic'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'mouse_nav_toolbar' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouse_nav_toolbar'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'keyboard_nav' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['keyboard_nav'],
      'exclude'                 => true,
      'default'                 => true,
      'inputType'               => 'checkbox'
    ),

    'nav_history' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['nav_history'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'fullscreen' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['fullscreen'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'measuretool' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['measuretool'],
      'exclude'                 => true,
      'default'                 => null,
      'inputType'               => 'radio',
      'options'         => array('1','2'),
      'eval'            => array('includeBlankOption' => true, blankOptionLabel => $GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_measuretool']['no_measuretool']),
      'reference'             => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_measuretool']
    ),

    'graticule' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['graticule'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'editor' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor'],
      'exclude'                 => true,
      'inputType'               => 'checkbox',
      'eval'                    => array('mandatory'=>false)
    ),

    'attribution' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['attribution'],
      'exclude'                 => true,
      'default'                 => true,
      'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange' => true)
    ),

    'cfg_logo_attribution' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['cfg_logo_attribution'],
      'exclude'                 => true,
      'default'                 => true,
      'inputType'               => 'checkbox'
    ),

    'div_attribution' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['div_attribution'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('maxlength'=>30)
    ),

    'add_attribution' => array
    (
      'label'           => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['add_attribution'],
      'filter'          => false,
      'inputType'           => 'text',
      'eval'                    => array('maxlength'=>255, 'tl_class'=>'long', 'allowHtml' => true)
    ),

    'overviewmap' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['overviewmap'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'scaleline' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['scaleline'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'mouseposition' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['mouseposition'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'permalink' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['permalink'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'zoomlevel' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['zoomlevel'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'geosearch' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch'],
      'exclude'                 => true,
      'default'                 => '',
      'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange' => true)
    ),

    'geosearch_engine' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_engine'],
      'exclude'                 => true,
      'default'                 => '2',
      'inputType'               => 'radio',
      'options'         => array('2','1','3'),
      'eval'            => array('submitOnChange' => true,'includeBlankOption' => false ),
      'reference'             => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references_geosearch_engine']
    ),

    'geosearch_customengine_url' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_customengine_url'],
      'exclude'                 => true,
      'inputType'               => 'text'
    ),

    'geosearch_customengine_attribution' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_customengine_attribution'],
      'exclude'                 => true,
      'inputType'               => 'text'
    ),

    'geosearch_div' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_div'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('maxlength'=>30)
    ),

    'geosearch_zoomto' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_zoomto'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('rgxp'=>'digit')
    ),

    'geosearch_zoombounds' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_zoombounds'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'geosearch_attribution' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geosearch_attribution'],
      'exclude'                 => true,
      'default'                 => true,
      'inputType'               => 'checkbox'
    ),

    'geopicker' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker'],
      'exclude'                 => true,
      'default'                 => '',
      'inputType'               => 'checkbox',
      'eval'                    => array('submitOnChange' => true)
    ),

    'geopicker_fieldx' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_fieldx'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('maxlength'=>30, 'mandatory'=>true)
    ),

    'geopicker_fieldy' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_fieldy'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('maxlength'=>30, 'mandatory'=>true)
    ),

    'geopicker_searchdiv' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_searchdiv'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('maxlength'=>30, 'mandatory'=>true)
    ),

    'geopicker_attribution' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['geopicker_attribution'],
      'exclude'                 => true,
      'default'                 => true,
      'inputType'               => 'checkbox'
    ),

    'router' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['router'],
      'exclude'                 => true,
      'default'                 => '',
      'inputType'               => 'checkbox',
      'eval'                    => array('submitOnChange' => true)
    ),

    'router_viaroute_url' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['router_viaroute_url'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
    ),

    'router_attribution' => array
    (
      'label'           => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['router_attribution'],
      'filter'          => false,
      'inputType'           => 'text',
      'eval'                    => array('maxlength'=>255, 'tl_class'=>'long', 'allowHtml' => true)
    ),

    'libsource' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['libsource'],
      'exclude'                 => true,
      'inputType'               => 'select',
      'default'                 => 'DEFAULT',
        'options_callback'        => array('tl_c4g_map_profiles','getLibSources'),
        'eval'                    => array('submitOnChange' => true, 'tl_class' => 'long'),
    ),

    'imagepath' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['imagepath'],
      'exclude'                 => true,
      'inputType'               => 'fileTree',
        'eval'            => array( 'trailingSlash' => false, 'files' => false, 'fieldType' => 'radio' )

    ),

    'script' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['script'],
      'exclude'                 => true,
      'search'                  => true,
      'inputType'               => 'textarea',
      'eval'                    => array('style'=>'height:120px;', 'preserveTags'=>true)
    ),

    'overpass_url' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['overpass_url'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'long'),
    ),

    'custom_div' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['custom_div'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('maxlength'=>30)
    ),

    'link_newwindow' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['link_newwindow'],
      'exclude'                 => true,
      'default'                 => '',
      'inputType'               => 'checkbox',
    ),

    'link_open_on' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['link_open_on'],
      'exclude'                 => true,
      'default'                 => 'CLICK',
      'inputType'               => 'radio',
        'options'                 => array('CLICK','DBLCL'),
      'reference'               => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['references'],
    ),

    'hover_popups' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['hover_popups'],
      'exclude'                 => true,
      'default'                 => '',
      'inputType'               => 'checkbox',
            'eval'                    => array('submitOnChange' => true)
    ),

    'hover_popups_stay' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['hover_popups_stay'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'div_layerswitcher' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['div_layerswitcher'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('maxlength'=>30)
    ),

    'label_baselayer' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['label_baselayer'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('maxlength'=>30)
    ),

    'label_overlays' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['label_overlays'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('maxlength'=>30)
    ),

    'editor_styles_point' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_styles_point'],
      'exclude'                 => true,
      'inputType'               => 'checkbox',
      'options_callback'        => array('tl_c4g_map_profiles','getAllLocStyles'),
      'eval'                    => array('mandatory'=>false, 'multiple'=>true)
    ),

    'editor_styles_line' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_styles_line'],
      'exclude'                 => true,
      'inputType'               => 'checkbox',
      'options_callback'        => array('tl_c4g_map_profiles','getAllLocStyles'),
      'eval'                    => array('mandatory'=>false, 'multiple'=>true)
    ),

    'editor_styles_polygon' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_styles_polygon'],
      'exclude'                 => true,
      'inputType'               => 'checkbox',
      'options_callback'        => array('tl_c4g_map_profiles','getAllLocStyles'),
      'eval'                    => array('mandatory'=>false, 'multiple'=>true)
    ),

    'editor_vars' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_vars'],
      'inputType'               => 'keyValueWizard',
      'exclude'                 => true
    ),

    'editor_show_items' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_show_items'],
      'exclude'                 => true,
      'default'                 => false,
      'inputType'               => 'checkbox'
    ),

    'editor_helpurl' => array
    (
      'label'                   => &$GLOBALS['TL_LANG']['tl_c4g_map_profiles']['editor_helpurl'],
      'exclude'                 => true,
      'inputType'               => 'text',
      'eval'                    => array('rgxp'=>'url', 'decodeEntities'=>true, 'maxlength'=>255, 'tl_class'=>'wizard'),
      'wizard'          => array(array('tl_c4g_map_profiles', 'pickUrl'))
    ),


    )
);

/**
 * Class tl_c4g_map_profiles
 *
 * Provide methods that are used by the data configuration array.
 * 
 */
class tl_c4g_map_profiles extends Backend {

  public function set_default($varValue, DataContainer $dc)
  {
    if ($varValue) {
      $this->Database->query('UPDATE tl_c4g_map_profiles SET is_default="" WHERE id <> '.$dc->id);
    }
    return $varValue;
  }

  /**
   * Return all available OpenLayers Libraries
   * @param object
   * @return array
   */
  public function getLibSources(DataContainer $dc)
  {
    $return = array();
    foreach ($GLOBALS['con4gis_maps_extension']['js_openlayers_libs'] as $key=>$lib)
    {
      $return[$key] = $lib;
    }
    return $return;
  }

  /**
   * Return all Location Styles as array
   * @param object
   * @return array
   */
  public function getAllLocStyles(DataContainer $dc)
  {
    $locStyles = $this->Database->prepare("SELECT id,name FROM tl_c4g_map_locstyles ORDER BY name")
      ->execute();
    while ($locStyles->next())
    {
      $return[$locStyles->id] = $locStyles->name;
    }
    return $return;
  }

  /**
   * Return all Base Layers as array
   * @param object
   * @return array
   */
  public function getAllBaseLayers(DataContainer $dc)
  {
    $baseLayers = $this->Database->prepare("SELECT id,name FROM tl_c4g_map_baselayers ORDER BY name")
      ->execute();
    while ($baseLayers->next())
    {
      $return[$baseLayers->id] = $baseLayers->name;
    }
    return $return;
  }

  /**
   * Update the palette information that depend on other values
   */
  public function updateDCA(DataContainer $dc)
  {
    if (!$dc->id) {
      return;
    }
    $objProfile = $this->Database->prepare("SELECT zoom_panel, geosearch_engine FROM tl_c4g_map_profiles WHERE id=?")
    ->limit(1)
    ->execute($dc->id);
    if ($objProfile->numRows > 0) {
      if ($objProfile->zoom_panel == '1') {
        $GLOBALS['TL_DCA']['tl_c4g_map_profiles']['palettes']['default'] =
          str_replace(',zoom_panel,',',zoom_panel,zoom_panel_world,',
            $GLOBALS['TL_DCA']['tl_c4g_map_profiles']['palettes']['default']);
      }
      if ($objProfile->geosearch_engine == '3') {
        $GLOBALS['TL_DCA']['tl_c4g_map_profiles']['subpalettes']['geosearch'] =
          str_replace(',geosearch_div,',',geosearch_customengine_url,geosearch_customengine_attribution,geosearch_div,',
            $GLOBALS['TL_DCA']['tl_c4g_map_profiles']['subpalettes']['geosearch']);
      }
    }

  }

  /**
   * Return the page pick wizard for the editor_helpurl
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
