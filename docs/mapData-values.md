Valid **mapData**-values:
=========================

*@optional*  
**addIdToDiv** *(boolean)*  
  >If set to `true` the *mapId* will be appended to *mapDiv*.  
  >***default***: `false`.

*@optional*  
**attribution** *(boolean)*  
  >If set to `true` the Attributions will be shown on the Map.

*@optional*  
**calc_extend** *(string)*  
  >currently not used.

*@optional*  
**center** *(object)*  
  >Defines the initial view on the map.  
  >
  >*@optional*  
  >**lat** *(float)*  
  >  >The latitude where the view should center on the map.  
  >  >Needs to be a valid float between `-90.0` and `90.0`.  
  >  >***default***: `37.41`.
  >
  >*@optional*  
  >**lon** *(float)*  
  >  >The longitude where the view should center on the map.  
  >  >Needs to be a valid float between `-180.0` and `180.0`.  
  >  >***default***: `8.82`.
  >
  >*@optional*  
  >**zoom** *(integer)*  
  >  >The initial zoom for the View on the Map.  
  >  >Needs to be an integer between `0`(far) and `20`(near).  
  >  >***default***: `4`.

*@optional*  
**fullscreen** *(boolean)*  
  >If set to `true` the fullscreen-button will appear on the Map.

*@optional*  
**graticule** *(boolean)*  
  >If set to `true` the graticule-button will appear on the Map,
  >which kann toggle a grid layed on the map.

*@optional*  
**height** *(string)*  
  >Defines the height of the map and should be valid CSS.  
  >e.g.: `10px` or `auto`.

*@optional*  
**keyboard_nav** *(object)*  
  >A set of booleans to handle keyboard-navigation.  
  >
  >*@optional*  
  >**pan** *(boolean)*  
  >  >Enables Map-panning with the keyboards arrow-keys.
  >
  >*@optional*  
  >**zoom** *(boolean)*  
  >  >Enables Map-zooming with `+` and `-`.

*@optional*  
**mapDiv** *(string)*  
  >The `id` of the `<div>` where the map should be displayed.  
  >***default***: `c4g_Map`.

*@optional*  
**mapId** *(string)*  
  >The unique ID of the Map.  
  >***default***: `1`.

*@optional*  
**margin** *(string)*  
  >Defines the margin of the map and should be valid CSS.  
  >e.g.: `10px auto` or `10px 0 20px 0`.

*@optional*  
**mouse_nav** *(object)*  
  >A set of booleans to handle mouse-navigation.  
  >
  >*@optional*  
  >**drag_pan** *(boolean)*  
  >  >Enables Map-panning with the mouse.
  >  
  >*@optional*  
  >**drag_rotate** *(boolean)*  
  >  >Enables Map-rotating with `Shift+LeftMouseButton`.
  >  
  >*@optional*  
  >**drag_zoom** *(boolean)*  
  >  >Enables "box-zooming" with `Shift+LeftMouseButton`.
  >  
  >*@optional*  
  >**kinetic** *(boolean)*  
  >  >Enables "kinetic-scrolling" for *"drag-pan"*.
  >  
  >*@optional*  
  >**wheel_zoom** *(boolean)*  
  >  >Enables Map-zooming with the mousewheel.

*@optional*  
**mouseposition** *(boolean)*  
  >If set to `true` the mouseposition will be shown on the Map.

*@optional*  
**profile** *(integer)*  
  >currently not used.

*@optional*  
**scaleline** *(boolean)*  
  >If set to `true` a scaleline will be shown on the Map.

*@optional*  
**width** *(string)*  
  >Defines the width of the map and should be valid CSS.  
  >e.g.: `10px` or `100%`.

*@optional*  
**zoom_extent** *(boolean)*  
  >If set to `true` the "extend to maxZoom"-Button will be shown on the Map.

*@optional*  
**zoom_panel** *(boolean)*  
  >If set to `true` the "Zoompanel" will be shown on the Map.

*@optional*  
**zoomlevel** *(boolean)*  
  >If set to `true` the zoomlevel will be shown on the Map.



&nbsp;
---
<sub>&copy; by Küstenschmiede GmbH Software & Design</sub>