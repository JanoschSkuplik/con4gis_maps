(function ($, ol) {

    /**
     * Adds a starboard to an existing map.
     * 
     * @param {c4g.Map} map [The map which the starboard gets added to.]
     * @param {object} config [The starboard configuration.]
     */
    c4g.addStarboard = function (map, config) {

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
            map.toggleStarboard();

            // Load starboard data
            if (!map.isStarboardLoaded) map.loadStarboard();
        });
        toggle.appendChild(document.createTextNode("Starboard"));
        board.appendChild(toggle);
        board.appendChild(content);

        /**
         * Remark:
         * Starboard properties and methods are added directly to the c4g.Map object.
         * Could also be put into an own object, that is then added to the c4g.Map,
         * but only after code structure of c4g.Map and c4g.Starboard get aligned.
         */

        // Add state properties
        map.isStarboardLoaded = false;
        map.isStarboardLoading = false;
        map.isStarboardOpened = false;

        // Add accessor functions
        map.openStarboard = function () {
            map.isStarboardOpened = true;
            $(content).show();
        };
        map.closeStarboard = function () {
            map.isStarboardOpened = false;
            $(content).hide();
        };
        map.toggleStarboard = function () {
            if (map.isStarboardOpened) map.closeStarboard();
            else map.openStarboard();
        };
        map.loadStarboard = function () {
            // Only allow one loading action
            if (map.isStarboardLoading) return;
            map.isStarboardLoading = true;

            $.getJSON("/api/c4g_maps_layerapi/1")
                .done(function (data) {
                    drawStarboard(data);
                })
                .fail(function () {
                })
                .always(function () {
                    map.isStarboardLoading = false;
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
            $.each(data, function (index, item) {
                // Todo:
                // Create layer, extend with state properties and keep the tree.
            });
            map.isStarboardLoaded = true;
        }

        // Create anddisplay the control
        map.map.addControl(new ol.control.Control({ element: board }));
    }
})(jQuery, ol);