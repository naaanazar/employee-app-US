'use strict';

jQuery(document).on('submit', 'form.search-employees', function (event) {

    event.defaultPrevented = true;

    var callback = function (data) {

        Sort.initSort('#overview_table');
        Sort.eventSort('#overview_table', '#filter_overview');

        var map = Map.init();

        data.coordinates.forEach(
            function (coordinate) {
                Map.addMarker(map, parseFloat(coordinate.latitude), parseFloat(coordinate.longitude));
            }
        );

        // jQuery('body').loading('toggle');
        jQuery('#employees-list').html(data.html);

    };

    var action = new AjaxAction(jQuery(this).attr('action'), jQuery(this).serializeArray(), callback);
    action.execute();

    return false;

});