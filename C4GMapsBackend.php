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
 * Class C4GMapsBackend
 *
 * Provide methods to handle backendconfigurations
 * @copyright  Küstenschmiede GmbH Software & Design 2014
 * @author     Jürgen Witte <http://www.kuestenschmiede.de> 
 * @package    con4gis 
 * @author     Jürgen Witte <http://www.kuestenschmiede.de>
 */
class C4GMapsBackend extends Backend
{
	/**
	 * Constructor
	 */
	public function __construct()
	{
		parent::__construct();
		$this->import('Database');
	}
	
	/**
	 * Form for update of database to C4G-Maps 2.0
	 */
	public function updateDB()
	{
		$message = '';
		$result = '';
		
		if ($this->Input->post('FORM_SUBMIT') == 'tl_c4g_maps_update_db')
		{
			$result = $this->performDBUpdate();
			
			$message ='
			<div class="tl_header" style="color:#090;">
			'.$GLOBALS['TL_LANG']['c4g_maps']['update_db_success'].'
			</div>';
		}
		
		// create the form
		$form=
			//back-button
			$result.'
			<div id="tl_buttons">
				<a href="'.ampersand(str_replace('&key=update_db', '', $this->Environment->request)).'" class="header_back" title="'.specialchars($GLOBALS['TL_LANG']['MSC']['backBT']).'" accesskey="b">'.$GLOBALS['TL_LANG']['MSC']['backBT'].'</a>
			</div>'.

			//headline
			'
			<h2 class="sub_headline">'.$GLOBALS['TL_LANG']['c4g_maps']['update_db'].'</h2>
			'.$this->getMessages().'
			<form action="'.ampersand($this->Environment->request, true).'" id="tl_c4g_maps_update_db" class="tl_form" method="post">
			<div class="tl_formbody_edit">
				<input type="hidden" name="FORM_SUBMIT" value="tl_c4g_maps_update_db">
				<input type="hidden" name="REQUEST_TOKEN" value="'.REQUEST_TOKEN.'">
			</div>
			<center>
			'.$message.'
			<br/>
			<div align="left" class="tl_tbox" style="padding:1px; box-shadow:0px 1px 6px #666; -moz-border-radius:3px; -webkit-border-radius:3px; border-radius:3px; width:600px">
				<div align="center">
					<h1 class="main_headline">'.$GLOBALS['TL_LANG']['c4g_maps']['db_status'].'</h1>
				</div>
			';
		
		$uptodate = $this->isDBUpToDate();
		if ($uptodate) {
			$form .= '<div align="center" style="color:green; padding:3px; margin:1px;">'.$GLOBALS['TL_LANG']['c4g_maps']['db_uptodate'].'</div>';
				
		}
		else {
			$form .= '<div align="center" style="color:#900; padding:3px; margin:1px;">'.$GLOBALS['TL_LANG']['c4g_maps']['db_update_necessary'].'</div>';
		}	
		$form .='
			<div>&nbsp;</div>
			</div>
			
			<br/>
			</center>
			<br/>
			';
		if (!$uptodate) {
			//submit-buttons
			$form .= '
				<div class="tl_formbody_submit">
					<div align="center" class="tl_submit_container">
						<input type="submit" name="index" id="index" class="tl_submit" accesskey="i" value="'.specialchars($GLOBALS['TL_LANG']['c4g_maps']['update_db']).'">
					</div>
				</div>';
		}	
		$form .= '</form>';
		
		// return the form
		return $form;
	}
	
	/**
	 * check if the database is up to date
	 */
	public function isDBUpToDate()
	{		
		if (!$this->Database->tableExists('tl_c4g_maps')) {
			// no C4G-Maps tables installed
			return false;
		}
		$new_count = 0;
		$tabMissing = false;
		if ($this->Database->tableExists('tl_c4g_map_locstyles',null,true)) {
			$locstyles = $this->Database->prepare(
					"SELECT count(*) AS count FROM tl_c4g_map_locstyles")->executeUncached();
			$new_count += $locstyles->count;			 
		}
		else {
			$tabMissing = true;
		}
		if ($this->Database->tableExists('tl_c4g_map_baselayers',null,true)) {
			$baselayers = $this->Database->prepare(
					"SELECT count(*) AS count FROM tl_c4g_map_baselayers")->executeUncached();
			$new_count += $baselayers->count;			 
		}
		else {
			$tabMissing = true;
		}
		
		$old_locstyles = $this->Database->prepare(
				"SELECT count(*) AS count FROM tl_c4g_map_prof_locstyles")->execute();
		$old_services = $this->Database->prepare(
				"SELECT count(*) AS count FROM tl_c4g_map_prof_services")->execute();
		
		$old_count = $old_locstyles->count + $old_services->count;
		
		if ($new_count>0) {
			return true;
		}
		else {
			if (($old_count>0) || ($tabMissing)) {
				return false;
			}
			else {
				return true;
			}
		}
	}
	
