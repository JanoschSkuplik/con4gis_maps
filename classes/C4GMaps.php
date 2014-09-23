<?php 

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Tobias Dobbrunz <http://www.kuestenschmiede.de>
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014
 * @link      https://www.kuestenschmiede.de
 * @filesource 
 */

namespace c4g;

/**
 * Class C4GMaps
 *
 * Static Function library for con4gis_maps
 */
class C4GMaps  
{
  private static $allLocstyles = false;

  /**
 	 * Validate a longitude coordinate
 	 * @param  [type] $value [description]
 	 * @return [type]        [description]
 	 */
  public static function validateLon ( $value )
  {
    if (C4GMaps::validateGeo( $value )) {
      return (($value >= -180.0) && ($value <= 180.0));
    }
    return false;
  }

  /**
 	 * Validate a latitude coordinate
 	 * @param  [type] $value [description]
 	 * @return [type]        [description]
 	 */
  public static function validateLat ( $value )
  {
    if (C4GMaps::validateGeo( $value )) {
      return (($value >= -90.0) && ($value <= 90.0));
    }
    return false;
  }

  /**
 	 * Validate a Geo Coordinate
 	 * @param  [type] $value [description]
 	 * @return [type]        [description]
 	 */
  public static function validateGeo ( $value )
  {
    if (!isset( $value )) {
      return false;
    }
    $value = floatval( $value );
    if ($value == 0) {
      return false;
    }
    return true;
  }

