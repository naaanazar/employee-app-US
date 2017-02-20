'use strict';

jQuery(document).on('submit', 'form.search-employees', function (event) {

    event.defaultPrevented = true;

    var callback = function (data) {

        jQuery('document').ready(function () {

            Sort.initTable('#employee_table');
            Sort.eventSort('#employee_table', '#filter-employee-form');

        });

        var map = Map.init();

        data.coordinates.forEach(
            function (coordinate) {
                Map.addMarker(map, parseFloat(coordinate.latitude), parseFloat(coordinate.longitude));
            }
        );

        jQuery('#employees-list').html(data.html);

    };

    var action = new AjaxAction(jQuery(this).attr('action'), jQuery(this).serializeArray(), callback);
    action.execute();

    return false;

});