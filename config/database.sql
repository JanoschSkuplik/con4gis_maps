-- ********************************************************
-- *                                                      *
-- * IMPORTANT NOTE                                       *
-- *                                                      *
-- * Do not import this file manually but use the Contao  *
-- * install tool to create and maintain database tables! *
-- *                                                      *
-- ********************************************************


--
-- Table `tl_c4g_maps`
--

CREATE TABLE `tl_c4g_maps` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `sorting` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `profile` int(10) unsigned NOT NULL default '0',
  `profile_mobile` int(10) unsigned NOT NULL default '0',
  `published` char(1) NOT NULL default '1',
  `is_map` char(1) NOT NULL default '',
  `mapsize` varchar(255) NOT NULL default '',
  `auto_width` char(1) NOT NULL default '',
  `auto_width_min` int(10) unsigned NOT NULL default '0',
  `auto_width_max` int(10) unsigned NOT NULL default '0',
  `auto_width_gap` int(10) unsigned NOT NULL default '0',
  `auto_height` char(1) NOT NULL default '',
  `auto_height_min` int(10) unsigned NOT NULL default '0',
  `auto_height_max` int(10) unsigned NOT NULL default '0',
  `auto_height_gap` int(10) unsigned NOT NULL default '0',
  `calc_extent` varchar(10) NOT NULL default '',
  `min_gap` int(10) unsigned NOT NULL default '0',
  `center_geox` varchar(20) NOT NULL default '',
  `center_geoy` varchar(20) NOT NULL default '',
  `geolocation` char(1) NOT NULL default '',
  `geolocation_zoom` int(10) unsigned NOT NULL default '14',
  `restrict_area` char(1) NOT NULL default '',
  `restr_bottomleft_geox` varchar(20) NOT NULL default '',
  `restr_bottomleft_geoy` varchar(20) NOT NULL default '',
  `restr_topright_geox` varchar(20) NOT NULL default '',
  `restr_topright_geoy` varchar(20) NOT NULL default '',
  `zoom` int(10) unsigned NOT NULL default '10',
  `include_sublocations` char(1) NOT NULL default '1',
  `location_type` char(10) NOT NULL default '',
  `loc_geox` varchar(20) NOT NULL default '',
  `loc_geoy` varchar(20) NOT NULL default '',
  `locstyle` int(10) unsigned NOT NULL default '0',
  `loc_label` varchar(100) NOT NULL default '',
  `loc_only_in_parent` char(1) NOT NULL default '',
  `tooltip` varchar(100) NOT NULL default '',
  `popup_info` text NULL,
  `popup_extend` char(1) NOT NULL default '0',
  `routing_to` char(1) NOT NULL default '',
  `loc_linkurl` varchar(255) NOT NULL default '',
  `loc_onclick_zoomto` int(10) unsigned NOT NULL default '0',
  `loc_minzoom` int(10) unsigned NOT NULL default '0',
  `loc_maxzoom` int(10) unsigned NOT NULL default '0',
  `tab_source` varchar(100) NOT NULL default '',
  `tab_pid` int(10) unsigned NOT NULL default '0',
  `tab_whereclause` text NULL,
  `tab_orderby` varchar(128) NOT NULL default '',
  `tab_labeldisplay` char(10) NOT NULL default '1ST_MORE',
  `tab_tooltipdisplay` char(10) NOT NULL default '1ST_MORE',
  `tab_directlink` char(1) NOT NULL default '',
  `tab_force_target_blank` char(1) NOT NULL default '',
  `tab_filter_alias` char(1) NOT NULL default '',
  `data_file` binary(16) NULL,
  `data_url` varchar(255) NOT NULL default '',
  `data_content` text NULL,
  `data_projection` char(5) NOT NULL default '',
  `data_forcenodes` char(1) NOT NULL default '',
  `data_layername` varchar(100) NOT NULL default '',
  `data_hidelayer` char(1) NOT NULL default '',
  `data_js_style_function` varchar(100) NOT NULL default '',
  `forums` blob NULL,
  `forum_jumpto` int(10) unsigned NOT NULL default '0',
  `forum_reassign_layer` char(10) NOT NULL default 'NO',
  `ovp_request` text NULL,
  `ovp_bbox_limited` char(1) NOT NULL default '',
  `link_id` int(10) unsigned NOT NULL default '0',
  `protect_element` char(1) NOT NULL default '',
  `permitted_groups` blob NULL,
  `use_specialprofile` char(1) NOT NULL default '',
  `specialprofile` int(10) unsigned NOT NULL default '0',
  `specialprofile_mobile` int(10) unsigned NOT NULL default '0',
  `specialprofile_groups` blob NULL,
  `be_optimize_checkboxes_limit` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table `tl_c4g_map_profiles`
