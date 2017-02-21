'use strict';

jQuery(document).on('submit', 'form.search-employees', function (event) {
    event.defaultPrevented = true;

    Map.clearMarker();

    var callback = function (data) {

        jQuery('document').ready(function () {

            Sort.initTable('#employee_table');
            Sort.eventSort('#employee_table', '#filter-employee-form');

        });

        var map = Map.mapObj;
        var points = [];

        data.coordinates.forEach(
            function (coordinate) {

                points.push({lat: parseFloat(coordinate.latitude), lng: parseFloat(coordinate.longitude)});

                var marker = Map.addMarker(map, parseFloat(coordinate.latitude), parseFloat(coordinate.longitude));
                var content = coordinate.employee;

                var infowindow = Map.infoWindows(content);

                google.maps.event.addListener(map, 'click', function() {
                    if (infowindow) {
                        infowindow.close();
                    }
                });

                marker.addListener('click', function() {
                    infowindow.open(Map.mapObj, marker);
                });

                Map.centeringMap(points, Map.mapObj);
            }
        );

        jQuery('#employees-list').html(data.html);

    };

    var action = new AjaxAction(jQuery(this).attr('action'), jQuery(this).serializeArray(), callback);
    action.execute();

    return false;

});

jQuery('#map').on('click', '#find_employee', function () {
    var element = $(this);
    var modalAction = new ModalAction(element.data('action'), element.data('element'));
    modalAction.execute();

    return false;
});
