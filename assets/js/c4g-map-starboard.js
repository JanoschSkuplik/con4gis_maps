// "namespace"
this.c4g = this.c4g || {};

(function ($, ol) {
  "use strict";

  /**
   * Adds a starboard to an existing mapContainer.
   * 
   * @param {c4g.MapContainer} mapContainer [The mapContainer which the starboard gets added to.]
   * @param {object} config [The starboard configuration.]
   */
  c4g.addStarboard = function (mapContainer, config) {

    /**
     * @TODO:
     * Create the starboard/control in an appropiate way.
     * Currenly:
     *     Basic elements are created with document.createElement.
     *     Events are attached using jquery.
     * Options:
     *     Use a template engine like knockout.
     *     Create elements using closure like other ol3 controls.
     */

    // Create the visual elements
    var board = document.createElement("div");
    var toggle = document.createElement("button");
    var content = document.createElement("ul");

    // Set attributes
    $(content).hide();
    $(board).addClass("c4g-starboard ol-unselectable ol-control");
    $(toggle).on('click', function (event) {
      event.stopPropagation();
      mapContainer.toggleStarboard();

      // Load starboard data
      if (!mapContainer.isStarboardLoaded) { mapContainer.loadStarboard(); }
    });
    toggle.appendChild(document.createTextNode("Starboard"));
    board.appendChild(toggle);
    board.appendChild(content);

    /**
     * Remark:
     * Starboard properties and methods are added directly to the c4g.MapContainer object.
     * Could also be put into an own object, that is then added to the c4g.MapContainer,
     * but only after code structure of c4g.MapContainer and c4g.Starboard get aligned.
     */

    // Add state properties
    mapContainer.isStarboardLoaded = false;
    mapContainer.isStarboardLoading = false;
    mapContainer.isStarboardOpened = false;

    // Add accessor functions
    mapContainer.openStarboard = function () {
      mapContainer.isStarboardOpened = true;
      $(content).show();
    };
    mapContainer.closeStarboard = function () {
      mapContainer.isStarboardOpened = false;
      $(content).hide();
    };
    mapContainer.toggleStarboard = function () {
      if (mapContainer.isStarboardOpened) {
        mapContainer.closeStarboard();
      } else {
        mapContainer.openStarboard();
      }
    };
    mapContainer.loadStarboard = function () {
      // Only allow one loading action
      if (mapContainer.isStarboardLoading) { return; }
      mapContainer.isStarboardLoading = true;

    //@todo get url as parameter
      $.getJSON("api4gis/c4g_maps_layerapi/1")
        .done(function (data) {
          drawStarboard(data);
        })
        .fail(function () {
          //@todo error-message
        })
        .always(function () {
          mapContainer.isStarboardLoading = false;
        });
    };

    // Private functions
    var drawStarboard = function(data) {
      /**
       * Remark:
       * Find a good, standardizable way to handle adding and removing layers.
       * Currently the starboard makes sure, it only loads once and then keeps
       * the list of layers.
       */

      // Add the layers
      $.each(data, function (index, item) {
        // Todo:
        // Create layer, extend with state properties and keep the tree.
      });
      mapContainer.isStarboardLoaded = true;
    }

    // Create anddisplay the control
    mapContainer.map.addControl(new ol.control.Control({ element: board }));
  }
}(jQuery, ol));