<?php

/**
 * Contao Open Source CMS
 *
 * Copyright (c) 2005-2014 Leo Feyer
 *
 * @package Con4gis_maps
 * @link    https://contao.org
 * @license http://www.gnu.org/licenses/lgpl-3.0.html LGPL
 */


/**
 * Register the namespaces
 */
ClassLoader::addNamespaces(array
(
	'c4g',
));


/**
 * Register the classes
 */
ClassLoader::addClasses(array
(
	// Classes
	'C4GImageSize'              => 'system/modules/con4gis_maps/classes/C4GImageSize.php',
	'c4g\C4GMaps'               => 'system/modules/con4gis_maps/classes/C4GMaps.php',
	'C4GTextField'              => 'system/modules/con4gis_maps/classes/C4GTextField.php',

	// Models
	'c4g\C4gMapBaselayersModel' => 'system/modules/con4gis_maps/models/C4gMapBaselayersModel.php',
	'c4g\C4gMapLocstylesModel'  => 'system/modules/con4gis_maps/models/C4gMapLocstylesModel.php',
	'c4g\C4gMapOverlaysModel'   => 'system/modules/con4gis_maps/models/C4gMapOverlaysModel.php',
	'c4g\C4gMapProfilesModel'   => 'system/modules/con4gis_maps/models/C4gMapProfilesModel.php',
	'c4g\C4gMapsModel'          => 'system/modules/con4gis_maps/models/C4gMapsModel.php',

	// Modules
	'LayerApi'                  => 'system/modules/con4gis_maps/modules/api/LayerApi.php',
	'c4g\Content_c4g_maps'      => 'system/modules/con4gis_maps/modules/Content_c4g_maps.php',
	'c4g\Module_c4g_maps'       => 'system/modules/con4gis_maps/modules/Module_c4g_maps.php',
));


/**
 * Register the templates
 */
TemplateLoader::addFiles(array
(
	'c4g_maps' => 'system/modules/con4gis_maps/templates',
));
