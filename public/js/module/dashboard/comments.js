'use strict';

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


