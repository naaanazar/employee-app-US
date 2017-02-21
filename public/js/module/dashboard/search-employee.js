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


jQuery(document).on('click', '.comment-delete', function (event) {


    var id = jQuery(event.target).closest('.comment-block').data('id');
    jQuery.post( "/employee/comment-delete", {id : id}, function( data ) {
        jQuery(event.target).closest('.comment-block').remove();
    })

});

jQuery(document).on('click', '.comment-edit-save', function (event) {

    var id = jQuery(event.target).closest('.comment-block').data('id');
    jQuery(event.target).closest('.comment-block').find('.comment-buttons').show();
    var html = jQuery(event.target).closest('.comment-body').find('.comment-edit-field').val();

    jQuery.post( "/employee/comment-edit", {id : id}, function( data ) {
        jQuery(event.target).closest('.comment-block').find('.comment-body').html(html);
    })





});

jQuery(document).on('click', '.comment-edit', function (event) {

    var commentText = jQuery(event.target).closest('.comment-block').find('.comment-body').text();
    var html = '' +
        '<div class="comment-edit-block">' +
        '<textarea name="body" id=""  rows="4" class="form-control comment-edit-field">' + commentText + '</textarea>' +
        '<div class="text-right">' +
        '<button class="btn btn-link comment-edit-save"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span> Save</button>&nbsp;' +
        '</div>' +
        '</div>';
    jQuery(event.target).closest('.comment-block').find('.comment-body').html(html);
    jQuery(event.target).closest('.comment-buttons').hide();
});


