// 'namespace'
this.c4g = this.c4g || {};

(function ($, ol) {
  'use strict';

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
    var container = document.createElement('div');

    var control = document.createElement('div');
    var toggle = document.createElement('button');
    var tooltip = document.createElement('span');

    // Set attributes
// $(container).hide();
    $(container).addClass('c4g-starboard-container c4g-close');
    $(control).addClass('c4g-starboard-control ol-unselectable ol-control c4g-close');
    $(toggle).addClass('ol-has-tooltip');
    $(tooltip).attr('role', 'tooltip');
    $(toggle).on('click', function (event) {
      event.stopPropagation();
      // loose focus, otherwise it looks messy
      this.blur();
      mapContainer.toggleStarboard();

      // Load starboard data
      if (!mapContainer.isStarboardLoaded) { mapContainer.loadStarboard(); }
    });
    tooltip.appendChild(document.createTextNode('Open Starboard'));
    toggle.appendChild(tooltip);
    control.appendChild(toggle);

    //@TODO  -> CSS-File?
    container.style.position = 'absolute';
    container.style.minWidth = '200px';
    container.style.top = '0';
    container.style.right = '-200px';
    container.style.height = '100%';
    mapContainer.map.getViewport().appendChild(container);
    
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
      $(control).removeClass('c4g-close');
      $(control).addClass('c4g-open');
      $(container).removeClass('c4g-close');
      $(container).addClass('c4g-open');
      mapContainer.isStarboardOpened = true;
      // $(container).show();
      control.style.right = container.offsetWidth + 'px';
      container.style.right = 0;
      // slide other elements
      mapContainer.rightSlideElements.forEach(function (element, index, array) {
        // element.style.right = (element.style.right + container.offsetWidth) + 'px';
        $(element).css('right', '+=' + container.offsetWidth);
      });

      // @TODO use a parameter
      tooltip.innerHTML = 'Close Starboard';
    };
    mapContainer.closeStarboard = function () {
      $(control).removeClass('c4g-open');
      $(control).addClass('c4g-close');
      $(container).removeClass('c4g-open');
      $(container).addClass('c4g-close');
      mapContainer.isStarboardOpened = false;
      // $(container).hide();
      container.style.right = '-' + container.offsetWidth + 'px';
      control.style.right = 0;
      // slide other elements
      mapContainer.rightSlideElements.forEach(function (element, index, array) {
        // element.style.right = (element.style.right - container.offsetWidth) + 'px';
        $(element).css('right', '-=' + container.offsetWidth);
      });

      // @TODO use a parameter
      tooltip.innerHTML = 'Open Starboard';
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

    //@TODO get url as parameter
      $.getJSON('api4gis/c4g_maps_layerapi/1')
        .done(function (data) {
          drawStarboard(data);
        })
        .fail(function () {
          //@TODO error-message
        })
        .always(function () {
          mapContainer.isStarboardLoading = false;
        });
    };

    // Private functions
    var drawStarboard = function (data) {
      /**
       * Remark:
       * Find a good, standardizable way to handle adding and removing layers.
       * Currently the starboard makes sure, it only loads once and then keeps
       * the list of layers.
       */

      // Add the layers
      $.each(data, function (index, item){
        // TODO:
        // Create layer, extend with state properties and keep the tree.
      });
      mapContainer.isStarboardLoaded = true;
    };

    // Create anddisplay the control
    mapContainer.map.addControl(new ol.control.Control({ element: control }));
  };
}(jQuery, ol));