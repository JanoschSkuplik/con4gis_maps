<?php if (!defined('TL_ROOT')) die('You cannot access this file directly!');

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @author    Jürgen Witte & Tobias Dobbrunz <http://www.kuestenschmiede.de>
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014
 * @link      https://www.kuestenschmiede.de
 * @filesource
 */



/**
 * Class C4GMaps
 *
 * Static Function library for c4g_maps
 */
class C4GMaps
{

	private static $allLocstyles = false;

	/**
 	* Validate a Geo X coordinate (longitude)
 	*/
	public static function validateGeoX( $value )
	{
		if (C4GMaps::validateGeo($value)) {
			return (($value >= -180.0) && ($value <= 180.0));
		}
		return false;
	}

	/**
 	* Validate a Geo Y coordinate (latitude)
 	*/
	public static function validateGeoY( $value )
	{
		if (C4GMaps::validateGeo($value)) {
			return (($value >= -90.0) && ($value <= 90.0));
		}
		return false;
	}

	/**
 	* Validate a Geo Coordinate
 	*/
	public static function validateGeo( $value )
	{
		if (!isset($value)) {
			return false;
		}
		$value = floatval( $value );
		if ($value == 0) {
			return false;
		}
		return true;
    }

    // reassignment of parent layers, e.g. set layer by threadname in forums
    private static function reassignLayers(&$mapData,&$array) {
    	foreach ($array AS &$child) {
    		if ($child['parent_layer']) {

    			foreach ($mapData['data'] AS &$data) {
    				if ($data['layername']==$child['parent_layer']) {
    					$child['parent'] = $data['id'];
    					break;
    				}
    			}
    			unset($child['parent_layer']);
    		}
    	}

    }

