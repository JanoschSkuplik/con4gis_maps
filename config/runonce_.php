<?php

/**
 * Contao Open Source CMS
 *
 * @version   php 5
 * @package   con4gis
 * @license   GNU/LGPL http://opensource.org/licenses/lgpl-3.0.html
 * @copyright Küstenschmiede GmbH Software & Design 2014
 * @link      https://www.kuestenschmiede.de
 * @filesource 
 */

	{

		//check if FileTree fields have to be updated to Contao 3.2

		if ($this->Database->tableExists('tl_c4g_maps'))
		{
			$arrFields = $this->Database->listFields('tl_c4g_maps');
			$blnDone = false;

			// check if one of the fields has already been converted 
			foreach ($arrFields as $arrField)
			{
				if ($arrField['name'] == 'data_file' && $arrField['type'] != 'varchar')
				{
					$blnDone = true;
				}
			}
			
			// Run the version 3.2 update for all fields 
			if ($blnDone == false)
			{
				Database\Updater::convertSingleField('tl_c4g_maps', 'data_file');
				Database\Updater::convertSingleField('tl_c4g_map_prof_locstyles', 'icon_src');
				Database\Updater::convertSingleField('tl_c4g_map_prof_locstyles', 'editor_icon');
				Database\Updater::convertSingleField('tl_c4g_map_locstyles', 'icon_src');
				Database\Updater::convertSingleField('tl_c4g_map_locstyles', 'editor_icon');
				Database\Updater::convertSingleField('tl_c4g_map_profiles', 'imagepath');
			}

		}

	}
}


$objC4GMapsBackend = new C4GMapsBackend();
$objC4GMapsBackend->performDBUpdate();

$objC4GMapsRunonceJob = new C4GMapsRunonceJob();
$objC4GMapsRunonceJob->run();

?>