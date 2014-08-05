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

		// import user, if not already done
		if (!isset( $objThis->User )) {
	        $objThis->import('FrontendUser', 'User');
		}

		// get map
		$map = C4gMapsModel::findByPk( $objThis->c4g_map_id );
		if(!$map) return false;
		$mapData['id'] = $objThis->c4g_map_id;

		// --------------------------------------------------------------------
		// get profile for map
		// --------------------------------------------------------------------
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

		if ($map->id == 0) {
			// use default settings
			// $mapData['center_lon'] = '1.0';
			// $mapData['center_lat'] = '1.0';
			$mapDat ['zoom'] = $objThis->c4g_map_zoom;
			if ($mapData['zoom'] == 0)
				$mapData['zoom'] = 1;
			$mapsize = deserialize( $objThis->c4g_map_mapsize );
			if (! is_array ( $mapsize ) || ($mapsize [0] == 0)) {
				$mapData['width'] = '400px';
				$mapData['height'] = '300px';				
			} else {
				$mapData['width'] = $mapsize [0] . $mapsize [2];
				$mapData['height'] = $mapsize [1] . $mapsize [2];
			}	
			// $mapData ['calc_extent'] = 'CENTERZOOM';
		} else {
			$mapData['center_lon'] = $map->center_geox;
			$mapData['center_lat'] = $map->center_geoy;
			if (is_numeric ( $objThis->c4g_map_zoom ) && ($objThis->c4g_map_zoom > 0)) {
				$mapData['zoom'] = $objThis->c4g_map_zoom;
			} else {
				$mapData['zoom'] = $map->zoom;
			}
			if ($map->geolocation) {
				$mapData['geolocation'] = true;
				$mapData['geolocation_zoom'] = $map->geolocation_zoom;				
			}
			$mapsize = deserialize ( $objThis->c4g_map_mapsize );
			if (! is_array ( $mapsize ) || ($mapsize [0] == 0)) {
				$mapsize = deserialize ( $map->mapsize );
			}
			$mapData['width'] = $mapsize [0] . $mapsize [2];
			$mapData['height'] = $mapsize [1] . $mapsize [2];
            if ($map->auto_width) {
                $mapData['auto_width'] = true;
                $mapData['auto_width_gap'] = $map->auto_width_gap;
                $mapData['auto_width_min'] = $map->auto_width_min;
                $mapData['auto_width_max'] = $map->auto_width_max;
            }
            if ($map->auto_height) {
                $mapData['auto_height'] = true;
                $mapData['auto_height_gap'] = $map->auto_height_gap;
                $mapData['auto_height_min'] = $map->auto_height_min;
                $mapData['auto_height_max'] = $map->auto_height_max;
            }
    	
			$mapData['calc_extent'] = $map->calc_extent;
			if ($map->calc_extent=='LOCATIONS') {
				$mapData['min_gap'] = $map->min_gap;				
			}
			
	  		$mapData['restrict_area'] = $map->restrict_area;
	  		if ($map->restrict_area) {  		    		    		  
	  			$mapData['restr_bottomleft_lon'] = $map->restr_bottomleft_lon;
	  			$mapData['restr_bottomleft_lat'] = $map->restr_bottomleft_lat;
	  			$mapData['restr_topright_lon'] = $map->restr_topright_lon;
	  			$mapData['restr_topright_lat'] = $map->restr_topright_lat;
	  		}
		}


		//[ DEV ]!!!!!!!!!!!!!!!!!!!!!!!!

		// $GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['con4gis_core_extension']['jQuery-path'];
		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/con4gis_core/lib/jQuery/jquery-1.11.1.min.js';
		$GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['c4g_maps_extension']['js_openlayers']['DEFAULT'];
		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/con4gis_maps/assets/js/c4g-maps.js';

		$GLOBALS['TL_CSS'][] = $GLOBALS['c4g_maps_extension']['css_openlayers']['DEFAULT'];


		// $mapData['mapDiv'] = 'c4gMap';
		// return print_r( $mapData, true );
		return $mapData;

		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	}
}