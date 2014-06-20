OpenLayers.Control.C4GArgParser=OpenLayers.Class(OpenLayers.Control,{center:null,zoom:null,layers:null,base:null,len:null,displayProjection:null,getParameters:function(url)
{url=url||window.location.href;var parameters=OpenLayers.Util.getParameters(url);var index=url.indexOf('#');if(index>0){url='?'+url.substring(index+1,url.length);OpenLayers.Util.extend(parameters,OpenLayers.Util.getParameters(url));}
return parameters;},setMap:function(map)
{OpenLayers.Control.prototype.setMap.apply(this,arguments);for(var i=0,len=this.map.controls.length;i<len;i++){var control=this.map.controls[i];if((control!=this)&&(control.CLASS_NAME=="OpenLayers.Control.C4GArgParser")){if(control.displayProjection!=this.displayProjection){this.displayProjection=control.displayProjection;}
break;}}
if(i==this.map.controls.length){var args=this.getParameters();if(args.len){this.len=args.len;}
if(args.layers){this.layers=args.layers;this.addLayer=true;}
if(args.base){this.base=args.base;this.addLayer=true;}
if(this.addLayer){this.map.events.register('addlayer',this,this.configureLayers);this.configureLayers();}
if(args.lat&&args.lon){this.center=new OpenLayers.LonLat(parseFloat(args.lon),parseFloat(args.lat));if(args.zoom){this.zoom=parseFloat(args.zoom);}
this.map.events.register('changebaselayer',this,this.setCenter);this.setCenter();}}},setCenter:function()
{if(this.map.baseLayer){this.map.events.unregister('changebaselayer',this,this.setCenter);if(this.displayProjection){this.center.transform(this.displayProjection,this.map.getProjectionObject());}
this.map.setCenter(this.center,this.zoom);}},configureLayers:function()
{if(this.len==this.map.layers.length&&this.addLayer){this.map.events.unregister('addlayer',this,this.configureLayers);var activeLayers=new Array();if(this.layers){activeLayers=atob(this.layers);activeLayers=activeLayers.split('-');}
for(var i=0;this.map.layers[i];i++){var layer=this.map.layers[i];if(layer.isBaseLayer){if(layer.key==this.base){this.map.setBaseLayer(layer);}
continue;}
for(var j=0,len=activeLayers.length;j<len;j++){if(layer.key==parseInt(activeLayers[j])){layer.setVisibility(true);}}}}},CLASS_NAME:"OpenLayers.Control.C4GArgParser"});