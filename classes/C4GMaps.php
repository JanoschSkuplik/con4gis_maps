<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

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
     * @param  [type]  $objThis             [description]
     * @param  [type]  $database            [description]
     * @param  [type]  $additionalLocations [description]
     * @param  boolean $forEditor           [description]
     * @return [type]                       [description]
     */
    public static function prepareMapData ( $objThis, $database, $additionalLocations=NULL, $forEditor=false )
	{
		// fetch user
        // $objThis->import('FrontendUser', 'User');

		$map = C4gMapsModel::findByPk( $objThis->c4g_map_id );

		if(empty( $map )) return false;

		$profileId = $map->profile;
	}
}