    /**
     *
     * Add child locations - eventually recursively
     *
     * @param unknown_type $objThis
     * @param unknown_type $database
     * @param unknown_type $mapId
     * @param unknown_type $mapData
     * @param unknown_type $locStyleIds
     * @param unknown_type $count
     */
    private static function addLocations($objThis, $database, $mapId, &$mapData, &$data, &$locStyleIds, &$count, $level=0, $parentIds=null)
    {
    	if ($parentIds===null) {
    		$parentIds=array();
    	}
    	if (array_search($mapId, $parentIds)!==false) {
    		// prevent endless loop
    		return;
    	}
    	$parentIds[] = $mapId;

    	$countOrg = $count-1;

    	// --------------------------------------------------------------------
    	// get child locations
    	// --------------------------------------------------------------------
    	if ($level==0) {
	    	$childData = $database->prepare (
	    			"SELECT * FROM tl_c4g_maps WHERE ".
	    			"(pid=? OR (id=? AND loc_only_in_parent<>?))".
	    			"AND published=? ORDER BY sorting")->execute ( $mapId, $mapId, 1, 1 );
    	}
    	else {
    		$childData = $database->prepare (
    				"SELECT * FROM tl_c4g_maps WHERE ".
    				"pid=? ".
    				"AND published=? ORDER BY sorting")->execute ( $mapId, 1 );
    	}

    	$forumHelper = null;
    	while ( $childData->next() ) {

            // Access protection
            if ($childData->protect_element) {
                $permittedGroups = deserialize( $childData->permitted_groups );
                if (!empty( $permittedGroups )) {
                    if (FE_USER_LOGGED_IN) {
                        $groupMatch = array_intersect( $objThis->User->groups, deserialize( $childData->permitted_groups ) );
                        if (empty( $groupMatch )) {
                            continue;
                        }
                    } else {
                        continue;
                    }
                }
            }

    		// ---------------------------------------------------------
    		// Location Type 'link'
    		// ---------------------------------------------------------
    		if ($childData->location_type == 'link') {
    			// TODO: catch possible recursions
    			$child = $database->prepare (
    					"SELECT * FROM tl_c4g_maps WHERE id=? ".
    					"AND published=?")->execute ( $childData->link_id, 1 );

    		}
    		else {
    			$child = $childData;
    		}

            // handle permalink-args
            if (!empty( $_GET['layers'] ) && is_array( $_GET['layers'] )) {
                $child->data_hidelayer = !in_array( $child->id, $_GET['layers'] );
            }

    		// ---------------------------------------------------------
    		// Location Type 'single'
    		// ---------------------------------------------------------
    		if ($child->location_type == 'single') {
    			$mapData ['child'] [$count] ['parent'] = ($level ? $mapId : 0);
    			$mapData ['child'] [$count] ['geox'] = $child->loc_geox;
    			$mapData ['child'] [$count] ['geoy'] = $child->loc_geoy;
    			$mapData ['child'] [$count] ['locstyle'] = $child->locstyle;
    			$mapData ['child'] [$count] ['label'] = html_entity_decode($child->loc_label);
    			$mapData ['child'] [$count] ['onclick_zoomto'] = $child->loc_onclick_zoomto;
    			$mapData ['child'] [$count] ['minzoom'] = $child->loc_minzoom;
    			$mapData ['child'] [$count] ['maxzoom'] = $child->loc_maxzoom;
    			$mapData ['child'] [$count] ['graphicTitle'] = html_entity_decode($child->tooltip);
    			$mapData ['child'] [$count] ['popupInfo'] = $child->popup_info;
    			if ($child->routing_to) {
    				$mapData ['child'] [$count] ['popupRouteTo'] = true;
    			}
    			$mapData ['child'] [$count] ['linkurl'] = $objThis->repInsertTags($child->loc_linkurl);

    			$locStyleIds [$child->locstyle] = $child->locstyle;
    			$count ++;
    		}

    		// -------------------------------------------------------------------------
    		// Location Type "c4gForum" - Forum locations from C4G-Forum
            //      and Popupextensions (for OSM/Overpass)
    		// -------------------------------------------------------------------------
            $popupExtend = array();

    		if ($child->location_type == 'c4gForum' || $child->popup_extend) {
    			if ($GLOBALS['c4g_forum_extension']['installed']) {
    				if ($forumHelper==null) {
    					$forumHelper = new C4GForumHelper($database);
    				}
    				$forumHelper->frontendUrl = '';
    				if ($child->forum_jumpto != 0) {
    					$objPage = $database->prepare("SELECT id, alias FROM tl_page WHERE id=?")
    					->limit(1)
    					->execute($child->forum_jumpto);

    					if ($objPage->numRows)
    						$forumHelper->frontendUrl = $objThis->getFrontendUrl($objPage->row());
    				}
    				$forums = deserialize($child->forums);
    				if ($forums) {
                        if ($child->location_type == 'c4gForum') {
        					foreach ($forums AS $forumId) {

                                $forumLocations = $forumHelper->getMapLocationsForForum($forumId);
                                foreach ($forumLocations AS $value) {
                                    $value['parent'] = ($level ? $mapId : 0);

                                    if (html_entity_decode($child->loc_label) != '')
                                        $value['label'] = html_entity_decode($child->loc_label);

                                    if (html_entity_decode($child->tooltip) != '')
                                        $value['graphicTitle'] = html_entity_decode($child->tooltip);

                                    if ($child->popup_info != '')
                                        $value['popupInfo'] = $child->popup_info;
                                    if ($child->routing_to) {
                                        $value['popupRouteTo'] = true;
                                    }
                                    $value['onclick_zoomto'] = $child->loc_onclick_zoomto;
                                    $value['minzoom'] = $child->loc_minzoom;
                                    $value['maxzoom'] = $child->loc_maxzoom;
                                    if($child->forum_reassign_layer == 'THREAD') {
                                        $value['parent_layer'] = $value['threadname'];
                                    }
                                    unset($value['threadname']);

                                    if ($value['type']) {
                                        $mapData ['data'] [$count] = $value;
                                        C4GMaps::$allLocstyles = true;
                                    }
                                    else {
                                        $mapData ['child'] [$count] = $value;
                                    }
                                    $locStyleIds [$value['locstyle']] = $value['locstyle'];
                                    $count ++;
                                }
                            }
                        } else {
                        //PopupExtend only
                            if (empty( $mapData['popupExtend'] )) {
                                $mapData['popupExtend'] = array();
                            }
                            foreach ($forums AS $forumId) {
                                $forumLocations = $forumHelper->getPopupExtensionsForForum($forumId);
                                foreach ($forumLocations AS $value) {
                                    $osmId = $value['osmid'];
                                    unset( $value['osmid'] );
                                    if (empty( $mapData['popupExtend'][$osmId] )) {
                                        $mapData['popupExtend'][$osmId] = array();
                                    }
                                    $mapData['popupExtend'][$osmId][] = $value;
                                }
                            }
                        }//end else
                    }

                }
            }

            // --------------------------------------------------------------------
            // Location Type "Table" - child locations from other tables in the DB
            // --------------------------------------------------------------------
            if ($child->location_type == 'table') {
                $source = $GLOBALS['c4g_maps_extension']['sourcetable'][$child->tab_source];
                if (is_array($source)) {
    				if (($source['geox']) && ($source['geoy'])) {
    					$orgCount = $count;
    					$stmt = "SELECT * FROM ".$child->tab_source.
    					" WHERE ".$source['geox']." <> '' AND ".$source['geoy']." <> ''";
    					if (($source['ptable']) and ($child->tab_pid)) {
    						$stmt .= ' AND pid = ?';
    					}
    					if ($child->tab_whereclause) {
    						$stmt .= ' AND ( '.html_entity_decode($child->tab_whereclause).' )';
    					}
    					if ($source['sqlwhere']) {
    						$stmt .= ' AND ( '.$source['sqlwhere'].' )';
    					}
    					if ($source['alias_getparam']) {
    						if ($child->tab_filter_alias) {
    							$alias = $objThis->getInput()->get($source['alias_getparam']);
    							if ($alias) {
    								if (is_numeric($alias)) {
    									$stmt .= ' AND (( alias = "'.$alias.'" ) OR ( id = '.$alias.' ))';
    								}
    								else {
    									$stmt .= ' AND (alias = "'.$alias.'")';
    								}
    							}
    						}
    					}

    					// HOOK: add custom sql condition
    					if (isset($GLOBALS['TL_HOOKS']['c4gMapsSqlCondition']) && is_array($GLOBALS['TL_HOOKS']['c4gMapsSqlCondition']))
    					{
    						foreach ($GLOBALS['TL_HOOKS']['c4gMapsSqlCondition'] as $callback)
    						{
    							$objThis->import($callback[0]);
    							$custCondition = $objThis->$callback[0]->$callback[1]($child->tab_source, $objThis->c4g_map_id, $child, $objThis);

    							if ($custCondition!='') {
    								$stmt .= ' AND ( '.$custCondition.' )';
    							}
    						}
    					}

    					$stmt .= ' ORDER BY '.$source['geox'].','.$source['geoy'];

                        if ($child->tab_orderby) {
                            $stmt .= ',' . html_entity_decode($child->tab_orderby);
                        }

    					$obj = $database->prepare( $stmt )->execute($child->tab_pid);
    					$lastGeo = '';
    					while ($obj->next())
    					{
    						$popupInfo = '';
    						if ($source['popup']) {
    							$popupElements = explode(',',$source['popup']);

    							foreach ($popupElements as $element) {
    								$rgxp = '';
    								if ((substr($element,0,1)=='[') && (substr($element,-1)==']'))
    								{
    									$element = substr($element,1,-1);
    									$element = explode(':',$element);
    									$rgxp = $element[1];
    									$element = $element[0];
    								}
    								if ($obj->$element!='') {
    									if ($rgxp) {
    										$objDate = new Date($obj->$element);
    										$popupInfo .= $objDate->$rgxp;
    									} else {
    										$popupInfo .= $obj->$element . ' ';
    									}
    								} else {
    									$popupInfo .=
    									str_replace('[id]',$obj->id,$element ) . ' ';
    								}
    							}

    							$popupInfo = $objThis->repInsertTags($popupInfo);

                                if ($child->tab_force_target_blank) {
                                    $popupInfo = preg_replace('/<a /', '<a target="_blank" ', $popupInfo);
                                }
    						}

    						if ($lastGeo == $obj->$source['geox'].$obj->$source['geoy']) {
    							$index = $count-1;
    							$mapData ['child'] [$index] ['count'] += 1;
    							$mapData ['child'] [$index] ['popupInfo'] .= '<br/>'.$popupInfo;
    							if (($source['label']) && ($child->tab_labeldisplay == 'ALL')) {
    								$mapData ['child'] [$index] ['label'] .= '{$NL}'.html_entity_decode($obj->$source['label']);
    							}
    							if (($source['tooltip']) && ($child->tab_tooltipdisplay == 'ALL')) {
    								$mapData ['child'] [$index] ['graphicTitle'] .= ', '.html_entity_decode($obj->$source['tooltip']);
    							}
    						}
    						else
    						{
    							$countSameGeo = 1;
    							$lastGeo = $obj->$source['geox'].$obj->$source['geoy'];
    							$mapData ['child'] [$count] ['count'] = 1;
    							$mapData ['child'] [$count] ['parent'] = ($level ? $mapId : 0);
    							$mapData ['child'] [$count] ['geox'] = $obj->$source['geox'];
    							$mapData ['child'] [$count] ['geoy'] = $obj->$source['geoy'];
    							$locstyle = $child->locstyle;
    							if ($source['locstyle']) {
    								if ($obj->$source['locstyle']) {
    									$locstyle = $obj->$source['locstyle'];
    								}
    							}
    							$mapData ['child'] [$count] ['locstyle'] = $locstyle;
    							$mapData ['child'] [$count] ['onclick_zoomto'] = $child->loc_onclick_zoomto;
    							$mapData ['child'] [$count] ['minzoom'] = $child->loc_minzoom;
    							$mapData ['child'] [$count] ['maxzoom'] = $child->loc_maxzoom;
    							if (($source['label']) && ($child->tab_labeldisplay <> 'OFF')) {
    								$mapData ['child'] [$count] ['label'] = html_entity_decode($obj->$source['label']);
    							}
    							if (($source['tooltip']) && ($child->tab_tooltipdisplay <> 'OFF')) {
    								$mapData ['child'] [$count] ['graphicTitle'] = html_entity_decode($obj->$source['tooltip']);
    							}
                                $mapData ['child'] [$count] ['popupInfo'] = $popupInfo;
                                if ($child->routing_to) {
                                    $mapData ['child'][$count]['popupRouteTo'] = true;
                                }
                                if (($source['linkurl']) && ($child->tab_directlink)) {
                                    $mapData ['child'] [$count] ['linkurl'] =
                                    $objThis->repInsertTags(
                                            str_replace('[id]',$obj->id,$source['linkurl']));
                                }
                                $locStyleIds [$locstyle] = $locstyle;
                                $count ++;
                            }

                        }
                    }
                }

                // for locations with more than one entry in the source table
                if (isset($mapData['child'] )) {
                    if ($count > $orgCount) {
                        for ($i = $orgCount; $i < $count; $i++) {
                            $child = &$mapData['child'][$i];
                            if ($child['count']>1) {
                                if ($child->tab_labeldisplay == '1ST_COUNT') {
                                    if ($child['label']) {
                                        $child['label'] .= ' ('.$child['count'].') ';
                                    } else {
                                        $child['label'] = '('.$child['count'].')';
                                    }
                                }

                                if ($child->tab_labeldisplay == '1ST_MORE') {
                                    if ($child['label']) {
                                        $child['label'] .= ' (...) ';
                                    } else {
                                        $child['label'] = '(...)';
                                    }
                                }

                                if ($child->tab_tooltipdisplay == '1ST_COUNT') {
                                    if ($child['graphicTitle']) {
                                        $child['graphicTitle'] .= ' ('.$child['count'].') ';
                                    } else {
                                        $child['graphicTitle'] = '('.$child['count'].')';
                                    }
                                }

                                if ($child->tab_tooltipdisplay == '1ST_MORE') {
                                    if ($child['graphicTitle']) {
                                        $child['graphicTitle'] .= ' (...) ';
                                    } else {
                                        $child['graphicTitle'] = '(...)';
                                    }
                                }

                            }
                        }
                    }
                }
            }

    		// --------------------------------------------------------------------
    		// Location Types "gpx", "kml", "osm" and "geojson"
    		// --------------------------------------------------------------------
    		if (($child->location_type=='gpx') ||
    				($child->location_type=='kml') ||
    				($child->location_type=='osm') ||
    				($child->location_type=='geojson') ||
    				($child->location_type=='overpass')) {

    			$addData = array();
    			$addData['parent'] = ($level ? $mapId : 0);
    			$addData['id'] = $child->id;
    			$addData['type'] = $child->location_type;
    			if ($child->data_file) {
    				if (version_compare(VERSION, '3.2', '>=')) {
                        // Contao 3.2 Format
                        $objFile = FilesModel::findByUuid($child->data_file);
                        $child->data_file = $objFile->path;
                    } else if (is_numeric($child->data_file)) {
                        // Contao 3 Format
                        $objFile = FilesModel::findByPk($child->data_file);
                        $child->data_file = $objFile->path;
                    }

    				if  (file_exists($child->data_file)) {
    					$addData['filecontent'] = file_get_contents($child->data_file);
    				}
    			}

    			if ($child->location_type == 'overpass') {
    				$addData['url'] = $GLOBALS['c4g_maps_extension']['overpass_proxy'];
    				$addData['ovp_request'] = $child->ovp_request;
    				$addData['ovp_bbox_limited'] = $child->ovp_bbox_limited;
    			}
    			else {
    				$addData['url'] = $child->data_url;
    			}
    			$addData['content'] = $child->data_content;
    			if ($child->data_projection) {
    				if ($child->data_projection=='WGS84') {
    					$addData['projection'] = 'EPSG:4326';
    				}
    			}
    			$addData['forcenodes'] = $child->data_forcenodes;
    			$addData['layername'] = $child->data_layername;
    			$addData['hidelayer'] = $child->data_hidelayer;
    			$addData['fnstyle'] = $child->data_js_style_function;
    			$addData['label'] = html_entity_decode($child->loc_label);
    			$addData['graphicTitle'] = html_entity_decode($child->tooltip);
    			$addData['popupInfo'] = $child->popup_info;
    			if ($child->routing_to) {
    				$addData ['popupRouteTo'] = true;
    			}
    			$addData['linkurl'] = $child->loc_linkurl;
    			$addData['locstyle'] = $child->locstyle;
    			$addData['onclick_zoomto'] = $child->loc_onclick_zoomto;
    			$addData['minzoom'] = $child->loc_minzoom;
    			$addData['maxzoom'] = $child->loc_maxzoom;

                if ((($child->location_type == 'osm') || ($child->location_type == 'overpass')) && !empty($popupExtend)) {
                    $addData['popupExtend'] = $popupExtend;
                }

    			$mapData['data'][$count] = $addData;

    			$locStyleIds [$child->locstyle] = $child->locstyle;
    			$count++;
    		}

    		if ($child->location_type=='geojson' || $child->location_type=='overpass') {
    			C4GMaps::$allLocstyles = true;
    		}

    		// structure entries
    		if (($child->location_type=='none') &&
    			($child->id!=$mapId)) {
    			$addData = array();
    			$addData['parent'] = ($level ? $mapId : 0);
    			$addData['id'] = $child->id;
    			$addData['type'] = 'struct';
    			$addData['layername'] = $child->data_layername;
    			$addData['hidelayer'] = $child->data_hidelayer;
    			$mapData['data'][$count] = $addData;
    			$count++;
    		}
    		/* add hook for track-implementation */

    		// HOOK: add custom logic
    		if (isset($GLOBALS['TL_HOOKS']['c4gAddLocationsParent']) && is_array($GLOBALS['TL_HOOKS']['c4gAddLocationsParent']))
    		{
    			foreach ($GLOBALS['TL_HOOKS']['c4gAddLocationsParent'] as $callback)
    			{
    				$objThis->import($callback[0]);
    				$arrData = $objThis->$callback[0]->$callback[1](($level ? $mapId : 0), $child, $objThis);

    				if ($arrData && is_array($arrData) && sizeof($arrData)>0)
    				{
    					foreach ($arrData as $data)
    					{
    						if ($data['type'])
    						{
    							$mapData['data'][$count] = $data;
    						}
    						else
    						{
    							$mapData['child'][$count] = $data;
    						}

    						$count++;
    					}

    				}
    			}
    		}

    		if ($data['include_sublocations']) {
    			if ($child->id!=$mapId) {
    				C4GMaps::addLocations($objThis, $database, $child->id, $mapData, $data, $locStyleIds, $count, $level+1, $parentIds);
    			}
    		}

    	}

    	if ($level==0) {

    		if ($mapData['child']) {
    			C4GMaps::reassignLayers($mapData, $mapData['child']);
    		}

    		if ($mapData['data']) {
    			C4GMaps::reassignLayers($mapData, $mapData['data']);
    		}

    	}

    }

