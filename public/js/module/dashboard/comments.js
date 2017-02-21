'use strict';

jQuery(document).on('click', '.paginator-a', function (event) {
    event.defaultPrevented = true;

    var page = jQuery(event.target).data('page');
    jQuery('#page-number').val(page);

    /**
     * @param data
     */
    var callback = function (data) {

        jQuery('document').ready(function () {
            Sort.initTable('#employee_table');
            Sort.eventSort('#employee_table', '#filter-employee-form');
        });

        jQuery('#employees-list').html(data.html);
    };

    /**
     * @type {AjaxAction}
     */
    var action = new AjaxAction(jQuery('.search-employees').attr('action'), jQuery('.search-employees').serializeArray(), callback);
    action.execute();

    return false;
});

/**
 * delete comment
 */
jQuery(document).on('click', '.comment-delete', function (event) {
    var id = jQuery(event.target).closest('.comment-block').data('id');
    jQuery.post( "/employee/comment-delete", {id : id}, function( data ) {
        jQuery(event.target).closest('.comment-block').remove();
    })
});

/**
 * show field edit comment
 */
jQuery(document).on('click', '.comment-edit-save', function (event) {
    var id = jQuery(event.target).closest('.comment-block').data('id');
    var html = jQuery(event.target).closest('.comment-body').find('.comment-edit-field').val();
    jQuery(event.target).closest('.comment-block').find('.comment-buttons').show();

    jQuery.post( "/employee/comment-edit", {id : id}, function( data ) {
        jQuery(event.target).closest('.comment-block').find('.comment-body').html(html);
    })
});

/**
 * save edit comment
 */
jQuery(document).on('click', '.comment-edit', function (event) {

    var commentText = jQuery(event.target).closest('.comment-block').find('.comment-body').text();
    /**
     *
     */
    jQuery.post( "/employee/show-comment-edit", {body : commentText}, function( data ) {
        jQuery(event.target).closest('.comment-block').find('.comment-body').html(data.html);
        jQuery(event.target).closest('.comment-buttons').hide();
    })


});