--

CREATE TABLE `tl_c4g_map_profiles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(255) NOT NULL default '',
  `is_default` char(1) NOT NULL default '',
  `baselayers` blob NULL,
  `locstyles` blob NULL,
  `pan_panel` char(1) NULL,
  `zoom_panel` char(1) NOT NULL default '1',
  `zoom_panel_world` char(1) NOT NULL default '1',
  `mouse_nav` char(1) NOT NULL default '1',
  `mouse_nav_wheel` char(1) NOT NULL default '1',
  `mouse_nav_zoombox` char(1) NOT NULL default '1',
  `mouse_nav_kinetic` char(1) NOT NULL default '',
  `mouse_nav_toolbar` char(1) NOT NULL default '',
  `keyboard_nav` char(1) NOT NULL default '1',
  `nav_history` char(1) NOT NULL default '',
  `attribution` char(1) NOT NULL default '1',
  `cfg_logo_attribution` char(1) NOT NULL default '1',
  `div_attribution` varchar(30) NOT NULL default '',
  `add_attribution` varchar(255) NOT NULL default '',
  `overviewmap` char(1) NOT NULL default '',
  `scaleline` char(1) NOT NULL default '',
  `mouseposition` char(1) NOT NULL default '',
  `permalink` char(1) NOT NULL default '',
  `graticule` char(1) NOT NULL default '',
  `editor` char(1) NOT NULL default '',
  `zoomlevel` char(1) NOT NULL default '',
  `fullscreen` char(1) NOT NULL default '',
  `measuretool` char(1) NULL,
  `geosearch` char(1) NOT NULL default '',
  `geosearch_engine` char(1) NOT NULL default '2',
  `geosearch_customengine_url` varchar(255) NOT NULL default '',
  `geosearch_customengine_attribution` varchar(255) NOT NULL default '',
  `geosearch_div` varchar(30) NOT NULL default '',
  `geosearch_zoomto` int(10) unsigned NOT NULL default '0',
  `geosearch_zoombounds` char(1) NOT NULL default '',
  `geosearch_attribution` char(1) NOT NULL default '1',
  `geopicker` char(1) NOT NULL default '',
  `geopicker_fieldx` varchar(30) NOT NULL default '',
  `geopicker_fieldy` varchar(30) NOT NULL default '',
  `geopicker_searchdiv` varchar(30) NOT NULL default '',
  `geopicker_attribution` char(1) NOT NULL default '1',
  `router` char(1) NOT NULL default '',
  `router_viaroute_url` varchar(255) NOT NULL default '',
  `router_attribution` varchar(255) NOT NULL default '',
  `imagepath` binary(16) NULL,
  `theme` char(10) NOT NULL default '',
  `libsource` varchar(10) NOT NULL default 'DEFAULT',
  `script` text NULL,
  `overpass_url` varchar(255) NOT NULL default '',
  `custom_div` varchar(30) NOT NULL default '',
  `hover_popups` char(1) NOT NULL default '',
  `hover_popups_stay` char(1) NOT NULL default '',
  `link_newwindow` char(1) NOT NULL default '',
  `link_open_on` char(5) NOT NULL default 'CLICK',
  `div_layerswitcher` varchar(30) NOT NULL default '',
  `label_baselayer` varchar(30) NOT NULL default '',
  `label_overlays` varchar(30) NOT NULL default '',
  `editor_styles_point` blob NULL,
  `editor_styles_line` blob NULL,
  `editor_styles_polygon` blob NULL,
  `editor_vars` text NULL,
  `editor_show_items` char(1) NOT NULL default '',
  `editor_helpurl` varchar(255) NOT NULL default '',
  `be_optimize_checkboxes_limit` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table `tl_c4g_map_prof_locstyles` - old table, will be deleted in the future!
--

CREATE TABLE `tl_c4g_map_prof_locstyles` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
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
  `icon_src` binary(16) NULL,
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
  `editor_icon` binary(16) NULL,
  `editor_sort` int(10) unsigned NOT NULL default '0',
  `editor_vars` text NULL,
  `editor_collect` char(1) NOT NULL default '',
  `be_optimize_checkboxes_limit` int(10) unsigned NOT NULL default '0',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table `tl_c4g_map_prof_services` - old table, will be deleted in the future!
--

CREATE TABLE `tl_c4g_map_prof_services` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
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
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table `tl_c4g_map_locstyles`
--

CREATE TABLE `tl_c4g_map_locstyles` (
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
  `icon_src` binary(16) NULL,
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
  `editor_icon` binary(16) NULL,
  `editor_sort` int(10) unsigned NOT NULL default '0',
  `editor_vars` text NULL,
  `editor_collect` char(1) NOT NULL default '',
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table `tl_c4g_map_baselayers`
--

CREATE TABLE `tl_c4g_map_baselayers` (
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
  `protect_baselayer` char(1) NOT NULL default '',
  `permitted_groups` blob NULL,
  PRIMARY KEY  (`id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------
