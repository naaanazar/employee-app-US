'use strict';

var searchEmployee = function (event) {
    if (event) {
        event.defaultPrevented = true;
        var page = jQuery(event.target).data('page');
    } else {
        page = jQuery('ul.pagination li.active .paginator-a').data('page');
    }

    jQuery('#page-number').val(page);

    if (typeof GoogleMap !== 'undefined') {
        GoogleMap.clearMarker();
    }

    var callback = function (data) {

        jQuery('document').ready(function () {

            Sort.initTable('#employee_table');
            Sort.eventSort('#employee_table', '#filter-employee-form');

        });

        if (typeof GoogleMap !== 'undefined' && GoogleMap.hasOwnProperty('instance')) {
            var map = GoogleMap.instance;
            var points = [];

            if( '' !== jQuery('#latitude').val() && '' !== jQuery('#longitude').val()) {
                GoogleMap.marker = GoogleMap.addMarker(
                    map,
                    parseFloat(jQuery('#latitude').val()),
                    parseFloat(jQuery('#longitude').val())
                );

                GoogleMap.marker.setIcon('/img/marker_green.png');
            }

            data.coordinates.forEach(

                function (coordinate) {

                    points.push({lat: parseFloat(coordinate.latitude), lng: parseFloat(coordinate.longitude)});

                    var marker = GoogleMap.addMarker(map, parseFloat(coordinate.latitude), parseFloat(coordinate.longitude));
                    var content = coordinate.employee;

                    var infowindow = GoogleMap.infoWindows(content);

                    google.maps.event.addListener(map, 'click', function() {
                        if (infowindow) {
                            infowindow.close();
                        }
                    });

                    marker.addListener('click', function() {
                        infowindow.open(GoogleMap.instance, marker);
                    });

                    GoogleMap.centeringMap(points, GoogleMap.instance);
                }
            );
        }

        jQuery('#employees-list').html(data.html);

    };

    promise = jQuery.Deferred();

    if (jQuery('[name="zip"]').val() !== '') {
        Address.findAddress(Address.map);
    } else {
        promise.resolve();
    }

    jQuery.when(promise).then(
        function () {
            var action = new AjaxAction(jQuery('.search-employees').attr('action'), jQuery('.search-employees').serializeArray(), callback);
            action.execute();
        }
    );

    return false;

};

jQuery(document).on('click', '#clear_marker', function(event) {
    if(null !== GoogleMap.marker) {
        GoogleMap.marker.setMap(null);
        jQuery('#latitude').val('');
        jQuery('#longitude').val('');
    }
});

jQuery(document).on('click', '.paginator-a', function (event) {

    var page = jQuery(event.target).data('page');
    jQuery('#page-number').val(page);
    searchEmployee(event);

    return false;
});

jQuery(document).on('submit', 'form.search-employees', searchEmployee);

jQuery('#map').on('click', '.find_employee', function () {
    var element = $(this);
    var modalAction = new ModalAction(element.data('action'), element.data('element'));
    modalAction.execute();

    return false;
});