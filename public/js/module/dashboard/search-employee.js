'use strict';

jQuery(document).on('submit', 'form.search-employees', function (event) {

    event.defaultPrevented = true;

    var action = new AjaxAction(jQuery(this).attr('action'), jQuery(this).serializeArray(), callback);
    action.execute();

    return false;

});

jQuery(document).on('click', '.paginator-a', function (event) {
    event.defaultPrevented = true;

    var page = jQuery(event.target).data('page');
    jQuery('#page-number').val(page);
    console.log(page);
    console.log(jQuery('.search-employees').serializeArray());

    var action = new AjaxAction(jQuery('.search-employees').attr('action'), jQuery('.search-employees').serializeArray(), callback);
    action.execute();

    return false;

});

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