	/**
	 * Set up the mapData-Array for the template by populating it with the data in the database
	 */
    public static function prepareMapData($objThis, $database, $additionalLocations=NULL, $forEditor=false)
	{
        // fetch user
        $objThis->import('FrontendUser', 'User');
		// --------------------------------------------------------------------
		// get data for map
		// --------------------------------------------------------------------
		$data = $database->prepare ( "SELECT * FROM tl_c4g_maps WHERE id=?" )->limit ( 1 )->execute ( $objThis->c4g_map_id )->fetchAssoc ();

		$profileId = $data ['profile'];

        $isMobile = false;
		if (($data['profile_mobile'] > 0) && (\Input::cookie('TL_VIEW') == 'mobile' || (\Environment::get('agent')->mobile && \Input::cookie('TL_VIEW') != 'desktop')))
		{
            $isMobile = true;
			$profileId = $data['profile_mobile'];
		}

        if ((FE_USER_LOGGED_IN) && ($data['use_specialprofile'])) {
            $groupMatch = array_intersect($objThis->User->groups, deserialize($data['specialprofile_groups']));
            if (!empty( $groupMatch )) {
                if (($isMobile) && ($data ['specialprofile_mobile'])) {
                    $profileId = $data ['specialprofile_mobile'];
                } else {
                    $profileId = $data ['specialprofile'];
                }
            }
        }

        $profile = $database->prepare ( "SELECT * FROM tl_c4g_map_profiles WHERE id=?" )->limit ( 1 )->execute ( $profileId )->fetchAssoc ();
        if (!$profile) {
            $profile = $database->prepare ( "SELECT * FROM tl_c4g_map_profiles WHERE is_default=?" )->limit ( 1 )->execute ( true )->fetchAssoc ();
            if ($profile) {
                $profileId = $profile['id'];
            }
        }

		$mapData['profile'] = $profileId;

		$mapData ['id'] = $objThis->c4g_map_id;

		if ($data['id']==0) {
			// Map data not found -> use defaults
			$mapData ['center_geox'] = '1.0';
			$mapData ['center_geoy'] = '1.0';
			$mapData ['zoom'] = $objThis->c4g_map_zoom;
			if ($mapData['zoom'] == 0)
				$mapData['zoom'] = 1;
			$mapsize = deserialize ( $objThis->c4g_map_mapsize );
			if (! is_array ( $mapsize ) || ($mapsize [0] == 0)) {
				$mapData ['width'] = '400px';
				$mapData ['height'] = '300px';
			} else {
				$mapData ['width'] = $mapsize [0] . $mapsize [2];
				$mapData ['height'] = $mapsize [1] . $mapsize [2];
			}
			$mapData ['calc_extent'] = 'CENTERZOOM';

		} else {
			$mapData ['center_geox'] = $data ['center_geox'];
			$mapData ['center_geoy'] = $data ['center_geoy'];
			if (is_numeric ( $objThis->c4g_map_zoom ) && ($objThis->c4g_map_zoom > 0)) {
				$mapData ['zoom'] = $objThis->c4g_map_zoom;
			} else {
				$mapData ['zoom'] = $data ['zoom'];
			}
			if ($data['geolocation']) {
				$mapData['geolocation'] = true;
				$mapData['geolocation_zoom'] = $data['geolocation_zoom'];
			}
			$mapsize = deserialize ( $objThis->c4g_map_mapsize );
			if (! is_array ( $mapsize ) || ($mapsize [0] == 0)) {
				$mapsize = deserialize ( $data ['mapsize'] );
			}
			$mapData ['width'] = $mapsize [0] . $mapsize [2];
			$mapData ['height'] = $mapsize [1] . $mapsize [2];
            if ($data['auto_width']) {
                $mapData['auto_width'] = true;
                $mapData['auto_width_gap'] = $data['auto_width_gap'];
                $mapData['auto_width_min'] = $data['auto_width_min'];
                $mapData['auto_width_max'] = $data['auto_width_max'];
            }
            if ($data['auto_height']) {
                $mapData['auto_height'] = true;
                $mapData['auto_height_gap'] = $data['auto_height_gap'];
                $mapData['auto_height_min'] = $data['auto_height_min'];
                $mapData['auto_height_max'] = $data['auto_height_max'];
            }

			$mapData ['calc_extent'] = $data['calc_extent'];
			if ($data['calc_extent']=='LOCATIONS') {
				$mapData ['min_gap'] = $data['min_gap'];
			}

	  		$mapData['restrict_area'] = $data['restrict_area'];
	  		if ($data['restrict_area']) {
	  			$mapData['restr_bottomleft_geox'] = $data['restr_bottomleft_geox'];
	  			$mapData['restr_bottomleft_geoy'] = $data['restr_bottomleft_geoy'];
	  			$mapData['restr_topright_geox'] = $data['restr_topright_geox'];
	  			$mapData['restr_topright_geoy'] = $data['restr_topright_geoy'];
	  		}

        }

        // parse permalink-args:
        if ($profile['permalink']) {
            $mapData['center_geox'] = $_GET['lat'] ?: $mapData['center_geox'];
            $mapData['center_geoy'] = $_GET['lon'] ?: $mapData['center_geoy'];
            $mapData['zoom'] = $_GET['zoom'] ?: $mapData['zoom'];
            $objThis->c4g_map_default_mapservice = $_GET['base'] ?: $objThis->c4g_map_default_mapservice;
            if (!empty( $_GET['layers'] )){
                $_GET['layers'] = explode('-', base64_decode( $_GET['layers'] ));
            }
        }

        $count = 0;
        $locStyleIds = array();
        C4GMaps::$allLocstyles = false;
		if (!$forEditor || $profile['editor_show_items']) {
			// add all locations, but not when editing (in forum)
			self::addLocations($objThis, $database, $objThis->c4g_map_id, $mapData, $data, $locStyleIds, $count);
		}
        // --------------------------------------------------------------------
        // add additional locations
        // --------------------------------------------------------------------
        if ($additionalLocations!=NULL) {
            foreach ($additionalLocations AS $value) {
                if ($value['type']) {
                    $mapData ['data'] [$count] = $value;
                    C4GMaps::$allLocstyles = true;
                }
                else {
                    $mapData ['child'] [$count] = $value;
                }
                $locStyleIds [$value['locstyle']] = $value['locstyle'];
                $count ++;
            }
        }

        // -------------------------------------------------------------------------
        // collect data from map profile
        // -------------------------------------------------------------------------
        if ($profile) {
          $mapData['pan_panel'] = $profile['pan_panel'];
          $mapData['zoom_panel'] = $profile['zoom_panel'];
          if ($mapData['zoom_panel']=='3') {
            $mapData['css'][] = 'system/modules/con4gis_maps/html/OpenLayers-2.13.1/theme/default/style.mobile.css';
          }
          $mapData['zoom_panel_world'] = $profile['zoom_panel_world'];
          $mapData['mouse_nav'] = $profile['mouse_nav'];
          $mapData['mouse_nav_wheel'] = $profile['mouse_nav_wheel'];
          $mapData['mouse_nav_zoombox'] = $profile['mouse_nav_zoombox'];
          $mapData['mouse_nav_kinetic'] = $profile['mouse_nav_kinetic'];
          $mapData['mouse_nav_toolbar'] = $profile['mouse_nav_toolbar'];
          $mapData['keyboard_nav'] = $profile['keyboard_nav'];
          $mapData['nav_history'] = $profile['nav_history'];
          $mapData['geosearch'] = $profile['geosearch'];
          if ($mapData['geosearch']) {
            $mapData['geocoding_url'] = 'system/modules/con4gis_maps/C4GNominatim.php';
            $mapData['geosearch_engine'] = $profile['geosearch_engine'];
            if ($profile['geosearch_engine'] == '3') {
                // $mapData['geosearch_customengine_url'] = $profile['geosearch_customengine_url'];
                $mapData['geosearch_customengine_attribution'] = $profile['geosearch_customengine_attribution'];
            }
            $mapData['geosearch_div'] = $profile['geosearch_div'];
            $mapData['geosearch_zoomto'] = $profile['geosearch_zoomto'];
            $mapData['geosearch_zoombounds'] = $profile['geosearch_zoombounds'];
            $mapData['geosearch_attribution'] = $profile['geosearch_attribution'];
          }
    	  if ($profile['editor']) {
            $profile['for_editor_old'] = $forEditor;
            if (!$forEditor) {
                $forEditor = true;
                $mapData['fe_editor'] = true;
            }
            $mapData['editor'] = true;
            $mapData['editor_hide'] = true;
            $mapData['editor_labels'] = $GLOBALS['TL_LANG']['c4g_maps']['editor_labels'];
            $mapData['editor_field'] = '#c4gForumPostMapEntryGeodata';
            // $mapData['editor_types'] = array('polygon');
          }
          if ($profile['geopicker']) {
            $mapData['geocoding'] = true;
            $mapData['pickGeo'] = true;
            $mapData['geocoding_url'] = 'system/modules/con4gis_maps/C4GNominatim.php';
            $mapData['geocoding_div'] = $profile['geopicker_searchdiv'];
            $mapData['geocoding_fieldx'] = $profile['geopicker_fieldx'];
            $mapData['geocoding_fieldy'] = $profile['geopicker_fieldy'];
            $mapData['geocoding_attribution'] = $profile['geopicker_attribution'];
          }
          $mapData['attribution'] = $profile['attribution'];
          if ($profile['attribution'] && $profile['cfg_logo_attribution']) {
            $mapData['cfg_logo_attribution'] = $profile['cfg_logo_attribution'];
          }
          if ($profile['div_attribution']) {
            $mapData['div_attribution'] = $profile['div_attribution'];
          }
          if ($profile['add_attribution']) {
            $mapData['add_attribution'] = $profile['add_attribution'];
          }
          $mapData['overviewmap'] = $profile['overviewmap'];
          $mapData['scaleline'] = $profile['scaleline'];
          $mapData['mouseposition'] = $profile['mouseposition'];
          $mapData['permalink'] = $profile['permalink'];
          $mapData['zoomlevel'] = $profile['zoomlevel'];
          $mapData['fullscreen'] = $profile['fullscreen'];
          if ($profile['imagepath'] <> '0') {    // workaround for errors in Database generated with Version 0.10
            $mapData['imagepath'] = $profile['imagepath'];
            $objFile = FilesModel::findByUuid($mapData['imagepath']);
            $mapData['imagepath'] = $objFile->path;

            if ($mapData['imagepath']) {
                if (substr($mapData['imagepath'],-1) <> '/' )
                  $mapData['imagepath'] .= '/';
            }
          }
          if ($profile['theme']) {
            $mapData['imagepath'] = 'system/modules/con4gis_maps/html/OpenLayers-2.13.1/theme/'.$profile['theme'].'/img/';
            $mapData['switcher_class'] = 'olC4gSwitcher_'.$profile['theme'];
          } else {
            if (!$mapData['imagepath']) {
                $mapData['imagepath'] = 'system/modules/con4gis_maps/html/OpenLayers-2.13.1/theme/default/img/';
            }
            $mapData['switcher_class'] = 'olC4gSwitcher_default';
          }
          $openlayers_libsource = $GLOBALS['c4g_maps_extension']['js_openlayers_libs'][$profile['libsource']];
          $openlayers_css = $GLOBALS['c4g_maps_extension']['css_openlayers'][$profile['libsource']];
          $mapData['script'] = $profile['script'];
          $mapData['link_newwindow'] = $profile['link_newwindow'];
          $mapData['link_open_on'] = $profile['link_open_on'];
          $mapData['hover_popups'] = $profile['hover_popups'];
          $mapData['hover_popups_stay'] = $profile['hover_popups_stay'];
          $mapData['div_layerswitcher'] = $profile['div_layerswitcher'];

          if ($forEditor) {
            $mapData['editor_helpurl'] = $objThis->repInsertTags($profile['editor_helpurl']);
          }

        }
        else {
          $mapData['pan_panel'] = true;
          $mapData['zoom_panel'] = true;
          $mapData['zoom_panel_world'] = true;
          $mapData['mouse_nav'] = true;
          $mapData['mouse_nav_wheel'] = true;
          $mapData['mouse_nav_zoombox'] = true;
          $mapData['mouse_nav_kinetic'] = false;
          $mapData['mouse_nav_toolbar'] = false;
          $mapData['keyboard_nav'] = true;
          $mapData['nav_history'] = false;
          $mapData['geosearch'] = false;
          $mapData['attribution'] = true;
          $mapData['overviewmap'] = false;
          $mapData['scaleline'] = false;
          $mapData['mouseposition'] = false;
          $mapData['permalink'] = false;
          $mapData['zoomlevel'] = false;
          $mapData['fullscreen'] = false;
          $mapData['imagepath'] = '';
          $mapData['script'] = '';
          $mapData['hover_popups'] = false;
          $mapData['hover_popups_stay'] = false;
          $mapData['link_newwindow'] = false;
          $mapData['link_open_on'] = 'CLICK';
          $mapData['div_layerswitcher'] = '';
  		  $mapData['imagepath'] = 'system/modules/con4gis_maps/html/OpenLayers-2.13.1/theme/dark/img/';
  		  $profile['theme'] = 'dark';
  		  $mapData['switcher_class'] = 'olC4gSwitcher_dark';
		}
		if (!$openlayers_libsource) {
  		  $openlayers_libsource = $GLOBALS['c4g_maps_extension']['js_openlayers_libs']['DEFAULT'];
  	  	  $openlayers_css = $GLOBALS['c4g_maps_extension']['css_openlayers']['DEFAULT'];
  		}

		// -------------------------------------------------------------------------
		// collect locationstyle data
		// -------------------------------------------------------------------------
		if ((count ( $locStyleIds ) > 0) || $forEditor || C4GMaps::$allLocstyles) {

			if ($forEditor) {
				$stylesPoint = deserialize($profile['editor_styles_point']);
				$stylesLine = deserialize($profile['editor_styles_line']);
				$stylesPolygon = deserialize($profile['editor_styles_polygon']);
				$editorStylesDefined = $stylesPoint || $stylesPolygon || $stylesLine;
			}

			if ($forEditor || C4GMaps::$allLocstyles) {
				$locStyles = $database->prepare (
						"SELECT * FROM tl_c4g_map_locstyles ORDER BY name"
				)->execute ($profileId);
			}
			else {
				// add hover location styles for all used styles
				$hoverLocStyles = $database->prepare (
						"SELECT onhover_locstyle FROM tl_c4g_map_locstyles ".
						"WHERE id in (" . implode ( ',', $locStyleIds ) . ") AND onhover_locstyle<>0"
				)->execute ();
				while($hoverLocStyles->next()) {
					$locStyleIds[$hoverLocStyles->onhover_locstyle] = $hoverLocStyles->onhover_locstyle;
				}
				$locStyles = $database->prepare (
					"SELECT * FROM tl_c4g_map_locstyles WHERE id in (" . implode ( ',', $locStyleIds ) . ") ORDER BY name"
					)->execute ();
			}
			$locStyleIdsNotFound = $locStyleIds;

			while ( $locStyles->next () ) {
				$key = $locStyles->id;

				if ($locStyles->styletype == 'ol_icon') {

					$locStyleData[$key]['internalGraphic'] = $locStyles->ol_icon;

					$iconSize = deserialize($locStyles->ol_icon_size);
					$locStyleData[$key]['graphicWidth'] = (int) $iconSize[0];
					$locStyleData[$key]['graphicHeight'] = (int) $iconSize[1];

					$offset = deserialize($locStyles->ol_icon_offset);
					$locStyleData[$key]['graphicXOffset'] = (int) $offset[0];
					$locStyleData[$key]['graphicYOffset'] = (int) $offset[1];

					$iconopacity = deserialize( $locStyles->icon_opacity );
					$locStyleData[$key]['graphicOpacity'] = $iconopacity['value'] / 100;

				}
				else if ($locStyles->styletype == 'cust_icon') {
					if (version_compare(VERSION, '3.2', '>=')) {
                        // Contao 3.2 Format
                        $objFile = FilesModel::findByUuid($locStyles->icon_src);
                        $locStyles->icon_src = $objFile->path;
                    } else if (is_numeric($locStyles->icon_src)) {
                        // Contao 3 Format
                        $objFile = FilesModel::findByPk($locStyles->icon_src);
                        $locStyles->icon_src = $objFile->path;
                    }

					$locStyleData[$key]['externalGraphic'] = $locStyles->icon_src;

					$iconSize = deserialize($locStyles->icon_size);
					$locStyleData[$key]['graphicWidth'] = (int) $iconSize[0];
					$locStyleData[$key]['graphicHeight'] = (int) $iconSize[1];

					$offset = deserialize($locStyles->icon_offset);
					$locStyleData[$key]['graphicXOffset'] = (int) $offset[0];
					$locStyleData[$key]['graphicYOffset'] = (int) $offset[1];

					$iconopacity = deserialize($locStyles->icon_opacity);
					$locStyleData[$key]['graphicOpacity'] = $iconopacity['value'] / 100;

				} else {

					$locStyleData [$key] ['strokeColor'] = '#' . $locStyles->strokecolor;
					$strokewidth = deserialize ( $locStyles->strokewidth );
					$locStyleData [$key] ['strokeWidth'] = (int) $strokewidth ['value'];
					$strokeopacity = deserialize ( $locStyles->strokeopacity );
					$locStyleData [$key] ['strokeOpacity'] = $strokeopacity ['value'] / 100;

					$locStyleData [$key] ['fillColor'] = '#' . $locStyles->fillcolor;
					$fillopacity = deserialize ( $locStyles->fillopacity );
					$locStyleData [$key] ['fillOpacity'] = $fillopacity ['value'] / 100;

					$radius = deserialize ( $locStyles->radius );
					$locStyleData [$key] ['pointRadius'] = (int) $radius ['value'];

					if ($locStyles->styletype == 'point') {
						$locStyleData [$key] ['graphicName'] = "";
					} else {
						$locStyleData [$key] ['graphicName'] = $locStyles->styletype;
					}
				}
				if (($locStyles->label_align_hor<>'') && ($locStyles->label_align_ver<>'')) {
				  $locStyleData[$key]['labelAlign'] =
				    substr($locStyles->label_align_hor,0,1).substr($locStyles->label_align_ver,0,1);
				}

				$offset = deserialize($locStyles->label_offset);
				if ($offset[0]<>0) {
				  $locStyleData[$key]['labelXOffset'] = (int) $offset[0];
				}
				if ($offset[1]<>0) {
				  $locStyleData[$key]['labelYOffset'] = (int) $offset[1];
				}

				if ($locStyles->font_color <> '') {
				  $locStyleData [$key] ['fontColor'] = '#' . $locStyles->font_color;
				}
				else {
				  $locStyleData [$key] ['fontColor'] = '#000000';
				}
				$fontopacity = deserialize ( $locStyles->font_opacity );
				if ($fontopacity <> 0) {
				  $locStyleData [$key] ['fontOpacity'] = $fontopacity['value'] / 100;
				}

				if ($locStyles->font_family <> '') {
				  $locStyleData [$key] ['fontFamily'] = $locStyles->font_family;
				}

				if ($locStyles->font_size <> '') {
				  $locStyleData [$key] ['fontSize'] = $locStyles->font_size;
				}

				if ($locStyles->font_style <> '') {
				  $locStyleData [$key] ['fontStyle'] = $locStyles->font_style;
				}

				if ($locStyles->font_weight <> '') {
				  $locStyleData [$key] ['fontWeight'] = $locStyles->font_weight;
				}

				if ($locStyles->label_outl_color <> '') {
					$locStyleData[$key]['labelOutlineColor'] = '#'.$locStyles->label_outl_color;
				}

				$outlineWidth = deserialize ( $locStyles->label_outl_width,true );
				if ($outlineWidth['value']) {
					$outlineWidth = (int) $outlineWidth['value'];
					if ($outlineWidth <> 0) {
						$locStyleData[$key]['labelOutlineWidth'] = $outlineWidth;
					}
				}

				if (($locStyles->popup_kind) == 'cloud') {
				    $locStyleData[$key]['popupClass'] = 'OpenLayers.Popup.FramedCloud';
				} else {
				    $locStyleData[$key]['popupClass'] = 'OpenLayers.Popup.Anchored';
				}

				$offset = deserialize($locStyles->popup_offset);
 			    $locStyleData[$key]['popupXOffset'] = (int) $offset[0] | 0;
				$locStyleData[$key]['popupYOffset'] = (int) $offset[1] | 0;

				$size = deserialize($locStyles->popup_size);
  			    $locStyleData[$key]['popupXSize'] = (int) $size[0] | 200;
 			    $locStyleData[$key]['popupYSize'] = (int) $size[1] | 200;

 			    if ($locStyles->label) {
 			    	$locStyleData[$key]['label'] = html_entity_decode($locStyles->label);
 			    }
			 	if ($locStyles->tooltip) {
 			    	$locStyleData[$key]['graphicTitle'] = html_entity_decode($locStyles->tooltip);
 			    }

 			    if ($locStyles->popup_info) {
 			    	$locStyleData[$key]['popupInfo'] = html_entity_decode($locStyles->popup_info);
 			    }

 			    if ($locStyles->onclick_zoomto) {
 			    	$locStyleData[$key]['onclick_zoomto'] = $locStyles->onclick_zoomto;
 			    }

 			    if ($locStyles->minzoom) {
 			    	$locStyleData[$key]['minzoom'] = $locStyles->minzoom;
 			    }

 			    if ($locStyles->maxzoom) {
 			    	$locStyleData[$key]['maxzoom'] = $locStyles->maxzoom;
 			    }

 			    if ($locStyles->onhover_locstyle) {
 			    	$locStyleData[$key]['hoverStyle'] = $locStyles->onhover_locstyle;
 			    }

 			    if ($locStyles->line_arrows) {
 			    	$arrow_radius = deserialize($locStyles->line_arrows_radius);
 			    	$locStyleData[$key]['arrowRadius'] = (int) $arrow_radius['value'];
 			    	$locStyleData[$key]['arrowBack'] = $locStyles->line_arrows_back;
 			    	$locStyleData[$key]['arrowMinzoom'] = $locStyles->line_arrows_minzoom;
 			    }
 			    unset($locStyleIdsNotFound[$key]);

 			    if ($forEditor) {
 			    	$locStyleData[$key]['name'] = $locStyles->name;

 			    	if (version_compare(VERSION, '3.2', '>=')) {
                        // Contao 3.2 Format
                        $objFile = FilesModel::findByUuid($locStyles->editor_icon);
                        $locStyles->editor_icon= $objFile->path;
                    } else if (is_numeric($locStyles->editor_icon)) {
                        // Contao 3 Format
                        $objFile = FilesModel::findByPk($locStyles->editor_icon);
                        $locStyles->editor_icon= $objFile->path;
                    }

 			    	$locStyleData[$key]['editor_icon'] = $locStyles->editor_icon;
 			    	$locStyleData[$key]['editor_collect'] = $locStyles->editor_collect;

 			    	if ($profile) {
 			    		$used = false;
 			    		if ($editorStylesDefined) {
	 			    		if ($stylesPoint) {
	 			    			if (array_search($locStyles->id,$stylesPoint)!==false) {
	 			    				$locStyleData[$key]['editor_points'] = true;
	 			    				$used = true;
	 			    			}

	 			    		}
	 			    		if ($stylesLine) {
	 			    			if (array_search($locStyles->id,$stylesLine)!==false) {
	 			    				$locStyleData[$key]['editor_lines'] = true;
	 			    				$used = true;
	 			    			}

	 			    		}

	 			    		if ($stylesPolygon) {
	 			    			if (array_search($locStyles->id,$stylesPolygon)!==false) {
	 			    				$locStyleData[$key]['editor_polygones'] = true;
	 			    				$used = true;
	 			    			}
	 			    		}
 			    		}
 			    		else {
 			    			// no editr styles explicitly defined -> use styles from profile
 			    			if ($locStyles->pid == $profileId) {
 			    				$used = true;
	 			    			$locStyleData[$key]['editor_points'] = true;
	 			    			if (($locStyles->styletype != 'ol_icon') && ($locStyles->styletype != 'cust_icon')) {
	 			    				$locStyleData[$key]['editor_lines'] = true;
	 			    				$locStyleData[$key]['editor_polygones'] = true;
	 			    			}
 			    			}

 			    		}
		    			$locStyleData[$key]['editor_vars'] =
		    				array_merge(
 	    					deserialize($profile['editor_vars'],true),
 	    					deserialize($locStyles->editor_vars,true)
 	    				);

 			    	}
 			    }

			}

		  // set defaults for Location Style IDs not found in database
		  foreach($locStyleIdsNotFound as $key) {
			  $locStyleData [$key] ['strokeColor']   = '#ee0016';
			  $locStyleData [$key] ['strokeWidth']   = 2;
			  $locStyleData [$key] ['strokeOpacity'] = 1;
			  $locStyleData [$key] ['fillColor']     = '#ee0011';
			  $locStyleData [$key] ['fillOpacity']   = 0.5;
			  $locStyleData [$key] ['pointRadius']   = 7;
			  $locStyleData [$key] ['graphicName']   = "";
			  $locStyleData [$key] ['popupClass']    = 'OpenLayers.Popup.Anchored';
			  $locStyleData [$key] ['popupXSize']    = 200;
		  	  $locStyleData [$key] ['popupYSize']    = 200;
			  $locStyleData [$key] ['popupXOffset']  = 0;
		      $locStyleData [$key] ['popupYOffset']  = 0;
		  }
		}
		$mapData['locStyles'] = $locStyleData;

		// -------------------------------------------------------------------------
		// set mapservice data
		// -------------------------------------------------------------------------
		$useBing = false;
	    $useGoogle = false;
	    $useOSM = false;

	    if ($objThis->c4g_map_layer_switcher) {
 	        $mapData['layerSwitcher'] = true;
 	        $mapData['layerSwitcherOpen'] = ($objThis->c4g_map_layer_switcher_open=='1');

 	        if ($profile) {
 	        	$ids = deserialize($profile['baselayers'],true);
 	        }
 	        else {
 	        	$ids = array();
 	        }
 	        if (count($ids)>0) {
 	        	$baseLayers = $database->prepare("SELECT * FROM tl_c4g_map_baselayers WHERE id IN (".implode(',',$ids).") ORDER BY sort,name")->execute();
 	        	$overlayLayers = $database->prepare("SELECT * FROM tl_c4g_map_overlays WHERE pid IN (".implode(',',$ids).") ORDER BY pid,name")->execute();

 	        }
 	        else {
 	        	$baseLayers = $database->prepare("SELECT * FROM tl_c4g_map_baselayers ORDER BY sort,name")->execute();
 	        	$overlayLayers = $database->prepare("SELECT * FROM tl_c4g_map_overlays ORDER BY pid,name")->execute();
 	        }
	    }
	    else {
 	        $mapData['layerSwitcher'] = false;
	    	$baseLayers = $database->prepare ( "SELECT * FROM tl_c4g_map_baselayers WHERE id=?" )
 	                                   ->execute ( $objThis->c4g_map_default_mapservice );
	    	$overlayLayers = $database->prepare ( "SELECT * FROM tl_c4g_map_overlays WHERE pid=?" )
 	                                   ->execute ( $objThis->c4g_map_default_mapservice );
	    }

	    $overlays=array();
	    while ($overlayLayers->next()) {
	    	$overlay=array();
	    	$overlay['name'] = $overlayLayers->name;
	    	$overlay['provider'] = $overlayLayers->provider;
	    	$overlay['attribution'] = $overlayLayers->attribution;
	    	if ($overlay['provider']=='custom') {
	    		$overlay['url1'] = $overlayLayers->url1;
	    		$overlay['url2'] = $overlayLayers->url2;
	    		$overlay['url3'] = $overlayLayers->url3;
	    		$overlay['url4'] = $overlayLayers->url4;
	    	}
	    	$found = false;
	    	foreach($overlays AS $key=>&$item) {
	    		if (($item['provider']===$overlay['provider']) &&
	    			($item['url1']===$overlay['url1']) &&
	    			($item['url2']===$overlay['url2']) &&
	    			($item['url3']===$overlay['url3']) &&
	    			($item['url4']===$overlay['url4'])) {
	    			$found = $key;
	    		}
	    	}
	    	if ($found===false)
	    	{
	    		$overlay['parents'][] = $overlayLayers->pid;
	    		$overlays[]=$overlay;
	    	} else {
	    		$overlays[$found]['parents'][]=$overlayLayers->pid;
	    	}
	    }
	    if (count($overlays)>0) {
	    	$mapData['overlays']=$overlays;
	    }

	    $i = 0;
        while ($baseLayers->next()) {

            // Access protection
            if ($baseLayers->protect_baselayer) {
                $permittedGroups = deserialize( $baseLayers->permitted_groups );
                if (!empty( $permittedGroups )) {
                    if (FE_USER_LOGGED_IN) {
                        $groupMatch = array_intersect( $objThis->User->groups, deserialize( $baseLayers->permitted_groups ) );
                        if (empty( $groupMatch )) {
                            continue;
                        }
                    } else {
                        continue;
                    }
                }
            }

        	$i++;
        	$mapData['service'][$i]['key'] = $baseLayers->id;
        	$mapData['service'][$i]['provider'] = $baseLayers->provider;
        	if ($baseLayers->display_name) {
        		$mapData['service'][$i]['name'] = $baseLayers->display_name;
        	}
        	else {
        		$mapData['service'][$i]['name'] = $baseLayers->name;
        	}
        	$mapData['service'][$i]['attribution'] = $baseLayers->attribution;
        	$mapData['service'][$i]['maxzoomlevel'] = $baseLayers->maxzoomlevel;
        	switch ($baseLayers->provider) {
        		case 'osm':
        			if ($baseLayers->osm_style=='Osmarender') {
        				// Osmarender service discontinued March 2012 -> for old entries in database
        				// take Mapnik instead
        				$mapData['service'][$i]['osm_style'] = 'Mapnik';
        			} else {
                    	$mapData['service'][$i]['osm_style'] = $baseLayers->osm_style;
        			}
                    if ($baseLayers->osm_style=='osm_custom') {
                        $mapData['service'][$i]['osm_url1'] = $objThis->repInsertTags($baseLayers->osm_style_url1);
                        $mapData['service'][$i]['osm_url2'] = $objThis->repInsertTags($baseLayers->osm_style_url2);
                        $mapData['service'][$i]['osm_url3'] = $objThis->repInsertTags($baseLayers->osm_style_url3);
                        $mapData['service'][$i]['osm_url4'] = $objThis->repInsertTags($baseLayers->osm_style_url4);
                        $mapData['service'][$i]['osm_keyname'] = $baseLayers->osm_keyname;
                    }
                    $useOSM = true;
        		    break;
        		case 'google':
                    $mapData['service'][$i]['google_style'] = $baseLayers->google_style;
                    $useGoogle = true;
        		    break;
        		case 'bing':
                    $mapData['service'][$i]['bing_style'] = $baseLayers->bing_style;
                    $mapData['service'][$i]['bing_key'] = $baseLayers->bing_key;
                    $useBing = true;
        		    break;
        		default:
            		break;
        	}
        }
        $mapData['defaultServiceKey'] = $objThis->c4g_map_default_mapservice;

	    if (count($mapData['service'])==0) {
	    	// no mapservice defined -> use OSM Mapnik by default
	    	$useOSM = true;
	    	$key = 0;
	    	$mapData['defaultServiceKey'] = $key;
	    	$mapData['service'][$key]['provider'] = 'osm';
	    	$mapData['service'][$key]['name'] = 'OSM';
	    	$mapData['service'][$key]['osm_style'] = 'Mapnik';
	    }

	    $mapData['labels'] = $GLOBALS['TL_LANG']['c4g_maps']['labels'];
	  	if ($profile['label_baselayer']!='') {
  			$mapData['labels']['baseLayer'] = $profile['label_baselayer'];
  		}
  		if ($profile['label_overlays']!='') {
  		 	$mapData['labels']['overlays'] = $profile['label_overlays'];
  		}

  		if ($profile['custom_div']) {
  			$mapData['createDiv'] = false;
	    	$mapData['div'] = $profile['custom_div'];
  		} else {
  			$mapData['createDiv'] = true;
  			$mapData['div'] = 'c4g_Map'.$mapData['id'];
  		}
        $GLOBALS ['TL_JAVASCRIPT'] [] = $openlayers_libsource;
	    $GLOBALS ['TL_JAVASCRIPT'] [] = 'system/modules/con4gis_maps/html/js/C4GMaps.js';


        // Include Extended OpenWeatherMap JS
        //TODO Abfrage, Hier anders positionieren
        $GLOBALS ['TL_JAVASCRIPT'] [] = $GLOBALS['c4g_maps_extension']['js_openlayers_owm'];

    // @TODO add toolbox-switch
        //if ($profile['toolbox']) {
        if ($profile['measuretool'] || $profile['graticule']) {
            $mapData['graticule'] = $profile['graticule'];
            $mapData['measuretool'] = $profile['measuretool'];
            $GLOBALS ['TL_JAVASCRIPT'] [] = 'system/modules/con4gis_maps/html/js/C4GMapsToolbox.js';
            //$GLOBALS ['TL_CSS']['c4g_maps_toolbox'] = 'system/modules/con4gis_maps/html/css/C4GMapsToolbox.css';
        }
        if (!$profile['for_editor_old'] && $profile['editor']){
             $GLOBALS ['TL_JAVASCRIPT'] [] = 'system/modules/con4gis_maps/html/js/C4GMapsEditor.js';
             $GLOBALS ['TL_CSS'] [] = 'system/modules/con4gis_maps/html/css/C4GMapsEditor.css';
        }
        if ($profile['router']) {
            $mapData['router_attribution'] = $profile['router_attribution'];
            $GLOBALS ['TL_JAVASCRIPT'] [] = 'system/modules/con4gis_maps/html/js/C4GMapsRouter.js';
            $GLOBALS ['TL_CSS']['c4g_maps_router'] = 'system/modules/con4gis_maps/html/css/C4GMapsRouter.css';
            $mapData['geocoding_url'] = 'system/modules/con4gis_maps/C4GNominatim.php';
            $mapData['reverse_url'] = 'system/modules/con4gis_maps/C4GReverse.php';
            $mapData['viaroute_url'] = 'system/modules/con4gis_maps/C4GViaRoute.php';
            $mapData['router_labels'] = $GLOBALS['TL_LANG']['c4g_maps']['router_labels'];
        }
        if ($mapData['permalink']) {
            $GLOBALS ['TL_JAVASCRIPT'] [] = 'system/modules/con4gis_maps/html/js/C4GPermalink.js';
        }

	    // Extended LayerSwitcher - only available when extension "con4gis_core" is installed
        if ($objThis->c4g_map_layer_switcher_ext && $GLOBALS['con4gis_core_extension']['installed']) {

	    	// Initialize Libraries for jQuery Dynatree
	    	C4GJQueryGUI::initializeTree();

	    	// Include Extended LayerSwitcher JS
	    	$GLOBALS ['TL_JAVASCRIPT'] [] = 'system/modules/con4gis_maps/html/js/C4GLayerSwitcher.js';

        }
		$GLOBALS['TL_CSS']['c4g_layerswitcher'] = 'system/modules/con4gis_maps/html/css/C4GLayerSwitcher.css';

		if ($openlayers_css) {
	    	$mapData['css'][] = $openlayers_css;
	    }
	    $mapData['css'][] = 'system/modules/con4gis_maps/html/css/styles.css';
	    if ($profile['theme']) {
	    	$mapData['css'][] = 'system/modules/con4gis_maps/html/css/theme_'.$profile['theme'].'.css';
	    }

        if ($useGoogle) {
		    $GLOBALS ['TL_JAVASCRIPT'] [] = $GLOBALS['c4g_maps_extension']['js_google'];
        }

        if ($useBing) {
        	// nothing to add
        }

        if (version_compare(VERSION,'3','<')) {
        	$objToken = RequestToken::getInstance();
        	$mapData['REQUEST_TOKEN'] = $objToken->get();
        } else {
        	$mapData['REQUEST_TOKEN'] = RequestToken::get();
        }
        //
        return $mapData;
	}

