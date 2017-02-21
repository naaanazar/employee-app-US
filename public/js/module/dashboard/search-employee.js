'use strict';

var searchEmployee = function (event) {
    event.defaultPrevented = true;

    var page = jQuery(event.target).data('page');
    jQuery('#page-number').val(page);

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
    console.log(jQuery('.search-employees').serializeArray());
    var action = new AjaxAction(jQuery('.search-employees').attr('action'), jQuery('.search-employees').serializeArray(), callback);
    action.execute();

    return false;

};

jQuery('document').ready(function (event) {
    var page = jQuery(event.target).data('page');
    jQuery('#page-number').val(page);

    jQuery(document).on('click', '.paginator-a', searchEmployee);
});

jQuery(document).on('submit', 'form.search-employees', searchEmployee);

jQuery('#map').on('click', '.find_employee', function () {
    var element = $(this);
    var modalAction = new ModalAction(element.data('action'), element.data('element'));
    modalAction.execute();

    return false;
});