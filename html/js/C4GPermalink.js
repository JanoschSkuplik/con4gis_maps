OpenLayers.Control.C4GPermalink=OpenLayers.Class(OpenLayers.Control,{argParserClass:OpenLayers.Control.C4GArgParser,element:null,anchor:false,base:'',displayProjection:null,initialize:function(element,base,options)
{if(element!==null&&typeof element=='object'&&!OpenLayers.Util.isElement(element)){options=element;this.base=document.location.href;OpenLayers.Control.prototype.initialize.apply(this,[options]);if(this.element!=null){this.element=OpenLayers.Util.getElement(this.element);}}
else{OpenLayers.Control.prototype.initialize.apply(this,[options]);this.element=OpenLayers.Util.getElement(element);this.base=base||document.location.href;}},destroy:function()
{if(this.element&&this.element.parentNode==this.div){this.div.removeChild(this.element);this.element=null;}
if(this.map){this.map.events.unregister('moveend',this,this.updateLink);}
OpenLayers.Control.prototype.destroy.apply(this,arguments);},setMap:function(map)
{OpenLayers.Control.prototype.setMap.apply(this,arguments);for(var i=0,len=this.map.controls.length;i<len;i++){var control=this.map.controls[i];if(control.CLASS_NAME==this.argParserClass.CLASS_NAME){if(control.displayProjection!=this.displayProjection){this.displayProjection=control.displayProjection;}
break;}}
if(i==this.map.controls.length){this.map.addControl(new this.argParserClass({'displayProjection':this.displayProjection}));}},draw:function()
{OpenLayers.Control.prototype.draw.apply(this,arguments);if(!this.element&&!this.anchor){this.element=document.createElement("a");this.element.innerHTML=OpenLayers.i18n("Permalink");this.element.href="";this.div.appendChild(this.element);}
this.map.events.on({'moveend':this.updateLink,'changelayer':this.updateLink,'changebaselayer':this.updateLink,scope:this});this.updateLink();return this.div;},updateLink:function()
{var separator=this.anchor?'#':'?';var href=this.base;var anchor=null;if(href.indexOf("#")!=-1&&this.anchor==false){anchor=href.substring(href.indexOf("#"),href.length);}
if(href.indexOf(separator)!=-1){href=href.substring(0,href.indexOf(separator));}
var splits=href.split("#");href=splits[0]+separator+OpenLayers.Util.getParameterString(this.createParams());if(anchor){href+=anchor;}
if(this.anchor&&!this.element){window.location.href=href;}
else{this.element.href=href;}},createParams:function(center,zoom,layers)
{center=center||this.map.getCenter();var params=OpenLayers.Util.getParameters(this.base);if(center){params.zoom=zoom||this.map.getZoom();var lat=center.lat;var lon=center.lon;if(this.displayProjection){var mapPosition=OpenLayers.Projection.transform({x:lon,y:lat},this.map.getProjectionObject(),this.displayProjection);lon=mapPosition.x;lat=mapPosition.y;}
params.lat=Math.round(lat*100000)/100000;params.lon=Math.round(lon*100000)/100000;layers=layers||this.map.layers;params.base='';var arrLayers=new Array();var len=layers.length;for(var i=0;i<len;i++){var layer=layers[i];if(layer==this.map.baseLayer){params.base=layer.key;}else if(layer.getVisibility()&&typeof(layer.key)!='undefined'&&layer.name!=""){arrLayers.push(layer.key.toString(36));}}
if(arrLayers.length){params.layers=arrLayers.join('-');params.layers=btoa(params.layers);}else if(params.layers){delete params.layers;}
params.len=len;}
return params;},CLASS_NAME:"OpenLayers.Control.C4GPermalink"});