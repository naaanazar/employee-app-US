jQuery(document).on('submit', 'form.leave-comment', function (event) {

    event.defaultPrevented = true;

    var action = new AjaxAction($(this).attr('action'), $(this).serializeArray(), '#comments-list');
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
    var commentText = jQuery(event.target).closest('.comment-block').find('.comment-edit-field').val();
    jQuery(event.target).closest('.comment-block').find('.comment-buttons').show();

    jQuery.post( "/employee/comment-edit", {body : commentText, id: id}, function( data ) {
        jQuery(event.target).closest('.comment-block').find('.comment-body').html(commentText);
    })
});

/**
 * save edit comment
 */
jQuery(document).on('click', '.comment-edit', function (event) {
    var commentText = jQuery(event.target).closest('.comment-block').find('.comment-body').text();

    jQuery.post( "/employee/show-comment-edit", {body : commentText}, function( data ) {
        jQuery(event.target).closest('.comment-block').find('.comment-body').html(data.html);
        jQuery(event.target).closest('.comment-buttons').hide();
    })
});