--
-- Table `tl_c4g_map_overlays` - contains children of `tl_c4g_map_baselayers`
--

CREATE TABLE `tl_c4g_map_overlays` (
  `id` int(10) unsigned NOT NULL auto_increment,
  `pid` int(10) unsigned NOT NULL default '0',
  `tstamp` int(10) unsigned NOT NULL default '0',
  `name` varchar(100) NOT NULL default '',
  `provider` varchar(100) NOT NULL default '',
  `url1` varchar(255) NOT NULL default '',
  `url2` varchar(255) NOT NULL default '',
  `url3` varchar(255) NOT NULL default '',
  `url4` varchar(255) NOT NULL default '',
  `attribution` varchar(255) NOT NULL default '',
  PRIMARY KEY  (`id`),
  KEY `pid` (`pid`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_content`
--

CREATE TABLE `tl_content` (
  `c4g_map_id` int(10) unsigned NOT NULL default '0',
  `c4g_map_mapsize` varchar(255) NOT NULL default '',
  `c4g_map_zoom` int(10) unsigned NOT NULL default '0',
  `c4g_map_default_mapservice` int(10) unsigned NOT NULL default '0',
  `c4g_map_layer_switcher` char(1) NOT NULL default '1',
  `c4g_map_layer_switcher_open` char(1) NOT NULL default '',
  `c4g_map_layer_switcher_ext` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
-- --------------------------------------------------------

--
-- Table `tl_module`
--

CREATE TABLE `tl_module` (
  `c4g_map_id` int(10) unsigned NOT NULL default '0',
  `c4g_map_mapsize` varchar(255) NOT NULL default '',
  `c4g_map_zoom` int(10) unsigned NOT NULL default '0',
  `c4g_map_default_mapservice` int(10) unsigned NOT NULL default '0',
  `c4g_map_layer_switcher` char(1) NOT NULL default '1',
  `c4g_map_layer_switcher_open` char(1) NOT NULL default '',
  `c4g_map_layer_switcher_ext` char(1) NOT NULL default '',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table `tl_calendar_events`
--

CREATE TABLE `tl_calendar_events` (
  `c4g_loc_geox` varchar(20) NOT NULL default '',
  `c4g_loc_geoy` varchar(20) NOT NULL default '',
  `c4g_loc_label` varchar(100) NOT NULL default '',
  `c4g_locstyle` int(10) unsigned NOT NULL default '0',
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
