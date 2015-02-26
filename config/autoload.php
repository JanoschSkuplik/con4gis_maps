<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2015 Leo Feyer
 *
 * @package Con4gis_maps
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	'C4GFeatureEditor'     => 'system/modules/con4gis_maps/C4GFeatureEditor.php',
	'C4GGeoPicker'         => 'system/modules/con4gis_maps/C4GGeoPicker.php',
	'C4GImageSize'         => 'system/modules/con4gis_maps/C4GImageSize.php',
	'C4GMaps'              => 'system/modules/con4gis_maps/C4GMaps.php',
	'C4GMapsBackend'       => 'system/modules/con4gis_maps/C4GMapsBackend.php',
	'C4GNominatim'         => 'system/modules/con4gis_maps/C4GNominatim.php',
	'C4GOverpass'          => 'system/modules/con4gis_maps/C4GOverpass.php',
	'C4GReverse'           => 'system/modules/con4gis_maps/C4GReverse.php',
	'C4GTextField'         => 'system/modules/con4gis_maps/C4GTextField.php',
	'C4GViaRoute'          => 'system/modules/con4gis_maps/C4GViaRoute.php',
	'Content_c4g_maps'     => 'system/modules/con4gis_maps/Content_c4g_maps.php',
	// Models
	'C4gMapLocstylesModel' => 'system/modules/con4gis_maps/models/C4gMapLocstylesModel.php',
	'Module_c4g_maps'      => 'system/modules/con4gis_maps/Module_c4g_maps.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'c4g_featureeditor' => 'system/modules/con4gis_maps/templates',
	'c4g_geopicker'     => 'system/modules/con4gis_maps/templates',
	'ce_c4g_maps'       => 'system/modules/con4gis_maps/templates',
	'mod_c4g_maps'      => 'system/modules/con4gis_maps/templates',
));
