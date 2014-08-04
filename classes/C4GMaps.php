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
		// if(empty( $map )) return false;
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



		//[ DEV ]!!!!!!!!!!!!!!!!!!!!!!!!

		// $GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['con4gis_core_extension']['jQuery-path'];
		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/con4gis_core/lib/jQuery/jquery-1.11.1.min.js';
		$GLOBALS['TL_JAVASCRIPT'][] = $GLOBALS['c4g_maps_extension']['js_openlayers']['DEFAULT'];
		$GLOBALS['TL_JAVASCRIPT'][] = 'system/modules/con4gis_maps/assets/js/c4g-maps.js';

		$GLOBALS['TL_CSS'][] = $GLOBALS['c4g_maps_extension']['css_openlayers']['DEFAULT'];


		$mapData['mapDiv'] = 'c4gMap';
		// return print_r( $mapData, true );
		return $mapData;

		//!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!
	}
}