	/**
	 * Update database to C4G-Maps 2.0
	 */
	public function performDBUpdate()
	{
		if (!$this->Database->tableExists('tl_c4g_maps')) {
			// new installation -> no update
			return 'C4G-Maps DB is not installed at all -> run contao/install.php';
		}
		if ($this->isDBUpToDate()) {
			return 'C4G-Maps DB is already up to date!';
		}
		
		$newTables = false;
		// Create the new baselayers table 
		if (!$this->Database->tableExists('tl_c4g_map_baselayers')) {			
			$this->Database->query(
				"CREATE TABLE `tl_c4g_map_baselayers` (
				  `id` int(10) unsigned NOT NULL auto_increment,
				  `tstamp` int(10) unsigned NOT NULL default '0',
				  `name` varchar(100) NOT NULL default '',
				  `display_name` varchar(100) NOT NULL default '',
				  `sort` int(10) NOT NULL default '0',
				  `provider` varchar(10) NOT NULL default '', 
				  `osm_style` varchar(30) NOT NULL default '',
				  `osm_style_url1` varchar(255) NOT NULL default '',
				  `osm_style_url2` varchar(255) NOT NULL default '',
				  `osm_style_url3` varchar(255) NOT NULL default '',
				  `osm_style_url4` varchar(255) NOT NULL default '',
				  `osm_keyname` varchar(30) NOT NULL default '',
				  `google_style` varchar(30) NOT NULL default '',
				  `bing_style` varchar(30) NOT NULL default '',
				  `bing_key` varchar(100) NOT NULL default '',
				  `attribution` varchar(255) NOT NULL default '',
				  `maxzoomlevel` int(10) NOT NULL default '0',
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
			);
			$newTables = true;
		}

		// Create the new location style table
		if (!$this->Database->tableExists('tl_c4g_map_locstyles')) {
			$this->Database->query(
				"CREATE TABLE `tl_c4g_map_locstyles` (
				  `id` int(10) unsigned NOT NULL auto_increment,
				  `tstamp` int(10) unsigned NOT NULL default '0',
				  `name` varchar(100) NOT NULL default '',
				  `styletype` varchar(10) NOT NULL default '', 
				  `strokewidth` varchar(100) NOT NULL default '',
				  `strokecolor` varchar(6) NOT NULL default '',
				  `strokeopacity` varchar(100) NOT NULL default '',
				  `fillcolor` varchar(6) NOT NULL default '',
				  `fillopacity` varchar(100) NOT NULL default '',
				  `radius` varchar(100) NOT NULL default '',
				  `ol_icon` varchar(100) NOT NULL default 'marker.png',
				  `ol_icon_size` varchar(100) NOT NULL default '',
				  `ol_icon_offset` varchar(100) NOT NULL default '',
				  `icon_src` varchar(255) NOT NULL default '',
				  `icon_size` varchar(100) NOT NULL default '',
				  `icon_offset` varchar(100) NOT NULL default '',
				  `icon_opacity` varchar(100) NOT NULL default '',
				  `onhover_locstyle` int(10) unsigned NOT NULL default '0',
				  `line_arrows` char(1) NOT NULL default '',
				  `line_arrows_back` char(1) NOT NULL default '',
				  `line_arrows_radius` varchar(100) NOT NULL default '',
				  `line_arrows_minzoom` int(10) unsigned NOT NULL default '0',
				  `label_align_hor` varchar(10) NOT NULL default '',
				  `label_align_ver` varchar(10) NOT NULL default '',
				  `label_offset` varchar(100) NOT NULL default '',
				  `font_family` varchar(100) NOT NULL default '',
				  `font_color` varchar(6) NOT NULL default '',
				  `font_size` varchar(100) NOT NULL default '',
				  `label_outl_color` varchar(6) NOT NULL default '',
				  `label_outl_width` varchar(100) NOT NULL default '',
				  `font_opacity` varchar(100) NOT NULL default '',
				  `font_style` varchar(100) NOT NULL default '',
				  `font_weight` varchar(100) NOT NULL default '',
				  `label` varchar(100) NOT NULL default '',
				  `tooltip` varchar(100) NOT NULL default '',
				  `popup_info` text NULL,
				  `popup_kind` varchar(30) NOT NULL default 'cloud',
				  `popup_size` varchar(100) NOT NULL default '',
				  `popup_offset` varchar(100) NOT NULL default '',  
				  `onclick_zoomto` int(10) unsigned NOT NULL default '0',  
				  `minzoom` int(10) unsigned NOT NULL default '0',  
				  `maxzoom` int(10) unsigned NOT NULL default '0',  
				  `editor_vars` text NULL,
				  `editor_icon` varchar(255) NOT NULL default '',
				  `editor_collect` char(1) NOT NULL default '',
				  PRIMARY KEY  (`id`)
				) ENGINE=MyISAM DEFAULT CHARSET=utf8;"
			);
			$newTables = true;
		}
		