	/**
	 *
	 * @param Database $database
	 * @param int $mapId
	 */
	public static function getLocStylesForMap($database, $mapId)
	{
		$profile = $database->prepare(
				"SELECT b.locstyles ".
				"FROM tl_c4g_maps a, tl_c4g_map_profiles b ".
				"WHERE a.id = ? and a.profile = b.id")
				->execute($mapId);

		$ids = deserialize($profile->locstyles,true);
		if (count($ids)>0) {
			$locStyles = $database->prepare("SELECT * FROM tl_c4g_map_locstyles WHERE id IN (".implode(',',$ids).") ORDER BY name")->execute();
		}
		else {
			$locStyles = $database->prepare("SELECT * FROM tl_c4g_map_locstyles ORDER BY name")->execute();
		}

		return $locStyles->fetchAllAssoc();
	}

	/**
	 *
	 * @param Database $database
	 */
	public static function getLocStyles($database)
	{
		$locStyles = $database->prepare(
				"SELECT * ".
				"FROM tl_c4g_map_locstyles ".
				"ORDER BY name")
				->execute();
		return $locStyles->fetchAllAssoc();
	}

	/**
	 *
	 * @param Database $database
	 * @param int $mapId
	 */
	public static function getMapForLocation($database, $mapId)
	{
		$map = $database->prepare(
				"SELECT pid,is_map ".
				"FROM tl_c4g_maps ".
				"WHERE id = ?")
				->execute($mapId);
		if ($map->numRows > 0) {
			if ($map->is_map) {
				return $mapId;
			}
			else {
				return C4GMaps::getMapForLocation($database, $map->pid);
			}
		} else {
			return 0;
		}

	}

}

?>