  /**
   * [prepareMapData description]
   * @param  [type] $objThis  [description]
   * @param  [type] $database [description]
   * @param  array  $options  [description]
   * @return [type]           [description]
   */
  public static function prepareMapData ( $objThis, $database, $options=array() )
  {
    $mapData = array();
    $mapData['mapId'] = $objThis->id;

    // import user, if not already done
    if (!isset( $objThis->User )) {
      $objThis->import('FrontendUser', 'User');
    }

    // get map
    $map = C4gMapsModel::findByPk( $objThis->c4g_map_id );
    if(!$map) return false;
    $mapData['id'] = $objThis->c4g_map_id;

    // ------------------------------------------------------------------------
    // get profile for map
    // ------------------------------------------------------------------------
    $profileId = $map->profile;
    // check for mobile-profile
    $isMobile = false;
    if (($map->profile_mobile > 0) && (\Input::cookie('TL_VIEW') == 'mobile' || (\Environment::get('agent')->mobile && \Input::cookie('TL_VIEW') != 'desktop')))
    {
      $isMobile = true;
      $profileId = $map->profile_mobile;
    }
    // check for special-profile
    if ((FE_USER_LOGGED_IN) && ($map->use_specialprofile)) {
      $groupMatch = array_intersect( $objThis->User->groups, deserialize( $map->specialprofile_groups ) );
      if (!empty( $groupMatch )) {
        if (($isMobile) && ($map->specialprofile_mobile)) {
          $profileId = $map->specialprofile_mobile;
        } else {
          $profileId = $map->specialprofile;
        }
      }
    }
    // get appropriate profile from database
    $profile = C4gMapProfilesModel::findByPk( $profileId );
    // use default if the profile was not found
    if (!$profile) {
      $profile = C4gMapProfilesModel::findByIs_default( true );
      if ($profile) {
        $profileId = $profile->id;
      } else {
        return false;
      }
    }
    $mapData['profile'] = $profileId;

    // ------------------------------------------------------------------------
    // set basic map options
    // ------------------------------------------------------------------------
    if ($map->id != 0) 
    {
      // map-center
      if (!empty( $map->center_geox )) {
        $mapData['center']['lon'] = $map->center_geox;
      }
      if (!empty( $map->center_geoy )) {
        $mapData['center']['lat'] = $map->center_geoy;
      }

      // map-zoom
      if (!empty( $map->zoom )) {
        $mapData['center']['zoom'] = $map->zoom;
      }

      // geolocation (use user-location, if possible)
      if ($map->geolocation) {
        $mapData['geolocation'] = true;
        $mapData['geolocation_zoom'] = $map->geolocation_zoom;				
      }

      // map-size
      $mapWidth = deserialize( $map->width );
      if (is_array( $mapWidth ) && ($mapWidth['value']!=0) && !empty($mapWidth['unit'])) {
        $mapData['width'] = $mapWidth['value'] . $mapWidth['unit'];
      }
      $mapHeight = deserialize( $map->height );
      if (is_array( $mapHeight ) && ($mapHeight['value']!=0) && !empty($mapHeight['unit'])) {
        $mapData['height'] = $mapHeight['value'] . $mapHeight['unit'];
      }

      // map-margin
      $mapMargin = deserialize( $map->margin );
      if (is_array( $mapMargin ) && !empty($mapMargin['unit'])) 
      {
        // [note]: inspired by contaos stylesheet-handling

        $top 	= $mapMargin['top'];
        $right 	= $mapMargin['right'];
        $bottom = $mapMargin['bottom'];
        $left 	= $mapMargin['left'];
        $unit 	= $mapMargin['unit'];

        // Try to shorten the definition
        if ($top != '' && $right != '' && $bottom != '' && $left != '')
        {
          if ($top == $right && $top == $bottom && $top == $left) {
            $mapData['margin'] = $top . (($top == 'auto' || $top === '0') ? '' : $unit);
          } elseif ($top == $bottom && $right == $left) {
            $mapData['margin'] = $top . (($top == 'auto' || $top === '0') ? '' : $unit) . ' ' . $right . (($right == 'auto' || $right === '0') ? '' : $unit);
          } else {
            $mapData['margin'] = $top . (($top == 'auto' || $top === '0') ? '' : $unit) . ' ' . $right . (($right == 'auto' || $right === '0') ? '' : $unit) . ' ' . $bottom . (($bottom == 'auto' || $bottom === '0') ? '' : $unit) . ' ' . $left . (($left == 'auto' || $left === '0') ? '' : $unit);
          }
        } else {
          $mapData['margin'] = ($top ? $top.$unit : '0') . ' ' . ($right ? $right.$unit : '0') . ' ' . ($bottom ? $bottom.$unit : '0') . ' ' . ($left ? $left.$unit : '0');
        }
        // [/note]
      }

      // map-extend
      $mapData['calc_extent'] = $map->calc_extent;
      if ($map->calc_extent=='LOCATIONS') {
        $mapData['min_gap'] = $map->min_gap;
      }

      // map-restriction
      $mapData['restrict_area'] = $map->restrict_area;
      if ($map->restrict_area) {
        $mapData['restr_bottomleft_lon'] = $map->restr_bottomleft_lon;
        $mapData['restr_bottomleft_lat'] = $map->restr_bottomleft_lat;
        $mapData['restr_topright_lon'] = $map->restr_topright_geox;
        $mapData['restr_topright_lat'] = $map->restr_topright_geoy;
      }
    }
    // override map-zoom from structure, with values from CE/FE, if set
    if (is_numeric ( $objThis->c4g_map_zoom ) && ($objThis->c4g_map_zoom > 0)) {
      $mapData['center']['zoom'] = $objThis->c4g_map_zoom;
    }
    // override map-size from structure, with values from CE/FE, if set
    $mapWidth = deserialize( $objThis->c4g_map_width );
    if (is_array( $mapWidth ) && ($mapWidth['value']!=0) && !empty($mapWidth['unit'])) {
      $mapData['width'] = $mapWidth['value'] . $mapWidth['unit'];
    }
    $mapHeight = deserialize( $objThis->c4g_map_height );
    if (is_array( $mapHeight ) && ($mapHeight['value']!=0) && !empty($mapHeight['unit'])) {
      $mapData['height'] = $mapHeight['value'] . $mapHeight['unit'];
    }

    // ------------------------------------------------------------------------
    // collect data from map profile
    // ------------------------------------------------------------------------
    if ($profile) 
    {
      // generel
      // 

      // basemaps
      // 

      // location-styles
      // 

      // map-navigation
      // 
      $mapData['zoom_panel'] = $profile->zoom_panel;
      $mapData['zoom_extent'] = $profile->zoom_panel_world;
      if ($profile->mouse_nav) {
        $mapData['mouse_nav']['drag_pan'] = $profile->mouse_nav;
        $mapData['mouse_nav']['wheel_zoom'] = $profile->mouse_nav_wheel;
        $mapData['mouse_nav']['drag_zoom'] = $profile->mouse_nav_zoombox;
        $mapData['mouse_nav']['kinetic'] = $profile->mouse_nav_kinetic;
        $mapData['mouse_nav']['toolbar'] = $profile->mouse_nav_toolbar;
      }
      if ($profile->keyboard_nav) {
        $mapData['keyboard_nav']['pan'] = $profile->keyboard_nav;
        $mapData['keyboard_nav']['zoom'] = $profile->keyboard_nav;
      }
      $mapData['fullscreen'] = $profile->fullscreen;

      // map-tools
      // 

      // map-information
      // 
      $mapData['attribution'] = $profile->attribution;
          if ($profile->attribution && $profile->cfg_logo_attribution) {
            $mapData['cfg_logo_attribution'] = $profile->cfg_logo_attribution;
          }
          if ($profile->div_attribution) {
            $mapData['div_attribution'] = $profile->div_attribution;
          }
          if ($profile->add_attribution) {
            $mapData['add_attribution'] = $profile->add_attribution;
          }
      $mapData['overviewmap'] = $profile->overviewmap;
      $mapData['scaleline'] = $profile->scaleline;
      $mapData['mouseposition'] = $profile->mouseposition;
      $mapData['zoomlevel'] = $profile->zoomlevel;

      // search
      // 

      // geopicker
      // 

      // router
      // 

      // editor
      // 

      // expert-configs
      // 

      // miscellaneous
      // 
    } 

    // -----
    // (...)
    // -----



    //[ DEV ]!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

    // mapservice
    $baseLayers = C4gMapBaselayersModel::findByPk( $objThis->c4g_map_default_mapservice );
    // while ($baseLayers->next()) {}
    if ($baseLayers) {
      $mapData['baseLayer']['id'] = $baseLayers->id;
      $mapData['baseLayer']['name'] = $baseLayers->display_name ?: $baseLayers->name;
      $mapData['baseLayer']['provider'] = $baseLayers->provider;
      switch ($baseLayers->provider) {
        case 'osm':
          $mapData['baseLayer']['style'] = $baseLayers->osm_style;
          if (!empty( $baseLayers->osm_keyname )) {
            $mapData['baseLayer']['apiKey'] = $baseLayers->osm_keyname;
          }
          // custom?
          if ($mapData['baseLayer']['style'] == 'osm_custom') {
            if (!empty( $baseLayers->osm_style_url1 ) && empty( $baseLayers->osm_style_url2 )) {
              $mapData['baseLayer']['url'] = $baseLayers->osm_style_url1;
            } else {
              if (!empty( $baseLayers->osm_style_url1 )) {
                $mapData['baseLayer']['urls'][] = $baseLayers->osm_style_url1;
              }
              if (!empty( $baseLayers->osm_style_url2 )) {
                $mapData['baseLayer']['urls'][] = $baseLayers->osm_style_url2;
              }
              if (!empty( $baseLayers->osm_style_url3 )) {
                $mapData['baseLayer']['urls'][] = $baseLayers->osm_style_url3;
              }
              if (!empty( $baseLayers->osm_style_url4 )) {
                $mapData['baseLayer']['urls'][] = $baseLayers->osm_style_url4;
              } 
            }
          }
          break;
        case 'google':
          $mapData['baseLayer']['style'] = $baseLayers->google_style;
          break;
        case 'bing':
          $mapData['baseLayer']['style'] = $baseLayers->bing_style;
          if (!empty( $baseLayers->bing_key )) {
            $mapData['baseLayer']['apiKey'] = $baseLayers->bing_key;
          }
          break;
        default:
          die('This should not have happened!');
      }
      if (!empty( $baseLayers->attribution )) {
        $mapData['baseLayer']['attribution'] = $baseLayers->attribution;
      }
      if (!empty( $baseLayers->maxzoomlevel )) {
        $mapData['baseLayer']['maxZoom'] = $baseLayers->maxzoomlevel;
      }
      if (!empty( $baseLayers->sort )) {
        $mapData['baseLayer']['sort'] = $baseLayers->sort;
      }
    }
    // CONTINUE HERE!!!

    // $GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['con4gis_core_extension']['jQuery-path'];
    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/con4gis_core/lib/jQuery/jquery-1.11.1.min.js';
    $GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['c4g_maps_extension']['js_openlayers']['DEFAULT'];
    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/con4gis_maps/assets/js/c4g-maps.js';
    $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/con4gis_maps/assets/js/c4g-map-starboard.js';

    // later: combine them
    // $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/con4gis_core/lib/jQuery/jquery-1.11.1.min.js|static';
    // $GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['c4g_maps_extension']['js_openlayers']['DEFAULT'] . '|static';
    // $GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/con4gis_maps/assets/js/c4g-maps.js|static';

    // $GLOBALS['TL_CSS'][] = $GLOBALS['c4g_maps_extension']['css_openlayers']['DEFAULT'];
    $GLOBALS['TL_CSS'][] = 'system/modules/con4gis_maps/assets/css/c4g-map-starboard.css';

    //@TODO "Theme-Test" - make this be-configurable
    $GLOBALS['TL_CSS'][] = 'system/modules/con4gis_maps/assets/css/themes/c4g-theme-icons-fontawesome.css';
    $GLOBALS['TL_CSS'][] = 'system/modules/con4gis_maps/assets/css/themes/c4g-theme-buttons-openlayers.css';
    // $GLOBALS['TL_CSS'][] = 'system/modules/con4gis_maps/assets/css/themes/c4g-theme-buttons-con4gis.css';
    $GLOBALS['TL_CSS'][] = 'system/modules/con4gis_maps/assets/css/themes/c4g-theme-TEMP.css';

    $mapData['addIdToDiv'] = true;
    // $mapData['mapDiv'] = 'c4gMap';
    // return print_r( $mapData, true );
    return $mapData;

    //!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
  }
}