		if ($newTables) {
			// needed for DB update
			try {
				$this->Database->query("ALTER TABLE `tl_c4g_map_profiles` ADD `baselayers` blob NULL;");				
			} catch (Exception $e) {
			}
			try {
				$this->Database->query("ALTER TABLE `tl_c4g_map_profiles` ADD `locstyles` blob NULL;");				
			} catch (Exception $e) {
			}
		}	
		
		$prof = $this->Database->prepare(
				"SELECT count(*) AS count FROM tl_c4g_map_profiles")->execute();
		$data = $this->Database->prepare(
				"SELECT a.*, b.name AS profname FROM tl_c4g_map_prof_services a, tl_c4g_map_profiles b WHERE a.pid = b.id")->execute()->fetchAllAssoc();
		foreach ($data as $row) {
			$set = array();
			foreach ($row as $key=>&$value ) {
				if (($key!='pid') && ($key!='profname')) {
					if ($key=='name') {
						if ($prof->count > 1) {							
							$set['name'] = $row['profname'] . ' - ' . $value;
						}
						else {
							$set['name'] = $value;								
						}	
						$set['display_name'] = $value;
					}
					else {
						$set[$key] = $value;
					}
				}
			}
			
			$objInsertStmt = $this->Database->prepare("INSERT INTO tl_c4g_map_baselayers %s")
			->set($set)
			->execute();
			
			if (!$objInsertStmt->affectedRows)
			{
				$result .= 'Error Service ID '.$row['id'].'<br/>';
			}
			
		}

		$data = $this->Database->prepare(
				"SELECT a.*, b.name AS profname FROM tl_c4g_map_prof_locstyles a, tl_c4g_map_profiles b WHERE a.pid = b.id")->execute()->fetchAllAssoc();
		foreach ($data as $row) {
			$set = array();
			foreach ($row as $key=>&$value ) {
				if (($key!='pid') && ($key!='profname')) {
					if ($key=='name') {
						if ($prof->count > 1) {							
							$set['name'] = $row['profname'] . ' - ' . $value;
						}
						else {
							$set['name'] = $value;								
						}	
					}
					else {
						$set[$key] = $value;
					}
				}
			}
				
			$objInsertStmt = $this->Database->prepare("INSERT INTO tl_c4g_map_locstyles %s")
			->set($set)
			->execute();
				
			if (!$objInsertStmt->affectedRows)
			{
				$result .= 'Error Locstyle ID '.$row['id'].'<br/>';
			}
		}
		
		if ($prof->count > 1) {				
			// update assignment for location styles and base layers in profiles from old child tables			
			$profiles = $this->Database->prepare(
					"SELECT id FROM tl_c4g_map_profiles")->execute();
			while($profiles->next()) {
				$locstyles = $this->Database->prepare(
						"SELECT id FROM tl_c4g_map_prof_locstyles WHERE pid = ?")->execute($profiles->id)->fetchEach('id');
				$baselayers = $this->Database->prepare(
						"SELECT id FROM tl_c4g_map_prof_services WHERE pid = ?")->execute($profiles->id)->fetchEach('id');
				$set = array();
				$set['locstyles'] = serialize($locstyles);
				$set['baselayers'] = serialize($baselayers);
				$objUpdateStmt = $this->Database->prepare("UPDATE tl_c4g_map_profiles %s WHERE id=?")
					->set($set)
					->execute($profiles->id);
			}
		}
		return $result;
	}
	
}

?>