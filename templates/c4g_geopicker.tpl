<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php echo $this->language; ?>" lang="<?php echo $this->language; ?>">
<head>
<base href="<?php echo $this->base; ?>"></base>
<title><?php echo $this->title; ?> :: Contao Open Source CMS <?php echo VERSION; ?></title>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo $this->charset; ?>" />
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/basic.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" />
<link rel="stylesheet" type="text/css" href="system/modules/con4gis_maps/html/css/styles.css" media="screen" />
<link rel="stylesheet" type="text/css" href="system/modules/con4gis_maps/html/css/geopicker.css" media="screen" />
<?php if ($this->isMac): ?>
<link rel="stylesheet" type="text/css" href="system/themes/<?php echo $this->theme; ?>/macfixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" />
<?php endif; ?>
<!--[if lte IE 7]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/iefixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" /><![endif]-->
<!--[if IE 8]><link type="text/css" rel="stylesheet" href="system/themes/<?php echo $this->theme; ?>/ie8fixes.css?<?php echo VERSION .'.'. BUILD; ?>" media="screen" /><![endif]-->
<script type="text/javascript" src="plugins/mootools/mootools-core.js?<?php echo MOOTOOLS_CORE; ?>"></script>
<script type="text/javascript" src="plugins/mootools/mootools-more.js?<?php echo MOOTOOLS_MORE; ?>"></script>
<script type="text/javascript" src="contao/contao.js?<?php echo VERSION .'.'. BUILD; ?>"></script>
<script type="text/javascript" src="system/themes/<?php echo $this->theme; ?>/hover.js?<?php echo VERSION .'.'. BUILD; ?>"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['c4g_maps_extension']['js_openlayers']; ?>"></script>
<script type="text/javascript" src="<?php echo $GLOBALS['c4g_maps_extension']['js_google']; ?>"></script>
<script type="text/javascript" src="system/modules/con4gis_maps/html/js/C4GMaps.js"></script>
<script type="text/javascript" src="system/modules/con4gis_maps/html/js/C4GMapsBackend.js"></script>

<script type="text/javascript">

//<![CDATA[
function doSubmit() {
  self.opener.$(self.opener.C4GMapsBackend.currentIdX).value = $("c4gGeoPickerGeoX").value;
  self.opener.$(self.opener.C4GMapsBackend.currentIdY).value = $("c4gGeoPickerGeoY").value;
  self.close();
}          
           
var mapdata = <?php echo json_encode($this->mapData) ?>;
mapdata.onPickGeo = function(geox, geoy) {
	if (typeof($)!='undefined') {
		$('c4gGeoPickerGeoX').value = geox;
		$('c4gGeoPickerGeoY').value = geoy;
	}	
};
C4GMaps(mapdata);
//]]>

</script>
</head>
<body class="__ua__">

<div id="container">
<div id="main" class="mod_c4g_maps">

<h1><?php echo $this->headline; ?></h1>
<input id="c4gGeoPickerGeoX" type="text" value="<?php echo $this->Input->get('GeoX') ?>" name="GeoX">
<input id="c4gGeoPickerGeoY" type="text" value="<?php echo $this->Input->get('GeoY') ?>" name="GeoY">
<input id="Ok" type="button" value="<?php echo $GLOBALS['TL_LANG']['c4g_maps']['transfer'] ?>" onclick="doSubmit()">
<input id="Cancel" type="button" value="<?php echo $GLOBALS['TL_LANG']['MSC']['cancelBT'] ?>" onclick="self.close()">
<div id="c4gGeoPickerGeocoding" class="c4gGeoPickerGeocoding"></div>
<hr>
<div id="c4g_Map"></div>
</div>
</div>

</body>
</html>